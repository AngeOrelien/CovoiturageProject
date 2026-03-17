<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TGether — Covoiturage au Cameroun</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/tailwindcss.js') }}"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary:   { DEFAULT: '#0374BA', 50:'#e6f2fa', 100:'#b3d8f0', 300:'#4da3dc', 500:'#0374BA', 600:'#025d95', 700:'#024670' },
                        secondary: { DEFAULT: '#64AC5A', 50:'#eef6ed', 300:'#88c784', 500:'#64AC5A', 600:'#508948', 700:'#3c6736' },
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* ── Navbar ── */
        .nav-link {
            color: #374151; font-weight: 500; font-size: 0.875rem;
            text-decoration: none; transition: color 0.2s; position: relative;
        }
        .nav-link::after {
            content: ''; position: absolute; bottom: -4px; left: 0;
            width: 0; height: 2px; background: #0374BA;
            transition: width 0.25s ease;
        }
        .nav-link:hover { color: #0374BA; }
        .nav-link:hover::after { width: 100%; }

        /* ── Hero ── */
        .hero-overlay {
            background: linear-gradient(135deg,
                rgba(2,70,112,0.93) 0%,
                rgba(3,116,186,0.80) 45%,
                rgba(100,172,90,0.60) 100%);
        }

        /* Floating dots */
        .dot {
            position: absolute; border-radius: 50%;
            background: rgba(255,255,255,0.06);
            animation: floatDot 8s ease-in-out infinite;
        }
        @keyframes floatDot {
            0%,100% { transform: translateY(0) scale(1); }
            50%      { transform: translateY(-30px) scale(1.05); }
        }

        /* Animated gradient title underline */
        .title-accent {
            position: relative; display: inline-block;
            background: linear-gradient(90deg, #64AC5A, #88c784, #64AC5A);
            background-size: 200% auto;
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 3s linear infinite;
        }
        @keyframes shimmer { to { background-position: 200% center; } }

        /* Scroll indicator */
        .scroll-arrow {
            animation: bounce 2s ease-in-out infinite;
        }
        @keyframes bounce {
            0%,100% { transform: translateY(0); }
            50%      { transform: translateY(8px); }
        }

        /* Fade-up entries */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(28px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up   { animation: fadeUp 0.75s cubic-bezier(.22,1,.36,1) both; }
        .fade-up-1 { animation-delay: 0.10s; }
        .fade-up-2 { animation-delay: 0.25s; }
        .fade-up-3 { animation-delay: 0.42s; }
        .fade-up-4 { animation-delay: 0.58s; }

        /* Slide-in from right (right column) */
        @keyframes slideRight {
            from { opacity: 0; transform: translateX(40px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        .slide-right { animation: slideRight 0.85s cubic-bezier(.22,1,.36,1) 0.3s both; }

        /* Search bar glow on focus */
        .search-bar { transition: box-shadow 0.3s; }
        .search-bar:focus-within {
            box-shadow: 0 0 0 4px rgba(3,116,186,0.18), 0 20px 50px rgba(0,0,0,0.15);
        }

        /* Stats counter card */
        .stat-pill {
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.22);
            transition: background 0.2s, transform 0.2s;
        }
        .stat-pill:hover {
            background: rgba(255,255,255,0.22);
            transform: translateY(-2px);
        }

        /* ── Steps ── */
        .step-card {
            position: relative;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .step-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 24px 48px rgba(3,116,186,0.12);
        }
        .step-connector {
            position: absolute; top: 40px; right: -28px;
            width: 56px; height: 2px;
            background: linear-gradient(90deg, #0374BA, #64AC5A);
            z-index: 10;
        }
        @media (max-width: 768px) { .step-connector { display: none; } }

        /* ── Ride cards ── */
        .ride-card {
            transition: transform 0.3s, box-shadow 0.3s, border-color 0.3s;
            border: 1.5px solid #f3f4f6;
        }
        .ride-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 24px 48px rgba(3,116,186,0.13);
            border-color: #b3d8f0;
        }
        .ride-card .ride-arrow {
            opacity: 0; transform: translateX(-6px);
            transition: opacity 0.25s, transform 0.25s;
        }
        .ride-card:hover .ride-arrow {
            opacity: 1; transform: translateX(0);
        }

        /* Section badge */
        .section-badge {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 0.75rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.1em;
            padding: 4px 14px; border-radius: 9999px;
        }
    </style>
</head>
<body class="bg-white overflow-x-hidden">

{{-- ── NAVBAR ───────────────────────────────────────────── --}}
<nav class="fixed top-0 left-0 right-0 z-50 bg-white backdrop-blur-md border-b border-gray-100 shadow-sm" id="navbar" style="background-color: white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3.5 flex items-center justify-between">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
            <div class="w-9 h-9 bg-primary rounded-xl flex items-center justify-center text-white font-black text-lg shadow-md shadow-primary/30 group-hover:scale-105 transition-transform">T</div>
            <div>
                <span class="text-xl font-black text-gray-900 tracking-tight">TGether</span>
                <div class="text-xs text-gray-400 font-medium leading-none -mt-0.5">Covoiturage 🇨🇲</div>
            </div>
        </a>

        {{-- Nav links --}}
        <div class="hidden md:flex items-center gap-8">
            <a href="#comment-ca-marche" class="nav-link">Comment ça marche</a>
            <a href="#trajets" class="nav-link">Trajets</a>
            <a href="#avantages" class="nav-link">Avantages</a>
            <a href="#a-propos" class="nav-link">À propos</a>
        </div>

        {{-- CTA --}}
        <div class="flex items-center gap-2">
            @auth
                <a href="{{ match(auth()->user()->role) { 'admin' => route('admin.dashboard'), 'driver' => route('driver.dashboard'), default => route('passenger.dashboard') } }}"
                   style="background:#0374BA;color:#fff;padding:8px 18px;border-radius:12px;font-size:0.875rem;font-weight:700;text-decoration:none;transition:background 0.2s;"
                   onmouseover="this.style.background='#025d95'" onmouseout="this.style.background='#0374BA'">
                    Mon Espace →
                </a>
            @else
                <a href="{{ route('login') }}" style="color:#374151;padding:8px 16px;border-radius:12px;font-size:0.875rem;font-weight:600;text-decoration:none;transition:background 0.2s;"
                   onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='transparent'">
                    Connexion
                </a>
                <a href="{{ route('register') }}"
                   style="background:#0374BA;color:#fff;padding:8px 18px;border-radius:12px;font-size:0.875rem;font-weight:700;text-decoration:none;box-shadow:0 2px 8px rgba(3,116,186,0.3);transition:all 0.2s;"
                   onmouseover="this.style.background='#025d95'" onmouseout="this.style.background='#0374BA'">
                    S'inscrire gratuitement
                </a>
            @endauth
        </div>
    </div>
</nav>

{{-- ── HERO ─────────────────────────────────────────────── --}}
<section class="relative min-h-screen flex items-center pt-20 overflow-hidden">

    {{-- Background photo --}}
    <div class="absolute inset-0">
        <img src="{{ asset('images/hero.jpg') }}" alt="TGether covoiturage" class="w-full h-full object-cover">
        <div class="hero-overlay absolute inset-0"></div>
    </div>

    {{-- Floating decorative dots --}}
    <div class="dot w-72 h-72" style="top:8%;right:5%;animation-duration:9s;animation-delay:0s;"></div>
    <div class="dot w-48 h-48" style="top:55%;right:18%;animation-duration:7s;animation-delay:1.5s;"></div>
    <div class="dot w-32 h-32" style="bottom:12%;left:6%;animation-duration:11s;animation-delay:0.5s;background:rgba(100,172,90,0.08);"></div>
    <div class="dot w-20 h-20" style="top:30%;left:55%;animation-duration:6s;animation-delay:2s;"></div>

    {{-- Diagonal colour band --}}
    <div class="absolute bottom-0 left-0 right-0 h-32"
         style="background:linear-gradient(to top, rgba(255,255,255,0.06), transparent);"></div>

    {{-- Content grid --}}
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-20 lg:py-28 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

            {{-- LEFT — Text --}}
            <div>
                {{-- Badge --}}
                <div class="fade-up mb-7" style="animation-delay:0s">
                    <span class="stat-pill inline-flex items-center gap-2 text-white text-xs font-bold px-4 py-2 rounded-full">
                        <span class="w-2 h-2 rounded-full bg-secondary-300" style="animation:pulse 1.5s infinite;"></span>
                        🇨🇲 &nbsp;Plateforme #1 de covoiturage au Cameroun
                    </span>
                </div>

                {{-- Title — "Voyagez Ensemble," sur la même ligne --}}
                <h1 class="fade-up fade-up-1 font-black text-white leading-[1.04] mb-6"
                    style="font-size:clamp(2.8rem,6vw,4.5rem);">
                    <span class="block">
                        Voyagez&nbsp;<span class="title-accent">Ensemble,</span>
                    </span>
                    <span class="block mt-1" style="color:rgba(255,255,255,0.92);">Économisez</span>
                </h1>

                <p class="fade-up fade-up-2 text-lg text-white/80 mb-9 leading-relaxed max-w-lg">
                    TGether connecte conducteurs et passagers pour des trajets partagés
                    <strong class="text-white font-semibold">économiques</strong>,
                    <strong class="text-secondary-300 font-semibold">écologiques</strong> et
                    conviviaux à travers tout le Cameroun.
                </p>

                {{-- Search bar --}}
                <div class="fade-up fade-up-3 bg-white rounded-2xl p-2 search-bar shadow-2xl max-w-lg mb-8">
                    <form action="{{ auth()->check() && auth()->user()->role === 'passenger' ? route('passenger.rides.index') : route('login') }}" method="GET"
                          class="flex flex-col sm:flex-row gap-2">
                        <input type="text" name="origin" placeholder="🏙️ Départ"
                               style="flex:1;padding:12px 16px;border-radius:12px;font-size:0.875rem;color:#374151;background:#f9fafb;border:none;outline:none;"
                               onfocus="this.style.background='#fff'" onblur="this.style.background='#f9fafb'">
                        <input type="text" name="destination" placeholder="📍 Arrivée"
                               style="flex:1;padding:12px 16px;border-radius:12px;font-size:0.875rem;color:#374151;background:#f9fafb;border:none;outline:none;"
                               onfocus="this.style.background='#fff'" onblur="this.style.background='#f9fafb'">
                        <button type="submit"
                                style="background:#0374BA;color:#fff;padding:12px 22px;border-radius:12px;font-weight:700;font-size:0.875rem;border:none;cursor:pointer;white-space:nowrap;transition:background 0.2s;"
                                onmouseover="this.style.background='#025d95'" onmouseout="this.style.background='#0374BA'">
                            Rechercher →
                        </button>
                    </form>
                </div>

                {{-- Stats pills --}}
                <div class="fade-up fade-up-4 flex flex-wrap gap-3">
                    @foreach([
                        ['icon'=>'🚗','val'=>$stats['rides'],'label'=>'trajets'],
                        ['icon'=>'👥','val'=>$stats['users'],'label'=>'membres'],
                        ['icon'=>'✅','val'=>$stats['bookings'],'label'=>'voyages'],
                    ] as $s)
                    <div class="stat-pill flex items-center gap-2 px-4 py-2.5 rounded-full text-white">
                        <span class="text-base">{{ $s['icon'] }}</span>
                        <span class="font-black text-lg leading-none">{{ number_format($s['val']) }}+</span>
                        <span class="text-white/70 text-xs font-medium">{{ $s['label'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- RIGHT — Feature mini-cards --}}
            <div class="hidden lg:block slide-right">
                <div class="grid grid-cols-2 gap-4">
                    @foreach([
                        ['icon'=>'💰','title'=>'Économique','desc'=>'Jusqu\'à 70% d\'économies','bg'=>'rgba(3,116,186,0.15)','border'=>'rgba(3,116,186,0.3)'],
                        ['icon'=>'🌿','title'=>'Écologique','desc'=>'Moins de CO₂ émis','bg'=>'rgba(100,172,90,0.15)','border'=>'rgba(100,172,90,0.35)'],
                        ['icon'=>'🔒','title'=>'Sécurisé','desc'=>'Profils vérifiés','bg'=>'rgba(255,255,255,0.1)','border'=>'rgba(255,255,255,0.2)'],
                        ['icon'=>'📱','title'=>'Simple','desc'=>'En 3 clics seulement','bg'=>'rgba(255,200,50,0.12)','border'=>'rgba(255,200,50,0.25)'],
                    ] as $i => $feat)
                    <div class="rounded-2xl p-5 backdrop-blur-sm transition-transform hover:-translate-y-1 duration-300"
                         style="background:{{ $feat['bg'] }};border:1.5px solid {{ $feat['border'] }};animation-delay:{{ 0.5 + $i*0.12 }}s;">
                        <div class="text-3xl mb-3">{{ $feat['icon'] }}</div>
                        <p class="text-white font-bold text-sm">{{ $feat['title'] }}</p>
                        <p class="text-white/60 text-xs mt-0.5">{{ $feat['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
                {{-- Extra testimonial card --}}
                <div class="mt-4 rounded-2xl p-5 backdrop-blur-sm flex items-center gap-4"
                     style="background:rgba(255,255,255,0.1);border:1.5px solid rgba(255,255,255,0.18);">
                    <div class="w-10 h-10 rounded-full bg-secondary flex items-center justify-center text-white font-black flex-shrink-0">A</div>
                    <div>
                        <p class="text-white text-sm font-semibold">"Parfait pour Yaoundé–Douala !"</p>
                        <p class="text-white/50 text-xs mt-0.5">Alix M. · Passagère TGether</p>
                    </div>
                    <div class="ml-auto flex gap-0.5">
                        @for($i=0;$i<5;$i++)<span style="color:#fbbf24;font-size:0.85rem;">★</span>@endfor
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Scroll indicator --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 scroll-arrow">
        <span class="text-white/50 text-xs font-medium tracking-widest uppercase">Découvrir</span>
        <svg class="w-5 h-5 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </div>
</section>

{{-- ── COMMENT ÇA MARCHE ───────────────────────────────── --}}
<section id="comment-ca-marche" class="py-24 relative overflow-hidden" style="background:linear-gradient(160deg,#f0f7ff 0%,#f9fafb 50%,#eef6ed 100%);">

    {{-- Decorative bg shapes --}}
    <div class="absolute top-0 right-0 w-96 h-96 rounded-full opacity-30" style="background:radial-gradient(circle,#b3d8f0,transparent);transform:translate(30%,-30%);"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 rounded-full opacity-20" style="background:radial-gradient(circle,#cce6ca,transparent);transform:translate(-30%,30%);"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-16">
            <span class="section-badge mb-4" style="background:#e6f2fa;color:#0374BA;">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                Simple &amp; Rapide
            </span>
            <h2 class="text-4xl lg:text-5xl font-black text-gray-900 mt-3 mb-4">Comment ça marche ?</h2>
            <p class="text-gray-500 max-w-lg mx-auto text-base leading-relaxed">En 3 étapes seulement, réservez votre prochain trajet ou proposez votre voiture.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
            @foreach([
                [
                    'step'=>'01','icon'=>'👤','title'=>'Créez votre compte',
                    'desc'=>'Inscrivez-vous gratuitement en tant que conducteur ou passager. Vérifiez votre identité pour plus de confiance.',
                    'color'=>'#0374BA','bg'=>'#e6f2fa','border'=>'#b3d8f0',
                    'tag'=>'Gratuit'
                ],
                [
                    'step'=>'02','icon'=>'🔍','title'=>'Trouvez ou publiez',
                    'desc'=>'Cherchez un trajet selon votre destination et date, ou publiez votre propre trajet en quelques clics.',
                    'color'=>'#508948','bg'=>'#eef6ed','border'=>'#cce6ca',
                    'tag'=>'Rapide'
                ],
                [
                    'step'=>'03','icon'=>'🚗','title'=>'Voyagez ensemble',
                    'desc'=>'Payez via votre portefeuille TGether, échangez avec votre conducteur et profitez du voyage !',
                    'color'=>'#b45309','bg'=>'#fffbeb','border'=>'#fde68a',
                    'tag'=>'Sécurisé'
                ],
            ] as $idx => $step)
            <div class="step-card bg-white rounded-3xl p-8 shadow-sm relative overflow-hidden"
                 style="border:1.5px solid {{ $step['border'] }};">

                {{-- Connector arrow (between cards) --}}
                @if($idx < 2)
                <div class="step-connector hidden md:block"></div>
                @endif

                {{-- Step number watermark --}}
                <div class="absolute top-4 right-5 font-black text-7xl leading-none select-none pointer-events-none"
                     style="color:{{ $step['bg'] }};font-size:5rem;">{{ $step['step'] }}</div>

                {{-- Icon --}}
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl mb-5 shadow-sm"
                     style="background:{{ $step['bg'] }};border:1.5px solid {{ $step['border'] }};">
                    {{ $step['icon'] }}
                </div>

                {{-- Tag --}}
                <span class="inline-flex items-center gap-1 text-xs font-bold px-3 py-1 rounded-full mb-3"
                      style="background:{{ $step['bg'] }};color:{{ $step['color'] }};">
                    ✓ {{ $step['tag'] }}
                </span>

                <h3 class="text-lg font-black text-gray-900 mb-3">{{ $step['title'] }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed">{{ $step['desc'] }}</p>

                {{-- Bottom accent line --}}
                <div class="absolute bottom-0 left-0 right-0 h-1 rounded-b-3xl"
                     style="background:linear-gradient(90deg,{{ $step['color'] }},transparent);"></div>
            </div>
            @endforeach
        </div>

        {{-- Bottom CTA strip --}}
        <div class="mt-14 rounded-3xl p-8 flex flex-col md:flex-row items-center justify-between gap-6"
             style="background:linear-gradient(135deg,#0374BA,#024670);box-shadow:0 20px 60px rgba(3,116,186,0.25);">
            <div>
                <p class="text-white font-black text-xl mb-1">Prêt à commencer ?</p>
                <p class="text-white/70 text-sm">Rejoignez des milliers de Camerounais qui voyagent déjà ensemble.</p>
            </div>
            <div class="flex gap-3 flex-shrink-0">
                <a href="{{ route('register') }}"
                   style="background:#64AC5A;color:#fff;padding:12px 24px;border-radius:12px;font-weight:700;font-size:0.875rem;text-decoration:none;transition:background 0.2s;white-space:nowrap;"
                   onmouseover="this.style.background='#508948'" onmouseout="this.style.background='#64AC5A'">
                    Je m'inscris →
                </a>
                <a href="#trajets"
                   style="background:rgba(255,255,255,0.15);color:#fff;padding:12px 24px;border-radius:12px;font-weight:600;font-size:0.875rem;text-decoration:none;border:1.5px solid rgba(255,255,255,0.3);transition:background 0.2s;white-space:nowrap;"
                   onmouseover="this.style.background='rgba(255,255,255,0.22)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                    Voir les trajets
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ── TRAJETS DISPONIBLES ─────────────────────────────── --}}
<section id="trajets" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div>
                <span class="section-badge mb-3" style="background:#eef6ed;color:#508948;">
                    🟢 En ce moment
                </span>
                <h2 class="text-4xl lg:text-5xl font-black text-gray-900 mt-3">Trajets disponibles</h2>
                <p class="text-gray-400 mt-2 text-sm">Réservez votre place en quelques secondes.</p>
            </div>
            <a href="{{ auth()->check() && auth()->user()->role === 'passenger' ? route('passenger.rides.index') : route('login') }}"
               style="background:#0374BA;color:#fff;padding:12px 24px;border-radius:12px;font-weight:700;font-size:0.875rem;text-decoration:none;display:inline-flex;align-items:center;gap:8px;white-space:nowrap;transition:background 0.2s;flex-shrink:0;"
               onmouseover="this.style.background='#025d95'" onmouseout="this.style.background='#0374BA'">
                Tous les trajets
                <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

        @if($latestRides->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($latestRides as $ride)
            <div class="ride-card bg-white rounded-2xl p-5 cursor-pointer group">

                {{-- Driver + Price --}}
                <div class="flex items-center justify-between mb-5">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-full flex items-center justify-center text-white font-black text-base shadow-sm flex-shrink-0"
                             style="background:linear-gradient(135deg,#0374BA,#024670);">
                            {{ strtoupper(substr($ride->driver->first_name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ $ride->driver->first_name }}</p>
                            <div class="flex items-center gap-1 mt-0.5">
                                <svg style="width:11px;height:11px;fill:#fbbf24;" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-xs text-gray-400 font-medium">{{ number_format($ride->driver->rating_avg, 1) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xl font-black" style="color:#0374BA;">{{ number_format($ride->price_per_seat, 0, ',', ' ') }}</span>
                        <span class="text-xs text-gray-400 font-medium block -mt-0.5">XAF / place</span>
                    </div>
                </div>

                {{-- Route --}}
                <div class="rounded-xl p-4 mb-4" style="background:#f8fafc;">
                    <div class="flex items-center gap-3">
                        <div class="flex flex-col items-center gap-1">
                            <div class="w-2.5 h-2.5 rounded-full" style="background:#0374BA;"></div>
                            <div class="w-0.5 h-6" style="background:repeating-linear-gradient(to bottom,#cbd5e1 0,#cbd5e1 3px,transparent 3px,transparent 6px);"></div>
                            <div class="w-2.5 h-2.5 rounded-full" style="background:#64AC5A;"></div>
                        </div>
                        <div class="flex-1 flex flex-col gap-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-gray-800">{{ $ride->origin->city }}</span>
                                <span class="text-xs font-mono" style="color:#0374BA;">{{ $ride->departure_datetime->format('H:i') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-gray-800">{{ $ride->destination->city }}</span>
                                @if($ride->arrival_datetime)
                                <span class="text-xs font-mono text-gray-400">{{ $ride->arrival_datetime->format('H:i') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3 text-xs text-gray-400">
                        <span>📅 {{ $ride->departure_datetime->translatedFormat('d M') }}</span>
                        <span class="w-1 h-1 rounded-full bg-gray-200"></span>
                        <span style="color:#64AC5A;font-weight:600;">💺 {{ $ride->seats_available }} place{{ $ride->seats_available > 1 ? 's' : '' }}</span>
                    </div>
                    <span class="ride-arrow text-sm font-bold" style="color:#0374BA;">→</span>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-20 rounded-3xl" style="background:#f8fafc;border:2px dashed #e2e8f0;">
            <div class="text-6xl mb-5">🚗</div>
            <h3 class="font-black text-gray-800 text-xl mb-2">Aucun trajet pour le moment</h3>
            <p class="text-gray-400 text-sm">Les conducteurs publieront bientôt leurs trajets. Revenez vite !</p>
        </div>
        @endif
    </div>
</section>

{{-- ── AVANTAGES ───────────────────────────────────────── --}}
<section id="avantages" class="py-24 bg-gradient-to-br from-primary-50 via-white to-secondary-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-16">
            <span class="text-secondary font-bold text-sm uppercase tracking-widest">Pourquoi TGether</span>
            <h2 class="text-4xl font-black text-gray-900 mt-2">Voyagez malin, voyagez vert</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['icon'=>'💰','title'=>'Économique','desc'=>'Réduisez vos frais de transport jusqu\'à 70% en partageant les coûts.'],
                ['icon'=>'🌿','title'=>'Écologique','desc'=>'Moins de voitures sur la route = moins de CO₂. Agissons ensemble pour l\'environnement.'],
                ['icon'=>'🔒','title'=>'Sécurisé','desc'=>'Profils vérifiés, paiement sécurisé par portefeuille électronique et système d\'évaluation.'],
                ['icon'=>'🤝','title'=>'Convivial','desc'=>'Rencontrez des gens de votre région, partagez des moments et créez des liens.'],
            ] as $item)
            <div class="bg-white rounded-2xl p-7 shadow-sm border border-gray-100 text-center hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                <div class="text-4xl mb-4">{{ $item['icon'] }}</div>
                <h3 class="text-base font-bold text-gray-900 mb-2">{{ $item['title'] }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed">{{ $item['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── A PROPOS / CTA ──────────────────────────────────── --}}
<section id="a-propos" class="py-24 bg-gray-900 relative overflow-hidden">
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-10 left-10 w-64 h-64 rounded-full bg-primary"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 rounded-full bg-secondary"></div>
    </div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <div class="text-5xl mb-6">🇨🇲</div>
        <h2 class="text-4xl md:text-5xl font-black text-white mb-6 leading-tight">
            Fait avec ❤️ pour<br>
            <span class="text-primary-300">le Cameroun</span>
        </h2>
        <p class="text-gray-400 text-lg leading-relaxed mb-10 max-w-2xl mx-auto">
            TGether est une plateforme camerounaise conçue pour faciliter la mobilité dans nos villes et sur les routes inter-urbaines. Nous croyons que voyager ensemble, c'est avancer ensemble.
        </p>
        @guest
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" class="bg-primary text-white px-8 py-4 rounded-xl font-bold text-base hover:bg-primary-600 transition inline-flex items-center justify-center gap-2 shadow-lg shadow-primary/30">
                🚗 Je suis conducteur
            </a>
            <a href="{{ route('register') }}" class="bg-secondary text-white px-8 py-4 rounded-xl font-bold text-base hover:bg-secondary-600 transition inline-flex items-center justify-center gap-2 shadow-lg shadow-secondary/30">
                👤 Je suis passager
            </a>
        </div>
        @endguest
    </div>
</section>

{{-- ── FOOTER ──────────────────────────────────────────── --}}
<footer class="bg-gray-900 border-t border-white/5 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 bg-primary rounded-lg flex items-center justify-center text-white font-black text-sm">T</div>
            <span class="text-white font-bold">TGether</span>
            <span class="text-gray-500 text-sm ml-2">© {{ date('Y') }} Tous droits réservés</span>
        </div>
        <p class="text-gray-500 text-sm">Covoiturage au Cameroun 🇨🇲</p>
    </div>
</footer>

</body>
</html>
