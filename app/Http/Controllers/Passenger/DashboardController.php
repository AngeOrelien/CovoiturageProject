<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user->load(['wallet']);

        $stats = [
            'bookings_total'     => $user->bookings()->count(),
            'bookings_completed' => $user->bookings()->where('status', 'completed')->count(),
            'bookings_upcoming'  => $user->bookings()->whereIn('status', ['pending', 'confirmed'])
                ->whereHas('ride', fn($q) => $q->where('departure_datetime', '>', now()))->count(),
            'wallet_balance'     => $user->wallet?->balance ?? 0,
            'reviews_count'      => $user->reviews()->count(),
        ];

        $upcomingBookings = $user->bookings()
            ->with(['ride.origin', 'ride.destination', 'ride.driver'])
            ->whereIn('bookings.status', ['pending', 'confirmed'])
            ->whereHas('ride', fn($q) => $q->where('departure_datetime', '>', now()))
            ->join('rides', 'bookings.ride_id', '=', 'rides.id')
            ->orderBy('rides.departure_datetime', 'asc')
            ->select('bookings.*')
            ->limit(3)
            ->get();

        $recentTransactions = $user->wallet?->transactions()->latest('created_at')->limit(5)->get();
        $notifications = $user->notifications()->where('is_read', false)->latest('created_at')->limit(5)->get();

        return view('passenger.dashboard', compact('user', 'stats', 'upcomingBookings', 'recentTransactions', 'notifications'));
    }
}
