@extends('layouts.app')
@section('title','Réservations — Conducteur')
@section('page-title','Réservations Reçues')
@section('page-subtitle','Gérez les demandes de réservation de vos passagers')

@section('content')
<div class="flex flex-wrap gap-2 mb-5">
    @foreach([''=>'Toutes','pending'=>'En attente','confirmed'=>'Confirmées','completed'=>'Terminées','cancelled'=>'Annulées'] as $val=>$label)
    <a href="{{ route('driver.bookings.index', $val ? ['status'=>$val] : []) }}"
       class="text-xs px-3 py-1.5 rounded-full font-semibold transition {{ request('status')===$val ? 'bg-primary text-white' : 'bg-white text-gray-600 border border-gray-200 hover:border-primary hover:text-primary' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50/70">
                <tr>
                    <th class="table-th">Passager</th>
                    <th class="table-th">Trajet</th>
                    <th class="table-th">Places</th>
                    <th class="table-th">Montant</th>
                    <th class="table-th">Statut</th>
                    <th class="table-th">Date</th>
                    <th class="table-th">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
            @forelse($bookings as $booking)
            <tr class="hover:bg-gray-50/50 transition">
                <td class="table-td">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-secondary-50 rounded-full flex items-center justify-center text-secondary-700 font-bold text-xs">
                            {{ strtoupper(substr($booking->passenger->first_name,0,1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-sm">{{ $booking->passenger->first_name }} {{ $booking->passenger->last_name }}</p>
                            <p class="text-xs text-gray-400">{{ $booking->passenger->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="table-td">
                    <p class="text-sm font-medium"><span class="text-primary">{{ $booking->ride->origin->city }}</span> → <span class="text-secondary-700">{{ $booking->ride->destination->city }}</span></p>
                    <p class="text-xs text-gray-400">{{ $booking->ride->departure_datetime->format('d/m/Y H:i') }}</p>
                </td>
                <td class="table-td text-center font-bold">{{ $booking->seats_booked }}</td>
                <td class="table-td font-black text-primary">{{ number_format($booking->total_price,0,',',' ') }} XAF</td>
                <td class="table-td"><span class="badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span></td>
                <td class="table-td text-gray-400 text-xs">{{ $booking->created_at->format('d/m H:i') }}</td>
                <td class="table-td">
                    @if($booking->status === 'pending')
                    <div class="flex gap-1">
                        <form action="{{ route('driver.bookings.confirm', $booking) }}" method="POST">
                            @csrf
                            <button class="text-xs bg-green-100 text-green-700 hover:bg-green-200 px-2.5 py-1.5 rounded-lg font-semibold transition">✅ Confirmer</button>
                        </form>
                        <form action="{{ route('driver.bookings.reject', $booking) }}" method="POST">
                            @csrf
                            <button class="text-xs bg-red-100 text-red-600 hover:bg-red-200 px-2.5 py-1.5 rounded-lg font-semibold transition">✕ Refuser</button>
                        </form>
                    </div>
                    @else
                    <span class="text-xs text-gray-400">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-10 text-gray-400">Aucune réservation trouvée.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($bookings->hasPages())
    <div class="px-5 py-4 border-t border-gray-50">{{ $bookings->links() }}</div>
    @endif
</div>
@endsection
