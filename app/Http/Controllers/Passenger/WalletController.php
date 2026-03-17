<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Models\{Wallet, WalletTransaction};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        $wallet       = Auth::user()->wallet;
        $transactions = $wallet?->transactions()->latest('created_at')->paginate(15);
        return view('passenger.wallet.index', compact('wallet', 'transactions'));
    }

    public function topup(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:500|max:500000',
        ]);

        $user   = Auth::user();
        $wallet = $user->wallet ?? Wallet::create(['user_id' => $user->id, 'balance' => 0, 'currency' => 'XAF']);

        $wallet->increment('balance', $data['amount']);
        WalletTransaction::create([
            'wallet_id'   => $wallet->id,
            'type'        => 'credit',
            'amount'      => $data['amount'],
            'description' => 'Rechargement portefeuille',
        ]);

        return back()->with('success', number_format($data['amount'], 0, ',', ' ').' XAF ajoutés à votre portefeuille.');
    }
}
