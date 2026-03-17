<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ride;
use Illuminate\Http\Request;

class RideController extends Controller
{
    public function index(Request $request)
    {
        $query = Ride::with(['driver', 'origin', 'destination', 'vehicle']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $q = $request->search;
            $query->whereHas('origin', fn($q2) => $q2->where('city', 'like', "%$q%"))
                  ->orWhereHas('destination', fn($q2) => $q2->where('city', 'like', "%$q%"));
        }

        $rides = $query->latest()->paginate(15);
        return view('admin.rides.index', compact('rides'));
    }

    public function show(Ride $ride)
    {
        $ride->load(['driver', 'vehicle', 'origin', 'destination', 'waypoints.location', 'bookings.passenger']);
        return view('admin.rides.show', compact('ride'));
    }

    public function cancel(Ride $ride)
    {
        $ride->update(['status' => 'cancelled']);
        $ride->bookings()->where('status', 'pending')->update(['status' => 'cancelled', 'cancelled_at' => now(), 'cancel_reason' => 'Trajet annulé par l\'administrateur']);
        return back()->with('success', 'Trajet annulé.');
    }
}
