<x-app-layout title="Dashboard">

{{-- Toast session --}}
@if(session('toast'))
<div id="page-toast"
     style="position:fixed;top:1.25rem;right:1.25rem;z-index:300;
            display:flex;align-items:center;gap:.875rem;
            padding:1rem 1.25rem;border-radius:16px;
            max-width:380px;font-family:'Nunito',sans-serif;
            box-shadow:0 8px 32px rgba(0,0,0,.18);
            animation:toastIn .4s cubic-bezier(.22,1,.36,1) forwards;
            {{ session('toast.type') === 'success' ? 'background:#00C853;' :
               (session('toast.type') === 'error'  ? 'background:#FF3B30;' :
               (session('toast.type') === 'info'   ? 'background:#006EFF;' : 'background:#FF9500;')) }}
            color:#fff;">
    <span style="font-size:1.1rem;font-weight:900;line-height:1;flex-shrink:0;">
        {{ session('toast.type') === 'success' ? '✓' : 'ℹ' }}
    </span>
    <span style="font-size:.875rem;font-weight:600;line-height:1.4;flex:1;">{{ session('toast.message') }}</span>
    <button onclick="this.parentElement.remove()"
            style="background:none;border:none;color:inherit;opacity:.7;cursor:pointer;font-size:1.3rem;line-height:1;padding:0;margin-left:.25rem;">×</button>
</div>
@endif

<style>
    *, *::before, *::after { font-family: 'Nunito', sans-serif; box-sizing: border-box; }
    body { margin: 0; background: #F2F6FC; }

    @keyframes toastIn {
        from { opacity:0; transform:translateY(-14px) scale(.96); }
        to   { opacity:1; transform:none; }
    }

    /* ── TOP NAV ── */
    .db-nav {
        height: 64px;
        background: #fff;
        border-bottom: 1.5px solid #E2EBF6;
        display: flex;
        align-items: center;
        padding: 0 2.5rem;
        position: sticky;
        top: 0;
        z-index: 100;
        gap: 1.5rem;
    }
    .db-brand { display:flex;align-items:center;gap:.6rem;text-decoration:none;color:#0D1B2A;font-weight:900;font-size:1.1rem;letter-spacing:-.015em; }
    .db-brand img { height:32px; }

    .db-nav-links { display:flex;align-items:center;gap:.25rem; }
    .db-nav-link {
        font-size:.875rem;font-weight:700;color:#7090B8;text-decoration:none;
        padding:.45rem .875rem;border-radius:10px;transition:color .2s,background .2s;
    }
    .db-nav-link:hover  { color:#006EFF;background:#EBF4FF; }
    .db-nav-link.active { color:#006EFF;background:#EBF4FF; }

    .db-nav-right { margin-left:auto;display:flex;align-items:center;gap:.875rem; }

    .avatar-sm { width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid #E2EBF6; }

    .btn-logout {
        display:flex;align-items:center;gap:.5rem;padding:.45rem .875rem;
        border-radius:10px;border:2px solid #E2EBF6;background:none;
        font-size:.875rem;font-weight:700;color:#7090B8;cursor:pointer;
        transition:all .2s;
    }
    .btn-logout:hover { border-color:#FF3B30;color:#FF3B30;background:#FFF5F5; }

    /* ── PAGE WRAPPER ── */
    .db-page {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2.5rem 2.5rem;
    }

    /* ── HERO CARD ── */
    .hero-card {
        background: linear-gradient(135deg, #004ECC 0%, #006EFF 55%, #1B8FFF 100%);
        border-radius: 24px;
        padding: 2.5rem 3rem;
        display: flex;
        align-items: center;
        gap: 2rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
    }
    .hero-card::before {
        content:'';position:absolute;top:-80px;right:-80px;
        width:320px;height:320px;border-radius:50%;
        background:rgba(255,255,255,.06);pointer-events:none;
    }
    .hero-card::after {
        content:'';position:absolute;bottom:-60px;right:160px;
        width:220px;height:220px;border-radius:50%;
        background:rgba(255,255,255,.04);pointer-events:none;
    }
    .hero-avatar { width:80px;height:80px;border-radius:20px;object-fit:cover;border:3px solid rgba(255,255,255,.3);box-shadow:0 8px 24px rgba(0,0,0,.2);flex-shrink:0;position:relative;z-index:1; }
    .hero-info { position:relative;z-index:1; }
    .hero-info .greeting { font-size:.9rem;color:rgba(255,255,255,.65);font-weight:600;margin:0 0 .25rem; }
    .hero-info .name     { font-size:2rem;font-weight:900;color:#fff;letter-spacing:-.025em;margin:0 0 .625rem; }
    .hero-info .badges   { display:flex;gap:.625rem;flex-wrap:wrap; }
    .badge {
        display:inline-flex;align-items:center;gap:.375rem;
        background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);
        color:rgba(255,255,255,.9);font-size:.78rem;font-weight:700;
        padding:.3rem .75rem;border-radius:99px;backdrop-filter:blur(4px);
    }

    /* ── STATS GRID ── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: #fff;
        border-radius: 20px;
        padding: 1.5rem 1.75rem;
        box-shadow: 0 2px 12px rgba(0,110,255,.06);
        transition: transform .2s, box-shadow .2s;
        border: 1.5px solid #F0F4FA;
    }
    .stat-card:hover { transform:translateY(-3px);box-shadow:0 8px 28px rgba(0,110,255,.11); }
    .stat-icon   { font-size:1.75rem;margin-bottom:.875rem; }
    .stat-value  { font-size:1.75rem;font-weight:900;letter-spacing:-.02em;margin:0 0 .2rem; }
    .stat-label  { font-size:.8rem;font-weight:700;color:#7090B8;text-transform:uppercase;letter-spacing:.04em;margin:0; }

    /* ── CONTENT GRID ── */
    .content-grid {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 1.5rem;
    }

    .section-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 2px 12px rgba(0,110,255,.06);
        border: 1.5px solid #F0F4FA;
        overflow: hidden;
    }
    .section-header {
        display:flex;align-items:center;justify-content:space-between;
        padding:1.375rem 1.75rem;border-bottom:1.5px solid #F0F4FA;
    }
    .section-title { font-size:1rem;font-weight:800;color:#0D1B2A;margin:0; }
    .section-body  { padding:1.75rem; }

    .empty-state {
        display:flex;flex-direction:column;align-items:center;
        text-align:center;padding:3rem 2rem;color:#7090B8;
    }
    .empty-icon  { font-size:3rem;margin-bottom:1rem;opacity:.5; }
    .empty-title { font-size:1rem;font-weight:800;color:#4A6080;margin:0 0 .375rem; }
    .empty-sub   { font-size:.875rem;margin:0 0 1.5rem;line-height:1.6; }

    .cta-row { display:flex;gap:.875rem;justify-content:center;flex-wrap:wrap; }
    .btn-cta {
        padding:.7rem 1.5rem;border-radius:12px;font-size:.9rem;font-weight:800;
        cursor:pointer;transition:all .2s;border:none;letter-spacing:.01em;
    }
    .btn-cta.primary {
        background:#006EFF;color:#fff;box-shadow:0 4px 16px rgba(0,110,255,.3);
    }
    .btn-cta.primary:hover { background:#0057CC;transform:translateY(-1px);box-shadow:0 6px 22px rgba(0,110,255,.4); }
    .btn-cta.outline {
        background:transparent;color:#006EFF;border:2px solid #E2EBF6;
    }
    .btn-cta.outline:hover { border-color:#006EFF;background:#EBF4FF; }

    /* Sidebar quick info */
    .info-row {
        display:flex;align-items:center;justify-content:space-between;
        padding:.875rem 0;border-bottom:1px solid #F0F4FA;
    }
    .info-row:last-child { border:none; }
    .info-key   { font-size:.82rem;font-weight:700;color:#7090B8; }
    .info-value { font-size:.875rem;font-weight:800;color:#0D1B2A; }
    .pill {
        font-size:.72rem;font-weight:800;padding:.25rem .625rem;border-radius:99px;
    }
    .pill-green { background:#E6FAF0;color:#00A846; }
    .pill-gray  { background:#F0F4FA;color:#7090B8; }

    /* ── RESPONSIVE ── */
    @media (max-width: 1100px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .content-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 700px) {
        .db-page { padding: 1.25rem 1rem; }
        .db-nav  { padding: 0 1rem; }
        .hero-card { padding: 1.75rem; flex-direction:column; text-align:center; }
        .hero-info .badges { justify-content:center; }
        .stats-grid { grid-template-columns: 1fr 1fr; gap:.875rem; }
        .db-nav-links { display:none; }
    }
    @media (max-width: 420px) {
        .stats-grid { grid-template-columns: 1fr; }
    }
</style>

{{-- ── Contenu ── --}}
<div class="db-page">

    {{-- Hero --}}
    <div class="hero-card">
        <img src="https://img.daisyui.com/images/profile/demo/distracted1@192.webp"
             class="hero-avatar" alt="avatar">
        <div class="hero-info">
            <p class="greeting">Bonjour,</p>
            <h1 class="name">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h1>
            <div class="badges">
                <span class="badge">{{ auth()->user()->role === 'driver' ? '🚗 Conducteur' : '🎫 Passager' }}</span>
                <span class="badge">📅 Membre {{ auth()->user()->created_at->diffForHumans() }}</span>
                <span class="badge">✉ {{ auth()->user()->email }}</span>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="stats-grid">
        @foreach([
            ['🚗', '0',     'Trajets',          '#006EFF'],
            ['🎫', '0',     'Réservations',     '#00C853'],
            ['⭐', '—',     'Note moyenne',     '#FF9500'],
            ['💰', '0 XAF', 'Solde wallet',     '#7C3AED'],
        ] as [$icon, $val, $label, $color])
        <div class="stat-card">
            <div class="stat-icon">{{ $icon }}</div>
            <p class="stat-value" style="color:{{ $color }}">{{ $val }}</p>
            <p class="stat-label">{{ $label }}</p>
        </div>
        @endforeach
    </div>

    {{-- Content --}}
    <div class="content-grid">

        {{-- Main : activité --}}
        <div class="section-card">
            <div class="section-header">
                <h2 class="section-title">Activité récente</h2>
                <a href="#" style="font-size:.82rem;font-weight:700;color:#006EFF;text-decoration:none;">Voir tout →</a>
            </div>
            <div class="section-body">
                <div class="empty-state">
                    <div class="empty-icon">🗺️</div>
                    <p class="empty-title">Aucune activité pour l'instant</p>
                    <p class="empty-sub">Publiez votre premier trajet ou trouvez<br>un covoiturage dès maintenant !</p>
                    <div class="cta-row">
                        <button class="btn-cta primary">Trouver un trajet</button>
                        <button class="btn-cta outline">Proposer un trajet</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar : infos profil --}}
        <div style="display:flex;flex-direction:column;gap:1.25rem;">
            <div class="section-card">
                <div class="section-header">
                    <h2 class="section-title">Mon profil</h2>
                    <a href="#" style="font-size:.82rem;font-weight:700;color:#006EFF;text-decoration:none;">Modifier</a>
                </div>
                <div class="section-body" style="padding-top:1.25rem;padding-bottom:1.25rem;">
                    <div class="info-row">
                        <span class="info-key">Rôle</span>
                        <span class="info-value">{{ ucfirst(auth()->user()->role) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Email vérifié</span>
                        <span class="pill {{ auth()->user()->is_verified ? 'pill-green' : 'pill-gray' }}">
                            {{ auth()->user()->is_verified ? 'Oui' : 'Non' }}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Genre</span>
                        <span class="info-value">{{ ucfirst(auth()->user()->gender ?? '—') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Téléphone</span>
                        <span class="info-value">{{ auth()->user()->phone ?? '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Statut</span>
                        <span class="pill {{ auth()->user()->is_active ? 'pill-green' : 'pill-gray' }}">
                            {{ auth()->user()->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="section-card">
                <div class="section-header">
                    <h2 class="section-title">Wallet</h2>
                </div>
                <div class="section-body" style="text-align:center;padding:2rem 1.75rem;">
                    <p style="font-size:2.25rem;font-weight:900;color:#7C3AED;margin:0 0 .25rem;">0 XAF</p>
                    <p style="font-size:.8rem;color:#7090B8;font-weight:600;margin:0 0 1.25rem;">Solde disponible</p>
                    <button class="btn-cta primary" style="width:100%;">Recharger</button>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    const pt = document.getElementById('page-toast');
    if (pt) {
        setTimeout(() => {
            pt.style.transition = 'opacity .6s ease, transform .6s ease';
            pt.style.opacity = '0';
            pt.style.transform = 'translateY(-12px)';
            setTimeout(() => pt.remove(), 600);
        }, 4500);
    }
</script>

</x-app-layout>
