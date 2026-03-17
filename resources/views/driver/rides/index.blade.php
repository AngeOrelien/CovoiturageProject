@extends('layouts.app')
@section('title','Mes Trajets — Conducteur')
@section('page-title','Mes Trajets')

@section('content')
<div class="flex items-center justify-between mb-5">
    <div class="flex gap-2">
        @foreach([''=>'Tous','scheduled'=>'Prévus','active'=>'Actifs','completed'=>'Terminés','cancelled'=>'Annulés'] as $val=>$label)
        <a href="{{ route('driver.rides.index', $val ? ['status'=>$val] : []) }}"
           class="text-xs px-3 py-1.5 rounded-full font-semibold transition {{ request('status')===$val ? 'bg-primary text-white' : 'bg-white text-gray-600 border border-gray-200 hover:border-primary hover:text-primary' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>
    <a href="{{ route('driver.rides.create') }}" class="btn-primary">+ Nouveau trajet</a>
</div>

<div class="space-y-4">
@forelse($rides as $ride)
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex-1">
            <div class="flex items-center gap-3 mb-2">
                <h3 class="text-base font-black text-gray-900">{{ $ride->origin->city }} → {{ $ride->destination->city }}</h3>
                <span class="badge-{{ $ride->status }}">{{ ucfirst($ride->status) }}</span>
            </div>
            <div class="flex flex-wrap gap-4 text-sm text-gray-500">
                <span>📅 {{ $ride->departure_datetime->format('d/m/Y à H:i') }}</span>
                <span>💺 {{ $ride->seats_available }}/{{ $ride->seats_total }} places</span>
                <span>💰 {{ number_format($ride->price_per_seat,0,',',' ') }} XAF/siège</span>
                <span>👥 {{ $ride->bookings->count() }} réservation(s)</span>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('driver.rides.show', $ride) }}" class="btn-outline text-xs">Voir</a>
            @if(!in_array($ride->status, ['completed','cancelled']))
            <a href="{{ route('driver.rides.edit', $ride) }}" class="btn-primary text-xs">Modifier</a>
            <form action="{{ route('driver.rides.cancel', $ride) }}" method="POST" onsubmit="return confirm('Annuler ce trajet ?')">
                @csrf
                <button class="text-xs bg-red-50 text-red-600 hover:bg-red-100 px-3 py-2 rounded-xl font-semibold transition">Annuler</button>
            </form>
            @endif
        </div>
    </div>
</div>
@empty
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
    <div class="text-5xl mb-4">🚗</div>
    <h3 class="font-bold text-gray-900 mb-2">Aucun trajet trouvé</h3>
    <p class="text-gray-400 text-sm mb-5">Publiez votre premier trajet et commencez à partager vos voyages!</p>
    <a href="{{ route('driver.rides.create') }}" class="btn-primary">Publier un trajet</a>
</div>
@endforelse
</div>
@if($rides->hasPages())<div class="mt-6">{{ $rides->links() }}</div>@endif
@endsection
