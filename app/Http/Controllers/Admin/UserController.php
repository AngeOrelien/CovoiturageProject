<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->where('first_name', 'like', "%$q%")
                   ->orWhere('last_name', 'like', "%$q%")
                   ->orWhere('email', 'like', "%$q%");
            });
        }

        $users = $query->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['driverProfile.vehicles', 'bookings.ride', 'wallet.transactions', 'reviews']);
        return view('admin.users.show', compact('user'));
    }

    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activé' : 'désactivé';
        return back()->with('success', "Compte $status avec succès.");
    }

    public function verifyDriver(User $user)
    {
        if ($user->driverProfile) {
            $user->driverProfile->update(['is_license_verified' => true]);
        }
        return back()->with('success', 'Profil conducteur vérifié.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé.');
    }
}
