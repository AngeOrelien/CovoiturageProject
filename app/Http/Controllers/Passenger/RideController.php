<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Models\{Ride, Location, Booking, Notification, Payment};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};

class RideController extends Controller
{
    public function index(Request $request)
    {
        $query = Ride::with(['driver', 'origin', 'destination', 'vehicle'])
            ->where('status', 'scheduled')
            ->where('departure_datetime', '>', now())
            ->where('seats_available', '>', 0);

        if ($request->filled('origin')) {
            $query->whereHas('origin', fn($q) => $q->where('city', 'like', '%'.$request->origin.'%'));
        }
        if ($request->filled('destination')) {
            $query->whereHas('destination', fn($q) => $q->where('city', 'like', '%'.$request->destination.'%'));
        }
        if ($request->filled('date')) {
            $query->whereDate('departure_datetime', $request->date);
        }
        if ($request->filled('seats')) {
            $query->where('seats_available', '>=', $request->seats);
        }
        if ($request->filled('max_price')) {
            $query->where('price_per_seat', '<=', $request->max_price);
        }

        $rides = $query->orderBy('departure_datetime')->paginate(12)->withQueryString();
        return view('passenger.rides.index', compact('rides'));
    }

    public function show(Ride $ride)
    {
        $ride->load(['driver', 'vehicle', 'origin', 'destination', 'waypoints.location', 'bookings']);
        $alreadyBooked = $ride->bookings()->where('passenger_id', Auth::id())->exists();
        return view('passenger.rides.show', compact('ride', 'alreadyBooked'));
    }
}

