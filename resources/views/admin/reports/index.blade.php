@extends('layouts.app')
@section('title','Signalements — Admin')
@section('page-title','Signalements')
@section('page-subtitle','Modérez les signalements des utilisateurs')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
    <form action="{{ route('admin.reports.index') }}" method="GET" class="flex gap-3">
        <select name="status" class="input-field w-40">
            <option value="">Tous</option>
            <option value="pending" {{ request('status')=='pending'?'selected':'' }}>En attente</option>
            <option value="reviewed" {{ request('status')=='reviewed'?'selected':'' }}>Examiné</option>
            <option value="resolved" {{ request('status')=='resolved'?'selected':'' }}>Résolu</option>
            <option value="dismissed" {{ request('status')=='dismissed'?'selected':'' }}>Rejeté</option>
        </select>
        <button class="btn-primary">Filtrer</button>
    </form>
</div>
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50/70"><tr>
                <th class="table-th">Auteur</th>
                <th class="table-th">Signalé</th>
                <th class="table-th">Raison</th>
                <th class="table-th">Description</th>
                <th class="table-th">Statut</th>
                <th class="table-th">Date</th>
                <th class="table-th">Actions</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-50">
            @forelse($reports as $report)
            <tr class="hover:bg-gray-50/50">
                <td class="table-td font-medium">{{ $report->reporter->first_name }}</td>
                <td class="table-td"><span class="font-semibold text-red-600">{{ $report->reportedUser->first_name }} {{ $report->reportedUser->last_name }}</span></td>
                <td class="table-td">{{ $report->reason }}</td>
                <td class="table-td text-gray-500 max-w-xs truncate">{{ $report->description ?? '—' }}</td>
                <td class="table-td">
                    <span class="{{ $report->status === 'pending' ? 'badge-pending' : ($report->status === 'resolved' ? 'badge-completed' : 'badge-cancelled') }}">{{ ucfirst($report->status) }}</span>
                </td>
                <td class="table-td text-gray-400 text-xs">{{ $report->created_at->diffForHumans() }}</td>
                <td class="table-td">
                    @if($report->status === 'pending')
                    <form action="{{ route('admin.reports.update', $report) }}" method="POST" class="flex gap-1">
                        @csrf @method('PUT')
                        <button name="status" value="resolved" class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-lg hover:bg-green-200 font-semibold">✅ Résoudre</button>
                        <button name="status" value="reviewed" class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-lg hover:bg-blue-200 font-semibold">👁 Examiner</button>
                        <button name="status" value="dismissed" class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-lg hover:bg-gray-200 font-semibold">✕ Rejeter</button>
                    </form>
                    @else <span class="text-xs text-gray-400">Traité</span>@endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-10 text-gray-400">Aucun signalement.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-gray-50">{{ $reports->links() }}</div>
</div>
@endsection
