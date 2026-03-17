@extends('layouts.app')
@section('title','Rechercher un Trajet')
@section('page-title','Rechercher un Trajet')
@section('page-subtitle','Trouvez le covoiturage qui vous convient')

@section('content')
{{-- Filter bar --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
    <form action="{{ route('passenger.rides.index') }}" method="GET">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
            <div class="lg:col-span-1">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Départ</label>
                <input type="text" name="origin" value="{{ request('origin') }}" placeholder="Ville..." class="input-field">
            </div>
            <div class="lg:col-span-1">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Destination</label>
                <input type="text" name="destination" value="{{ request('destination') }}" placeholder="Ville..." class="input-field">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Date</label>
                <input type="date" name="date" value="{{ request('date') }}" class="input-field">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Places</label>
                <input type="number" name="seats" value="{{ request('seats',1) }}" min="1" max="8" class="input-field">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Prix max (XAF)</label>
                <input type="number" name="max_price" value="{{ request('max_price') }}" min="0" step="500" class="input-field" placeholder="Illimité">
            </div>
            <div class="flex items-end gap-2">
                <button class="btn-primary flex-1 justify-center">🔍 Filtrer</button>
                @if(request()->anyFilled(['origin','destination','date','seats','max_price']))
                <a href="{{ route('passenger.rides.index') }}" class="btn-outline px-3">✕</a>
                @endif
            </div>
        </div>
    </form>
</div>

{{-- Results --}}
<div class="mb-4 flex items-center justify-between">
    <p class="text-sm text-gray-500">{{ $rides->total() }} trajet(s) trouvé(s)</p>
</div>

@if($rides->isEmpty())
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
    <div class="text-5xl mb-4">🔍</div>
    <h3 class="font-bold text-gray-900 mb-2">Aucun trajet disponible</h3>
    <p class="text-gray-400 text-sm">Essayez d'autres critères de recherche ou revenez plus tard.</p>
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
@foreach($rides as $ride)
<a href="{{ route('passenger.rides.show', $ride) }}" class="block bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-lg hover:-translate-y-1 transition-all duration-200 group">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 bg-primary-50 rounded-full flex items-center justify-center text-primary font-black text-base group-hover:bg-primary group-hover:text-white transition">
                {{ strtoupper(substr($ride->driver->first_name,0,1)) }}
            </div>
            <div>
                <p class="text-sm font-bold text-gray-900">{{ $ride->driver->first_name }} {{ $ride->driver->last_name }}</p>
                @if($ride->driver->rating_count > 0)
                <div class="flex items-center gap-1">
                    <span class="text-yellow-400 text-xs">★</span>
                    <span class="text-xs text-gray-500">{{ number_format($ride->driver->rating_avg,1) }} ({{ $ride->driver->rating_count }})</span>
                </div>
                @else
                <p class="text-xs text-gray-400">Nouveau conducteur</p>
                @endif
            </div>
        </div>
        <div class="text-right">
            <p class="text-xl font-black text-primary">{{ number_format($ride->price_per_seat,0,',',' ') }}</p>
            <p class="text-xs text-gray-400">XAF/place</p>
        </div>
    </div>

    <div class="space-y-2 mb-4">
        <div class="flex items-center gap-2.5">
            <div class="w-2.5 h-2.5 rounded-full bg-primary flex-shrink-0"></div>
            <p class="text-sm font-semibold text-gray-800 truncate">{{ $ride->origin->city }}</p>
            <p class="text-xs text-gray-400 ml-auto">{{ $ride->departure_datetime->format('H:i') }}</p>
        </div>
        <div class="ml-1 border-l-2 border-dashed border-gray-200 h-4 ml-1.5"></div>
        <div class="flex items-center gap-2.5">
            <div class="w-2.5 h-2.5 rounded-full bg-secondary flex-shrink-0"></div>
            <p class="text-sm font-semibold text-gray-800 truncate">{{ $ride->destination->city }}</p>
            @if($ride->arrival_datetime)
            <p class="text-xs text-gray-400 ml-auto">{{ $ride->arrival_datetime->format('H:i') }}</p>
            @endif
        </div>
    </div>

    <div class="pt-3 border-t border-gray-50 flex items-center justify-between text-xs text-gray-500">
        <span>📅 {{ $ride->departure_datetime->translatedFormat('d M Y') }}</span>
        <span>💺 {{ $ride->seats_available }} place{{ $ride->seats_available>1?'s':'' }}</span>
        @if($ride->vehicle)<span>🚗 {{ $ride->vehicle->brand }}</span>@endif
    </div>
</a>
@endforeach
</div>
<div class="mt-6">{{ $rides->links() }}</div>
@endif
@endsection
