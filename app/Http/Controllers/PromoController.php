<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $promos = \App\Models\Promo::query()
            ->when($request->is_active !== null, fn($q) =>
                $q->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN))
            )
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json($promos);
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
            'code'               => 'required|string|uppercase|unique:promos,code|max:20',
            'discount_type'      => 'required|in:percentage,fixed',
            'discount_value'     => 'required|numeric|min:0',
            'min_booking_amount' => 'nullable|numeric|min:0',
            'max_uses'           => 'nullable|integer|min:1',
            'expires_at'         => 'nullable|date|after:now',
            'is_active'          => 'boolean',
        ]);

        if ($data['discount_type'] === 'percentage' && $data['discount_value'] > 100) {
            return response()->json(['message' => 'La remise en pourcentage ne peut pas dépasser 100%.'], 422);
        }

        $promo = \App\Models\Promo::create([...$data, 'used_count' => 0]);

        return response()->json(['message' => 'Code promo créé.', 'promo' => $promo], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Promo $promo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Promo $promo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Promo $promo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\Promo $promo): JsonResponse
    {
        $promo->delete();
        return response()->json(['message' => 'Code promo supprimé.']);
    }

    /**
     * Valider un code promo (passager).
     */
    public function validate(Request $request): JsonResponse
    {
        $request->validate([
            'code'           => 'required|string',
            'booking_amount' => 'required|numeric|min:0',
        ]);

        $promo = \App\Models\Promo::where('code', strtoupper($request->code))->first();

        if (!$promo) {
            return response()->json(['message' => 'Code promo invalide.'], 404);
        }
        if (!$promo->is_active) {
            return response()->json(['message' => 'Ce code promo est désactivé.'], 422);
        }
        if ($promo->expires_at && $promo->expires_at->isPast()) {
            return response()->json(['message' => 'Ce code promo a expiré.'], 422);
        }
        if ($promo->max_uses && $promo->used_count >= $promo->max_uses) {
            return response()->json(['message' => 'Ce code promo a atteint sa limite d\'utilisation.'], 422);
        }
        if ($promo->min_booking_amount && $request->booking_amount < $promo->min_booking_amount) {
            return response()->json([
                'message' => "Montant minimum requis : {$promo->min_booking_amount}.",
            ], 422);
        }

        $discount = $promo->discount_type === 'percentage'
            ? ($request->booking_amount * $promo->discount_value / 100)
            : $promo->discount_value;

        $finalAmount = max(0, $request->booking_amount - $discount);

        return response()->json([
            'valid'         => true,
            'promo'         => $promo,
            'discount'      => round($discount, 2),
            'final_amount'  => round($finalAmount, 2),
        ]);
    }

    /**
     * Activer / désactiver un promo (admin).
     */
    public function toggleActive(\App\Models\Promo $promo): JsonResponse
    {
        $promo->update(['is_active' => !$promo->is_active]);

        $status = $promo->is_active ? 'activé' : 'désactivé';
        return response()->json(['message' => "Code promo $status.", 'is_active' => $promo->is_active]);
    }
}
