@extends('layouts.app')
@section('title','Utilisateurs — Admin')
@section('page-title','Gestion des Utilisateurs')
@section('page-subtitle','Liste de tous les membres inscrits sur TGether')

@section('content')
{{-- Filters --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
    <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <label class="block text-xs font-semibold text-gray-500 mb-1.5">Recherche</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, email..." class="input-field">
        </div>
        <div class="w-36">
            <label class="block text-xs font-semibold text-gray-500 mb-1.5">Rôle</label>
            <select name="role" class="input-field">
                <option value="">Tous</option>
                <option value="admin" {{ request('role')=='admin'?'selected':'' }}>Admin</option>
                <option value="driver" {{ request('role')=='driver'?'selected':'' }}>Conducteur</option>
                <option value="passenger" {{ request('role')=='passenger'?'selected':'' }}>Passager</option>
            </select>
        </div>
        <button class="btn-primary">Filtrer</button>
        @if(request()->anyFilled(['search','role']))<a href="{{ route('admin.users.index') }}" class="btn-outline">Réinitialiser</a>@endif
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-50">
        <p class="text-sm text-gray-500">{{ $users->total() }} utilisateur(s) trouvé(s)</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50/70">
                <tr>
                    <th class="table-th">Utilisateur</th>
                    <th class="table-th">Rôle</th>
                    <th class="table-th">Statut</th>
                    <th class="table-th">Note</th>
                    <th class="table-th">Inscription</th>
                    <th class="table-th">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="table-td">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-primary-50 flex items-center justify-center text-primary font-bold text-sm flex-shrink-0">
                                {{ strtoupper(substr($user->first_name,0,1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">{{ $user->first_name }} {{ $user->last_name }}</p>
                                <p class="text-xs text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="table-td">
                        <span class="text-xs px-2.5 py-1 rounded-full font-semibold capitalize {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : ($user->role === 'driver' ? 'bg-primary-50 text-primary' : 'bg-secondary-50 text-secondary-700') }}">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="table-td">
                        <span class="{{ $user->is_active ? 'badge-completed' : 'badge-cancelled' }}">
                            {{ $user->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </td>
                    <td class="table-td">
                        @if($user->rating_count > 0)
                        <div class="flex items-center gap-1">
                            <span class="text-yellow-400">★</span>
                            <span class="text-sm font-semibold">{{ number_format($user->rating_avg,1) }}</span>
                            <span class="text-xs text-gray-400">({{ $user->rating_count }})</span>
                        </div>
                        @else
                        <span class="text-gray-300 text-xs">—</span>
                        @endif
                    </td>
                    <td class="table-td text-gray-400 text-xs">{{ $user->created_at->format('d/m/Y') }}</td>
                    <td class="table-td">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.users.show', $user) }}" class="text-xs bg-primary-50 text-primary px-2.5 py-1 rounded-lg hover:bg-primary hover:text-white transition font-semibold">Voir</a>
                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST">
                                @csrf
                                <button class="text-xs {{ $user->is_active ? 'bg-yellow-50 text-yellow-700 hover:bg-yellow-100' : 'bg-green-50 text-green-700 hover:bg-green-100' }} px-2.5 py-1 rounded-lg transition font-semibold">
                                    {{ $user->is_active ? 'Désactiver' : 'Activer' }}
                                </button>
                            </form>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                @csrf @method('DELETE')
                                <button class="text-xs bg-red-50 text-red-600 px-2.5 py-1 rounded-lg hover:bg-red-100 transition font-semibold">Suppr.</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-10 text-gray-400">Aucun utilisateur trouvé.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-gray-50">
        {{ $users->links() }}
    </div>
</div>
@endsection
