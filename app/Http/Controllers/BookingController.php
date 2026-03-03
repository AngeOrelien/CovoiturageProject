<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Ride;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = $user->role === 'admin'
            ? Booking::query()
            : $user->bookings();

        $bookings = $query
            ->with('ride.origin', 'ride.destination', 'ride.driver', 'pickupLocation', 'dropoffLocation', 'payment')
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->orderByDesc('created_at')
            ->paginate($request->per_page ?? 10);

        return response()->json($bookings);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'ride_id'       => 'required|uuid|exists:rides,id',
            'seats_booked'  => 'required|integer|min:1|max:4',
            'pickup_location_id'  => 'nullable|uuid|exists:locations,id',
            'dropoff_location_id' => 'nullable|uuid|exists:locations,id',
        ]);

        $ride = Ride::findOrFail($data['ride_id']);

        // Vérifications métier
        if ($ride->status !== 'scheduled') {
            return response()->json(['message' => 'Ce trajet n\'est plus disponible.'], 422);
        }
        if ($ride->driver_id === $request->user()->id) {
            return response()->json(['message' => 'Vous ne pouvez pas réserver votre propre trajet.'], 422);
        }
        if ($ride->seats_available < $data['seats_booked']) {
            return response()->json(['message' => 'Pas assez de places disponibles.'], 422);
        }

        // Vérifier doublon
        $exists = Booking::where('ride_id', $ride->id)
            ->where('passenger_id', $request->user()->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Vous avez déjà réservé ce trajet.'], 422);
        }

        $booking = DB::transaction(function () use ($data, $ride, $request) {
            $totalPrice = $ride->price_per_seat * $data['seats_booked'];

            $booking = Booking::create([
                'ride_id'             => $ride->id,
                'passenger_id'        => $request->user()->id,
                'seats_booked'        => $data['seats_booked'],
                'pickup_location_id'  => $data['pickup_location_id'] ?? $ride->origin_id,
                'dropoff_location_id' => $data['dropoff_location_id'] ?? $ride->destination_id,
                'status'              => 'pending',
                'total_price'         => $totalPrice,
            ]);

            // Décrémenter les places disponibles
            $ride->decrement('seats_available', $data['seats_booked']);

            // Créer une conversation entre conducteur et passager
            $conversation = Conversation::create([
                'ride_id'    => $ride->id,
                'booking_id' => $booking->id,
            ]);

            ConversationParticipant::insert([
                ['conversation_id' => $conversation->id, 'user_id' => $ride->driver_id,        'joined_at' => now()],
                ['conversation_id' => $conversation->id, 'user_id' => $request->user()->id,    'joined_at' => now()],
            ]);

            return $booking;
        });

        return response()->json([
            'message' => 'Réservation créée.',
            'booking' => $booking->load('ride.origin', 'ride.destination', 'ride.driver', 'conversation'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking): JsonResponse
    {
        return response()->json(
            $booking->load('ride.origin', 'ride.destination', 'ride.driver', 'ride.vehicle',
                           'passenger', 'pickupLocation', 'dropoffLocation', 'payment', 'review', 'conversation')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        //
    }

    /**
     * Confirmer une réservation (conducteur).
     */
    public function confirm(Booking $booking): JsonResponse
    {
        if ($booking->status !== 'pending') {
            return response()->json(['message' => 'Seules les réservations en attente peuvent être confirmées.'], 422);
        }

        $booking->update(['status' => 'confirmed']);

        return response()->json(['message' => 'Réservation confirmée.', 'booking' => $booking->fresh()]);
    }

    /**
     * Annuler une réservation (passager ou conducteur).
     */
    public function cancel(Request $request, Booking $booking): JsonResponse
    {
        $request->validate([
            'cancel_reason' => 'nullable|string|max:500',
        ]);

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return response()->json(['message' => 'Cette réservation ne peut pas être annulée.'], 422);
        }

        DB::transaction(function () use ($booking, $request) {
            $booking->update([
                'status'        => 'cancelled',
                'cancelled_at'  => now(),
                'cancel_reason' => $request->cancel_reason,
            ]);

            // Restituer les places
            $booking->ride->increment('seats_available', $booking->seats_booked);
        });

        return response()->json(['message' => 'Réservation annulée.']);
    }

    /**
     * Mes réservations (passager connecté).
     */
    public function myBookings(Request $request): JsonResponse
    {
        $bookings = $request->user()
            ->bookings()
            ->with('ride.origin', 'ride.destination', 'ride.driver', 'ride.vehicle', 'payment', 'review')
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->orderByDesc('created_at')
            ->paginate($request->per_page ?? 10);

        return response()->json($bookings);
    }
}
