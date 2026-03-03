<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use App\Models\Location;
use App\Models\RideWaypoint;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RideController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'origin_city'      => 'nullable|string',
            'destination_city' => 'nullable|string',
            'date'             => 'nullable|date|after_or_equal:today',
            'seats'            => 'nullable|integer|min:1',
            'max_price'        => 'nullable|numeric|min:0',
            'sort'             => 'nullable|in:price_asc,price_desc,departure_asc,departure_desc',
        ]);

        $rides = Ride::with('driver', 'vehicle', 'origin', 'destination', 'waypoints.location')
            ->where('status', 'scheduled')
            ->where('departure_datetime', '>=', now())
            ->when($request->origin_city, fn($q, $c) =>
                $q->whereHas('origin', fn($o) => $o->where('city', 'like', "%$c%"))
            )
            ->when($request->destination_city, fn($q, $c) =>
                $q->whereHas('destination', fn($d) => $d->where('city', 'like', "%$c%"))
            )
            ->when($request->date, fn($q, $d) =>
                $q->whereDate('departure_datetime', $d)
            )
            ->when($request->seats, fn($q, $s) =>
                $q->where('seats_available', '>=', $s)
            )
            ->when($request->max_price, fn($q, $p) =>
                $q->where('price_per_seat', '<=', $p)
            )
            ->when($request->sort, function ($q, $sort) {
                match ($sort) {
                    'price_asc'       => $q->orderBy('price_per_seat'),
                    'price_desc'      => $q->orderByDesc('price_per_seat'),
                    'departure_asc'   => $q->orderBy('departure_datetime'),
                    'departure_desc'  => $q->orderByDesc('departure_datetime'),
                    default           => null,
                };
            }, fn($q) => $q->orderBy('departure_datetime'))
            ->paginate($request->per_page ?? 15);

        return response()->json($rides);
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
            'vehicle_id'         => 'required|uuid|exists:vehicles,id',
            'origin'             => 'required|array',
            'origin.label'       => 'required|string',
            'origin.city'        => 'required|string',
            'origin.country'     => 'required|string',
            'origin.latitude'    => 'required|numeric|between:-90,90',
            'origin.longitude'   => 'required|numeric|between:-180,180',
            'origin.place_id'    => 'nullable|string',
            'destination'        => 'required|array',
            'destination.label'  => 'required|string',
            'destination.city'   => 'required|string',
            'destination.country'=> 'required|string',
            'destination.latitude'  => 'required|numeric|between:-90,90',
            'destination.longitude' => 'required|numeric|between:-180,180',
            'destination.place_id'  => 'nullable|string',
            'departure_datetime' => 'required|date|after:now',
            'arrival_datetime'   => 'nullable|date|after:departure_datetime',
            'distance_km'        => 'nullable|numeric|min:0',
            'duration_min'       => 'nullable|integer|min:0',
            'seats_total'        => 'required|integer|min:1|max:8',
            'price_per_seat'     => 'required|numeric|min:0',
            'description'        => 'nullable|string|max:1000',
            'is_recurrent'       => 'boolean',
            'recurrence_rule'    => 'nullable|string|required_if:is_recurrent,true',
            'waypoints'          => 'nullable|array',
            'waypoints.*.label'  => 'required|string',
            'waypoints.*.city'   => 'required|string',
            'waypoints.*.country'=> 'required|string',
            'waypoints.*.latitude'  => 'required|numeric',
            'waypoints.*.longitude' => 'required|numeric',
            'waypoints.*.order'     => 'required|integer|min:1',
            'waypoints.*.arrival_time'   => 'nullable|date',
            'waypoints.*.departure_time' => 'nullable|date',
        ]);

        $ride = DB::transaction(function () use ($data, $request) {
            $origin      = Location::firstOrCreate(
                ['place_id' => $data['origin']['place_id'] ?? null,
                 'label'    => $data['origin']['label']],
                $data['origin']
            );
            $destination = Location::firstOrCreate(
                ['place_id' => $data['destination']['place_id'] ?? null,
                 'label'    => $data['destination']['label']],
                $data['destination']
            );

            $ride = Ride::create([
                'driver_id'          => $request->user()->id,
                'vehicle_id'         => $data['vehicle_id'],
                'origin_id'          => $origin->id,
                'destination_id'     => $destination->id,
                'departure_datetime' => $data['departure_datetime'],
                'arrival_datetime'   => $data['arrival_datetime'] ?? null,
                'distance_km'        => $data['distance_km'] ?? null,
                'duration_min'       => $data['duration_min'] ?? null,
                'seats_total'        => $data['seats_total'],
                'seats_available'    => $data['seats_total'],
                'price_per_seat'     => $data['price_per_seat'],
                'description'        => $data['description'] ?? null,
                'is_recurrent'       => $data['is_recurrent'] ?? false,
                'recurrence_rule'    => $data['recurrence_rule'] ?? null,
                'status'             => 'scheduled',
            ]);

            // Créer les waypoints si fournis
            foreach ($data['waypoints'] ?? [] as $wp) {
                $loc = Location::firstOrCreate(
                    ['place_id' => $wp['place_id'] ?? null, 'label' => $wp['label']],
                    $wp
                );
                RideWaypoint::create([
                    'ride_id'        => $ride->id,
                    'location_id'    => $loc->id,
                    'order'          => $wp['order'],
                    'arrival_time'   => $wp['arrival_time'] ?? null,
                    'departure_time' => $wp['departure_time'] ?? null,
                ]);
            }

            return $ride;
        });

        return response()->json([
            'message' => 'Trajet créé avec succès.',
            'ride'    => $ride->load('origin', 'destination', 'vehicle', 'waypoints.location'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ride $ride): JsonResponse
    {
        return response()->json(
            $ride->load('driver', 'vehicle', 'origin', 'destination', 'waypoints.location', 'bookings.passenger')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ride $ride)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ride $ride): JsonResponse
    {
        if ($ride->status !== 'scheduled') {
            return response()->json(['message' => 'Seuls les trajets planifiés peuvent être modifiés.'], 422);
        }

        $data = $request->validate([
            'departure_datetime' => 'sometimes|date|after:now',
            'arrival_datetime'   => 'nullable|date|after:departure_datetime',
            'price_per_seat'     => 'sometimes|numeric|min:0',
            'description'        => 'nullable|string|max:1000',
            'seats_total'        => 'sometimes|integer|min:1|max:8',
        ]);

        // Recalculer seats_available si seats_total change
        if (isset($data['seats_total'])) {
            $booked = $ride->bookings()->whereIn('status', ['confirmed', 'pending'])->sum('seats_booked');
            $data['seats_available'] = max(0, $data['seats_total'] - $booked);
        }

        $ride->update($data);

        return response()->json([
            'message' => 'Trajet mis à jour.',
            'ride'    => $ride->fresh()->load('origin', 'destination', 'vehicle'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ride $ride)
    {
        //
    }

    /**
     * Annuler un trajet.
     */
    public function cancel(Request $request, Ride $ride): JsonResponse
    {
        if (!in_array($ride->status, ['scheduled', 'active'])) {
            return response()->json(['message' => 'Ce trajet ne peut pas être annulé.'], 422);
        }

        $ride->update(['status' => 'cancelled']);

        // Annuler toutes les réservations en attente/confirmées
        $ride->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->update([
                'status'       => 'cancelled',
                'cancelled_at' => now(),
                'cancel_reason'=> 'Trajet annulé par le conducteur.',
            ]);

        return response()->json(['message' => 'Trajet annulé. Les passagers ont été notifiés.']);
    }

    /**
     * Démarrer un trajet.
     */
    public function start(Ride $ride): JsonResponse
    {
        if ($ride->status !== 'scheduled') {
            return response()->json(['message' => 'Le trajet doit être planifié pour démarrer.'], 422);
        }

        $ride->update(['status' => 'active']);

        return response()->json(['message' => 'Trajet démarré.', 'ride' => $ride->fresh()]);
    }

    /**
     * Terminer un trajet.
     */
    public function complete(Ride $ride): JsonResponse
    {
        if ($ride->status !== 'active') {
            return response()->json(['message' => 'Le trajet doit être actif pour être complété.'], 422);
        }

        $ride->update(['status' => 'completed']);

        return response()->json(['message' => 'Trajet terminé.', 'ride' => $ride->fresh()]);
    }

    /**
     * Mes trajets (conducteur connecté).
     */
    public function myRides(Request $request): JsonResponse
    {
        $rides = $request->user()
            ->rides()
            ->with('origin', 'destination', 'vehicle', 'bookings')
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->orderByDesc('departure_datetime')
            ->paginate($request->per_page ?? 10);

        return response()->json($rides);
    }
}
