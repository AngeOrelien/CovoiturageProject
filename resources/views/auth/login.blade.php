<x-auth.auth-layout>

    <h1 class="form-heading">Bon retour 👋</h1>
    <p class="form-sub">Connectez-vous à votre compte TGether.</p>

    {{-- Erreur globale --}}
    @if ($errors->any())
    <div style="display:flex;align-items:flex-start;gap:.75rem;background:#FFF0EF;border:1.5px solid #FFD0CC;border-radius:14px;padding:.875rem 1rem;margin-bottom:1.5rem;">
        <span style="color:#FF3B30;font-size:1rem;line-height:1;margin-top:.1rem">⚠</span>
        <p style="margin:0;font-size:.875rem;font-weight:600;color:#CC2A1F;">{{ $errors->first() }}</p>
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}" style="display:flex;flex-direction:column;gap:1.25rem;">
        @csrf

        {{-- Email --}}
        <div>
            <label class="field-label" for="email">Adresse email</label>
            <div class="field-icon-wrap">
                <span class="fi-left">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </span>
                <input type="email" id="email" name="email"
                       value="{{ old('email') }}"
                       placeholder="vous@exemple.cm"
                       autofocus
                       class="input-field {{ $errors->has('email') ? 'is-error' : '' }}" />
            </div>
        </div>

        {{-- Mot de passe --}}
        <div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.4rem;">
                <label class="field-label" style="margin:0;" for="password">Mot de passe</label>
                <a href="#" style="font-size:.8rem;font-weight:700;color:var(--blue);text-decoration:none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">Mot de passe oublié ?</a>
            </div>
            <div class="field-icon-wrap" style="position:relative;">
                <span class="fi-left">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </span>
                <input type="password" id="password" name="password"
                       placeholder="••••••••"
                       style="padding-right:3rem;"
                       class="input-field {{ $errors->has('password') ? 'is-error' : '' }}" />
                <button type="button" class="pw-eye" onclick="togglePw('password', this)" aria-label="Voir le mot de passe">
                    <svg class="eye-on" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg class="eye-off hidden" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Se souvenir --}}
        <label style="display:flex;align-items:center;gap:.625rem;cursor:pointer;width:fit-content;">
            <input type="checkbox" name="remember"
                   style="width:16px;height:16px;border-radius:5px;accent-color:var(--blue);cursor:pointer;flex-shrink:0;" />
            <span style="font-size:.875rem;font-weight:600;color:var(--mid);">Se souvenir de moi</span>
        </label>

        {{-- CTA --}}
        <button type="submit" class="btn-primary" style="margin-top:.5rem;">
            Se connecter →
        </button>

        <div class="divider"><span>ou</span></div>

        <a href="{{ route('register') }}" class="btn-ghost">Créer un compte</a>

    </form>

</x-auth.auth-layout>
