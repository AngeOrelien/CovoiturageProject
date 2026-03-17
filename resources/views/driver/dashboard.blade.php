@extends('layouts.app')
@section('title','Conducteur — Dashboard')
@section('page-title','Mon Espace Conducteur')
@section('page-subtitle','Bonjour '.$user->first_name.' 👋 Gérez vos trajets et revenus')

@section('content')

{{-- Driver profile alert if incomplete --}}
@if(!$user->driverProfile || !$user->driverProfile->is_license_verified)
<div class="mb-6 px-5 py-4 bg-yellow-50 border border-yellow-200 rounded-2xl flex items-center justify-between">
    <div class="flex items-center gap-3">
        <span class="text-2xl">⚠️</span>
        <div>
            <p class="font-bold text-yellow-800 text-sm">{{ !$user->driverProfile ? 'Profil conducteur incomplet' : 'Permis en attente de vérification' }}</p>
            <p class="text-xs text-yellow-600">{{ !$user->driverProfile ? 'Ajoutez votre véhicule pour publier des trajets.' : 'Votre permis est en cours de vérification par l\'administrateur.' }}</p>
        </div>
    </div>
    @if(!$user->driverProfile)
    <a href="{{ route('driver.vehicles.create') }}" class="bg-yellow-500 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-yellow-600 transition">Compléter →</a>
    @endif
</div>
@endif

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @foreach([
        ['label'=>'Trajets publiés','value'=>$stats['rides_total'],'icon'=>'🗺️','color'=>'text-primary','bg'=>'bg-primary-50'],
        ['label'=>'Trajets complétés','value'=>$stats['rides_completed'],'icon'=>'✅','color'=>'text-secondary','bg'=>'bg-secondary-50'],
        ['label'=>'Trajets à venir','value'=>$stats['rides_upcoming'],'icon'=>'📅','color'=>'text-blue-600','bg'=>'bg-blue-50'],
        ['label'=>'Solde portefeuille','value'=>number_format($stats['wallet_balance'],0,',',' ').' XAF','icon'=>'💰','color'=>'text-yellow-600','bg'=>'bg-yellow-50'],
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

{{-- Rating + CTA --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
    <div class="bg-gradient-to-br from-primary to-primary-700 rounded-2xl p-6 text-white">
        <div class="text-3xl font-black mb-1">{{ number_format($stats['avg_rating'],1) }}</div>
        <div class="flex items-center gap-1 mb-2">
            @for($i=1;$i<=5;$i++)
            <span class="text-xl {{ $i <= round($stats['avg_rating']) ? 'text-yellow-300' : 'text-white/30' }}">★</span>
            @endfor
        </div>
        <p class="text-white/80 text-sm">{{ $stats['rating_count'] }} évaluation(s)</p>
    </div>
    <div class="md:col-span-2 bg-secondary-50 border-2 border-secondary/20 rounded-2xl p-6 flex items-center justify-between">
        <div>
            <p class="font-black text-gray-900 text-lg">Publier un nouveau trajet</p>
            <p class="text-gray-500 text-sm mt-1">Proposez vos places disponibles et économisez sur vos frais.</p>
        </div>
        <a href="{{ route('driver.rides.create') }}" class="btn-secondary flex-shrink-0">+ Nouveau trajet</a>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

    {{-- Upcoming rides --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
            <h3 class="font-bold text-gray-900 text-sm">📅 Prochains trajets</h3>
            <a href="{{ route('driver.rides.index') }}" class="text-primary text-xs font-semibold hover:underline">Voir tous</a>
        </div>
        <div class="divide-y divide-gray-50">
        @forelse($upcomingRides as $ride)
        <div class="px-5 py-4">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <p class="font-bold text-gray-900 text-sm">{{ $ride->origin->city }} → {{ $ride->destination->city }}</p>
                    <p class="text-xs text-gray-400">{{ $ride->departure_datetime->format('d/m/Y à H:i') }}</p>
                </div>
                <div class="text-right">
                    <p class="font-black text-primary text-sm">{{ number_format($ride->price_per_seat,0,',',' ') }} XAF</p>
                    <p class="text-xs text-gray-400">{{ $ride->bookings->count() }}/{{ $ride->seats_total }} passagers</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex-1 bg-gray-100 rounded-full h-1.5">
                    <div class="bg-primary rounded-full h-1.5" style="width: {{ $ride->seats_total > 0 ? ($ride->bookings->count() / $ride->seats_total * 100) : 0 }}%"></div>
                </div>
                <a href="{{ route('driver.rides.show', $ride) }}" class="text-xs text-primary font-semibold hover:underline">Gérer →</a>
            </div>
        </div>
        @empty
        <div class="px-5 py-8 text-center text-gray-400">
            <p class="text-3xl mb-2">🚗</p>
            <p class="text-sm">Aucun trajet à venir.</p>
            <a href="{{ route('driver.rides.create') }}" class="text-primary text-xs font-semibold mt-2 inline-block hover:underline">Publier maintenant →</a>
        </div>
        @endforelse
        </div>
    </div>

    {{-- Pending bookings --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
            <h3 class="font-bold text-gray-900 text-sm">📋 Réservations en attente</h3>
            <a href="{{ route('driver.bookings.index') }}" class="text-primary text-xs font-semibold hover:underline">Voir toutes</a>
        </div>
        <div class="divide-y divide-gray-50">
        @forelse($pendingBookings as $booking)
        <div class="px-5 py-4">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-secondary-50 rounded-full flex items-center justify-center text-secondary-700 font-bold text-sm">{{ strtoupper(substr($booking->passenger->first_name,0,1)) }}</div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $booking->passenger->first_name }} {{ $booking->passenger->last_name }}</p>
                        <p class="text-xs text-gray-400">{{ $booking->seats_booked }} place(s) · {{ $booking->ride->origin->city }} → {{ $booking->ride->destination->city }}</p>
                    </div>
                </div>
                <p class="font-black text-gray-900 text-sm">{{ number_format($booking->total_price,0,',',' ') }} XAF</p>
            </div>
            <div class="flex gap-2">
                <form action="{{ route('driver.bookings.confirm', $booking) }}" method="POST">
                    @csrf
                    <button class="text-xs bg-green-100 text-green-700 hover:bg-green-200 px-3 py-1.5 rounded-lg font-semibold transition">✅ Confirmer</button>
                </form>
                <form action="{{ route('driver.bookings.reject', $booking) }}" method="POST">
                    @csrf
                    <button class="text-xs bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1.5 rounded-lg font-semibold transition">✕ Refuser</button>
                </form>
            </div>
        </div>
        @empty
        <div class="px-5 py-8 text-center text-gray-400">
            <p class="text-3xl mb-2">📭</p>
            <p class="text-sm">Aucune réservation en attente.</p>
        </div>
        @endforelse
        </div>
    </div>

    {{-- Wallet transactions --}}
    @if($recentTransactions && $recentTransactions->count())
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
            <h3 class="font-bold text-gray-900 text-sm">💳 Dernières transactions</h3>
        </div>
        <div class="divide-y divide-gray-50">
        @foreach($recentTransactions as $tx)
        <div class="px-5 py-3 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-700">{{ $tx->description ?? 'Transaction' }}</p>
                <p class="text-xs text-gray-400">{{ $tx->created_at->diffForHumans() }}</p>
            </div>
            <span class="font-black text-sm {{ $tx->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                {{ $tx->type === 'credit' ? '+' : '-' }}{{ number_format($tx->amount,0,',',' ') }} XAF
            </span>
        </div>
        @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
