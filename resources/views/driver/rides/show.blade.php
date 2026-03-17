@extends('layouts.app')
@section('title','Détail Trajet — Conducteur')
@section('page-title','Détail du Trajet')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <div class="xl:col-span-2 space-y-5">
        {{-- Main info --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-start justify-between mb-5">
                <div>
                    <h2 class="text-2xl font-black text-gray-900">{{ $ride->origin->city }} → {{ $ride->destination->city }}</h2>
                    <p class="text-gray-400 mt-1">{{ $ride->departure_datetime->format('l d F Y à H:i') }}</p>
                </div>
                <span class="badge-{{ $ride->status }} text-sm px-3 py-1">{{ ucfirst($ride->status) }}</span>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-5">
                <div class="bg-primary-50 rounded-xl p-3 text-center"><p class="text-xs text-gray-500 mb-1">Prix/siège</p><p class="font-black text-primary">{{ number_format($ride->price_per_seat,0,',',' ') }} XAF</p></div>
                <div class="bg-gray-50 rounded-xl p-3 text-center"><p class="text-xs text-gray-500 mb-1">Places dispo</p><p class="font-black text-gray-800">{{ $ride->seats_available }}/{{ $ride->seats_total }}</p></div>
                <div class="bg-gray-50 rounded-xl p-3 text-center"><p class="text-xs text-gray-500 mb-1">Distance</p><p class="font-black text-gray-800">{{ $ride->distance_km ? $ride->distance_km.' km' : '—' }}</p></div>
                <div class="bg-secondary-50 rounded-xl p-3 text-center"><p class="text-xs text-gray-500 mb-1">CO₂ économisé</p><p class="font-black text-secondary">{{ $ride->co2_saved_kg ? $ride->co2_saved_kg.' kg' : '—' }}</p></div>
            </div>
            @if($ride->description)
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-sm text-gray-600 leading-relaxed">{{ $ride->description }}</p>
            </div>
            @endif
            <div class="flex gap-3 mt-5">
                @if(!in_array($ride->status, ['completed','cancelled']))
                <a href="{{ route('driver.rides.edit', $ride) }}" class="btn-primary text-xs">✏️ Modifier</a>
                <form action="{{ route('driver.rides.cancel', $ride) }}" method="POST" onsubmit="return confirm('Annuler ce trajet ? Les passagers seront notifiés.')">
                    @csrf
                    <button class="text-xs bg-red-50 text-red-600 hover:bg-red-100 px-4 py-2.5 rounded-xl font-semibold transition">Annuler le trajet</button>
                </form>
                @endif
            </div>
        </div>

        {{-- Passengers / Bookings --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
                <h3 class="font-bold text-gray-900 text-sm">👥 Passagers ({{ $ride->bookings->count() }})</h3>
                <a href="{{ route('driver.bookings.index') }}" class="text-primary text-xs font-semibold hover:underline">Voir toutes les réservations</a>
            </div>
            <div class="divide-y divide-gray-50">
            @forelse($ride->bookings as $booking)
            <div class="px-5 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-secondary-50 rounded-full flex items-center justify-center text-secondary-700 font-bold text-sm">
                        {{ strtoupper(substr($booking->passenger->first_name,0,1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $booking->passenger->first_name }} {{ $booking->passenger->last_name }}</p>
                        <p class="text-xs text-gray-400">{{ $booking->seats_booked }} place(s) · {{ number_format($booking->total_price,0,',',' ') }} XAF</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
                    @if($booking->status === 'pending')
                    <form action="{{ route('driver.bookings.confirm', $booking) }}" method="POST" class="inline">
                        @csrf <button class="text-xs bg-green-100 text-green-700 hover:bg-green-200 px-2.5 py-1 rounded-lg font-semibold">✅</button>
                    </form>
                    <form action="{{ route('driver.bookings.reject', $booking) }}" method="POST" class="inline">
                        @csrf <button class="text-xs bg-red-100 text-red-600 hover:bg-red-200 px-2.5 py-1 rounded-lg font-semibold">✕</button>
                    </form>
                    @endif
                </div>
            </div>
            @empty
            <div class="px-5 py-8 text-center text-gray-400">
                <p class="text-2xl mb-2">😴</p>
                <p class="text-sm">Aucune réservation pour ce trajet.</p>
            </div>
            @endforelse
            </div>
        </div>
    </div>

    {{-- Sidebar: Vehicle + Waypoints --}}
    <div class="space-y-5">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-bold text-gray-900 text-sm mb-4">🚗 Véhicule</h3>
            <div class="text-sm space-y-2 text-gray-600">
                <p><span class="font-semibold text-gray-800">{{ $ride->vehicle->brand }} {{ $ride->vehicle->model }}</span></p>
                <p>Couleur: {{ $ride->vehicle->color }}</p>
                <p>Immatriculation: <span class="font-mono">{{ $ride->vehicle->license_plate }}</span></p>
                <p>Carburant: {{ ucfirst($ride->vehicle->fuel_type) }}</p>
            </div>
        </div>
        @if($ride->waypoints->count())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-bold text-gray-900 text-sm mb-4">📍 Étapes</h3>
            <div class="space-y-3">
                @foreach($ride->waypoints as $wp)
                <div class="flex items-center gap-3 text-sm">
                    <div class="w-6 h-6 bg-primary-50 rounded-full flex items-center justify-center text-primary font-bold text-xs flex-shrink-0">{{ $wp->order }}</div>
                    <div>
                        <p class="font-medium text-gray-800">{{ $wp->location->label }}</p>
                        <p class="text-xs text-gray-400">{{ $wp->arrival_time?->format('H:i') ?? '—' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
