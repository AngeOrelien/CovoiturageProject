@extends('layouts.app')
@section('title','Nouveau Trajet')
@section('page-title','Publier un Trajet')
@section('page-subtitle','Remplissez le formulaire pour proposer votre trajet')

@section('content')
<div class="max-w-2xl">
    @if($vehicles->isEmpty())
    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-5 mb-6 flex items-center gap-4">
        <span class="text-3xl">⚠️</span>
        <div>
            <p class="font-bold text-yellow-800">Aucun véhicule vérifié</p>
            <p class="text-sm text-yellow-600">Vous devez avoir un véhicule vérifié pour publier un trajet.</p>
            <a href="{{ route('driver.vehicles.create') }}" class="text-yellow-700 font-bold text-sm underline mt-1 inline-block">Ajouter un véhicule →</a>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-7">
        <form action="{{ route('driver.rides.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Ville de départ *</label>
                    <input type="text" name="origin_city" value="{{ old('origin_city') }}" required placeholder="Ex: Yaoundé" class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Adresse / Point précis de départ *</label>
                    <input type="text" name="origin_label" value="{{ old('origin_label') }}" required placeholder="Ex: Gare Centrale de Yaoundé" class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Ville d'arrivée *</label>
                    <input type="text" name="destination_city" value="{{ old('destination_city') }}" required placeholder="Ex: Douala" class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Adresse / Point d'arrivée *</label>
                    <input type="text" name="destination_label" value="{{ old('destination_label') }}" required placeholder="Ex: Akwa Douala" class="input-field">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Véhicule *</label>
                @if($vehicles->isEmpty())
                <p class="text-sm text-gray-400 italic">Aucun véhicule vérifié disponible.</p>
                @else
                <select name="vehicle_id" required class="input-field">
                    <option value="">— Choisir un véhicule —</option>
                    @foreach($vehicles as $v)
                    <option value="{{ $v->id }}" {{ old('vehicle_id')===$v->id?'selected':'' }}>
                        {{ $v->brand }} {{ $v->model }} ({{ $v->year }}) — {{ $v->color }} · {{ $v->license_plate }}
                    </option>
                    @endforeach
                </select>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date et heure de départ *</label>
                    <input type="datetime-local" name="departure_datetime" value="{{ old('departure_datetime') }}" required class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date et heure d'arrivée (estimée)</label>
                    <input type="datetime-local" name="arrival_datetime" value="{{ old('arrival_datetime') }}" class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre de places proposées *</label>
                    <input type="number" name="seats_total" value="{{ old('seats_total',1) }}" required min="1" max="8" class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Prix par place (XAF) *</label>
                    <input type="number" name="price_per_seat" value="{{ old('price_per_seat') }}" required min="0" step="100" class="input-field" placeholder="Ex: 5000">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Distance (km)</label>
                    <input type="number" name="distance_km" value="{{ old('distance_km') }}" min="0" step="0.1" class="input-field" placeholder="Optionnel">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Description / Informations complémentaires</label>
                <textarea name="description" rows="3" class="input-field" placeholder="Bagages autorisés, arrêts prévus, préférences...">{{ old('description') }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button class="btn-primary" {{ $vehicles->isEmpty() ? 'disabled' : '' }}>🚗 Publier le trajet</button>
                <a href="{{ route('driver.rides.index') }}" class="btn-outline">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
