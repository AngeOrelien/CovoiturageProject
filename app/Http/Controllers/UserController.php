<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $users = User::all();
        return response()->json($users);
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
    public function store(Request $request): void
    {
        //
        $request->validate([
            "name"=> "required",
            "email"=> "required",
            "password"=> "required",
            ]);
            $user = User::create([
                "name"=> $request->name,
                "email"=> $request->email
            ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        //
        return response()->json(
            $user->load('driverProfile.vehicles', 'wallet', 'reviews', 'rideSearches')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): JsonResponse
    {
        //
        $data = $request->validate([
            'first_name'    => 'sometimes|string|max:100',
            'last_name'     => 'sometimes|string|max:100',
            'email'         => 'sometimes|email|unique:users,email,' . $user->id,
            'role'          => 'sometimes|in:passenger,driver,admin',
            'is_active'     => 'sometimes|boolean',
            'is_verified'   => 'sometimes|boolean',
            'date_of_birth' => 'nullable|date',
            'gender'        => 'nullable|in:male,female,other',
            'bio'           => 'nullable|string|max:500',
            'avatar_url'    => 'nullable|url',
        ]);

        $user->update($data);

        return response()->json([
            'message' => 'Utilisateur mis à jour.',
            'user'    => $user->fresh(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        //
        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé.']);
    }

    /**
     * Activer / désactiver un compte (admin).
     */
    public function toggleActive(User $user): JsonResponse
    {
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activé' : 'désactivé';

        return response()->json([
            'message'   => "Compte $status avec succès.",
            'is_active' => $user->is_active,
        ]);
    }

    /**
     * Statistiques d'un utilisateur.
     */
    public function stats(User $user): JsonResponse
    {

        return response()->json([
            'total_rides_as_driver'    => $user->rides()->count(),
            'total_bookings'           => $user->bookings()->count(),
            'total_reviews_given'      => $user->reviews()->count(),
            'total_reviews_received'   => $user->hasMany(\App\Models\Review::class, 'reviewee_id')->count(),
            'rating_avg'               => $user->rating_avg,
            'rating_count'             => $user->rating_count,
            'wallet_balance'           => $user->wallet?->balance,
        ]);
    }
}
