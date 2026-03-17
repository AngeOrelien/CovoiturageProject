@extends('layouts.app')
@section('title','Mes Réservations')
@section('page-title','Mes Réservations')

@section('content')
<div class="flex flex-wrap gap-2 mb-5">
    @foreach([''=>'Toutes','pending'=>'En attente','confirmed'=>'Confirmées','completed'=>'Terminées','cancelled'=>'Annulées'] as $val=>$label)
    <a href="{{ route('passenger.bookings.index', $val ? ['status'=>$val] : []) }}"
       class="text-xs px-3 py-1.5 rounded-full font-semibold transition {{ request('status')===$val ? 'bg-secondary text-white' : 'bg-white text-gray-600 border border-gray-200 hover:border-secondary hover:text-secondary' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

<div class="space-y-4">
@forelse($bookings as $booking)
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex-1">
            <div class="flex items-center gap-3 mb-2">
                <h3 class="text-base font-black text-gray-900">{{ $booking->ride->origin->city }} → {{ $booking->ride->destination->city }}</h3>
                <span class="badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
            </div>
            <div class="flex flex-wrap gap-4 text-sm text-gray-500">
                <span>📅 {{ $booking->ride->departure_datetime->format('d/m/Y H:i') }}</span>
                <span>👤 {{ $booking->ride->driver->first_name }} {{ $booking->ride->driver->last_name }}</span>
                <span>💺 {{ $booking->seats_booked }} place(s)</span>
                <span>💰 {{ number_format($booking->total_price,0,',',' ') }} XAF</span>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('passenger.bookings.show', $booking) }}" class="btn-outline text-xs">Détails</a>
            @if(in_array($booking->status, ['pending','confirmed']))
            <form action="{{ route('passenger.bookings.cancel', $booking) }}" method="POST" onsubmit="return confirm('Annuler cette réservation ?')">
                @csrf
                <button class="text-xs bg-red-50 text-red-600 hover:bg-red-100 px-3 py-2 rounded-xl font-semibold transition">Annuler</button>
            </form>
            @endif
        </div>
    </div>
</div>
@empty
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
    <div class="text-5xl mb-4">📋</div>
    <h3 class="font-bold text-gray-900 mb-2">Aucune réservation</h3>
    <p class="text-gray-400 text-sm mb-5">Vous n'avez pas encore réservé de trajet.</p>
    <a href="{{ route('passenger.rides.index') }}" class="btn-secondary">Trouver un trajet</a>
</div>
@endforelse
</div>
@if($bookings->hasPages())<div class="mt-5">{{ $bookings->links() }}</div>@endif
@endsection
