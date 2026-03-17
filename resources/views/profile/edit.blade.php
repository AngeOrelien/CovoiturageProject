@extends('layouts.app')
@section('title','Mon Profil')
@section('page-title','Mon Profil')
@section('page-subtitle','Gérez vos informations personnelles')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- Avatar card --}}
    <div class="xl:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
            <div class="w-24 h-24 bg-primary-50 rounded-full flex items-center justify-center text-primary font-black text-4xl mx-auto mb-4">
                {{ strtoupper(substr($user->first_name,0,1)) }}
            </div>
            <h2 class="text-xl font-black text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</h2>
            <p class="text-gray-400 text-sm">{{ $user->email }}</p>
            <div class="flex justify-center gap-2 mt-3">
                <span class="text-xs px-3 py-1 rounded-full font-semibold capitalize {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : ($user->role === 'driver' ? 'bg-primary-50 text-primary' : 'bg-secondary-50 text-secondary-700') }}">
                    {{ $user->role }}
                </span>
                @if($user->rating_count > 0)
                <span class="text-xs px-3 py-1 rounded-full bg-yellow-50 text-yellow-700 font-semibold">
                    ★ {{ number_format($user->rating_avg,1) }}
                </span>
                @endif
            </div>
            <p class="text-xs text-gray-400 mt-4">Membre depuis {{ $user->created_at->format('F Y') }}</p>
        </div>
    </div>

    {{-- Forms --}}
    <div class="xl:col-span-2 space-y-5">
        {{-- Personal info --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 text-sm mb-5">✏️ Informations personnelles</h3>
            <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Prénom *</label>
                        <input type="text" name="first_name" value="{{ old('first_name',$user->first_name) }}" required class="input-field">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nom *</label>
                        <input type="text" name="last_name" value="{{ old('last_name',$user->last_name) }}" required class="input-field">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Date de naissance</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth',$user->date_of_birth?->format('Y-m-d')) }}" class="input-field">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Genre</label>
                        <select name="gender" class="input-field">
                            <option value="">—</option>
                            <option value="male" {{ old('gender',$user->gender)==='male'?'selected':'' }}>Homme</option>
                            <option value="female" {{ old('gender',$user->gender)==='female'?'selected':'' }}>Femme</option>
                            <option value="other" {{ old('gender',$user->gender)==='other'?'selected':'' }}>Autre</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Bio</label>
                    <textarea name="bio" rows="3" class="input-field" placeholder="Parlez-vous un peu...">{{ old('bio',$user->bio) }}</textarea>
                </div>
                <button class="btn-primary">Enregistrer les modifications</button>
            </form>
        </div>

        {{-- Password change --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 text-sm mb-5">🔒 Modifier le mot de passe</h3>
            <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Mot de passe actuel *</label>
                    <input type="password" name="current_password" required class="input-field" placeholder="••••••••">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nouveau mot de passe *</label>
                        <input type="password" name="password" required class="input-field" placeholder="Min. 8 caractères">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Confirmer *</label>
                        <input type="password" name="password_confirmation" required class="input-field" placeholder="Répéter">
                    </div>
                </div>
                <button class="btn-outline">Changer le mot de passe</button>
            </form>
        </div>
    </div>
</div>
@endsection
