{{-- conversations/index.blade.php --}}
@extends('layouts.app')
@section('title','Messages')
@section('page-title','Mes Messages')

@section('content')
@if($conversations->isEmpty())
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
    <div class="text-5xl mb-4">💬</div>
    <h3 class="font-bold text-gray-900 mb-2">Aucune conversation</h3>
    <p class="text-gray-400 text-sm">Vos conversations avec conducteurs et passagers apparaîtront ici.</p>
</div>
@else
<div class="space-y-3 max-w-2xl">
@foreach($conversations as $conv)
@php
    $other = $conv->booking?->passenger_id === auth()->id()
        ? $conv->booking?->ride?->driver
        : $conv->booking?->passenger;
    $lastMsg = $conv->messages->first();
@endphp
<a href="{{ route('conversations.show', $conv) }}" class="block bg-white rounded-2xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition hover:border-primary/30">
    <div class="flex items-center gap-3">
        <div class="w-11 h-11 bg-primary-50 rounded-full flex items-center justify-center text-primary font-black flex-shrink-0">
            {{ $other ? strtoupper(substr($other->first_name,0,1)) : '?' }}
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between">
                <p class="font-bold text-gray-900 text-sm">{{ $other ? $other->first_name.' '.$other->last_name : 'Utilisateur inconnu' }}</p>
                @if($lastMsg)<p class="text-xs text-gray-400">{{ $lastMsg->created_at->diffForHumans() }}</p>@endif
            </div>
            <p class="text-xs text-gray-500 mt-0.5 truncate">
                @if($conv->booking) {{ $conv->booking->ride?->origin?->city }} → {{ $conv->booking->ride?->destination?->city }} @endif
            </p>
            @if($lastMsg)<p class="text-xs text-gray-400 truncate mt-0.5">{{ $lastMsg->content }}</p>@endif
        </div>
    </div>
</a>
@endforeach
</div>
@endif
@endsection
