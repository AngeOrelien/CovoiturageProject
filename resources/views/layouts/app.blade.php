<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TGether') — Covoiturage Cameroun</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/tailwindcss.js') }}"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary:   { DEFAULT: '#0374BA', 50:'#e6f2fa', 100:'#b3d8f0', 200:'#80bee6', 300:'#4da3dc', 400:'#1a89d2', 500:'#0374BA', 600:'#025d95', 700:'#024670', 800:'#012f4b', 900:'#011826' },
                        secondary: { DEFAULT: '#64AC5A', 50:'#eef6ed', 100:'#cce6ca', 200:'#aad7a7', 300:'#88c784', 400:'#76b870', 500:'#64AC5A', 600:'#508948', 700:'#3c6736', 800:'#284524', 900:'#142312' },
                    },
                    fontFamily: {
                        sans:    ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui'],
                        display: ['"Clash Display"', '"Plus Jakarta Sans"', 'ui-sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* ── Sidebar links ── */
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 1rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
        }
        .sidebar-link:hover { background: rgba(255,255,255,0.12); }
        .sidebar-link.active { background: #ffffff; color: #0374BA !important; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }

        /* ── Stat cards ── */
        .stat-card {
            background: #ffffff;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            border: 1px solid #f3f4f6;
            transition: box-shadow 0.2s;
        }
        .stat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.1); }

        /* ── Buttons ── */
        .btn-primary {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: #0374BA; color: #ffffff;
            padding: 0.625rem 1.25rem; border-radius: 0.75rem;
            font-weight: 600; font-size: 0.875rem;
            border: none; cursor: pointer; text-decoration: none;
            transition: all 0.2s; box-shadow: 0 1px 3px rgba(3,116,186,0.3);
        }
        .btn-primary:hover { background: #025d95; box-shadow: 0 4px 12px rgba(3,116,186,0.35); color: #fff; }
        .btn-primary:disabled { opacity: 0.5; cursor: not-allowed; }

        .btn-secondary {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: #64AC5A; color: #ffffff;
            padding: 0.625rem 1.25rem; border-radius: 0.75rem;
            font-weight: 600; font-size: 0.875rem;
            border: none; cursor: pointer; text-decoration: none;
            transition: all 0.2s; box-shadow: 0 1px 3px rgba(100,172,90,0.3);
        }
        .btn-secondary:hover { background: #508948; box-shadow: 0 4px 12px rgba(100,172,90,0.35); color: #fff; }

        .btn-outline {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: transparent; color: #0374BA;
            padding: 0.625rem 1.25rem; border-radius: 0.75rem;
            font-weight: 600; font-size: 0.875rem;
            border: 2px solid #0374BA; cursor: pointer; text-decoration: none;
            transition: all 0.2s;
        }
        .btn-outline:hover { background: #0374BA; color: #ffffff; }

        /* ── Badges ── */
        .badge-pending, .badge-confirmed, .badge-completed,
        .badge-cancelled, .badge-scheduled, .badge-active {
            display: inline-flex; align-items: center;
            padding: 0.125rem 0.625rem; border-radius: 9999px;
            font-size: 0.75rem; font-weight: 600;
        }
        .badge-pending   { background: #fef9c3; color: #854d0e; }
        .badge-confirmed { background: #dbeafe; color: #1e40af; }
        .badge-completed { background: #dcfce7; color: #166534; }
        .badge-cancelled { background: #fee2e2; color: #991b1b; }
        .badge-scheduled { background: #e6f2fa; color: #0374BA; }
        .badge-active    { background: #d1fae5; color: #065f46; }

        /* ── Form inputs ── */
        .input-field {
            width: 100%; padding: 0.625rem 1rem;
            border: 1px solid #e5e7eb; border-radius: 0.75rem;
            font-size: 0.875rem; background: #f9fafb;
            outline: none; transition: all 0.2s;
            color: #111827;
        }
        .input-field:focus {
            border-color: #0374BA;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(3,116,186,0.12);
        }

        /* ── Table cells ── */
        .table-th {
            padding: 0.75rem 1rem;
            text-align: left; font-size: 0.75rem;
            font-weight: 600; color: #6b7280;
            text-transform: uppercase; letter-spacing: 0.05em;
        }
        .table-td { padding: 0.75rem 1rem; font-size: 0.875rem; color: #374151; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen">

@auth
<div class="flex h-screen overflow-hidden">
    {{-- Sidebar --}}
    <aside id="sidebar" class="w-64 flex-shrink-0 flex flex-col shadow-xl z-30
        @auth
            @if(auth()->user()->role === 'admin') bg-gradient-to-b from-gray-900 to-gray-800
            @elseif(auth()->user()->role === 'driver') bg-gradient-to-b from-gray-900 to-gray-800
            @else bg-gradient-to-b from-gray-900 to-gray-800
            @endif
        @endauth
        text-white transition-all duration-300">

        {{-- Logo --}}
        <div class="p-6 border-b border-white/10">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center font-bold text-lg">T</div>
                <span class="text-xl font-extrabold tracking-tight">TGether</span>
            </a>
            <p class="text-xs text-white/50 mt-1 ml-12">
                @if(auth()->user()->role === 'admin') Administration
                @elseif(auth()->user()->role === 'driver') Espace Conducteur
                @else Espace Passager
                @endif
            </p>
        </div>

        {{-- Nav links --}}
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active text-primary' : 'text-white/80' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg>
                    Tableau de bord
                </a>
                <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active text-primary' : 'text-white/80' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
                    Utilisateurs
                </a>
                <a href="{{ route('admin.rides.index') }}" class="sidebar-link {{ request()->routeIs('admin.rides*') ? 'active text-primary' : 'text-white/80' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    Trajets
                </a>
                <a href="{{ route('admin.reports.index') }}" class="sidebar-link {{ request()->routeIs('admin.reports*') ? 'active text-primary' : 'text-white/80' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Signalements
                </a>
                <a href="{{ route('admin.promos.index') }}" class="sidebar-link {{ request()->routeIs('admin.promos*') ? 'active text-primary' : 'text-white/80' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    Codes Promo
                </a>
            @elseif(auth()->user()->role === 'driver')
                <a href="{{ route('driver.dashboard') }}" class="sidebar-link {{ request()->routeIs('driver.dashboard') ? 'active text-primary' : 'text-white/80' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg>
                    Tableau de bord
                </a>
                <a href="{{ route('driver.rides.index') }}" class="sidebar-link {{ request()->routeIs('driver.rides*') ? 'active text-primary' : 'text-white/80' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    Mes Trajets
                </a>
                <a href="{{ route('driver.vehicles.index') }}" class="sidebar-link {{ request()->routeIs('driver.vehicles*') ? 'active text-primary' : 'text-white/80' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    Mes Véhicules
                </a>
                <a href="{{ route('driver.bookings.index') }}" class="sidebar-link {{ request()->routeIs('driver.bookings*') ? 'active text-primary' : 'text-white/80' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Réservations
                </a>
                <a href="{{ route('conversations.index') }}" class="sidebar-link {{ request()->routeIs('conversations*') ? 'active text-primary' : 'text-white/80' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    Messages
                </a>
            @else
                <a href="{{ route('passenger.dashboard') }}" class="sidebar-link {{ request()->routeIs('passenger.dashboard') ? 'active text-secondary' : 'text-white/80' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg>
                    Tableau de bord
                </a>
                <a href="{{ route('passenger.rides.index') }}" class="sidebar-link {{ request()->routeIs('passenger.rides*') ? 'active text-secondary' : 'text-white/80' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Rechercher
                </a>
                <a href="{{ route('passenger.bookings.index') }}" class="sidebar-link {{ request()->routeIs('passenger.bookings*') ? 'active text-secondary' : 'text-white/80' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Mes Réservations
                </a>
                <a href="{{ route('passenger.wallet.index') }}" class="sidebar-link {{ request()->routeIs('passenger.wallet*') ? 'active text-secondary' : 'text-white/80' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Portefeuille
                </a>
                <a href="{{ route('conversations.index') }}" class="sidebar-link {{ request()->routeIs('conversations*') ? 'active text-secondary' : 'text-white/80' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    Messages
                </a>
            @endif

            <div class="pt-4 border-t border-white/10 mt-4 space-y-1">
                <a href="{{ route('notifications.index') }}" class="sidebar-link text-white/80 hover:text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    Notifications
                </a>
                <a href="{{ route('profile.edit') }}" class="sidebar-link text-white/80 hover:text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Mon Profil
                </a>
            </div>
        </nav>

        {{-- User footer --}}
        <div class="p-4 border-t border-white/10">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center text-sm font-bold">
                    {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold truncate">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>
                    <p class="text-xs text-white/50 capitalize">{{ auth()->user()->role }}</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full text-left sidebar-link text-white/70 hover:text-white hover:bg-white/10 text-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Déconnexion
                </button>
            </form>
        </div>
    </aside>

    {{-- Main content --}}
    <main class="flex-1 flex flex-col overflow-hidden">
        {{-- Top bar --}}
        <header class="bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between shadow-sm flex-shrink-0">
            <div>
                <h1 class="text-lg font-bold text-gray-900">@yield('page-title', 'Tableau de bord')</h1>
                <p class="text-xs text-gray-400">@yield('page-subtitle', '')</p>
            </div>
            <div class="flex items-center gap-3">
                @php $unread = auth()->user()->notifications()->where('is_read', false)->count(); @endphp
                <a href="{{ route('notifications.index') }}" class="relative p-2 rounded-xl text-gray-500 hover:bg-gray-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    @if($unread > 0)<span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold">{{ $unread }}</span>@endif
                </a>
                <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white text-sm font-bold">
                    {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}
                </div>
            </div>
        </header>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="mx-6 mt-4 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm flex items-center gap-2">
                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error') || $errors->any())
            <div class="mx-6 mt-4 px-4 py-3 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm">
                @if(session('error')){{ session('error') }}@endif
                @if($errors->any())<ul>@foreach($errors->all() as $e)<li>• {{ $e }}</li>@endforeach</ul>@endif
            </div>
        @endif

        <div class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </div>
    </main>
</div>
@else
    @yield('content')
@endauth

@stack('scripts')
</body>
</html>
