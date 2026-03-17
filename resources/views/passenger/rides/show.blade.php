@extends('layouts.app')
@section('title','Détail Trajet')
@section('page-title','Détail du Trajet')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    {{-- Main details --}}
    <div class="xl:col-span-2 space-y-5">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-start justify-between mb-5">
                <div>
                    <h2 class="text-2xl font-black text-gray-900">{{ $ride->origin->city }} → {{ $ride->destination->city }}</h2>
                    <p class="text-gray-400 mt-1">{{ $ride->departure_datetime->translatedFormat('l d F Y à H:i') }}</p>
                </div>
                <span class="badge-scheduled px-3 py-1">Disponible</span>
            </div>

            {{-- Route visual --}}
            <div class="bg-gray-50 rounded-xl p-4 mb-5 space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full bg-primary flex-shrink-0"></div>
                    <div class="flex-1">
                        <p class="font-bold text-gray-800 text-sm">{{ $ride->origin->city }}</p>
                        <p class="text-xs text-gray-400">{{ $ride->origin->label }}</p>
                    </div>
                    <p class="text-sm font-mono text-gray-600">{{ $ride->departure_datetime->format('H:i') }}</p>
                </div>
                @foreach($ride->waypoints as $wp)
                <div class="ml-1 pl-4 border-l-2 border-dashed border-gray-300 py-1">
                    <div class="flex items-center gap-3">
                        <div class="w-2.5 h-2.5 rounded-full bg-yellow-400 flex-shrink-0"></div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-700 text-sm">{{ $wp->location->city }}</p>
                            <p class="text-xs text-gray-400">Étape {{ $wp->order }}</p>
                        </div>
                        @if($wp->arrival_time)<p class="text-xs font-mono text-gray-400">{{ $wp->arrival_time->format('H:i') }}</p>@endif
                    </div>
                </div>
                @endforeach
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full bg-secondary flex-shrink-0"></div>
                    <div class="flex-1">
                        <p class="font-bold text-gray-800 text-sm">{{ $ride->destination->city }}</p>
                        <p class="text-xs text-gray-400">{{ $ride->destination->label }}</p>
                    </div>
                    @if($ride->arrival_datetime)<p class="text-sm font-mono text-gray-600">{{ $ride->arrival_datetime->format('H:i') }}</p>@endif
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                <div class="bg-primary-50 rounded-xl p-3 text-center"><p class="text-xs text-gray-500">Prix/place</p><p class="font-black text-primary">{{ number_format($ride->price_per_seat,0,',',' ') }} XAF</p></div>
                <div class="bg-gray-50 rounded-xl p-3 text-center"><p class="text-xs text-gray-500">Places dispo</p><p class="font-black text-gray-800">{{ $ride->seats_available }}</p></div>
                @if($ride->distance_km)<div class="bg-gray-50 rounded-xl p-3 text-center"><p class="text-xs text-gray-500">Distance</p><p class="font-black text-gray-800">{{ $ride->distance_km }} km</p></div>@endif
                @if($ride->duration_min)<div class="bg-gray-50 rounded-xl p-3 text-center"><p class="text-xs text-gray-500">Durée</p><p class="font-black text-gray-800">{{ $ride->duration_min }} min</p></div>@endif
            </div>

            @if($ride->description)
            <div class="mt-4 bg-gray-50 rounded-xl p-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Description</p>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $ride->description }}</p>
            </div>
            @endif
        </div>

        {{-- Vehicle --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-bold text-gray-900 text-sm mb-3">🚗 Véhicule</h3>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center text-2xl">🚙</div>
                <div class="text-sm text-gray-600">
                    <p class="font-bold text-gray-900">{{ $ride->vehicle->brand }} {{ $ride->vehicle->model }} ({{ $ride->vehicle->year }})</p>
                    <p>{{ $ride->vehicle->color }} · {{ $ride->vehicle->nb_seats }} places · {{ ucfirst($ride->vehicle->fuel_type) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-5">
        {{-- Driver card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-bold text-gray-900 text-sm mb-4">👤 Conducteur</h3>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-14 h-14 bg-primary-50 rounded-full flex items-center justify-center text-primary font-black text-xl">
                    {{ strtoupper(substr($ride->driver->first_name,0,1)) }}
                </div>
                <div>
                    <p class="font-bold text-gray-900">{{ $ride->driver->first_name }} {{ $ride->driver->last_name }}</p>
                    @if($ride->driver->rating_count > 0)
                    <div class="flex items-center gap-1 mt-1">
                        @for($i=1;$i<=5;$i++)
                        <span class="text-sm {{ $i <= round($ride->driver->rating_avg) ? 'text-yellow-400' : 'text-gray-200' }}">★</span>
                        @endfor
                        <span class="text-xs text-gray-400 ml-1">{{ number_format($ride->driver->rating_avg,1) }} ({{ $ride->driver->rating_count }})</span>
                    </div>
                    @else
                    <p class="text-xs text-gray-400">Nouveau conducteur</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Booking form --}}
        @if(!$alreadyBooked && $ride->seats_available > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-bold text-gray-900 text-sm mb-4">🎟️ Réserver ce trajet</h3>
            @php $wallet = auth()->user()->wallet; @endphp
            <div class="bg-gray-50 rounded-xl p-3 mb-4 flex items-center justify-between">
                <span class="text-xs text-gray-500">Votre solde</span>
                <span class="font-black text-sm {{ ($wallet?->balance ?? 0) > 0 ? 'text-green-600' : 'text-red-500' }}">{{ number_format($wallet?->balance ?? 0,0,',',' ') }} XAF</span>
            </div>
            @if(($wallet?->balance ?? 0) <= 0)
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3 mb-4 text-xs text-yellow-700">
                ⚠️ Solde insuffisant. <a href="{{ route('passenger.wallet.index') }}" class="font-bold underline">Recharger votre portefeuille</a>
            </div>
            @endif
            <form action="{{ route('passenger.bookings.store', $ride) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Nombre de places</label>
                    <select name="seats_booked" id="seats_booked" class="input-field">
                        @for($i=1;$i<=min($ride->seats_available,4);$i++)
                        <option value="{{ $i }}">{{ $i }} place{{ $i>1?'s':'' }}</option>
                        @endfor
                    </select>
                </div>
                <div class="bg-primary-50 rounded-xl p-3 mb-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Total à payer</span>
                        <span class="font-black text-primary" id="total-price">{{ number_format($ride->price_per_seat,0,',',' ') }} XAF</span>
                    </div>
                </div>
                <button class="w-full btn-secondary justify-center py-3" {{ ($wallet?->balance ?? 0) <= 0 ? 'disabled' : '' }}>
                    ✅ Confirmer la réservation
                </button>
            </form>
        </div>
        @elseif($alreadyBooked)
        <div class="bg-green-50 border border-green-200 rounded-2xl p-5 text-center">
            <p class="text-2xl mb-2">✅</p>
            <p class="font-bold text-green-800 text-sm">Vous avez déjà réservé ce trajet</p>
            <a href="{{ route('passenger.bookings.index') }}" class="text-green-700 text-xs font-semibold mt-2 inline-block hover:underline">Voir mes réservations →</a>
        </div>
        @else
        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5 text-center">
            <p class="text-2xl mb-2">😔</p>
            <p class="font-bold text-gray-700 text-sm">Plus de places disponibles</p>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
const pricePerSeat = {{ $ride->price_per_seat }};
document.getElementById('seats_booked')?.addEventListener('change', function() {
    const total = this.value * pricePerSeat;
    document.getElementById('total-price').textContent = new Intl.NumberFormat('fr-FR').format(total) + ' XAF';
});
</script>
@endpush
@endsection
