<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Models\{Ride, Booking, Notification, Payment, WalletTransaction};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->bookings()->with(['ride.origin', 'ride.destination', 'ride.driver', 'payment']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->paginate(10);
        return view('passenger.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        abort_if($booking->passenger_id !== Auth::id(), 403);
        $booking->load(['ride.driver', 'ride.origin', 'ride.destination', 'ride.vehicle', 'payment', 'review']);
        return view('passenger.bookings.show', compact('booking'));
    }

    public function store(Request $request, Ride $ride)
    {
        $data = $request->validate([
            'seats_booked' => 'required|integer|min:1|max:'.$ride->seats_available,
        ]);

        abort_if($ride->seats_available < $data['seats_booked'], 422, 'Plus assez de places disponibles.');
        abort_if($ride->bookings()->where('passenger_id', Auth::id())->exists(), 422, 'Vous avez déjà réservé ce trajet.');

        $totalPrice = $ride->price_per_seat * $data['seats_booked'];
        $user       = Auth::user();

        // Check wallet balance
        abort_if(($user->wallet?->balance ?? 0) < $totalPrice, 422, 'Solde insuffisant dans votre portefeuille.');

        DB::transaction(function () use ($ride, $data, $totalPrice, $user) {
            $booking = Booking::create([
                'ride_id'      => $ride->id,
                'passenger_id' => $user->id,
                'seats_booked' => $data['seats_booked'],
                'total_price'  => $totalPrice,
                'status'       => 'pending',
            ]);

            // Deduct from wallet
            $user->wallet->decrement('balance', $totalPrice);
            WalletTransaction::create([
                'wallet_id'    => $user->wallet->id,
                'type'         => 'debit',
                'amount'       => $totalPrice,
                'description'  => 'Paiement réservation',
                'reference_id' => $booking->id,
            ]);

            // Create payment record
            Payment::create([
                'booking_id'     => $booking->id,
                'payer_id'       => $user->id,
                'amount'         => $totalPrice,
                'currency'       => 'XAF',
                'method'         => 'wallet',
                'status'         => 'completed',
                'transaction_id' => uniqid('TXN_'),
                'paid_at'        => now(),
            ]);

            // Decrement seats
            $ride->decrement('seats_available', $data['seats_booked']);

            // Notify driver
            Notification::create([
                'user_id' => $ride->driver_id,
                'type'    => 'new_booking',
                'title'   => 'Nouvelle réservation',
                'body'    => $user->first_name.' '.$user->last_name.' a réservé '.$data['seats_booked'].' place(s) sur votre trajet.',
                'data'    => ['booking_id' => $booking->id, 'ride_id' => $ride->id],
            ]);
        });

        return redirect()->route('passenger.bookings.index')->with('success', 'Réservation effectuée avec succès!');
    }

    public function cancel(Booking $booking)
    {
        abort_if($booking->passenger_id !== Auth::id(), 403);
        abort_if(!in_array($booking->status, ['pending', 'confirmed']), 422, 'Impossible d\'annuler cette réservation.');

        DB::transaction(function () use ($booking) {
            $booking->update(['status' => 'cancelled', 'cancelled_at' => now(), 'cancel_reason' => 'Annulé par le passager']);
            $booking->ride->increment('seats_available', $booking->seats_booked);

            // Refund wallet
            $wallet = Auth::user()->wallet;
            if ($wallet) {
                $wallet->increment('balance', $booking->total_price);
                WalletTransaction::create([
                    'wallet_id'    => $wallet->id,
                    'type'         => 'credit',
                    'amount'       => $booking->total_price,
                    'description'  => 'Remboursement annulation',
                    'reference_id' => $booking->id,
                ]);
            }

            Notification::create([
                'user_id' => $booking->ride->driver_id,
                'type'    => 'booking_cancelled',
                'title'   => 'Réservation annulée',
                'body'    => 'Un passager a annulé sa réservation sur votre trajet.',
                'data'    => ['booking_id' => $booking->id],
            ]);
        });

        return back()->with('success', 'Réservation annulée. Remboursement effectué.');
    }

    public function review(Request $request, Booking $booking)
    {
        abort_if($booking->passenger_id !== Auth::id(), 403);
        abort_if($booking->status !== 'completed', 422);
        abort_if($booking->review()->where('reviewer_id', Auth::id())->exists(), 422, 'Vous avez déjà évalué ce trajet.');

        $data = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $booking->review()->create([
            'reviewer_id' => Auth::id(),
            'reviewee_id' => $booking->ride->driver_id,
            'rating'      => $data['rating'],
            'comment'     => $data['comment'] ?? null,
            'type'        => 'passenger_to_driver',
        ]);

        // Update driver rating
        $driver = $booking->ride->driver;
        $avg = $driver->reviews()->where('reviewee_id', $driver->id)->avg('rating');
        $count = $driver->reviews()->where('reviewee_id', $driver->id)->count();
        $driver->update(['rating_avg' => round($avg, 2), 'rating_count' => $count]);

        return back()->with('success', 'Merci pour votre évaluation!');
    }
}
