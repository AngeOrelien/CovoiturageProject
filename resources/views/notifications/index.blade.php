@extends('layouts.app')
@section('title','Notifications')
@section('page-title','Notifications')

@section('content')
<div class="max-w-2xl">
    @if($notifications->total() > 0)
    <div class="flex justify-end mb-4">
        <form action="{{ route('notifications.readAll') }}" method="POST">
            @csrf
            <button class="text-xs text-primary font-semibold hover:underline">✅ Tout marquer comme lu</button>
        </form>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 divide-y divide-gray-50">
    @forelse($notifications as $notif)
    <div class="px-5 py-4 flex items-start gap-3 {{ !$notif->is_read ? 'bg-primary-50/20' : '' }}">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-base flex-shrink-0 mt-0.5 {{ !$notif->is_read ? 'bg-primary-50' : 'bg-gray-50' }}">
            @switch($notif->type)
                @case('new_booking') 🎟️ @break
                @case('booking_confirmed') ✅ @break
                @case('booking_cancelled') ❌ @break
                @case('booking_rejected') 🚫 @break
                @default 🔔
            @endswitch
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-bold text-gray-900">{{ $notif->title }}</p>
            <p class="text-sm text-gray-500 mt-0.5 leading-relaxed">{{ $notif->body }}</p>
            <p class="text-xs text-gray-300 mt-1">{{ $notif->created_at->format('d/m/Y à H:i') }} · {{ $notif->created_at->diffForHumans() }}</p>
        </div>
        @if(!$notif->is_read)
        <form action="{{ route('notifications.read', $notif) }}" method="POST" class="flex-shrink-0">
            @csrf
            <button class="w-2 h-2 rounded-full bg-primary mt-2 hover:bg-primary-600"></button>
        </form>
        @endif
    </div>
    @empty
    <div class="px-5 py-16 text-center text-gray-400">
        <p class="text-4xl mb-3">🔔</p>
        <p class="font-medium text-sm">Aucune notification pour l'instant.</p>
    </div>
    @endforelse
    </div>
    @if($notifications->hasPages())<div class="mt-5">{{ $notifications->links() }}</div>@endif
</div>
@endsection
