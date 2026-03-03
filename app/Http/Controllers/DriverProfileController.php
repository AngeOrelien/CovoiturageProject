<?php

namespace App\Http\Controllers;

use App\Models\DriverProfile;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DriverProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        if ($request->user()->driverProfile) {
            return response()->json(['message' => 'Vous avez déjà un profil conducteur.'], 422);
        }

        $data = $request->validate([
            'license_number'      => 'required|string|max:50|unique:driver_profiles,license_number',
            'license_expiry'      => 'required|date|after:today',
            'years_of_experience' => 'nullable|integer|min:0|max:60',
            'preferences'         => 'nullable|array',
            'preferences.music'   => 'nullable|string',
            'preferences.smoking' => 'nullable|boolean',
            'preferences.pets'    => 'nullable|boolean',
            'preferences.talk'    => 'nullable|string|in:silent,little,chatty',
        ]);

        $profile = DriverProfile::create([
            ...$data,
            'user_id'              => $request->user()->id,
            'is_license_verified'  => false,
        ]);

        // Passer le rôle utilisateur à "driver"
        $request->user()->update(['role' => 'driver']);

        return response()->json([
            'message' => 'Profil conducteur créé. En attente de vérification.',
            'profile' => $profile,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request): JsonResponse
    {
        $profile = $request->user()->driverProfile;

        if (!$profile) {
            return response()->json(['message' => 'Profil conducteur introuvable.'], 404);
        }

        return response()->json($profile->load('vehicles', 'user:id,first_name,last_name,email,avatar_url,rating_avg'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DriverProfile $driverProfile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request): JsonResponse
    {
        $profile = $request->user()->driverProfile;

        if (!$profile) {
            return response()->json(['message' => 'Profil conducteur introuvable.'], 404);
        }

        $data = $request->validate([
            'license_number'      => 'sometimes|string|max:50|unique:driver_profiles,license_number,' . $profile->id,
            'license_expiry'      => 'sometimes|date|after:today',
            'years_of_experience' => 'nullable|integer|min:0|max:60',
            'preferences'         => 'nullable|array',
        ]);

        $profile->update($data);

        return response()->json([
            'message' => 'Profil conducteur mis à jour.',
            'profile' => $profile->fresh(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DriverProfile $driverProfile)
    {
        //
    }

    /**
     * Vérifier le permis d'un conducteur (admin).
     */
    public function verify(DriverProfile $driverProfile): JsonResponse
    {
        $driverProfile->update(['is_license_verified' => true]);

        return response()->json(['message' => 'Permis de conduire vérifié.']);
    }

    /**
     * Affiche le profil public d'un conducteur.
     */
    public function publicProfile(User $user): JsonResponse
    {
        $profile = $user->driverProfile;

        if (!$profile) {
            return response()->json(['message' => 'Cet utilisateur n\'est pas conducteur.'], 404);
        }

        return response()->json([
            'driver' => [
                'id'              => $user->id,
                'first_name'      => $user->first_name,
                'last_name'       => substr($user->last_name, 0, 1) . '.',
                'avatar_url'      => $user->avatar_url,
                'rating_avg'      => $user->rating_avg,
                'rating_count'    => $user->rating_count,
                'bio'             => $user->bio,
            ],
            'profile' => [
                'years_of_experience' => $profile->years_of_experience,
                'is_license_verified' => $profile->is_license_verified,
                'preferences'         => $profile->preferences,
            ],
            'vehicles' => $profile->vehicles->map(fn($v) => [
                'brand'     => $v->brand,
                'model'     => $v->model,
                'year'      => $v->year,
                'color'     => $v->color,
                'fuel_type' => $v->fuel_type,
                'nb_seats'  => $v->nb_seats,
            ]),
        ]);
    }
}
