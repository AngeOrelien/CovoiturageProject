<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    @include('partials.header')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { font-family: 'Nunito', sans-serif; box-sizing: border-box; }

        :root {
            --blue:        #006EFF;
            --blue-dark:   #0057CC;
            --blue-light:  #EBF4FF;
            --green:       #00C853;
            --dark:        #0D1B2A;
            --mid:         #4A6080;
            --muted:       #7090B8;
            --border:      #E2EBF6;
            --bg:          #F2F6FC;
        }

        html, body { height: 100%; margin: 0; }

        body {
            background: var(--bg);
            background-image:
                radial-gradient(ellipse 900px 600px at 12% -8%,  rgba(0,110,255,.10) 0%, transparent 70%),
                radial-gradient(ellipse 700px 500px at 92% 108%, rgba(0,200,83,.08)  0%, transparent 70%);
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ────────── NAVBAR ────────── */
        .auth-nav {
            height: 64px;
            background: rgba(255,255,255,.88);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1.5px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 2.5rem;
            position: sticky;
            top: 0;
            z-index: 200;
            gap: 1rem;
            flex-shrink: 0;
        }
        .nav-brand {
            display: flex; align-items: center; gap: .6rem;
            text-decoration: none; color: var(--dark);
            font-weight: 900; font-size: 1.15rem; letter-spacing: -.015em;
        }
        .nav-brand img  { height: 34px; width: auto; }
        .nav-right      { margin-left: auto; display: flex; align-items: center; gap: .5rem; }
        .nav-link {
            font-size: .875rem; font-weight: 700; color: var(--muted);
            text-decoration: none; padding: .45rem .9rem;
            border-radius: 10px; transition: color .2s, background .2s;
        }
        .nav-link:hover       { color: var(--blue); background: var(--blue-light); }
        .nav-link.is-primary  {
            background: var(--blue); color: #fff;
            box-shadow: 0 3px 14px rgba(0,110,255,.28);
        }
        .nav-link.is-primary:hover { background: var(--blue-dark); }

        /* ────────── MAIN LAYOUT ────────── */
        .auth-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
        }

        /* Two-column card — desktop */
        .auth-card {
            display: grid;
            grid-template-columns: 420px 1fr;
            width: 100%;
            max-width: 1100px;
            min-height: 600px;
            background: #fff;
            border-radius: 28px;
            overflow: hidden;
            box-shadow:
                0 4px 6px -2px rgba(0,110,255,.04),
                0 28px 90px -14px rgba(13,27,42,.15);
        }

        /* ────────── LEFT PANEL ────────── */
        .panel-left {
            background: linear-gradient(165deg, #0047B3 0%, #005CF5 50%, #1B7FFF 100%);
            padding: 3rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }
        /* Cercles décoratifs */
        .panel-left::before {
            content: ''; position: absolute;
            top: -90px; right: -90px;
            width: 340px; height: 340px;
            border-radius: 50%;
            background: rgba(255,255,255,.06);
            pointer-events: none;
        }
        .panel-left::after {
            content: ''; position: absolute;
            bottom: -70px; left: -70px;
            width: 260px; height: 260px;
            border-radius: 50%;
            background: rgba(255,255,255,.04);
            pointer-events: none;
        }

        .p-brand { display: flex; align-items: center; gap: .75rem; position: relative; z-index: 1; }
        .p-brand img  { height: 42px; filter: brightness(0) invert(1); opacity: .9; }
        .p-brand span { font-size: 1.4rem; font-weight: 900; color: #fff; letter-spacing: -.015em; }

        .p-headline { position: relative; z-index: 1; }
        .p-headline h2 {
            font-size: 2rem; font-weight: 900; color: #fff; line-height: 1.22;
            margin: 0 0 .875rem; letter-spacing: -.025em;
        }
        .p-headline p { color: rgba(255,255,255,.72); font-size: .9rem; line-height: 1.65; margin: 0; }

        .p-features { position: relative; z-index: 1; display: flex; flex-direction: column; gap: .75rem; }
        .p-feature {
            display: flex; align-items: center; gap: .875rem;
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.14);
            border-radius: 14px; padding: .875rem 1rem;
            backdrop-filter: blur(4px);
        }
        .p-feature .f-icon {
            font-size: 1.4rem; width: 40px; height: 40px; flex-shrink: 0;
            background: rgba(255,255,255,.14); border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .p-feature .f-title { font-size: .86rem; font-weight: 800; color: #fff; }
        .p-feature .f-sub   { font-size: .76rem; color: rgba(255,255,255,.62); margin-top: .1rem; }

        /* ────────── RIGHT PANEL ────────── */
        .panel-right {
            padding: 3.5rem 3.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto;
        }
        .panel-right::-webkit-scrollbar { width: 4px; }
        .panel-right::-webkit-scrollbar-thumb { background: var(--border); border-radius: 99px; }

        /* ────────── FORM PRIMITIVES ────────── */
        .form-heading { font-size: 1.8rem; font-weight: 900; color: var(--dark); letter-spacing: -.025em; margin: 0 0 .3rem; }
        .form-sub     { font-size: .9rem; color: var(--muted); margin: 0 0 2rem; }

        .field-label {
            display: block; font-size: .76rem; font-weight: 800;
            color: var(--mid); margin-bottom: .4rem;
            letter-spacing: .055em; text-transform: uppercase;
        }
        .input-field {
            border: 2px solid var(--border); border-radius: 12px;
            padding: .78rem 1rem; width: 100%;
            font-size: .95rem; font-family: 'Nunito', sans-serif;
            background: #FAFCFF; color: var(--dark); outline: none;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }
        .input-field::placeholder { color: #B0C4DC; }
        .input-field:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 4px rgba(0,110,255,.1);
            background: #fff;
        }
        .input-field.is-error { border-color: #FF3B30; box-shadow: 0 0 0 4px rgba(255,59,48,.1); }
        select.input-field {
            appearance: none; cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='13' height='13' fill='%23A8BCDA' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            padding-right: 2.5rem;
        }
        textarea.input-field { resize: none; }

        .field-icon-wrap { position: relative; }
        .field-icon-wrap .fi-left {
            position: absolute; left: .875rem; top: 50%; transform: translateY(-50%);
            color: #B0C4DC; pointer-events: none; display: flex; align-items: center;
        }
        .field-icon-wrap input { padding-left: 2.6rem; }

        .pw-eye {
            position: absolute; right: .875rem; top: 50%; transform: translateY(-50%);
            background: none; border: none; padding: 0; cursor: pointer;
            color: #B0C4DC; transition: color .2s; display: flex; align-items: center;
        }
        .pw-eye:hover { color: var(--blue); }

        .field-error {
            font-size: .8rem; color: #FF3B30; margin-top: .3rem;
            display: flex; align-items: center; gap: .3rem; font-weight: 600;
        }

        /* Buttons */
        .btn-primary {
            background: var(--blue); color: #fff; border: none;
            border-radius: 12px; font-weight: 800; font-size: 1rem;
            padding: .9rem 2rem; width: 100%; cursor: pointer;
            transition: background .2s, transform .15s, box-shadow .2s;
            box-shadow: 0 4px 20px rgba(0,110,255,.3); letter-spacing: .01em;
        }
        .btn-primary:hover  { background: var(--blue-dark); transform: translateY(-1px); box-shadow: 0 8px 28px rgba(0,110,255,.42); }
        .btn-primary:active { transform: none; }

        .btn-ghost {
            background: transparent; color: var(--blue);
            border: 2px solid var(--border); border-radius: 12px;
            font-weight: 700; font-size: .95rem;
            padding: .8rem 2rem; width: 100%; cursor: pointer;
            transition: all .2s; display: block; text-align: center;
            text-decoration: none;
        }
        .btn-ghost:hover { border-color: var(--blue); background: var(--blue-light); }

        /* Divider */
        .divider {
            display: flex; align-items: center; gap: .875rem; margin: 1rem 0;
        }
        .divider::before, .divider::after { content:''; flex:1; height:1px; background: var(--border); }
        .divider span { font-size: .76rem; color: #C0D0E4; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; }

        /* ────────── WIZARD ────────── */
        .step-pane { display: none; }
        .step-pane.active      { display: block; animation: sIn  .32s cubic-bezier(.4,0,.2,1) forwards; }
        .step-pane.going-back  { display: block; animation: sInB .32s cubic-bezier(.4,0,.2,1) forwards; }
        @keyframes sIn  { from { opacity:0; transform:translateX(22px); } to { opacity:1; transform:none; } }
        @keyframes sInB { from { opacity:0; transform:translateX(-22px); } to { opacity:1; transform:none; } }

        .step-dot { width: 8px; height: 8px; border-radius: 50%; background: #D0E4FF; transition: all .3s; }
        .step-dot.active { width: 22px; border-radius: 99px; background: var(--blue); }
        .step-dot.done   { background: var(--green); }

        .prog-bar  { height: 3px; background: var(--border); border-radius: 99px; overflow: hidden; margin-bottom: 1.75rem; }
        .prog-fill { height: 100%; border-radius: 99px; background: linear-gradient(90deg, var(--blue) 0%, var(--green) 100%); transition: width .5s cubic-bezier(.4,0,.2,1); }

        /* Role cards */
        .role-card          { position: relative; }
        .role-card input    { position: absolute; opacity: 0; pointer-events: none; }
        .role-card label {
            display: flex; flex-direction: column; align-items: center; gap: .5rem;
            padding: 1.5rem 1rem; border: 2px solid var(--border); border-radius: 16px;
            cursor: pointer; font-weight: 800; font-size: .9rem; color: var(--muted);
            background: #FAFCFF; transition: all .22s; text-align: center;
        }
        .role-card label .icon { font-size: 2.2rem; }
        .role-card label .sub  { font-size: .74rem; font-weight: 600; opacity: .7; line-height: 1.4; }
        .role-card input:checked + label {
            border-color: var(--blue); background: var(--blue-light); color: var(--blue);
            box-shadow: 0 0 0 4px rgba(0,110,255,.12);
        }
        .role-card label:hover { border-color: #A0C4F0; background: #F0F7FF; }

        /* Strength */
        .strength-track { height: 4px; background: var(--border); border-radius: 99px; overflow: hidden; margin-top: .5rem; }
        .strength-fill  { height: 100%; border-radius: 99px; transition: width .4s, background .4s; width: 0; }
        .checklist      { background: var(--bg); border-radius: 14px; padding: 1rem 1.125rem; margin-top: .875rem; display: grid; grid-template-columns: 1fr 1fr; gap: .45rem; }
        .chk-item       { display: flex; align-items: center; gap: .45rem; font-size: .79rem; color: var(--muted); font-weight: 600; }
        .chk-icon       { font-size: .75rem; color: #D0E4FF; width: 14px; text-align: center; flex-shrink: 0; }

        /* Avatar */
        .avatar-wrap       { position: relative; cursor: pointer; display: inline-block; }
        .avatar-wrap img   { display: block; width: 80px; height: 80px; object-fit: cover; border-radius: 50%; border: 3px solid #fff; box-shadow: 0 4px 16px rgba(0,110,255,.18); }
        .avatar-overlay    {
            position: absolute; inset: 0; border-radius: 50%;
            background: rgba(0,110,255,.45); opacity: 0;
            display: flex; align-items: center; justify-content: center;
            transition: opacity .2s; color: #fff;
        }
        .avatar-wrap:hover .avatar-overlay { opacity: 1; }

        /* Toast */
        .toast-anim { animation: toastIn .4s cubic-bezier(.22,1,.36,1) forwards; }
        @keyframes toastIn { from { opacity:0; transform:translateY(-14px) scale(.96); } to { opacity:1; transform:none; } }

        /* ────────── RESPONSIVE ────────── */
        @media (max-width: 900px) {
            .auth-card { grid-template-columns: 1fr; }
            .panel-left { display: none; }
            .panel-right { padding: 2.5rem 2rem; }
            .auth-content { padding: 1.5rem 1rem; align-items: flex-start; }
        }
        @media (max-width: 480px) {
            .panel-right { padding: 1.75rem 1.25rem; }
            .form-heading { font-size: 1.5rem; }
            .auth-nav { padding: 0 1rem; }
            .checklist { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body>

    {{-- ── Toast ── --}}
    @if(session('toast'))
    <div id="g-toast"
         class="toast-anim fixed top-5 right-5 z-[300] flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl
                {{ session('toast.type') === 'success' ? 'bg-[#00C853]' :
                   (session('toast.type') === 'error'  ? 'bg-[#FF3B30]' :
                   (session('toast.type') === 'info'   ? 'bg-[#006EFF]' : 'bg-[#FF9500]')) }} text-white"
         style="max-width:360px;">
        <span style="font-size:1.1rem;font-weight:900;line-height:1">
            {{ session('toast.type') === 'success' ? '✓' : (session('toast.type') === 'error' ? '✕' : 'ℹ') }}
        </span>
        <span style="font-size:.875rem;font-weight:600;line-height:1.4;flex:1">{{ session('toast.message') }}</span>
        <button onclick="this.parentElement.remove()" style="background:none;border:none;color:inherit;opacity:.7;cursor:pointer;font-size:1.2rem;line-height:1;padding:0;margin-left:.5rem">×</button>
    </div>
    @endif

    {{-- ── Navbar ── --}}
    <nav class="auth-nav">
        <a href="{{ route('home') }}" class="nav-brand">
            <img src="{{ asset('logo.png') }}" alt="TGether">
            <span>TGether</span>
        </a>
        <div class="nav-right">
            @guest
                <a href="{{ route('login') }}"    class="nav-link {{ request()->routeIs('login')    ? 'is-primary' : '' }}">Se connecter</a>
                <a href="{{ route('register') }}" class="nav-link {{ request()->routeIs('register') ? 'is-primary' : '' }}">Créer un compte</a>
            @endguest
        </div>
    </nav>

    {{-- ── Content ── --}}
    <main class="auth-content">
        <div class="auth-card">

            {{-- Panneau gauche : valeurs brand --}}
            <aside class="panel-left">
                <div class="p-brand">
                    <img src="{{ asset('logo.png') }}" alt="">
                    <span>TGether</span>
                </div>

                <div class="p-headline">
                    <h2>{{ $leftTitle ?? "Voyagez ensemble,\npayez moins." }}</h2>
                    <p>{{ $leftSub ?? 'Des milliers de trajets disponibles au Cameroun. Partagez les frais, rencontrez des gens, réduisez votre empreinte.' }}</p>
                </div>

                <div class="p-features">
                    @foreach([
                        ['🚗', 'Des trajets partout',      'Toutes les grandes villes du Cameroun'],
                        ['💰', 'Économisez jusqu\'à 70%',  'Partagez les frais de carburant'],
                        ['🌿', 'Moins de CO₂',             'Chaque passager réduit l\'empreinte'],
                        ['🔒', 'Membres vérifiés',          'Profils notés et paiements sécurisés'],
                    ] as [$ico, $t, $s])
                    <div class="p-feature">
                        <div class="f-icon">{{ $ico }}</div>
                        <div>
                            <p class="f-title">{{ $t }}</p>
                            <p class="f-sub">{{ $s }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </aside>

            {{-- Panneau droit : slot formulaire --}}
            <div class="panel-right">
                {{ $slot }}
            </div>

        </div>
    </main>

    <footer style="text-align:center;padding:1.25rem 1rem;font-size:.76rem;color:#A8BCDA;font-weight:600;flex-shrink:0;">
        © {{ date('Y') }} TGether &mdash; Covoiturage &amp; Mobilité Partagée 🌿
    </footer>

    <script>
        const gt = document.getElementById('g-toast');
        if (gt) {
            setTimeout(() => {
                gt.style.transition = 'opacity .6s ease, transform .6s ease';
                gt.style.opacity = '0';
                gt.style.transform = 'translateY(-12px)';
                setTimeout(() => gt.remove(), 600);
            }, 4500);
        }

        // Helpers globaux réutilisés dans login + register
        function togglePw(id, btn) {
            const inp = document.getElementById(id);
            inp.type = inp.type === 'password' ? 'text' : 'password';
            btn.querySelector('.eye-on').classList.toggle('hidden');
            btn.querySelector('.eye-off').classList.toggle('hidden');
        }
    </script>
</body>
</html>
