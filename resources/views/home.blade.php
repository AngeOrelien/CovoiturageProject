<x-app-layout :title="'Accueil'">

<style>
    /* ── HERO ── */
    .hero {
        background: linear-gradient(160deg, #0047B3 0%, #006EFF 55%, #2E9CFF 100%);
        padding: 6rem 2.5rem 5rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .hero::before {
        content: '';
        position: absolute; inset: 0;
        background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Ccircle cx='30' cy='30' r='1' fill='rgba(255,255,255,.08)'/%3E%3C/svg%3E");
        pointer-events: none;
    }
    .hero-inner { max-width: 800px; margin: 0 auto; position: relative; z-index: 1; }
    .hero-badge {
        display: inline-flex; align-items: center; gap: .5rem;
        background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25);
        color: rgba(255,255,255,.9); font-size: .8rem; font-weight: 700;
        padding: .375rem .875rem; border-radius: 99px; margin-bottom: 1.75rem;
        backdrop-filter: blur(4px);
    }
    .hero h1 {
        font-size: clamp(2.5rem, 5vw, 4rem);
        font-weight: 900; color: #fff; letter-spacing: -.03em;
        line-height: 1.1; margin: 0 0 1.25rem;
    }
    .hero h1 span { color: #A5CFFF; }
    .hero-sub {
        font-size: 1.15rem; color: rgba(255,255,255,.75); line-height: 1.65;
        margin: 0 auto 2.5rem; max-width: 560px;
    }
    .hero-cta {
        display: flex; align-items: center; justify-content: center; gap: 1rem; flex-wrap: wrap;
    }
    .btn-white {
        background: #fff; color: var(--blue); font-size: 1rem; font-weight: 800;
        padding: .9rem 2rem; border-radius: 14px; border: none; cursor: pointer;
        box-shadow: 0 4px 20px rgba(0,0,0,.15); transition: all .2s;
        text-decoration: none; display: inline-block;
    }
    .btn-white:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(0,0,0,.2); }

    .btn-outline-white {
        background: transparent; color: #fff;
        border: 2px solid rgba(255,255,255,.45); font-size: 1rem; font-weight: 700;
        padding: .875rem 2rem; border-radius: 14px; cursor: pointer; transition: all .2s;
        text-decoration: none; display: inline-block;
    }
    .btn-outline-white:hover { background: rgba(255,255,255,.12); border-color: rgba(255,255,255,.75); }

    /* ── SEARCH BAR ── */
    .search-section {
        background: #fff;
        padding: 2rem 2.5rem;
        border-bottom: 1.5px solid var(--border);
        box-shadow: 0 4px 20px rgba(0,110,255,.06);
    }
    .search-bar {
        max-width: 900px; margin: 0 auto;
        display: grid; grid-template-columns: 1fr 1fr 160px 140px;
        gap: 0; border: 2px solid var(--border); border-radius: 16px; overflow: hidden;
        background: #fff; box-shadow: 0 4px 24px rgba(0,110,255,.1);
    }
    .search-field {
        display: flex; flex-direction: column; justify-content: center;
        padding: 1rem 1.25rem; border-right: 1.5px solid var(--border); min-width: 0;
    }
    .search-field:last-child { border: none; padding: 0; }
    .search-field label {
        font-size: .7rem; font-weight: 800; color: var(--mid);
        text-transform: uppercase; letter-spacing: .06em; margin-bottom: .2rem;
    }
    .search-field input, .search-field select {
        border: none; outline: none; font-size: .95rem; font-weight: 700;
        color: var(--dark); background: transparent; font-family: 'Nunito', sans-serif;
        padding: 0; width: 100%;
    }
    .search-field input::placeholder, .search-field select::placeholder { color: #B0C4DC; font-weight: 600; }
    .search-field select { appearance: none; cursor: pointer; }
    .search-btn {
        background: var(--blue); color: #fff; border: none;
        font-size: .95rem; font-weight: 800; cursor: pointer;
        transition: background .2s; padding: 0 1.5rem; height: 100%;
        display: flex; align-items: center; justify-content: center; gap: .5rem;
        white-space: nowrap;
    }
    .search-btn:hover { background: var(--blue-dark); }

    /* ── STATS ── */
    .stats-strip {
        background: var(--bg); padding: 3rem 2.5rem;
        border-bottom: 1.5px solid var(--border);
    }
    .stats-inner {
        max-width: 1000px; margin: 0 auto;
        display: grid; grid-template-columns: repeat(4, 1fr); gap: 2rem;
        text-align: center;
    }
    .stat-num  { font-size: 2.25rem; font-weight: 900; color: var(--blue); letter-spacing: -.03em; margin-bottom: .25rem; }
    .stat-desc { font-size: .875rem; color: var(--muted); font-weight: 600; }

    /* ── HOW IT WORKS ── */
    .section { padding: 5rem 2.5rem; }
    .section-inner { max-width: 1100px; margin: 0 auto; }
    .section-label {
        font-size: .75rem; font-weight: 800; color: var(--blue); letter-spacing: .1em;
        text-transform: uppercase; margin-bottom: .75rem;
    }
    .section-title {
        font-size: clamp(1.75rem, 3vw, 2.25rem); font-weight: 900; color: var(--dark);
        letter-spacing: -.025em; margin: 0 0 .75rem;
    }
    .section-sub { font-size: 1rem; color: var(--muted); line-height: 1.65; margin: 0 0 3.5rem; max-width: 520px; }

    .steps-grid {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 2.5rem;
    }
    .step-card {
        background: #fff; border-radius: 20px; padding: 2rem 1.75rem;
        border: 1.5px solid var(--border);
        box-shadow: 0 2px 12px rgba(0,110,255,.05);
        transition: transform .2s, box-shadow .2s;
    }
    .step-card:hover { transform: translateY(-4px); box-shadow: 0 12px 36px rgba(0,110,255,.1); }
    .step-num {
        width: 44px; height: 44px; border-radius: 12px; background: var(--blue-light);
        color: var(--blue); font-size: 1rem; font-weight: 900;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 1.25rem;
    }
    .step-card h3 { font-size: 1.1rem; font-weight: 800; color: var(--dark); margin: 0 0 .5rem; }
    .step-card p  { font-size: .875rem; color: var(--muted); line-height: 1.65; margin: 0; }

    /* ── FEATURES SPLIT ── */
    .features-section {
        padding: 5rem 2.5rem;
        background: var(--dark);
    }
    .features-inner {
        max-width: 1100px; margin: 0 auto;
        display: grid; grid-template-columns: 1fr 1fr; gap: 5rem; align-items: center;
    }
    .features-text .section-label { color: #5BAAFF; }
    .features-text .section-title { color: #fff; }
    .features-text .section-sub   { color: rgba(255,255,255,.55); margin-bottom: 2rem; }
    .feature-list { display: flex; flex-direction: column; gap: 1rem; }
    .feature-item {
        display: flex; align-items: flex-start; gap: 1rem;
        background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.08);
        border-radius: 14px; padding: 1.125rem 1.25rem;
    }
    .feature-icon {
        width: 42px; height: 42px; flex-shrink: 0; font-size: 1.4rem;
        background: rgba(0,110,255,.25); border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
    }
    .feature-item h4 { font-size: .9rem; font-weight: 800; color: #fff; margin: 0 0 .2rem; }
    .feature-item p  { font-size: .8rem; color: rgba(255,255,255,.55); margin: 0; line-height: 1.5; }
    .features-visual {
        background: linear-gradient(160deg, rgba(0,110,255,.15), rgba(0,200,83,.1));
        border: 1px solid rgba(255,255,255,.08);
        border-radius: 24px; padding: 2.5rem;
        display: flex; flex-direction: column; gap: 1rem;
    }
    .ride-card {
        background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1);
        border-radius: 14px; padding: 1.125rem 1.25rem;
        display: flex; align-items: center; gap: 1rem;
    }
    .ride-avatar {
        width: 44px; height: 44px; border-radius: 50%; background: var(--blue); flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem;
    }
    .ride-info { flex: 1; min-width: 0; }
    .ride-route { font-size: .875rem; font-weight: 800; color: #fff; margin: 0 0 .2rem; }
    .ride-meta  { font-size: .75rem; color: rgba(255,255,255,.5); font-weight: 600; }
    .ride-price { font-size: 1rem; font-weight: 900; color: #5BFF97; flex-shrink: 0; }

    /* ── DESTINATIONS ── */
    .destinations-section { padding: 5rem 2.5rem; background: #fff; }
    .destinations-grid {
        max-width: 1100px; margin: 0 auto;
        display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem;
    }
    .dest-card {
        border-radius: 18px; overflow: hidden; position: relative;
        aspect-ratio: 4/3; cursor: pointer; transition: transform .2s;
        background: linear-gradient(135deg, #1a3a5c, #006EFF);
    }
    .dest-card:hover { transform: scale(1.02); }
    .dest-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,.7) 0%, transparent 60%);
        display: flex; flex-direction: column; justify-content: flex-end;
        padding: 1.25rem;
    }
    .dest-city  { font-size: 1.1rem; font-weight: 900; color: #fff; margin: 0 0 .2rem; }
    .dest-rides { font-size: .78rem; font-weight: 700; color: rgba(255,255,255,.7); margin: 0; }
    .dest-emoji { font-size: 2.5rem; position: absolute; top: 1rem; right: 1rem; }

    /* ── CTA BANNER ── */
    .cta-banner {
        background: linear-gradient(135deg, #00C853, #00A340);
        padding: 4rem 2.5rem; text-align: center;
    }
    .cta-banner h2 {
        font-size: clamp(1.75rem, 3vw, 2.5rem); font-weight: 900;
        color: #fff; letter-spacing: -.025em; margin: 0 0 .875rem;
    }
    .cta-banner p  { font-size: 1rem; color: rgba(255,255,255,.8); margin: 0 0 2rem; }
    .btn-white-green {
        background: #fff; color: #00A340; font-size: 1rem; font-weight: 800;
        padding: .9rem 2.25rem; border-radius: 14px; border: none; cursor: pointer;
        box-shadow: 0 4px 20px rgba(0,0,0,.12); transition: all .2s; text-decoration: none;
        display: inline-block;
    }
    .btn-white-green:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(0,0,0,.18); }

    /* ── RESPONSIVE ── */
    @media (max-width: 1024px) {
        .search-bar        { grid-template-columns: 1fr 1fr; }
        .search-field:nth-child(2) { border-right: none; border-bottom: 1.5px solid var(--border); }
        .search-field:nth-child(3) { border-right: 1.5px solid var(--border); }
        .search-btn        { height: 52px; }
        .steps-grid        { grid-template-columns: 1fr 1fr; }
        .features-inner    { grid-template-columns: 1fr; gap: 3rem; }
        .destinations-grid { grid-template-columns: repeat(2, 1fr); }
        .stats-inner       { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 640px) {
        .hero { padding: 4rem 1.25rem 3.5rem; }
        .search-section { padding: 1.5rem 1rem; }
        .search-bar { grid-template-columns: 1fr; }
        .search-field { border-right: none !important; border-bottom: 1.5px solid var(--border) !important; }
        .search-field:last-child { border-bottom: none !important; }
        .steps-grid, .destinations-grid { grid-template-columns: 1fr; }
        .section { padding: 3.5rem 1.25rem; }
        .features-section { padding: 3.5rem 1.25rem; }
        .stats-inner { grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .cta-banner { padding: 3rem 1.25rem; }
    }
</style>

{{-- ════════════ HERO ════════════ --}}
<section class="hero">
    <div class="hero-inner">
        <div class="hero-badge">🌿 Covoiturage responsable au Cameroun</div>
        <h1>Voyagez <span>ensemble</span>,<br>payez moins.</h1>
        <p class="hero-sub">
            Connectez-vous avec des conducteurs qui font votre trajet.
            Économisez jusqu'à 70%, rencontrez des gens, et réduisez votre empreinte carbone.
        </p>
        <div class="hero-cta">
            <a href="#" class="btn-white">🔍 Trouver un trajet</a>
            <a href="{{ route('register') }}" class="btn-outline-white">Rejoindre TGether →</a>
        </div>
    </div>
</section>

{{-- ════════════ SEARCH BAR ════════════ --}}
<section class="search-section">
    <form>
        <div class="search-bar">
            <div class="search-field">
                <label>Départ</label>
                <input type="text" placeholder="D'où partez-vous ?">
            </div>
            <div class="search-field">
                <label>Destination</label>
                <input type="text" placeholder="Où allez-vous ?">
            </div>
            <div class="search-field">
                <label>Date</label>
                <input type="date">
            </div>
            <div class="search-field">
                <button type="submit" class="search-btn">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
                    </svg>
                    Rechercher
                </button>
            </div>
        </div>
    </form>
</section>

{{-- ════════════ STATS ════════════ --}}
<section class="stats-strip">
    <div class="stats-inner">
        @foreach([
            ['12 000+', 'Voyageurs inscrits'],
            ['450+',    'Trajets publiés'],
            ['98%',     'Taux de satisfaction'],
            ['40%',     'CO₂ économisé en moyenne'],
        ] as [$num, $desc])
        <div>
            <div class="stat-num">{{ $num }}</div>
            <div class="stat-desc">{{ $desc }}</div>
        </div>
        @endforeach
    </div>
</section>

{{-- ════════════ HOW IT WORKS ════════════ --}}
<section class="section" style="background:#fff;">
    <div class="section-inner">
        <p class="section-label">Comment ça marche</p>
        <h2 class="section-title">En 3 étapes simples</h2>
        <p class="section-sub">Que vous soyez conducteur ou passager, TGether rend le covoiturage facile et sûr.</p>

        <div class="steps-grid">
            @foreach([
                ['1', 'Créez votre compte', 'Inscrivez-vous en moins de 2 minutes. Renseignez votre profil et vérifiez votre identité pour renforcer la confiance.'],
                ['2', 'Trouvez ou publiez', 'Cherchez un trajet qui correspond à vos besoins ou publiez le vôtre avec quelques clics. Définissez votre itinéraire et vos prix.'],
                ['3', 'Voyagez & économisez', 'Réservez votre place, retrouvez-vous au point de rendez-vous et voyagez en partageant les frais. Simple, rapide, économique.'],
            ] as [$n, $title, $desc])
            <div class="step-card">
                <div class="step-num">{{ $n }}</div>
                <h3>{{ $title }}</h3>
                <p>{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ════════════ FEATURES ════════════ --}}
<section class="features-section">
    <div class="features-inner">
        <div class="features-text">
            <p class="section-label">Pourquoi TGether</p>
            <h2 class="section-title">La confiance avant tout</h2>
            <p class="section-sub">Chaque membre est vérifié. Chaque trajet est noté. Votre sécurité est notre priorité numéro un.</p>
            <div class="feature-list">
                @foreach([
                    ['🔒', 'Profils vérifiés',      'Pièce d\'identité et numéro de téléphone vérifiés pour tous les membres.'],
                    ['⭐', 'Système de notation',   'Des avis réels après chaque trajet pour maintenir la qualité de la communauté.'],
                    ['💳', 'Paiement sécurisé',     'Transactions gérées par notre wallet intégré. Pas d\'argent liquide nécessaire.'],
                    ['📞', 'Support réactif',       'Notre équipe est disponible 7j/7 pour résoudre tout problème rapidement.'],
                ] as [$icon, $title, $desc])
                <div class="feature-item">
                    <div class="feature-icon">{{ $icon }}</div>
                    <div>
                        <h4>{{ $title }}</h4>
                        <p>{{ $desc }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="features-visual">
            <p style="font-size:.72rem;font-weight:800;color:rgba(255,255,255,.35);letter-spacing:.08em;text-transform:uppercase;margin:0 0 .5rem;">Trajets disponibles maintenant</p>
            @foreach([
                ['🚌', 'Yaoundé → Douala',   'Auj. 08h30 · 3 places', '3 500 XAF'],
                ['🚗', 'Douala → Bafoussam', 'Auj. 10h00 · 2 places', '4 200 XAF'],
                ['🚕', 'Yaoundé → Kribi',    'Auj. 14h00 · 4 places', '2 800 XAF'],
            ] as [$emoji, $route, $meta, $price])
            <div class="ride-card">
                <div class="ride-avatar">{{ $emoji }}</div>
                <div class="ride-info">
                    <p class="ride-route">{{ $route }}</p>
                    <p class="ride-meta">{{ $meta }}</p>
                </div>
                <div class="ride-price">{{ $price }}</div>
            </div>
            @endforeach
            <a href="{{ route('register') }}" style="
                display:block;text-align:center;background:var(--blue);color:#fff;
                font-size:.875rem;font-weight:800;padding:.75rem;border-radius:12px;
                text-decoration:none;margin-top:.5rem;transition:background .2s;
            " onmouseover="this.style.background='var(--blue-dark)'" onmouseout="this.style.background='var(--blue)'">
                Voir tous les trajets →
            </a>
        </div>
    </div>
</section>

{{-- ════════════ DESTINATIONS ════════════ --}}
<section class="destinations-section">
    <div style="max-width:1100px;margin:0 auto;">
        <p class="section-label" style="text-align:center;">Destinations populaires</p>
        <h2 class="section-title" style="text-align:center;margin-bottom:3rem;">Où voulez-vous aller ?</h2>
        <div class="destinations-grid">
            @foreach([
                ['Douala',     '🌊', '142 trajets'],
                ['Bafoussam',  '⛰️',  '87 trajets'],
                ['Kribi',      '🏖️',  '64 trajets'],
                ['Garoua',     '🌅', '53 trajets'],
                ['Bertoua',    '🌿',  '41 trajets'],
                ['Ngaoundéré', '🏔️',  '38 trajets'],
                ['Ebolowa',    '🌳', '29 trajets'],
                ['Bamenda',    '☁️',  '35 trajets'],
            ] as [$city, $emoji, $rides])
            <div class="dest-card">
                <span class="dest-emoji">{{ $emoji }}</span>
                <div class="dest-overlay">
                    <p class="dest-city">{{ $city }}</p>
                    <p class="dest-rides">{{ $rides }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ════════════ CTA FINAL ════════════ --}}
<section class="cta-banner">
    <h2>Prêt à voyager autrement ?</h2>
    <p>Rejoignez des milliers de Camerounais qui font du covoiturage chaque jour avec TGether.</p>
    <a href="{{ route('register') }}" class="btn-white-green">Créer mon compte gratuitement →</a>
</section>

</x-app-layout>
