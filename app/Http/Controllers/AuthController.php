<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Votre compte a été désactivé.']);
            }

            return match ($user->role) {
                'admin'     => redirect()->route('admin.dashboard'),
                'driver'    => redirect()->route('driver.dashboard'),
                default     => redirect()->route('passenger.dashboard'),
            };
        }

        return back()->withErrors(['email' => 'Identifiants incorrects.'])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'email'         => 'required|email|unique:users',
            'password'      => ['required', 'confirmed', Password::min(8)],
            'role'          => 'required|in:driver,passenger',
            'date_of_birth' => 'nullable|date|before:today',
            'gender'        => 'nullable|in:male,female,other',
        ]);

        $user = User::create([
            'first_name'    => $data['first_name'],
            'last_name'     => $data['last_name'],
            'email'         => $data['email'],
            'password'      => Hash::make($data['password']),
            'role'          => $data['role'],
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'gender'        => $data['gender'] ?? null,
        ]);

        // Create wallet for new user
        Wallet::create(['user_id' => $user->id, 'balance' => 0, 'currency' => 'XAF']);

        Auth::login($user);

        return match ($user->role) {
            'driver'  => redirect()->route('driver.dashboard')->with('success', 'Bienvenue sur TGether, '.$user->first_name.'!'),
            default   => redirect()->route('passenger.dashboard')->with('success', 'Bienvenue sur TGether, '.$user->first_name.'!'),
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
