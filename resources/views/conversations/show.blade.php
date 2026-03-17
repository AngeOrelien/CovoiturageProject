@extends('layouts.app')
@section('title','Conversation')
@section('page-title','Conversation')

@section('content')
<div class="max-w-2xl mx-auto flex flex-col" style="height: calc(100vh - 200px)">
    {{-- Header --}}
    <div class="bg-white rounded-t-2xl shadow-sm border border-gray-100 border-b-0 px-5 py-4 flex items-center gap-3">
        <a href="{{ route('conversations.index') }}" class="text-gray-400 hover:text-gray-700 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div class="w-9 h-9 bg-primary-50 rounded-full flex items-center justify-center text-primary font-bold text-sm">💬</div>
        <div>
            <p class="font-bold text-gray-900 text-sm">Trajet: {{ $conversation->booking?->ride?->origin?->city }} → {{ $conversation->booking?->ride?->destination?->city }}</p>
            <p class="text-xs text-gray-400">{{ $conversation->participants->count() }} participant(s)</p>
        </div>
    </div>

    {{-- Messages --}}
    <div class="flex-1 bg-white border-x border-gray-100 overflow-y-auto px-5 py-4 space-y-4" id="messages-container">
        @foreach($messages as $msg)
        @php $isMe = $msg->sender_id === auth()->id(); @endphp
        <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
            <div class="max-w-xs lg:max-w-md">
                @if(!$isMe)
                <p class="text-xs text-gray-400 mb-1 ml-1">{{ $msg->sender->first_name }}</p>
                @endif
                <div class="px-4 py-2.5 rounded-2xl text-sm {{ $isMe
                    ? 'bg-primary text-white rounded-br-sm'
                    : 'bg-gray-100 text-gray-800 rounded-bl-sm' }}">
                    {{ $msg->content }}
                </div>
                <p class="text-xs text-gray-300 mt-1 {{ $isMe ? 'text-right mr-1' : 'ml-1' }}">
                    {{ $msg->created_at->format('H:i') }}
                </p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Input --}}
    <div class="bg-white rounded-b-2xl border border-gray-100 border-t-gray-100 px-4 py-3">
        <form action="{{ route('conversations.send', $conversation) }}" method="POST" class="flex items-center gap-3">
            @csrf
            <input type="text" name="content" required
                class="flex-1 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                placeholder="Écrivez votre message..." autocomplete="off">
            <button type="submit" class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center text-white hover:bg-primary-600 transition flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
const container = document.getElementById('messages-container');
container.scrollTop = container.scrollHeight;
</script>
@endpush
@endsection
