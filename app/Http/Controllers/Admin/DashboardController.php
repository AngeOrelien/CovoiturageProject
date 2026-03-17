<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ride;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Report;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users'         => User::count(),
            'drivers'       => User::where('role', 'driver')->count(),
            'passengers'    => User::where('role', 'passenger')->count(),
            'rides'         => Ride::count(),
            'active_rides'  => Ride::where('status', 'active')->count(),
            'bookings'      => Booking::count(),
            'revenue'       => Payment::where('status', 'completed')->sum('amount'),
            'pending_reports' => Report::where('status', 'pending')->count(),
        ];

        $recentUsers    = User::latest()->limit(5)->get();
        $recentRides    = Ride::with(['driver', 'origin', 'destination'])->latest()->limit(5)->get();
        $pendingReports = Report::with(['reporter', 'reportedUser'])->where('status', 'pending')->limit(5)->get();

        $monthlyRevenue = Payment::where('status', 'completed')
            ->selectRaw('strftime("%m", created_at) as month, SUM(amount) as total')
            ->groupByRaw('strftime("%m", created_at)')
            ->orderByRaw('strftime("%m", created_at)')
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentRides', 'pendingReports', 'monthlyRevenue'));
    }
}
