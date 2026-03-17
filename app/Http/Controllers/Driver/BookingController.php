<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\{Booking, Notification};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::whereHas('ride', fn($q) => $q->where('driver_id', Auth::id()))
            ->with(['passenger', 'ride.origin', 'ride.destination']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->paginate(15);
        return view('driver.bookings.index', compact('bookings'));
    }

    public function confirm(Booking $booking)
    {
        $this->authorizeBooking($booking);
        $booking->update(['status' => 'confirmed']);

        Notification::create([
            'user_id' => $booking->passenger_id,
            'type'    => 'booking_confirmed',
            'title'   => 'Réservation confirmée',
            'body'    => 'Le conducteur a confirmé votre réservation.',
            'data'    => ['booking_id' => $booking->id],
        ]);

        return back()->with('success', 'Réservation confirmée.');
    }

    public function reject(Booking $booking)
    {
        $this->authorizeBooking($booking);
        $booking->update(['status' => 'cancelled', 'cancelled_at' => now(), 'cancel_reason' => 'Refusé par le conducteur']);

        // Restore seats
        $booking->ride->increment('seats_available', $booking->seats_booked);

        Notification::create([
            'user_id' => $booking->passenger_id,
            'type'    => 'booking_rejected',
            'title'   => 'Réservation refusée',
            'body'    => 'Le conducteur a refusé votre demande de réservation.',
            'data'    => ['booking_id' => $booking->id],
        ]);

        return back()->with('success', 'Réservation refusée.');
    }

    private function authorizeBooking(Booking $booking): void
    {
        abort_if($booking->ride->driver_id !== Auth::id(), 403);
    }
}
