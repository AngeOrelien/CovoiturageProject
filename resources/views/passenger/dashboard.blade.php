@extends('layouts.app')
@section('title','Passager — Dashboard')
@section('page-title','Mon Espace')
@section('page-subtitle','Bonjour '.$user->first_name.' 👋 Prêt pour votre prochain voyage ?')

@section('content')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @foreach([
        ['label'=>'Réservations','value'=>$stats['bookings_total'],'icon'=>'📋','color'=>'text-primary','bg'=>'bg-primary-50'],
        ['label'=>'Voyages effectués','value'=>$stats['bookings_completed'],'icon'=>'✅','color'=>'text-secondary','bg'=>'bg-secondary-50'],
        ['label'=>'À venir','value'=>$stats['bookings_upcoming'],'icon'=>'📅','color'=>'text-blue-600','bg'=>'bg-blue-50'],
        ['label'=>'Solde portefeuille','value'=>number_format($stats['wallet_balance'],0,',',' ').' XAF','icon'=>'💳','color'=>'text-yellow-600','bg'=>'bg-yellow-50'],
    ] as $stat)
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ $stat['label'] }}</span>
            <div class="w-8 h-8 {{ $stat['bg'] }} rounded-xl flex items-center justify-center text-base">{{ $stat['icon'] }}</div>
        </div>
        <p class="text-2xl font-black {{ $stat['color'] }}">{{ $stat['value'] }}</p>
    </div>
    @endforeach
</div>

{{-- Quick search bar --}}
<div class="bg-gradient-to-r from-primary to-primary-700 rounded-2xl p-6 mb-8 text-white">
    <h3 class="font-black text-xl mb-4">🔍 Trouvez votre prochain trajet</h3>
    <form action="{{ route('passenger.rides.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
        <input type="text" name="origin" placeholder="🏙️ Départ" class="flex-1 px-4 py-3 rounded-xl text-sm text-gray-700 focus:outline-none bg-white/95">
        <input type="text" name="destination" placeholder="📍 Destination" class="flex-1 px-4 py-3 rounded-xl text-sm text-gray-700 focus:outline-none bg-white/95">
        <input type="date" name="date" class="px-4 py-3 rounded-xl text-sm text-gray-700 focus:outline-none bg-white/95">
        <button class="bg-secondary text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-secondary-600 transition flex-shrink-0">Rechercher →</button>
    </form>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
    {{-- Upcoming bookings --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
            <h3 class="font-bold text-gray-900 text-sm">📅 Prochains voyages</h3>
            <a href="{{ route('passenger.bookings.index') }}" class="text-primary text-xs font-semibold hover:underline">Voir tous</a>
        </div>
        <div class="divide-y divide-gray-50">
        @forelse($upcomingBookings as $booking)
        <div class="px-5 py-4">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <p class="font-bold text-gray-900 text-sm">{{ $booking->ride->origin->city }} → {{ $booking->ride->destination->city }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $booking->ride->departure_datetime->format('d/m/Y à H:i') }}</p>
                </div>
                <span class="badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <div class="w-6 h-6 bg-primary-50 rounded-full flex items-center justify-center text-primary font-bold text-xs">{{ strtoupper(substr($booking->ride->driver->first_name,0,1)) }}</div>
                    <span>Conducteur: {{ $booking->ride->driver->first_name }}</span>
                </div>
                <a href="{{ route('passenger.bookings.show', $booking) }}" class="text-primary text-xs font-semibold hover:underline">Détails →</a>
            </div>
        </div>
        @empty
        <div class="px-5 py-10 text-center text-gray-400">
            <p class="text-3xl mb-2">🗺️</p>
            <p class="text-sm font-medium">Aucun voyage à venir</p>
            <a href="{{ route('passenger.rides.index') }}" class="text-secondary font-semibold text-xs mt-2 inline-block hover:underline">Chercher un trajet →</a>
        </div>
        @endforelse
        </div>
    </div>

    {{-- Notifications + wallet --}}
    <div class="space-y-5">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
                <h3 class="font-bold text-gray-900 text-sm">🔔 Notifications récentes</h3>
                <a href="{{ route('notifications.index') }}" class="text-primary text-xs font-semibold hover:underline">Toutes</a>
            </div>
            <div class="divide-y divide-gray-50">
            @forelse($notifications as $notif)
            <div class="px-5 py-3 flex items-start gap-3 {{ !$notif->is_read ? 'bg-primary-50/30' : '' }}">
                <div class="w-2 h-2 rounded-full mt-1.5 flex-shrink-0 {{ !$notif->is_read ? 'bg-primary' : 'bg-gray-300' }}"></div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900">{{ $notif->title }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $notif->body }}</p>
                    <p class="text-xs text-gray-300 mt-0.5">{{ $notif->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <p class="px-5 py-6 text-center text-gray-400 text-sm">Aucune notification.</p>
            @endforelse
            </div>
        </div>

        @if($recentTransactions && $recentTransactions->count())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
                <h3 class="font-bold text-gray-900 text-sm">💳 Dernières transactions</h3>
                <a href="{{ route('passenger.wallet.index') }}" class="text-primary text-xs font-semibold hover:underline">Portefeuille</a>
            </div>
            <div class="divide-y divide-gray-50">
            @foreach($recentTransactions as $tx)
            <div class="px-5 py-3 flex items-center justify-between">
                <p class="text-sm text-gray-700">{{ $tx->description ?? 'Transaction' }}</p>
                <span class="font-black text-sm {{ $tx->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                    {{ $tx->type === 'credit' ? '+' : '-' }}{{ number_format($tx->amount,0,',',' ') }} XAF
                </span>
            </div>
            @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
