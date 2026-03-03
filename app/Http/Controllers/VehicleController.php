<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $profile = $request->user()->driverProfile;

        if (!$profile) {
            return response()->json(['message' => 'Profil conducteur requis.'], 403);
        }

        return response()->json($profile->vehicles);
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
        $profile = $request->user()->driverProfile;

        if (!$profile) {
            return response()->json(['message' => 'Créez d\'abord un profil conducteur.'], 403);
        }

        $data = $request->validate([
            'brand'         => 'required|string|max:50',
            'model'         => 'required|string|max:50',
            'year'          => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'color'         => 'required|string|max:30',
            'license_plate' => 'required|string|max:20|unique:vehicles,license_plate',
            'nb_seats'      => 'required|integer|min:1|max:9',
            'fuel_type'     => 'required|in:gasoline,diesel,electric,hybrid,lpg',
            'photos'        => 'nullable|array|max:5',
            'photos.*'      => 'url',
        ]);

        $vehicle = Vehicle::create([
            ...$data,
            'driver_id'   => $profile->id,
            'is_verified' => false,
        ]);

        return response()->json([
            'message' => 'Véhicule ajouté. En attente de vérification.',
            'vehicle' => $vehicle,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Vehicle $vehicle): JsonResponse
    {
        return response()->json($vehicle->load('driver.user:id,first_name,last_name'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle): JsonResponse
    {
        $data = $request->validate([
            'brand'         => 'sometimes|string|max:50',
            'model'         => 'sometimes|string|max:50',
            'year'          => 'sometimes|integer|min:2000|max:' . (date('Y') + 1),
            'color'         => 'sometimes|string|max:30',
            'license_plate' => 'sometimes|string|max:20|unique:vehicles,license_plate,' . $vehicle->id,
            'nb_seats'      => 'sometimes|integer|min:1|max:9',
            'fuel_type'     => 'sometimes|in:gasoline,diesel,electric,hybrid,lpg',
            'photos'        => 'nullable|array|max:5',
            'photos.*'      => 'url',
        ]);

        $vehicle->update($data);

        return response()->json([
            'message' => 'Véhicule mis à jour.',
            'vehicle' => $vehicle->fresh(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Vehicle $vehicle): JsonResponse
    {
        $hasActiveRides = $vehicle->rides()
            ->whereIn('status', ['scheduled', 'active'])
            ->exists();

        if ($hasActiveRides) {
            return response()->json([
                'message' => 'Impossible de supprimer : des trajets sont liés à ce véhicule.',
            ], 422);
        }

        $vehicle->delete();

        return response()->json(['message' => 'Véhicule supprimé.']);
    }

    /**
     * Vérifier un véhicule (admin).
     */
    public function verify(Vehicle $vehicle): JsonResponse
    {
        $vehicle->update(['is_verified' => true]);

        return response()->json(['message' => 'Véhicule vérifié.']);
    }
}
