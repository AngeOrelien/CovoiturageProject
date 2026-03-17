@extends('layouts.app')
@section('title','Admin — Tableau de bord')
@section('page-title','Tableau de bord')
@section('page-subtitle','Vue d\'ensemble de la plateforme TGether')

@section('content')
{{-- Stats Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    @foreach([
        ['label'=>'Utilisateurs','value'=>$stats['users'],'icon'=>'👥','color'=>'text-blue-600','bg'=>'bg-blue-50','sub'=>$stats['drivers'].' conducteurs / '.$stats['passengers'].' passagers'],
        ['label'=>'Trajets publiés','value'=>$stats['rides'],'icon'=>'🗺️','color'=>'text-primary','bg'=>'bg-primary-50','sub'=>$stats['active_rides'].' actifs en ce moment'],
        ['label'=>'Réservations','value'=>$stats['bookings'],'icon'=>'📋','color'=>'text-secondary','bg'=>'bg-secondary-50','sub'=>'Toutes réservations confondues'],
        ['label'=>'Revenus (XAF)','value'=>number_format($stats['revenue'],0,',',' '),'icon'=>'💵','color'=>'text-yellow-600','bg'=>'bg-yellow-50','sub'=>'Paiements complétés'],
    ] as $stat)
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ $stat['label'] }}</span>
            <div class="w-9 h-9 {{ $stat['bg'] }} rounded-xl flex items-center justify-center text-lg">{{ $stat['icon'] }}</div>
        </div>
        <p class="text-3xl font-black {{ $stat['color'] }}">{{ $stat['value'] }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $stat['sub'] }}</p>
    </div>
    @endforeach
</div>

{{-- Pending reports alert --}}
@if($stats['pending_reports'] > 0)
<div class="mb-6 px-5 py-4 bg-red-50 border border-red-200 rounded-2xl flex items-center justify-between">
    <div class="flex items-center gap-3">
        <span class="text-2xl">⚠️</span>
        <div>
            <p class="font-bold text-red-800 text-sm">{{ $stats['pending_reports'] }} signalement(s) en attente</p>
            <p class="text-xs text-red-600">Ces signalements nécessitent votre attention.</p>
        </div>
    </div>
    <a href="{{ route('admin.reports.index') }}" class="bg-red-600 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-red-700 transition">Traiter →</a>
</div>
@endif

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- Recent users --}}
    <div class="xl:col-span-1 bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
            <h3 class="font-bold text-gray-900 text-sm">Derniers inscrits</h3>
            <a href="{{ route('admin.users.index') }}" class="text-primary text-xs font-semibold hover:underline">Voir tous</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentUsers as $user)
            <div class="px-5 py-3 flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-primary-50 flex items-center justify-center text-primary font-bold text-sm flex-shrink-0">
                    {{ strtoupper(substr($user->first_name,0,1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $user->first_name }} {{ $user->last_name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $user->email }}</p>
                </div>
                <span class="text-xs px-2 py-1 rounded-full capitalize {{ $user->role === 'driver' ? 'bg-primary-50 text-primary' : 'bg-secondary-50 text-secondary-700' }}">{{ $user->role }}</span>
            </div>
            @empty
            <p class="px-5 py-6 text-center text-gray-400 text-sm">Aucun utilisateur.</p>
            @endforelse
        </div>
    </div>

    {{-- Recent rides --}}
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
            <h3 class="font-bold text-gray-900 text-sm">Derniers trajets</h3>
            <a href="{{ route('admin.rides.index') }}" class="text-primary text-xs font-semibold hover:underline">Voir tous</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="table-th">Conducteur</th>
                        <th class="table-th">Trajet</th>
                        <th class="table-th">Départ</th>
                        <th class="table-th">Prix/Siège</th>
                        <th class="table-th">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recentRides as $ride)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="table-td font-medium">{{ $ride->driver->first_name }} {{ $ride->driver->last_name }}</td>
                        <td class="table-td">
                            <span class="text-primary font-medium">{{ $ride->origin->city }}</span>
                            <span class="text-gray-400 mx-1">→</span>
                            <span class="text-secondary-700 font-medium">{{ $ride->destination->city }}</span>
                        </td>
                        <td class="table-td text-gray-500">{{ $ride->departure_datetime->format('d/m H:i') }}</td>
                        <td class="table-td font-bold text-gray-900">{{ number_format($ride->price_per_seat,0,',',' ') }} XAF</td>
                        <td class="table-td">
                            <span class="badge-{{ $ride->status }}">{{ ucfirst($ride->status) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="table-td text-center text-gray-400 py-6">Aucun trajet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pending reports list --}}
    @if($pendingReports->count())
    <div class="xl:col-span-3 bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
            <h3 class="font-bold text-gray-900 text-sm">⚠️ Signalements en attente</h3>
            <a href="{{ route('admin.reports.index') }}" class="text-primary text-xs font-semibold hover:underline">Voir tous</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50/50"><tr>
                    <th class="table-th">Auteur</th>
                    <th class="table-th">Signalé</th>
                    <th class="table-th">Raison</th>
                    <th class="table-th">Date</th>
                    <th class="table-th">Action</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-50">
                @foreach($pendingReports as $report)
                <tr class="hover:bg-gray-50/50">
                    <td class="table-td">{{ $report->reporter->first_name }}</td>
                    <td class="table-td font-medium">{{ $report->reportedUser->first_name }} {{ $report->reportedUser->last_name }}</td>
                    <td class="table-td text-gray-500">{{ $report->reason }}</td>
                    <td class="table-td text-gray-400 text-xs">{{ $report->created_at->diffForHumans() }}</td>
                    <td class="table-td">
                        <form action="{{ route('admin.reports.update', $report) }}" method="POST" class="flex gap-1">
                            @csrf @method('PUT')
                            <button name="status" value="resolved" class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-lg hover:bg-green-200 transition font-semibold">Résoudre</button>
                            <button name="status" value="dismissed" class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-lg hover:bg-gray-200 transition font-semibold">Rejeter</button>
                        </form>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection
