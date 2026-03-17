@extends('layouts.app')
@section('title','Détail Réservation')
@section('page-title','Détail de la Réservation')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <div class="xl:col-span-2 space-y-5">
        {{-- Booking info --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-start justify-between mb-5">
                <div>
                    <h2 class="text-2xl font-black text-gray-900">{{ $booking->ride->origin->city }} → {{ $booking->ride->destination->city }}</h2>
                    <p class="text-gray-400 mt-1">{{ $booking->ride->departure_datetime->translatedFormat('l d F Y à H:i') }}</p>
                </div>
                <span class="badge-{{ $booking->status }} text-sm px-3 py-1">{{ ucfirst($booking->status) }}</span>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <div class="bg-gray-50 rounded-xl p-3"><p class="text-xs text-gray-500">Places réservées</p><p class="font-black text-gray-900">{{ $booking->seats_booked }}</p></div>
                <div class="bg-primary-50 rounded-xl p-3"><p class="text-xs text-gray-500">Total payé</p><p class="font-black text-primary">{{ number_format($booking->total_price,0,',',' ') }} XAF</p></div>
                @if($booking->payment)
                <div class="bg-gray-50 rounded-xl p-3"><p class="text-xs text-gray-500">Paiement</p><p class="font-medium capitalize text-sm">{{ $booking->payment->method }}</p></div>
                <div class="bg-{{ $booking->payment->status === 'completed' ? 'green' : 'yellow' }}-50 rounded-xl p-3"><p class="text-xs text-gray-500">Statut paiement</p><p class="font-bold text-sm capitalize text-{{ $booking->payment->status === 'completed' ? 'green' : 'yellow' }}-700">{{ $booking->payment->status }}</p></div>
                @endif
            </div>
            @if($booking->status === 'cancelled' && $booking->cancel_reason)
            <div class="mt-4 bg-red-50 rounded-xl p-4"><p class="text-xs text-red-500 font-semibold mb-1">Raison d'annulation</p><p class="text-sm text-red-700">{{ $booking->cancel_reason }}</p></div>
            @endif
            @if(in_array($booking->status, ['pending','confirmed']))
            <div class="mt-4 flex gap-3">
                <form action="{{ route('passenger.bookings.cancel', $booking) }}" method="POST" onsubmit="return confirm('Annuler cette réservation ?')">
                    @csrf
                    <button class="text-sm bg-red-50 text-red-600 hover:bg-red-100 px-4 py-2.5 rounded-xl font-semibold transition">Annuler la réservation</button>
                </form>
            </div>
            @endif
        </div>

        {{-- Conductor --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-bold text-gray-900 text-sm mb-4">👤 Conducteur</h3>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-primary-50 rounded-full flex items-center justify-center text-primary font-black text-lg">
                    {{ strtoupper(substr($booking->ride->driver->first_name,0,1)) }}
                </div>
                <div>
                    <p class="font-bold text-gray-900">{{ $booking->ride->driver->first_name }} {{ $booking->ride->driver->last_name }}</p>
                    @if($booking->ride->driver->rating_count > 0)
                    <div class="flex items-center gap-1 text-xs text-gray-500"><span class="text-yellow-400">★</span> {{ number_format($booking->ride->driver->rating_avg,1) }}</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Review form --}}
        @if($booking->status === 'completed' && !$booking->review->where('reviewer_id', auth()->id())->count())
        <div class="bg-white rounded-2xl shadow-sm border border-secondary/20 border-2 p-6">
            <h3 class="font-bold text-gray-900 text-sm mb-1">⭐ Évaluer ce trajet</h3>
            <p class="text-xs text-gray-400 mb-4">Partagez votre expérience avec la communauté TGether.</p>
            <form action="{{ route('passenger.bookings.review', $booking) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Note *</label>
                    <div class="flex gap-2" id="star-rating">
                        @for($i=1;$i<=5;$i++)
                        <button type="button" data-value="{{ $i }}" class="star-btn text-3xl text-gray-300 hover:text-yellow-400 transition cursor-pointer focus:outline-none">★</button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-input" value="{{ old('rating',5) }}">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Commentaire</label>
                    <textarea name="comment" rows="3" class="input-field" placeholder="Décrivez votre expérience avec ce conducteur...">{{ old('comment') }}</textarea>
                </div>
                <button class="btn-secondary">Publier mon évaluation</button>
            </form>
        </div>
        @elseif($booking->review->where('reviewer_id', auth()->id())->count())
        <div class="bg-green-50 border border-green-200 rounded-2xl p-5">
            <p class="font-bold text-green-800 text-sm mb-1">✅ Évaluation envoyée</p>
            @php $myReview = $booking->review->where('reviewer_id', auth()->id())->first(); @endphp
            <div class="flex items-center gap-1 mt-2">
                @for($i=1;$i<=5;$i++)<span class="text-lg {{ $i <= $myReview->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>@endfor
            </div>
            @if($myReview->comment)<p class="text-sm text-gray-600 mt-2 italic">"{{ $myReview->comment }}"</p>@endif
        </div>
        @endif
    </div>

    {{-- Vehicle sidebar --}}
    <div class="space-y-5">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-bold text-gray-900 text-sm mb-3">🚗 Véhicule</h3>
            <p class="font-bold text-gray-800 text-sm">{{ $booking->ride->vehicle->brand }} {{ $booking->ride->vehicle->model }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $booking->ride->vehicle->color }} · {{ $booking->ride->vehicle->nb_seats }} places</p>
            <p class="font-mono text-xs text-gray-500 mt-1">{{ $booking->ride->vehicle->license_plate }}</p>
        </div>
        <a href="{{ route('passenger.bookings.index') }}" class="block text-center text-sm text-primary font-semibold hover:underline">← Retour aux réservations</a>
    </div>
</div>

@push('scripts')
<script>
const stars = document.querySelectorAll('.star-btn');
const input = document.getElementById('rating-input');
let selected = parseInt(input.value) || 5;
function updateStars(val) {
    stars.forEach((s,i) => s.classList.toggle('text-yellow-400', i < val));
    stars.forEach((s,i) => s.classList.toggle('text-gray-300', i >= val));
}
updateStars(selected);
stars.forEach(s => {
    s.addEventListener('click', function() {
        selected = parseInt(this.dataset.value);
        input.value = selected;
        updateStars(selected);
    });
    s.addEventListener('mouseenter', function() { updateStars(parseInt(this.dataset.value)); });
    s.addEventListener('mouseleave', () => updateStars(selected));
});
</script>
@endpush
@endsection
