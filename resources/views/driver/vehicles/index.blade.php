@extends('layouts.app')
@section('title','Mes Véhicules — Conducteur')
@section('page-title','Mes Véhicules')
@section('page-subtitle','Gérez vos véhicules pour publier des trajets')

@section('content')
<div class="flex justify-end mb-5">
    <a href="{{ route('driver.vehicles.create') }}" class="btn-primary">+ Ajouter un véhicule</a>
</div>

@if(!$profile)
<div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-5 mb-6 flex items-start gap-4">
    <span class="text-3xl">🪪</span>
    <div>
        <p class="font-bold text-yellow-800">Profil conducteur incomplet</p>
        <p class="text-sm text-yellow-600 mt-1">Ajoutez votre permis de conduire et un véhicule pour commencer à proposer des trajets.</p>
    </div>
</div>
@elseif(!$profile->is_license_verified)
<div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 mb-6 flex items-start gap-4">
    <span class="text-3xl">⏳</span>
    <div>
        <p class="font-bold text-blue-800">Permis en cours de vérification</p>
        <p class="text-sm text-blue-600 mt-1">Votre permis (N° {{ $profile->license_number }}) est examiné par notre équipe. Vous pourrez publier des trajets dès validation.</p>
    </div>
</div>
@endif

@if($vehicles->isEmpty())
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
    <div class="text-5xl mb-4">🚗</div>
    <h3 class="font-bold text-gray-900 mb-2">Aucun véhicule enregistré</h3>
    <p class="text-gray-400 text-sm mb-5">Ajoutez votre véhicule pour commencer à proposer des covoiturages.</p>
    <a href="{{ route('driver.vehicles.create') }}" class="btn-primary">Ajouter mon véhicule</a>
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
@foreach($vehicles as $vehicle)
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
    <div class="flex items-start justify-between mb-4">
        <div>
            <h3 class="text-lg font-black text-gray-900">{{ $vehicle->brand }} {{ $vehicle->model }}</h3>
            <p class="text-gray-400 text-sm">{{ $vehicle->year }} · {{ $vehicle->color }}</p>
        </div>
        <span class="{{ $vehicle->is_verified ? 'badge-completed' : 'badge-pending' }}">
            {{ $vehicle->is_verified ? '✅ Vérifié' : '⏳ En attente' }}
        </span>
    </div>
    <div class="grid grid-cols-2 gap-3 text-sm mb-5">
        <div class="bg-gray-50 rounded-xl p-3">
            <p class="text-xs text-gray-400 mb-0.5">Immatriculation</p>
            <p class="font-mono font-bold text-gray-800">{{ $vehicle->license_plate }}</p>
        </div>
        <div class="bg-gray-50 rounded-xl p-3">
            <p class="text-xs text-gray-400 mb-0.5">Places</p>
            <p class="font-bold text-gray-800">{{ $vehicle->nb_seats }} sièges</p>
        </div>
        <div class="bg-gray-50 rounded-xl p-3">
            <p class="text-xs text-gray-400 mb-0.5">Carburant</p>
            <p class="font-medium text-gray-800 capitalize">{{ $vehicle->fuel_type }}</p>
        </div>
    </div>
    <form action="{{ route('driver.vehicles.destroy', $vehicle) }}" method="POST" onsubmit="return confirm('Supprimer ce véhicule ?')">
        @csrf @method('DELETE')
        <button class="text-xs bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg font-semibold transition">Supprimer</button>
    </form>
</div>
@endforeach
</div>
@endif
@endsection
