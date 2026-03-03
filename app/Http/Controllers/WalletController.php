<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
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
    public function show(Request $request): JsonResponse
    {
        $wallet = $request->user()->wallet;

        if (!$wallet) {
            return response()->json(['message' => 'Wallet introuvable.'], 404);
        }

        return response()->json($wallet->load('transactions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wallet $wallet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Wallet $wallet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wallet $wallet)
    {
        //
    }

    /**
     * Recharger le wallet.
     */
    public function topUp(Request $request): JsonResponse
    {
        $data = $request->validate([
            'amount'      => 'required|numeric|min:100',
            'description' => 'nullable|string|max:255',
        ]);

        $wallet = $request->user()->wallet;

        if (!$wallet) {
            return response()->json(['message' => 'Wallet introuvable.'], 404);
        }

        DB::transaction(function () use ($wallet, $data) {
            $wallet->increment('balance', $data['amount']);

            WalletTransaction::create([
                'wallet_id'   => $wallet->id,
                'type'        => 'credit',
                'amount'      => $data['amount'],
                'description' => $data['description'] ?? 'Recharge du wallet',
            ]);
        });

        return response()->json([
            'message'     => 'Wallet rechargé avec succès.',
            'new_balance' => $wallet->fresh()->balance,
        ]);
    }

    /**
     * Retrait depuis le wallet.
     */
    public function withdraw(Request $request): JsonResponse
    {
        $data = $request->validate([
            'amount'      => 'required|numeric|min:500',
            'description' => 'nullable|string|max:255',
        ]);

        $wallet = $request->user()->wallet;

        if (!$wallet) {
            return response()->json(['message' => 'Wallet introuvable.'], 404);
        }

        if ($wallet->balance < $data['amount']) {
            return response()->json(['message' => 'Solde insuffisant.'], 422);
        }

        DB::transaction(function () use ($wallet, $data) {
            $wallet->decrement('balance', $data['amount']);

            WalletTransaction::create([
                'wallet_id'   => $wallet->id,
                'type'        => 'debit',
                'amount'      => $data['amount'],
                'description' => $data['description'] ?? 'Retrait',
            ]);
        });

        return response()->json([
            'message'     => 'Retrait effectué avec succès.',
            'new_balance' => $wallet->fresh()->balance,
        ]);
    }

    /**
     * Historique des transactions paginé.
     */
    public function transactions(Request $request): JsonResponse
    {
        $wallet = $request->user()->wallet;

        if (!$wallet) {
            return response()->json(['message' => 'Wallet introuvable.'], 404);
        }

        $transactions = $wallet->transactions()
            ->when($request->type, fn($q, $t) => $q->where('type', $t))
            ->orderByDesc('created_at')
            ->paginate($request->per_page ?? 20);

        return response()->json($transactions);
    }

    /**
     * Transfert vers un autre utilisateur.
     */
    public function transfer(Request $request): JsonResponse
    {
        $data = $request->validate([
            'recipient_id' => 'required|uuid|exists:users,id',
            'amount'       => 'required|numeric|min:100',
            'description'  => 'nullable|string|max:255',
        ]);

        if ($data['recipient_id'] === $request->user()->id) {
            return response()->json(['message' => 'Vous ne pouvez pas vous transférer à vous-même.'], 422);
        }

        $senderWallet    = $request->user()->wallet;
        $recipientWallet = \App\Models\User::find($data['recipient_id'])->wallet;

        if (!$senderWallet || $senderWallet->balance < $data['amount']) {
            return response()->json(['message' => 'Solde insuffisant.'], 422);
        }
        if (!$recipientWallet) {
            return response()->json(['message' => 'Wallet du destinataire introuvable.'], 404);
        }

        DB::transaction(function () use ($senderWallet, $recipientWallet, $data, $request) {
            $desc = $data['description'] ?? 'Transfert';

            $senderWallet->decrement('balance', $data['amount']);
            WalletTransaction::create([
                'wallet_id'    => $senderWallet->id,
                'type'         => 'debit',
                'amount'       => $data['amount'],
                'description'  => "Transfert vers {$data['recipient_id']}: $desc",
                'reference_id' => $data['recipient_id'],
            ]);

            $recipientWallet->increment('balance', $data['amount']);
            WalletTransaction::create([
                'wallet_id'    => $recipientWallet->id,
                'type'         => 'credit',
                'amount'       => $data['amount'],
                'description'  => "Reçu de {$request->user()->id}: $desc",
                'reference_id' => $request->user()->id,
            ]);
        });

        return response()->json([
            'message'     => 'Transfert effectué avec succès.',
            'new_balance' => $senderWallet->fresh()->balance,
        ]);
    }
}
