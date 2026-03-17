<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use App\Models\User;
use App\Models\Booking;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'rides'      => Ride::count(),
            'users'      => User::count(),
            'bookings'   => Booking::where('status', 'completed')->count(),
        ];

        $latestRides = Ride::with(['driver', 'origin', 'destination'])
            ->where('status', 'scheduled')
            ->where('departure_datetime', '>', now())
            ->orderBy('departure_datetime')
            ->limit(6)
            ->get();

        return view('welcome', compact('stats', 'latestRides'));
    }
}
