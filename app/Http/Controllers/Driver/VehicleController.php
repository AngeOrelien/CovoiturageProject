<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\{Vehicle, DriverProfile};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    public function index()
    {
        $profile  = Auth::user()->driverProfile;
        $vehicles = $profile ? $profile->vehicles()->latest()->get() : collect();
        return view('driver.vehicles.index', compact('vehicles', 'profile'));
    }

    public function create()
    {
        return view('driver.vehicles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'brand'         => 'required|string|max:50',
            'model'         => 'required|string|max:50',
            'year'          => 'required|integer|min:1990|max:'.(date('Y')+1),
            'color'         => 'required|string|max:30',
            'license_plate' => 'required|string|max:20|unique:vehicles',
            'nb_seats'      => 'required|integer|min:2|max:9',
            'fuel_type'     => 'required|in:essence,diesel,hybride,electrique',
            // Driver profile fields if not yet created
            'license_number'        => 'required_without:skip_profile|string|max:50',
            'license_expiry'        => 'required_without:skip_profile|date|after:today',
            'years_of_experience'   => 'nullable|integer|min:0',
        ]);

        $user    = Auth::user();
        $profile = $user->driverProfile;

        if (!$profile) {
            $profile = DriverProfile::create([
                'user_id'             => $user->id,
                'license_number'      => $data['license_number'],
                'license_expiry'      => $data['license_expiry'],
                'years_of_experience' => $data['years_of_experience'] ?? 0,
            ]);
        }

        $profile->vehicles()->create([
            'brand'         => $data['brand'],
            'model'         => $data['model'],
            'year'          => $data['year'],
            'color'         => $data['color'],
            'license_plate' => $data['license_plate'],
            'nb_seats'      => $data['nb_seats'],
            'fuel_type'     => $data['fuel_type'],
        ]);

        return redirect()->route('driver.vehicles.index')->with('success', 'Véhicule ajouté. En attente de vérification.');
    }

    public function destroy(Vehicle $vehicle)
    {
        abort_if($vehicle->driver->user_id !== Auth::id(), 403);
        $vehicle->delete();
        return back()->with('success', 'Véhicule supprimé.');
    }
}
