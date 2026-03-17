@extends('layouts.app')
@section('title','Trajets — Admin')
@section('page-title','Gestion des Trajets')
@section('page-subtitle','Tous les trajets publiés sur la plateforme')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
    <form action="{{ route('admin.rides.index') }}" method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="w-36">
            <label class="block text-xs font-semibold text-gray-500 mb-1.5">Statut</label>
            <select name="status" class="input-field">
                <option value="">Tous</option>
                <option value="scheduled" {{ request('status')=='scheduled'?'selected':'' }}>Prévu</option>
                <option value="active" {{ request('status')=='active'?'selected':'' }}>Actif</option>
                <option value="completed" {{ request('status')=='completed'?'selected':'' }}>Terminé</option>
                <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>Annulé</option>
            </select>
        </div>
        <button class="btn-primary">Filtrer</button>
    </form>
</div>
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50/70"><tr>
                <th class="table-th">Conducteur</th>
                <th class="table-th">Trajet</th>
                <th class="table-th">Départ</th>
                <th class="table-th">Places</th>
                <th class="table-th">Prix/Siège</th>
                <th class="table-th">Statut</th>
                <th class="table-th">Actions</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-50">
            @forelse($rides as $ride)
            <tr class="hover:bg-gray-50/50">
                <td class="table-td font-medium">{{ $ride->driver->first_name }} {{ $ride->driver->last_name }}</td>
                <td class="table-td">
                    <span class="text-primary font-medium">{{ $ride->origin->city }}</span>
                    <span class="text-gray-400 mx-1">→</span>
                    <span class="text-secondary-700 font-medium">{{ $ride->destination->city }}</span>
                </td>
                <td class="table-td text-gray-500 text-xs">{{ $ride->departure_datetime->format('d/m/Y H:i') }}</td>
                <td class="table-td">{{ $ride->seats_available }}/{{ $ride->seats_total }}</td>
                <td class="table-td font-bold">{{ number_format($ride->price_per_seat,0,',',' ') }} XAF</td>
                <td class="table-td"><span class="badge-{{ $ride->status }}">{{ ucfirst($ride->status) }}</span></td>
                <td class="table-td">
                    <div class="flex gap-2">
                        <a href="{{ route('admin.rides.show', $ride) }}" class="text-xs bg-primary-50 text-primary px-2.5 py-1 rounded-lg font-semibold hover:bg-primary hover:text-white transition">Voir</a>
                        @if(!in_array($ride->status, ['completed','cancelled']))
                        <form action="{{ route('admin.rides.cancel', $ride) }}" method="POST" onsubmit="return confirm('Annuler ce trajet ?')">
                            @csrf
                            <button class="text-xs bg-red-50 text-red-600 px-2.5 py-1 rounded-lg font-semibold hover:bg-red-100 transition">Annuler</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-10 text-gray-400">Aucun trajet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-gray-50">{{ $rides->links() }}</div>
</div>
@endsection
