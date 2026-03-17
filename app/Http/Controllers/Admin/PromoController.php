<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::latest('created_at')->paginate(15);
        return view('admin.promos.index', compact('promos'));
    }

    public function create()
    {
        return view('admin.promos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'               => 'required|unique:promos|max:20',
            'discount_type'      => 'required|in:percentage,fixed',
            'discount_value'     => 'required|numeric|min:0',
            'min_booking_amount' => 'nullable|numeric|min:0',
            'max_uses'           => 'nullable|integer|min:1',
            'expires_at'         => 'nullable|date|after:today',
            'is_active'          => 'boolean',
        ]);

        Promo::create($data);
        return redirect()->route('admin.promos.index')->with('success', 'Code promo créé avec succès.');
    }

    public function toggle(Promo $promo)
    {
        $promo->update(['is_active' => !$promo->is_active]);
        return back()->with('success', 'Statut du code promo mis à jour.');
    }

    public function destroy(Promo $promo)
    {
        $promo->delete();
        return redirect()->route('admin.promos.index')->with('success', 'Code promo supprimé.');
    }
}
