<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\WalletTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }

    /**
     * Initier un paiement pour une réservation.
     */
    public function pay(Request $request, Booking $booking): JsonResponse
    {
        if ($booking->status !== 'confirmed') {
            return response()->json(['message' => 'La réservation doit être confirmée avant paiement.'], 422);
        }

        if ($booking->payment()->exists()) {
            return response()->json(['message' => 'Cette réservation a déjà été payée.'], 422);
        }

        $data = $request->validate([
            'method'   => 'required|in:wallet,mobile_money,card,cash',
            'currency' => 'nullable|string|size:3',
        ]);

        $payment = DB::transaction(function () use ($data, $booking, $request) {
            $user = $request->user();

            // Paiement par wallet
            if ($data['method'] === 'wallet') {
                $wallet = $user->wallet;
                if (!$wallet || $wallet->balance < $booking->total_price) {
                    throw new \Exception('Solde insuffisant dans le wallet.');
                }

                $wallet->decrement('balance', $booking->total_price);

                WalletTransaction::create([
                    'wallet_id'    => $wallet->id,
                    'type'         => 'debit',
                    'amount'       => $booking->total_price,
                    'description'  => "Paiement réservation #{$booking->id}",
                    'reference_id' => $booking->id,
                ]);
            }

            $payment = Payment::create([
                'booking_id'     => $booking->id,
                'payer_id'       => $user->id,
                'amount'         => $booking->total_price,
                'currency'       => $data['currency'] ?? 'XAF',
                'method'         => $data['method'],
                'status'         => $data['method'] === 'wallet' ? 'completed' : 'pending',
                'transaction_id' => Str::uuid(),
                'paid_at'        => $data['method'] === 'wallet' ? now() : null,
            ]);

            return $payment;
        });

        return response()->json([
            'message' => 'Paiement initié.',
            'payment' => $payment,
        ], 201);
    }

    /**
     * Confirmer un paiement (callback passerelle externe).
     */
    public function confirm(Request $request, Payment $payment): JsonResponse
    {
        $request->validate([
            'transaction_id' => 'required|string',
        ]);

        if ($payment->status === 'completed') {
            return response()->json(['message' => 'Paiement déjà confirmé.']);
        }

        $payment->update([
            'status'         => 'completed',
            'transaction_id' => $request->transaction_id,
            'paid_at'        => now(),
        ]);

        return response()->json(['message' => 'Paiement confirmé.', 'payment' => $payment->fresh()]);
    }

    /**
     * Rembourser un paiement.
     */
    public function refund(Payment $payment): JsonResponse
    {
        if ($payment->status !== 'completed') {
            return response()->json(['message' => 'Seuls les paiements complétés peuvent être remboursés.'], 422);
        }

        DB::transaction(function () use ($payment) {
            $payment->update(['status' => 'refunded']);

            // Créditer le wallet du payeur
            $payer = $payment->payer;
            if ($payer->wallet) {
                $payer->wallet->increment('balance', $payment->amount);

                WalletTransaction::create([
                    'wallet_id'    => $payer->wallet->id,
                    'type'         => 'credit',
                    'amount'       => $payment->amount,
                    'description'  => "Remboursement réservation #{$payment->booking_id}",
                    'reference_id' => $payment->booking_id,
                ]);
            }
        });

        return response()->json(['message' => 'Paiement remboursé.', 'payment' => $payment->fresh()]);
    }

    /**
     * Afficher le paiement d'une réservation.
     */
    public function showByBooking(Booking $booking): JsonResponse
    {
        $payment = $booking->payment;

        if (!$payment) {
            return response()->json(['message' => 'Aucun paiement pour cette réservation.'], 404);
        }

        return response()->json($payment);
    }
}
