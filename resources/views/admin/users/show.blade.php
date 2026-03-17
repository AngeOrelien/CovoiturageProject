@extends('layouts.app')
@section('title','Profil Utilisateur — Admin')
@section('page-title', $user->first_name.' '.$user->last_name)
@section('page-subtitle','Détails du profil utilisateur')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- Profile Card --}}
    <div class="xl:col-span-1 space-y-5">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
            <div class="w-20 h-20 bg-primary-50 rounded-full flex items-center justify-center text-primary font-black text-3xl mx-auto mb-4">
                {{ strtoupper(substr($user->first_name,0,1)) }}
            </div>
            <h2 class="text-xl font-black text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</h2>
            <p class="text-gray-400 text-sm mb-3">{{ $user->email }}</p>
            <div class="flex justify-center gap-2 mb-4">
                <span class="text-xs px-3 py-1 rounded-full font-semibold capitalize {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : ($user->role === 'driver' ? 'bg-primary-50 text-primary' : 'bg-secondary-50 text-secondary-700') }}">
                    {{ $user->role }}
                </span>
                <span class="{{ $user->is_active ? 'badge-completed' : 'badge-cancelled' }}">
                    {{ $user->is_active ? 'Actif' : 'Inactif' }}
                </span>
            </div>
            @if($user->rating_count > 0)
            <div class="flex items-center justify-center gap-2 text-gray-600">
                <span class="text-yellow-400 text-lg">★</span>
                <span class="font-bold">{{ number_format($user->rating_avg,2) }}</span>
                <span class="text-gray-400 text-sm">({{ $user->rating_count }} avis)</span>
            </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-3">
            <h3 class="font-bold text-gray-900 text-sm mb-3">Actions</h3>
            <form action="{{ route('admin.users.toggle', $user) }}" method="POST">
                @csrf
                <button class="w-full {{ $user->is_active ? 'bg-yellow-50 text-yellow-700 hover:bg-yellow-100' : 'bg-green-50 text-green-700 hover:bg-green-100' }} px-4 py-2.5 rounded-xl text-sm font-semibold transition text-left flex items-center gap-2">
                    {{ $user->is_active ? '🔒 Désactiver le compte' : '✅ Activer le compte' }}
                </button>
            </form>
            @if($user->role === 'driver' && $user->driverProfile && !$user->driverProfile->is_license_verified)
            <form action="{{ route('admin.users.verify', $user) }}" method="POST">
                @csrf
                <button class="w-full bg-primary-50 text-primary hover:bg-primary hover:text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition text-left flex items-center gap-2">
                    ✔️ Vérifier le permis
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Details --}}
    <div class="xl:col-span-2 space-y-5">
        {{-- Info --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 text-sm mb-4">Informations personnelles</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><p class="text-gray-400 text-xs mb-0.5">Date de naissance</p><p class="font-medium">{{ $user->date_of_birth?->format('d/m/Y') ?? '—' }}</p></div>
                <div><p class="text-gray-400 text-xs mb-0.5">Genre</p><p class="font-medium capitalize">{{ $user->gender ?? '—' }}</p></div>
                <div><p class="text-gray-400 text-xs mb-0.5">Vérifié</p><p class="font-medium">{{ $user->is_verified ? '✅ Oui' : '❌ Non' }}</p></div>
                <div><p class="text-gray-400 text-xs mb-0.5">Inscription</p><p class="font-medium">{{ $user->created_at->format('d/m/Y à H:i') }}</p></div>
            </div>
            @if($user->bio)
            <div class="mt-4"><p class="text-gray-400 text-xs mb-1">Bio</p><p class="text-sm text-gray-600 leading-relaxed">{{ $user->bio }}</p></div>
            @endif
        </div>

        {{-- Driver profile --}}
        @if($user->driverProfile)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 text-sm mb-4">Profil Conducteur</h3>
            <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                <div><p class="text-gray-400 text-xs mb-0.5">Numéro de permis</p><p class="font-mono font-medium">{{ $user->driverProfile->license_number }}</p></div>
                <div><p class="text-gray-400 text-xs mb-0.5">Expiration permis</p><p class="font-medium">{{ $user->driverProfile->license_expiry->format('d/m/Y') }}</p></div>
                <div><p class="text-gray-400 text-xs mb-0.5">Permis vérifié</p><p>{{ $user->driverProfile->is_license_verified ? '<span class="badge-completed">Vérifié</span>' : '<span class="badge-pending">En attente</span>' }}</p></div>
                <div><p class="text-gray-400 text-xs mb-0.5">Expérience</p><p class="font-medium">{{ $user->driverProfile->years_of_experience }} an(s)</p></div>
            </div>
            @if($user->driverProfile->vehicles->count())
            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Véhicules ({{ $user->driverProfile->vehicles->count() }})</h4>
            <div class="space-y-2">
                @foreach($user->driverProfile->vehicles as $v)
                <div class="flex items-center justify-between bg-gray-50 rounded-xl px-4 py-3">
                    <span class="text-sm font-medium">{{ $v->brand }} {{ $v->model }} ({{ $v->year }}) — {{ $v->color }}</span>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-500">{{ $v->license_plate }}</span>
                        <span class="{{ $v->is_verified ? 'badge-completed' : 'badge-pending' }} text-xs">{{ $v->is_verified ? 'Vérifié' : 'En attente' }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @endif

        {{-- Wallet --}}
        @if($user->wallet)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 text-sm mb-3">Portefeuille</h3>
            <p class="text-3xl font-black text-primary">{{ number_format($user->wallet->balance, 0, ',', ' ') }} <span class="text-sm font-normal text-gray-400">{{ $user->wallet->currency }}</span></p>
            @if($user->wallet->transactions->count())
            <div class="mt-4 space-y-2">
                @foreach($user->wallet->transactions->take(5) as $tx)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">{{ $tx->description ?? 'Transaction' }}</span>
                    <span class="{{ $tx->type === 'credit' ? 'text-green-600' : 'text-red-600' }} font-semibold">
                        {{ $tx->type === 'credit' ? '+' : '-' }}{{ number_format($tx->amount,0,',',' ') }} XAF
                    </span>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
