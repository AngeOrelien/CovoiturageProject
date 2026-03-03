<?php

namespace App\Http\Controllers;

use App\Models\RideSearch;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RideSearchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $searches = $request->user()
            ->rideSearches()
            ->with('origin', 'destination')
            ->orderByDesc('created_at')
            ->get();

        return response()->json($searches);
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
            'origin_id'      => 'required|uuid|exists:locations,id',
            'destination_id' => 'required|uuid|exists:locations,id',
            'desired_date'   => 'required|date|after_or_equal:today',
            'seats_needed'   => 'nullable|integer|min:1|max:8',
            'max_price'      => 'nullable|numeric|min:0',
            'is_alert_active'=> 'boolean',
        ]);

        $search = $request->user()->rideSearches()->create([
            ...$data,
            'is_alert_active' => $data['is_alert_active'] ?? true,
        ]);

        return response()->json([
            'message' => 'Alerte de recherche créée.',
            'search'  => $search->load('origin', 'destination'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(RideSearch $rideSearch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RideSearch $rideSearch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RideSearch $rideSearch)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RideSearch $rideSearch)
    {
        $rideSearch->delete();

        return response()->json(['message' => 'Alerte de recherche supprimée.']);
    }

    /**
     * Activer / désactiver une alerte.
     */
    public function toggleAlert(\App\Models\RideSearch $rideSearch): JsonResponse
    {
        $rideSearch->update(['is_alert_active' => !$rideSearch->is_alert_active]);

        $status = $rideSearch->is_alert_active ? 'activée' : 'désactivée';
        return response()->json(['message' => "Alerte $status.", 'is_alert_active' => $rideSearch->is_alert_active]);
    }
}
