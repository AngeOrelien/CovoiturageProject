@extends('layouts.app')
@section('title','Nouveau Code Promo')
@section('page-title','Créer un Code Promo')

@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-7">
        <form action="{{ route('admin.promos.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Code promo *</label>
                <input type="text" name="code" value="{{ old('code') }}" required placeholder="EX: TGETHER20" class="input-field uppercase" style="text-transform:uppercase">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Type de remise *</label>
                    <select name="discount_type" class="input-field">
                        <option value="percentage" {{ old('discount_type')=='percentage'?'selected':'' }}>Pourcentage (%)</option>
                        <option value="fixed" {{ old('discount_type')=='fixed'?'selected':'' }}>Montant fixe (XAF)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Valeur *</label>
                    <input type="number" name="discount_value" value="{{ old('discount_value') }}" required min="0" step="0.01" class="input-field" placeholder="20">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Montant min. (XAF)</label>
                    <input type="number" name="min_booking_amount" value="{{ old('min_booking_amount') }}" min="0" class="input-field" placeholder="Optionnel">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre max. d'utilisations</label>
                    <input type="number" name="max_uses" value="{{ old('max_uses') }}" min="1" class="input-field" placeholder="Illimité">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Date d'expiration</label>
                <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}" class="input-field">
            </div>
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" checked class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary">
                <label for="is_active" class="text-sm font-medium text-gray-700">Activer immédiatement</label>
            </div>
            <div class="flex gap-3 pt-2">
                <button class="btn-primary">Créer le code promo</button>
                <a href="{{ route('admin.promos.index') }}" class="btn-outline">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
