<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $reports = Report::with('reporter:id,first_name,last_name', 'reportedUser:id,first_name,last_name', 'ride')
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->orderByDesc('created_at')
            ->paginate($request->per_page ?? 20);

        return response()->json($reports);
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
            'reported_user_id' => 'required|uuid|exists:users,id|different:' . $request->user()->id,
            'ride_id'          => 'nullable|uuid|exists:rides,id',
            'reason'           => 'required|in:spam,harassment,fraud,unsafe_behavior,other',
            'description'      => 'required|string|min:20|max:2000',
        ]);

        // Éviter les doublons
        $exists = Report::where('reporter_id', $request->user()->id)
            ->where('reported_user_id', $data['reported_user_id'])
            ->where('ride_id', $data['ride_id'] ?? null)
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Vous avez déjà signalé cet utilisateur pour ce trajet.'], 422);
        }

        $report = Report::create([
            ...$data,
            'reporter_id' => $request->user()->id,
            'status'      => 'pending',
        ]);

        return response()->json([
            'message' => 'Signalement soumis. Notre équipe va l\'examiner.',
            'report'  => $report,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report): JsonResponse
    {
        return response()->json($report->load('reporter', 'reportedUser', 'ride'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        //
    }

    /**
     * Mettre à jour le statut d'un signalement (admin).
     */
    public function updateStatus(Request $request, Report $report): JsonResponse
    {
        $data = $request->validate([
            'status' => 'required|in:pending,reviewed,resolved,dismissed',
        ]);

        $report->update($data);

        return response()->json(['message' => 'Statut du signalement mis à jour.', 'report' => $report->fresh()]);
    }
}
