<footer style="background:#0D1B2A;color:#fff;padding:4rem 2.5rem 2rem;margin-top:auto;">
    <div style="max-width:1200px;margin:0 auto;">

        {{-- Top row --}}
        <div style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:3rem;padding-bottom:3rem;border-bottom:1px solid rgba(255,255,255,.1);">

            {{-- Brand --}}
            <div>
                <div style="display:flex;align-items:center;gap:.625rem;margin-bottom:1rem;">
                    <img src="{{ asset('logo.png') }}" alt="TGether" style="height:36px;filter:brightness(0) invert(1);opacity:.9;">
                    <span style="font-size:1.25rem;font-weight:900;color:#fff;letter-spacing:-.015em;">TGether</span>
                </div>
                <p style="font-size:.875rem;color:rgba(255,255,255,.55);line-height:1.7;margin:0 0 1.5rem;max-width:280px;">
                    La plateforme de covoiturage qui connecte les voyageurs au Cameroun. Partagez les frais, réduisez votre empreinte.
                </p>
                {{-- Social icons --}}
                <div style="display:flex;gap:.75rem;">
                    @foreach([
                        ['Facebook',  'M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z'],
                        ['Twitter/X', 'M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z'],
                        ['LinkedIn',  'M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z M4 6a2 2 0 100-4 2 2 0 000 4z'],
                    ] as [$name, $path])
                    <a href="#" aria-label="{{ $name }}"
                       style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);
                              display:flex;align-items:center;justify-content:center;transition:background .2s;"
                       onmouseover="this.style.background='rgba(0,110,255,.5)'"
                       onmouseout="this.style.background='rgba(255,255,255,.08)'">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="rgba(255,255,255,.8)" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/>
                        </svg>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Covoiturage --}}
            <div>
                <h4 style="font-size:.78rem;font-weight:800;color:rgba(255,255,255,.4);letter-spacing:.08em;text-transform:uppercase;margin:0 0 1.25rem;">Covoiturage</h4>
                <ul style="list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:.625rem;">
                    @foreach(['Trouver un trajet', 'Proposer un trajet', 'Comment ça marche', 'Sécurité', 'Application mobile'] as $link)
                    <li>
                        <a href="#" style="font-size:.875rem;color:rgba(255,255,255,.6);text-decoration:none;font-weight:600;transition:color .2s;"
                           onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,.6)'">
                            {{ $link }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Entreprise --}}
            <div>
                <h4 style="font-size:.78rem;font-weight:800;color:rgba(255,255,255,.4);letter-spacing:.08em;text-transform:uppercase;margin:0 0 1.25rem;">Entreprise</h4>
                <ul style="list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:.625rem;">
                    @foreach(['À propos', 'Blog', 'Carrières', 'Presse', 'Contact'] as $link)
                    <li>
                        <a href="#" style="font-size:.875rem;color:rgba(255,255,255,.6);text-decoration:none;font-weight:600;transition:color .2s;"
                           onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,.6)'">
                            {{ $link }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Aide --}}
            <div>
                <h4 style="font-size:.78rem;font-weight:800;color:rgba(255,255,255,.4);letter-spacing:.08em;text-transform:uppercase;margin:0 0 1.25rem;">Aide</h4>
                <ul style="list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:.625rem;">
                    @foreach(['Centre d\'aide', 'CGU', 'Confidentialité', 'Cookies', 'Accessibilité'] as $link)
                    <li>
                        <a href="#" style="font-size:.875rem;color:rgba(255,255,255,.6);text-decoration:none;font-weight:600;transition:color .2s;"
                           onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,.6)'">
                            {{ $link }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Bottom row --}}
        <div style="display:flex;align-items:center;justify-content:space-between;padding-top:1.75rem;flex-wrap:wrap;gap:1rem;">
            <p style="font-size:.8rem;color:rgba(255,255,255,.35);margin:0;font-weight:600;">
                © {{ date('Y') }} TGether. Tous droits réservés. 🌿
            </p>
            <div style="display:flex;gap:1.5rem;">
                @foreach(['Mentions légales', 'Confidentialité', 'CGU'] as $link)
                <a href="#" style="font-size:.8rem;color:rgba(255,255,255,.35);text-decoration:none;font-weight:600;transition:color .2s;"
                   onmouseover="this.style.color='rgba(255,255,255,.7)'" onmouseout="this.style.color='rgba(255,255,255,.35)'">
                    {{ $link }}
                </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Responsive --}}
    <style>
        @media (max-width: 900px) {
            footer > div > div:first-child { grid-template-columns: 1fr 1fr !important; }
        }
        @media (max-width: 560px) {
            footer > div > div:first-child { grid-template-columns: 1fr !important; gap: 2rem !important; }
            footer { padding: 3rem 1.25rem 1.5rem !important; }
        }
    </style>
</footer>
