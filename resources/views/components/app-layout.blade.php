<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('partials.header')
<body>

    {{-- ════════════════════════════════════
         NAVBAR PRINCIPALE
    ════════════════════════════════════ --}}
    <nav id="main-nav" style="
        height: 64px;
        background: rgba(255,255,255,.95);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border-bottom: 1.5px solid var(--border);
        display: flex;
        align-items: center;
        padding: 0 2.5rem;
        position: sticky;
        top: 0;
        z-index: 500;
        gap: 0;
    ">
        {{-- Brand --}}
        <a href="{{ route('home') }}" style="
            display: flex; align-items: center; gap: .6rem;
            text-decoration: none; color: var(--dark);
            font-weight: 900; font-size: 1.1rem; letter-spacing: -.015em;
            flex-shrink: 0;
        ">
            <img src="{{ asset('logo.png') }}" alt="TGether" style="height: 32px; width: auto;">
            <span>TGether</span>
        </a>

        {{-- Nav links — desktop --}}
        <div id="nav-links" style="
            display: flex; align-items: center; gap: .25rem; margin-left: 2rem;
        ">
            @php $r = request(); @endphp
            @foreach([
                ['Accueil',           route('home'),      $r->routeIs('home')],
                ['Trajets',           '#',                false],
                ['Comment ça marche', '#',                false],
            ] as [$label, $href, $active])
            <a href="{{ $href }}" style="
                font-size: .875rem; font-weight: 700;
                color: {{ $active ? 'var(--blue)' : 'var(--muted)' }};
                text-decoration: none; padding: .45rem .9rem; border-radius: 10px;
                background: {{ $active ? 'var(--blue-light)' : 'transparent' }};
                transition: color .2s, background .2s;
                white-space: nowrap;
            "
            onmouseover="if('{{ $active }}' !== '1'){ this.style.color='var(--blue)'; this.style.background='var(--blue-light)'; }"
            onmouseout="if('{{ $active }}' !== '1'){ this.style.color='var(--muted)'; this.style.background='transparent'; }">
                {{ $label }}
            </a>
            @endforeach
        </div>

        {{-- Spacer --}}
        <div style="flex: 1;"></div>

        {{-- Right actions --}}
        <div id="nav-actions" style="display: flex; align-items: center; gap: .625rem;">
            @auth
                <div style="display:flex;align-items:center;gap:.5rem;">
                    <img src="https://img.daisyui.com/images/profile/demo/distracted1@192.webp"
                         style="width:32px;height:32px;border-radius:50%;object-fit:cover;border:2px solid var(--border);" alt="">
                    <span style="font-size:.875rem;font-weight:700;color:var(--dark);">{{ auth()->user()->first_name }}</span>
                </div>
                <a href="{{ url('/dashboard') }}" style="
                    font-size: .875rem; font-weight: 700; color: var(--muted);
                    text-decoration: none; padding: .45rem .9rem;
                    border-radius: 10px; border: 1.5px solid var(--border);
                    transition: all .2s;
                "
                onmouseover="this.style.borderColor='var(--blue)';this.style.color='var(--blue)'"
                onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--muted)'">
                    Dashboard
                </a>
            @else
                @if (Route::has('login'))
                <a href="{{ route('login') }}" style="
                    font-size: .875rem; font-weight: 700; color: var(--muted);
                    text-decoration: none; padding: .45rem .9rem; border-radius: 10px;
                    transition: color .2s, background .2s;
                "
                onmouseover="this.style.color='var(--blue)';this.style.background='var(--blue-light)'"
                onmouseout="this.style.color='var(--muted)';this.style.background='transparent'">
                    Se connecter
                </a>
                @endif
                @if (Route::has('register'))
                <a href="{{ route('register') }}" style="
                    font-size: .875rem; font-weight: 800; color: #fff;
                    text-decoration: none; padding: .5rem 1.125rem;
                    border-radius: 10px; background: var(--blue);
                    box-shadow: 0 3px 12px rgba(0,110,255,.28);
                    transition: background .2s, box-shadow .2s;
                    white-space: nowrap;
                "
                onmouseover="this.style.background='var(--blue-dark)';this.style.boxShadow='0 4px 18px rgba(0,110,255,.42)'"
                onmouseout="this.style.background='var(--blue)';this.style.boxShadow='0 3px 12px rgba(0,110,255,.28)'">
                    Créer un compte
                </a>
                @endif
            @endauth
        </div>

        {{-- Hamburger — mobile only --}}
        <button id="hamburger" onclick="toggleMobileMenu()" aria-label="Menu" style="
            display: none; background: none; border: 1.5px solid var(--border);
            border-radius: 8px; padding: .4rem .5rem; cursor: pointer;
            color: var(--muted); margin-left: .75rem; transition: all .2s;
        "
        onmouseover="this.style.borderColor='var(--blue)';this.style.color='var(--blue)'"
        onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--muted)'">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </nav>

    {{-- Mobile menu --}}
    <div id="mobile-menu" style="
        display: none; flex-direction: column; gap: .25rem;
        background: #fff; border-bottom: 1.5px solid var(--border);
        padding: .875rem 1.25rem 1.25rem;
    ">
        <a href="{{ route('home') }}"    style="font-size:.9rem;font-weight:700;color:var(--dark);text-decoration:none;padding:.65rem .75rem;border-radius:10px;display:block;">Accueil</a>
        <a href="#"                       style="font-size:.9rem;font-weight:700;color:var(--muted);text-decoration:none;padding:.65rem .75rem;border-radius:10px;display:block;">Trajets</a>
        <a href="#"                       style="font-size:.9rem;font-weight:700;color:var(--muted);text-decoration:none;padding:.65rem .75rem;border-radius:10px;display:block;">Comment ça marche</a>
        <hr style="border:none;border-top:1px solid var(--border);margin:.5rem 0;">
        @auth
            <a href="{{ url('/dashboard') }}" style="font-size:.9rem;font-weight:700;color:var(--blue);text-decoration:none;padding:.65rem .75rem;border-radius:10px;display:block;">Dashboard</a>
        @else
            <a href="{{ route('login') }}"    style="font-size:.9rem;font-weight:700;color:var(--muted);text-decoration:none;padding:.65rem .75rem;border-radius:10px;display:block;">Se connecter</a>
            <a href="{{ route('register') }}" style="font-size:.9rem;font-weight:800;color:#fff;background:var(--blue);text-decoration:none;padding:.65rem .75rem;border-radius:10px;display:block;text-align:center;margin-top:.25rem;">Créer un compte</a>
        @endauth
    </div>

    {{-- ════════════════════════════════════
         CONTENU
    ════════════════════════════════════ --}}
    <main style="min-height: calc(100vh - 64px); display: flex; flex-direction: column;">
        {{ $slot }}
    </main>

    {{-- ════════════════════════════════════
         FOOTER
    ════════════════════════════════════ --}}
    @include('partials.footer')

    <style>
        @media (max-width: 900px) {
            #nav-links    { display: none !important; }
            #hamburger    { display: block !important; }
        }
        @media (max-width: 600px) {
            #main-nav { padding: 0 1rem !important; }
        }
    </style>

    <script>
        let mobileOpen = false;
        function toggleMobileMenu() {
            mobileOpen = !mobileOpen;
            const m = document.getElementById('mobile-menu');
            m.style.display = mobileOpen ? 'flex' : 'none';
        }
        // Close on resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 900 && mobileOpen) {
                document.getElementById('mobile-menu').style.display = 'none';
                mobileOpen = false;
            }
        });
    </script>

</body>
</html>
