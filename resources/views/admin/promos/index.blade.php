@extends('layouts.app')
@section('title','Codes Promo — Admin')
@section('page-title','Codes Promotionnels')

@section('content')
<div class="flex justify-end mb-5">
    <a href="{{ route('admin.promos.create') }}" class="btn-primary">+ Nouveau code promo</a>
</div>
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50/70"><tr>
                <th class="table-th">Code</th>
                <th class="table-th">Type</th>
                <th class="table-th">Valeur</th>
                <th class="table-th">Utilisations</th>
                <th class="table-th">Expiration</th>
                <th class="table-th">Statut</th>
                <th class="table-th">Actions</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-50">
            @forelse($promos as $promo)
            <tr class="hover:bg-gray-50/50">
                <td class="table-td"><span class="font-mono font-bold bg-gray-100 px-2 py-0.5 rounded-lg text-gray-800">{{ $promo->code }}</span></td>
                <td class="table-td capitalize text-gray-600">{{ $promo->discount_type === 'percentage' ? 'Pourcentage' : 'Fixe' }}</td>
                <td class="table-td font-bold text-primary">{{ $promo->discount_type === 'percentage' ? $promo->discount_value.'%' : number_format($promo->discount_value,0,',',' ').' XAF' }}</td>
                <td class="table-td">{{ $promo->used_count }}{{ $promo->max_uses ? '/'.$promo->max_uses : '' }}</td>
                <td class="table-td text-gray-500 text-xs">{{ $promo->expires_at?->format('d/m/Y') ?? '∞' }}</td>
                <td class="table-td"><span class="{{ $promo->is_active ? 'badge-completed' : 'badge-cancelled' }}">{{ $promo->is_active ? 'Actif' : 'Inactif' }}</span></td>
                <td class="table-td">
                    <div class="flex gap-1">
                        <form action="{{ route('admin.promos.toggle', $promo) }}" method="POST">
                            @csrf
                            <button class="text-xs {{ $promo->is_active ? 'bg-yellow-50 text-yellow-700' : 'bg-green-50 text-green-700' }} px-2 py-1 rounded-lg font-semibold hover:opacity-80">
                                {{ $promo->is_active ? 'Désactiver' : 'Activer' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.promos.destroy', $promo) }}" method="POST" onsubmit="return confirm('Supprimer ?')">
                            @csrf @method('DELETE')
                            <button class="text-xs bg-red-50 text-red-600 px-2 py-1 rounded-lg font-semibold hover:bg-red-100">Suppr.</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-10 text-gray-400">Aucun code promo.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-gray-50">{{ $promos->links() }}</div>
</div>
@endsection
