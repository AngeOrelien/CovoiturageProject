<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function registerForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'first_name'    => ['required', 'string', 'max:100'],
            'last_name'     => ['required', 'string', 'max:100'],
            'email'         => ['required', 'email', 'unique:users,email'],
            'password'      => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'date_of_birth' => ['nullable', 'date', 'before:-18 years'],
            'gender'        => ['nullable', 'in:male,female,other'],
            'bio'           => ['nullable', 'string', 'max:500'],
            'phone'         => ['nullable', 'string', 'max:20'],
        ]);

        $user = User::create([
            'first_name'    => $data['first_name'],
            'last_name'     => $data['last_name'],
            'email'         => $data['email'],
            'password'      => Hash::make($data['password']),
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'gender'        => $data['gender'] ?? null,
            'bio'           => $data['bio'] ?? null,
            'phone'         => $data['phone'] ?? null,
            'role'          => 'passenger',
            'is_active'     => true,
            'is_verified'   => false,
        ]);

        // Créer le wallet automatiquement
        Wallet::create([
            'user_id'  => $user->id,
            'balance'  => 0,
            'currency' => 'XAF',
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('toast', [
                'type'    => 'success',
                'message' => "Bienvenue {$user->first_name} 🎉 Votre compte a été créé avec succès !",
            ]);
    }

    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))
                ->with('toast', [
                    'type'    => 'success',
                    'message' => 'Connexion réussie ! Bon retour 👋',
                ]);
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Ces identifiants ne correspondent à aucun compte.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('toast', [
                'type'    => 'info',
                'message' => 'Vous avez été déconnecté avec succès.',
            ]);
    }
}
