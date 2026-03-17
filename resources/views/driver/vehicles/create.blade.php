@extends('layouts.app')
@section('title','Ajouter un Véhicule')
@section('page-title','Ajouter un Véhicule')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-7">
        <form action="{{ route('driver.vehicles.store') }}" method="POST" class="space-y-6">
            @csrf

            @if(!auth()->user()->driverProfile)
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <p class="text-sm font-bold text-blue-800 mb-1">📋 Informations du permis de conduire</p>
                <p class="text-xs text-blue-600">Renseignez votre permis une seule fois pour activer votre profil conducteur.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">N° de permis *</label>
                    <input type="text" name="license_number" value="{{ old('license_number') }}" required class="input-field" placeholder="Ex: CM-123456">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date d'expiration du permis *</label>
                    <input type="date" name="license_expiry" value="{{ old('license_expiry') }}" required class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Années d'expérience</label>
                    <input type="number" name="years_of_experience" value="{{ old('years_of_experience',0) }}" min="0" max="50" class="input-field">
                </div>
            </div>
            <hr class="border-gray-100">
            @endif

            <div>
                <p class="text-sm font-bold text-gray-800 mb-4">🚗 Informations du véhicule</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Marque *</label>
                        <input type="text" name="brand" value="{{ old('brand') }}" required class="input-field" placeholder="Ex: Toyota">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Modèle *</label>
                        <input type="text" name="model" value="{{ old('model') }}" required class="input-field" placeholder="Ex: Corolla">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Année *</label>
                        <input type="number" name="year" value="{{ old('year') }}" required min="1990" max="{{ date('Y')+1 }}" class="input-field" placeholder="{{ date('Y') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Couleur *</label>
                        <input type="text" name="color" value="{{ old('color') }}" required class="input-field" placeholder="Ex: Blanc">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Plaque d'immatriculation *</label>
                        <input type="text" name="license_plate" value="{{ old('license_plate') }}" required class="input-field" placeholder="Ex: LT-1234-A" style="text-transform:uppercase">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre de places (passagers) *</label>
                        <input type="number" name="nb_seats" value="{{ old('nb_seats',4) }}" required min="2" max="9" class="input-field">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Type de carburant *</label>
                        <select name="fuel_type" class="input-field">
                            <option value="essence" {{ old('fuel_type','essence')==='essence'?'selected':'' }}>Essence</option>
                            <option value="diesel" {{ old('fuel_type')==='diesel'?'selected':'' }}>Diesel</option>
                            <option value="hybride" {{ old('fuel_type')==='hybride'?'selected':'' }}>Hybride</option>
                            <option value="electrique" {{ old('fuel_type')==='electrique'?'selected':'' }}>Électrique</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-4 text-xs text-yellow-700">
                ⚠️ Votre véhicule sera soumis à vérification par notre équipe avant de pouvoir publier des trajets. Cela prend généralement 24-48h.
            </div>

            <div class="flex gap-3">
                <button class="btn-primary">Soumettre pour vérification</button>
                <a href="{{ route('driver.vehicles.index') }}" class="btn-outline">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
