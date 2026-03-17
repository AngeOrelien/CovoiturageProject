@extends('layouts.app')
@section('title','Mon Portefeuille')
@section('page-title','Mon Portefeuille')
@section('page-subtitle','Gérez votre solde TGether')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    {{-- Balance card --}}
    <div class="xl:col-span-1 space-y-5">
        <div class="bg-gradient-to-br from-primary to-primary-700 rounded-2xl p-6 text-white shadow-lg shadow-primary/30">
            <p class="text-white/70 text-sm font-semibold mb-2">Solde disponible</p>
            <p class="text-4xl font-black mb-1">{{ number_format($wallet?->balance ?? 0, 0, ',', ' ') }}</p>
            <p class="text-white/70 text-lg">{{ $wallet?->currency ?? 'XAF' }}</p>
        </div>

        {{-- Top-up form --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-bold text-gray-900 text-sm mb-4">💳 Recharger mon portefeuille</h3>
            <form action="{{ route('passenger.wallet.topup') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Montant (XAF)</label>
                    <input type="number" name="amount" min="500" max="500000" step="500" required
                        class="input-field" placeholder="Ex: 10000">
                    <p class="text-xs text-gray-400 mt-1">Minimum: 500 XAF · Maximum: 500 000 XAF</p>
                </div>

                {{-- Quick amounts --}}
                <div class="grid grid-cols-3 gap-2">
                    @foreach([2000,5000,10000,20000,50000,100000] as $amount)
                    <button type="button" onclick="document.querySelector('[name=amount]').value={{ $amount }}"
                        class="text-xs bg-gray-50 hover:bg-primary-50 hover:text-primary border border-gray-200 hover:border-primary text-gray-600 px-2 py-2 rounded-xl font-semibold transition">
                        {{ number_format($amount,0,',',' ') }}
                    </button>
                    @endforeach
                </div>

                <div class="bg-blue-50 rounded-xl p-3 text-xs text-blue-700">
                    ℹ️ Simulation de recharge — dans un environnement de production, vous seriez redirigé vers un opérateur de paiement.
                </div>

                <button class="w-full btn-primary justify-center py-3">⚡ Recharger maintenant</button>
            </form>
        </div>
    </div>

    {{-- Transaction history --}}
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-50">
            <h3 class="font-bold text-gray-900 text-sm">📊 Historique des transactions</h3>
        </div>
        <div class="divide-y divide-gray-50">
        @forelse($transactions as $tx)
        <div class="px-5 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-lg {{ $tx->type === 'credit' ? 'bg-green-50' : 'bg-red-50' }}">
                    {{ $tx->type === 'credit' ? '⬇️' : '⬆️' }}
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">{{ $tx->description ?? 'Transaction' }}</p>
                    @if($tx->reference_id)<p class="text-xs text-gray-400 font-mono">Réf: {{ Str::limit($tx->reference_id, 20) }}</p>@endif
                    <p class="text-xs text-gray-300 mt-0.5">{{ $tx->created_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
            <div class="text-right">
                <p class="font-black text-base {{ $tx->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                    {{ $tx->type === 'credit' ? '+' : '-' }}{{ number_format($tx->amount, 0, ',', ' ') }}
                </p>
                <p class="text-xs text-gray-400">XAF</p>
            </div>
        </div>
        @empty
        <div class="px-5 py-14 text-center text-gray-400">
            <p class="text-4xl mb-3">📭</p>
            <p class="text-sm font-medium">Aucune transaction pour l'instant.</p>
        </div>
        @endforelse
        </div>
        @if($transactions && $transactions->hasPages())
        <div class="px-5 py-4 border-t border-gray-50">{{ $transactions->links() }}</div>
        @endif
    </div>
</div>
@endsection
