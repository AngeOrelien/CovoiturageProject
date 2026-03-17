<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\{Ride, Location, Vehicle};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RideController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->rides()->with(['origin', 'destination', 'vehicle', 'bookings']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rides = $query->latest()->paginate(10);
        return view('driver.rides.index', compact('rides'));
    }

    public function create()
    {
        $vehicles = Auth::user()->driverProfile?->vehicles()->where('is_verified', true)->get() ?? collect();
        return view('driver.rides.create', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'origin_city'        => 'required|string|max:100',
            'origin_label'       => 'required|string|max:200',
            'destination_city'   => 'required|string|max:100',
            'destination_label'  => 'required|string|max:200',
            'vehicle_id'         => 'required|uuid',
            'departure_datetime' => 'required|date|after:now',
            'arrival_datetime'   => 'nullable|date|after:departure_datetime',
            'seats_total'        => 'required|integer|min:1|max:8',
            'price_per_seat'     => 'required|numeric|min:0',
            'description'        => 'nullable|string|max:500',
            'distance_km'        => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($data) {
            $origin = Location::create([
                'label'   => $data['origin_label'],
                'city'    => $data['origin_city'],
                'country' => 'Cameroun',
            ]);
            $destination = Location::create([
                'label'   => $data['destination_label'],
                'city'    => $data['destination_city'],
                'country' => 'Cameroun',
            ]);

            Ride::create([
                'driver_id'          => Auth::id(),
                'vehicle_id'         => $data['vehicle_id'],
                'origin_id'          => $origin->id,
                'destination_id'     => $destination->id,
                'departure_datetime' => $data['departure_datetime'],
                'arrival_datetime'   => $data['arrival_datetime'] ?? null,
                'seats_total'        => $data['seats_total'],
                'seats_available'    => $data['seats_total'],
                'price_per_seat'     => $data['price_per_seat'],
                'description'        => $data['description'] ?? null,
                'distance_km'        => $data['distance_km'] ?? null,
                'status'             => 'scheduled',
            ]);
        });

        return redirect()->route('driver.rides.index')->with('success', 'Trajet publié avec succès!');
    }

    public function show(Ride $ride)
    {
        $this->authorizeRide($ride);
        $ride->load(['origin', 'destination', 'vehicle', 'waypoints.location', 'bookings.passenger']);
        return view('driver.rides.show', compact('ride'));
    }

    public function edit(Ride $ride)
    {
        $this->authorizeRide($ride);
        $vehicles = Auth::user()->driverProfile?->vehicles()->where('is_verified', true)->get() ?? collect();
        return view('driver.rides.edit', compact('ride', 'vehicles'));
    }

    public function update(Request $request, Ride $ride)
    {
        $this->authorizeRide($ride);
        abort_if($ride->status === 'completed', 403);

        $data = $request->validate([
            'departure_datetime' => 'required|date',
            'price_per_seat'     => 'required|numeric|min:0',
            'description'        => 'nullable|string|max:500',
            'status'             => 'required|in:scheduled,active,cancelled',
        ]);

        $ride->update($data);
        return redirect()->route('driver.rides.show', $ride)->with('success', 'Trajet mis à jour.');
    }

    public function cancel(Ride $ride)
    {
        $this->authorizeRide($ride);
        $ride->update(['status' => 'cancelled']);
        $ride->bookings()->whereIn('status', ['pending', 'confirmed'])
            ->update(['status' => 'cancelled', 'cancelled_at' => now(), 'cancel_reason' => 'Trajet annulé par le conducteur']);

        return back()->with('success', 'Trajet annulé. Les passagers ont été notifiés.');
    }

    private function authorizeRide(Ride $ride): void
    {
        abort_if($ride->driver_id !== Auth::id(), 403);
    }
}
