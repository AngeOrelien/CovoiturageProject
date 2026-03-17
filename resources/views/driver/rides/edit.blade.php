@extends('layouts.app')
@section('title','Modifier Trajet')
@section('page-title','Modifier le Trajet')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-7">
        <form action="{{ route('driver.rides.update', $ride) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')
            <div class="bg-blue-50 rounded-xl p-4 text-sm text-blue-700 flex items-start gap-3">
                <span class="text-lg">ℹ️</span>
                <p>Pour modifier le trajet <strong>{{ $ride->origin->city }} → {{ $ride->destination->city }}</strong>, seuls certains champs sont éditables après publication.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date et heure de départ *</label>
                    <input type="datetime-local" name="departure_datetime" value="{{ old('departure_datetime', $ride->departure_datetime->format('Y-m-d\TH:i')) }}" required class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Prix par place (XAF) *</label>
                    <input type="number" name="price_per_seat" value="{{ old('price_per_seat', $ride->price_per_seat) }}" required min="0" step="100" class="input-field">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Statut *</label>
                <select name="status" class="input-field">
                    <option value="scheduled" {{ $ride->status==='scheduled'?'selected':'' }}>Prévu</option>
                    <option value="active" {{ $ride->status==='active'?'selected':'' }}>Actif (en cours)</option>
                    <option value="cancelled" {{ $ride->status==='cancelled'?'selected':'' }}>Annulé</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="3" class="input-field">{{ old('description', $ride->description) }}</textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button class="btn-primary">Enregistrer les modifications</button>
                <a href="{{ route('driver.rides.show', $ride) }}" class="btn-outline">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
