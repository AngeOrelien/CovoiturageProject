@extends('layouts.app')
@section('title','Détail Trajet — Admin')
@section('page-title','Détail du Trajet')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <div class="xl:col-span-2 space-y-5">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-xl font-black text-gray-900">{{ $ride->origin->city }} → {{ $ride->destination->city }}</h2>
                    <p class="text-gray-400 text-sm">{{ $ride->departure_datetime->format('d/m/Y à H:i') }}</p>
                </div>
                <span class="badge-{{ $ride->status }} text-sm px-3 py-1">{{ ucfirst($ride->status) }}</span>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div class="bg-gray-50 rounded-xl p-3"><p class="text-gray-400 text-xs mb-1">Prix/Siège</p><p class="font-black text-primary">{{ number_format($ride->price_per_seat,0,',',' ') }} XAF</p></div>
                <div class="bg-gray-50 rounded-xl p-3"><p class="text-gray-400 text-xs mb-1">Places</p><p class="font-bold">{{ $ride->seats_available }}/{{ $ride->seats_total }}</p></div>
                <div class="bg-gray-50 rounded-xl p-3"><p class="text-gray-400 text-xs mb-1">Distance</p><p class="font-bold">{{ $ride->distance_km ? $ride->distance_km.' km' : '—' }}</p></div>
                <div class="bg-gray-50 rounded-xl p-3"><p class="text-gray-400 text-xs mb-1">Durée</p><p class="font-bold">{{ $ride->duration_min ? $ride->duration_min.' min' : '—' }}</p></div>
            </div>
            @if($ride->description)<p class="mt-4 text-sm text-gray-600 bg-gray-50 rounded-xl p-4">{{ $ride->description }}</p>@endif
        </div>

        {{-- Passengers --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 text-sm mb-4">Passagers ({{ $ride->bookings->count() }})</h3>
            @forelse($ride->bookings as $booking)
            <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-secondary-50 rounded-full flex items-center justify-center text-secondary-700 font-bold text-xs">{{ strtoupper(substr($booking->passenger->first_name,0,1)) }}</div>
                    <div>
                        <p class="text-sm font-medium">{{ $booking->passenger->first_name }} {{ $booking->passenger->last_name }}</p>
                        <p class="text-xs text-gray-400">{{ $booking->seats_booked }} place(s) · {{ number_format($booking->total_price,0,',',' ') }} XAF</p>
                    </div>
                </div>
                <span class="badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
            </div>
            @empty<p class="text-gray-400 text-sm">Aucune réservation.</p>@endforelse
        </div>
    </div>

    <div class="space-y-5">
        {{-- Driver info --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 text-sm mb-4">Conducteur</h3>
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 bg-primary-50 rounded-full flex items-center justify-center text-primary font-black">{{ strtoupper(substr($ride->driver->first_name,0,1)) }}</div>
                <div>
                    <p class="font-bold text-gray-900">{{ $ride->driver->first_name }} {{ $ride->driver->last_name }}</p>
                    <div class="flex items-center gap-1 text-xs text-gray-500">
                        <span class="text-yellow-400">★</span> {{ number_format($ride->driver->rating_avg,1) }}
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.users.show', $ride->driver) }}" class="btn-outline w-full justify-center text-xs">Voir le profil</a>
        </div>
        {{-- Vehicle --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 text-sm mb-3">Véhicule</h3>
            <div class="text-sm space-y-2 text-gray-600">
                <p><span class="font-semibold">Marque:</span> {{ $ride->vehicle->brand }} {{ $ride->vehicle->model }}</p>
                <p><span class="font-semibold">Couleur:</span> {{ $ride->vehicle->color }}</p>
                <p><span class="font-semibold">Immat.:</span> {{ $ride->vehicle->license_plate }}</p>
                <p><span class="font-semibold">Places:</span> {{ $ride->vehicle->nb_seats }}</p>
            </div>
        </div>
        @if(!in_array($ride->status, ['completed','cancelled']))
        <form action="{{ route('admin.rides.cancel', $ride) }}" method="POST" onsubmit="return confirm('Annuler ce trajet ?')">
            @csrf
            <button class="w-full bg-red-50 text-red-600 hover:bg-red-600 hover:text-white px-4 py-3 rounded-xl font-bold text-sm transition">Annuler ce trajet</button>
        </form>
        @endif
    </div>
</div>
@endsection
