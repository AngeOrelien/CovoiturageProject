<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user->load(['driverProfile.vehicles', 'wallet']);

        $stats = [
            'rides_total'       => $user->rides()->count(),
            'rides_completed'   => $user->rides()->where('status', 'completed')->count(),
            'rides_upcoming'    => $user->rides()->where('status', 'scheduled')->where('departure_datetime', '>', now())->count(),
            'total_passengers'  => $user->rides()->withCount('bookings')->get()->sum('bookings_count'),
            'wallet_balance'    => $user->wallet?->balance ?? 0,
            'avg_rating'        => $user->rating_avg,
            'rating_count'      => $user->rating_count,
        ];

        $upcomingRides = $user->rides()
            ->with(['origin', 'destination', 'vehicle', 'bookings.passenger'])
            ->where('status', 'scheduled')
            ->where('departure_datetime', '>', now())
            ->orderBy('departure_datetime')
            ->limit(5)
            ->get();

        $pendingBookings = \App\Models\Booking::whereHas('ride', fn($q) => $q->where('driver_id', $user->id))
            ->with(['passenger', 'ride.origin', 'ride.destination'])
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        $recentTransactions = $user->wallet?->transactions()->latest('created_at')->limit(5)->get();

        return view('driver.dashboard', compact('user', 'stats', 'upcomingRides', 'pendingBookings', 'recentTransactions'));
    }
}
