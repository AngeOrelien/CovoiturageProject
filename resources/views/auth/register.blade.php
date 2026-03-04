<x-auth.auth-layout>

    {{-- ── Wizard header : dots + barre de progression ── --}}
    <div style="margin-bottom:1.75rem;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.875rem;">

            {{-- Bouton retour --}}
            <button type="button" id="btn-back" onclick="goStep(currentStep - 1, true)"
                    style="display:none;width:36px;height:36px;border-radius:50%;border:2px solid var(--border);background:none;
                           cursor:pointer;align-items:center;justify-content:center;color:var(--muted);
                           transition:border-color .2s,color .2s;flex-shrink:0;"
                    onmouseover="this.style.borderColor='var(--blue)';this.style.color='var(--blue)'"
                    onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--muted)'">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <div id="back-spacer" style="width:36px;flex-shrink:0;"></div>

            {{-- Dots --}}
            <div style="display:flex;align-items:center;gap:.5rem;" id="step-dots">
                <div class="step-dot active" data-step="0"></div>
                <div class="step-dot"        data-step="1"></div>
                <div class="step-dot"        data-step="2"></div>
                <div class="step-dot"        data-step="3"></div>
            </div>

            {{-- Étape X/4 --}}
            <span id="step-label" style="font-size:.76rem;font-weight:800;color:var(--muted);width:36px;text-align:right;flex-shrink:0;">1 / 4</span>
        </div>

        <div class="prog-bar">
            <div class="prog-fill" id="prog-fill" style="width:25%;"></div>
        </div>
    </div>

    <form method="POST" action="{{ route('register') }}" id="reg-form" enctype="multipart/form-data">
        @csrf

        {{-- ════════════════════════════════════════
             ÉTAPE 1 — Rôle
        ════════════════════════════════════════ --}}
        <div class="step-pane active" id="step-0">

            <h1 class="form-heading">Bienvenue !</h1>
            <p class="form-sub">Comment souhaitez-vous utiliser TGether ?</p>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:2rem;">
                <div class="role-card">
                    <input type="radio" name="role" id="role_passenger" value="passenger" checked>
                    <label for="role_passenger">
                        <span class="icon">🎫</span>
                        <strong>Passager</strong>
                        <span class="sub">Je cherche des trajets</span>
                    </label>
                </div>
                <div class="role-card">
                    <input type="radio" name="role" id="role_driver" value="driver">
                    <label for="role_driver">
                        <span class="icon">🚗</span>
                        <strong>Conducteur</strong>
                        <span class="sub">Je propose des trajets</span>
                    </label>
                </div>
            </div>

            <button type="button" onclick="goStep(1)" class="btn-primary">Continuer →</button>

            <p style="text-align:center;font-size:.875rem;color:var(--muted);margin-top:1.25rem;">
                Déjà un compte ?
                <a href="{{ route('login') }}" style="color:var(--blue);font-weight:700;text-decoration:none;">Se connecter</a>
            </p>
        </div>

        {{-- ════════════════════════════════════════
             ÉTAPE 2 — Identité
        ════════════════════════════════════════ --}}
        <div class="step-pane" id="step-1">

            <h1 class="form-heading">Votre identité</h1>
            <p class="form-sub">Ces informations seront visibles par les autres membres.</p>

            {{-- Avatar --}}
            <div style="display:flex;flex-direction:column;align-items:center;gap:.5rem;margin-bottom:1.75rem;">
                <div class="avatar-wrap" onclick="document.getElementById('avatar_input').click()">
                    <img id="avatar_preview"
                         src="https://img.daisyui.com/images/profile/demo/distracted1@192.webp"
                         alt="Avatar" />
                    <div class="avatar-overlay">
                        <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <input type="file" id="avatar_input" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(event)">
                <span style="font-size:.78rem;color:var(--muted);cursor:pointer;font-weight:600;"
                      onclick="document.getElementById('avatar_input').click()"
                      onmouseover="this.style.color='var(--blue)'" onmouseout="this.style.color='var(--muted)'">
                    Modifier la photo
                </span>
            </div>

            <div style="display:flex;flex-direction:column;gap:1.125rem;">
                {{-- Prénom / Nom --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.875rem;">
                    <div>
                        <label class="field-label" for="first_name">Prénom <span style="color:#FF3B30">*</span></label>
                        <input type="text" id="first_name" name="first_name"
                               value="{{ old('first_name') }}" placeholder="Jean"
                               class="input-field {{ $errors->has('first_name') ? 'is-error' : '' }}" />
                        @error('first_name')<p class="field-error">⚠ {{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label" for="last_name">Nom <span style="color:#FF3B30">*</span></label>
                        <input type="text" id="last_name" name="last_name"
                               value="{{ old('last_name') }}" placeholder="Dupont"
                               class="input-field {{ $errors->has('last_name') ? 'is-error' : '' }}" />
                        @error('last_name')<p class="field-error">⚠ {{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Date / Genre --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.875rem;">
                    <div>
                        <label class="field-label" for="date_of_birth">Date de naissance</label>
                        <input type="date" id="date_of_birth" name="date_of_birth"
                               value="{{ old('date_of_birth') }}"
                               class="input-field {{ $errors->has('date_of_birth') ? 'is-error' : '' }}" />
                    </div>
                    <div>
                        <label class="field-label" for="gender">Genre</label>
                        <select id="gender" name="gender" class="input-field">
                            <option value="" disabled {{ old('gender') ? '' : 'selected' }}>—</option>
                            <option value="male"   {{ old('gender') === 'male'   ? 'selected' : '' }}>Homme</option>
                            <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Femme</option>
                            <option value="other"  {{ old('gender') === 'other'  ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>
                </div>

                {{-- Bio --}}
                <div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:.4rem;">
                        <label class="field-label" style="margin:0;" for="bio">Bio</label>
                        <span id="bio-count" style="font-size:.76rem;color:var(--muted);font-weight:600;">0 / 500</span>
                    </div>
                    <textarea id="bio" name="bio" rows="3" maxlength="500"
                              placeholder="Quelques mots sur vous..."
                              class="input-field">{{ old('bio') }}</textarea>
                </div>
            </div>

            <button type="button" onclick="validateStep1()" class="btn-primary" style="margin-top:1.75rem;">Continuer →</button>
        </div>

        {{-- ════════════════════════════════════════
             ÉTAPE 3 — Contact
        ════════════════════════════════════════ --}}
        <div class="step-pane" id="step-2">

            <h1 class="form-heading">Vos coordonnées</h1>
            <p class="form-sub">Pour vous contacter et protéger votre compte.</p>

            <div style="display:flex;flex-direction:column;gap:1.125rem;">
                <div>
                    <label class="field-label" for="email">Email <span style="color:#FF3B30">*</span></label>
                    <div class="field-icon-wrap">
                        <span class="fi-left">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <input type="email" id="email" name="email"
                               value="{{ old('email') }}"
                               placeholder="vous@exemple.cm"
                               class="input-field {{ $errors->has('email') ? 'is-error' : '' }}" />
                    </div>
                    @error('email')<p class="field-error">⚠ {{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="field-label" for="phone">Téléphone</label>
                    <div class="field-icon-wrap">
                        <span class="fi-left">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </span>
                        <input type="tel" id="phone" name="phone"
                               value="{{ old('phone') }}"
                               placeholder="+237 6XX XXX XXX"
                               class="input-field" />
                    </div>
                </div>
            </div>

            <button type="button" onclick="validateStep2()" class="btn-primary" style="margin-top:1.75rem;">Continuer →</button>
        </div>

        {{-- ════════════════════════════════════════
             ÉTAPE 4 — Mot de passe
        ════════════════════════════════════════ --}}
        <div class="step-pane" id="step-3">

            <h1 class="form-heading">Sécurisez votre compte</h1>
            <p class="form-sub">Choisissez un mot de passe fort et unique.</p>

            <div style="display:flex;flex-direction:column;gap:1.125rem;">

                {{-- Password --}}
                <div>
                    <label class="field-label" for="password">Mot de passe <span style="color:#FF3B30">*</span></label>
                    <div class="field-icon-wrap" style="position:relative;">
                        <span class="fi-left">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input type="password" id="password" name="password"
                               placeholder="••••••••"
                               style="padding-right:3rem;"
                               class="input-field {{ $errors->has('password') ? 'is-error' : '' }}"
                               oninput="updateStrength(this.value)" />
                        <button type="button" class="pw-eye" onclick="togglePw('password', this)">
                            <svg class="eye-on" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg class="eye-off hidden" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    <div class="strength-track"><div class="strength-fill" id="strength-fill"></div></div>
                    <p id="strength-label" style="font-size:.78rem;font-weight:700;margin-top:.35rem;min-height:1em;"></p>
                    @error('password')<p class="field-error">⚠ {{ $message }}</p>@enderror
                </div>

                {{-- Confirmation --}}
                <div>
                    <label class="field-label" for="password_confirmation">Confirmer <span style="color:#FF3B30">*</span></label>
                    <div class="field-icon-wrap" style="position:relative;">
                        <span class="fi-left">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </span>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               placeholder="••••••••"
                               style="padding-right:3rem;"
                               class="input-field" />
                        <button type="button" class="pw-eye" onclick="togglePw('password_confirmation', this)">
                            <svg class="eye-on" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg class="eye-off hidden" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    <p id="pw-match-err" class="field-error hidden">⚠ Les mots de passe ne correspondent pas</p>
                </div>

                {{-- Checklist --}}
                <div class="checklist">
                    <div class="chk-item" id="chk-len">  <span class="chk-icon">○</span><span>8+ caractères</span></div>
                    <div class="chk-item" id="chk-upper"><span class="chk-icon">○</span><span>Une majuscule</span></div>
                    <div class="chk-item" id="chk-lower"><span class="chk-icon">○</span><span>Une minuscule</span></div>
                    <div class="chk-item" id="chk-num">  <span class="chk-icon">○</span><span>Un chiffre</span></div>
                </div>

                {{-- CGU --}}
                <label style="display:flex;align-items:flex-start;gap:.625rem;cursor:pointer;">
                    <input type="checkbox" id="terms" required
                           style="width:16px;height:16px;border-radius:4px;accent-color:var(--blue);cursor:pointer;margin-top:.15rem;flex-shrink:0;" />
                    <span style="font-size:.82rem;color:var(--muted);line-height:1.5;">
                        J'accepte les
                        <a href="#" style="color:var(--blue);font-weight:700;text-decoration:none;">conditions d'utilisation</a>
                        et la
                        <a href="#" style="color:var(--blue);font-weight:700;text-decoration:none;">politique de confidentialité</a>.
                    </span>
                </label>
            </div>

            <button type="submit" class="btn-primary" style="margin-top:1.75rem;">
                Créer mon compte 🎉
            </button>
        </div>

    </form>

    <script>
        let currentStep = 0;
        const TOTAL = 4;

        // ── Navigation ──────────────────────────────────
        function goStep(n, back = false) {
            if (n < 0 || n >= TOTAL) return;
            const prev = document.getElementById('step-' + currentStep);
            prev.classList.remove('active', 'going-back');
            prev.style.display = 'none';
            currentStep = n;
            const next = document.getElementById('step-' + currentStep);
            next.style.display = 'block';
            next.classList.remove('active', 'going-back');
            void next.offsetWidth;
            next.classList.add(back ? 'going-back' : 'active');
            updateNav();
        }

        function updateNav() {
            const pct = Math.round(((currentStep + 1) / TOTAL) * 100);
            document.getElementById('prog-fill').style.width = pct + '%';
            document.getElementById('step-label').textContent = (currentStep + 1) + ' / ' + TOTAL;

            const btnBack  = document.getElementById('btn-back');
            const spacer   = document.getElementById('back-spacer');
            if (currentStep > 0) {
                btnBack.style.display = 'flex';
                spacer.style.display  = 'none';
            } else {
                btnBack.style.display = 'none';
                spacer.style.display  = 'block';
            }

            document.querySelectorAll('.step-dot').forEach((d, i) => {
                d.classList.remove('active', 'done');
                if (i < currentStep)  d.classList.add('done');
                if (i === currentStep) d.classList.add('active');
            });
        }

        // ── Validation étape 2 ───────────────────────────
        function validateStep1() {
            const fn = document.getElementById('first_name');
            const ln = document.getElementById('last_name');
            let ok = true;
            [fn, ln].forEach(f => {
                if (!f.value.trim()) {
                    f.classList.add('is-error');
                    shake(f);
                    f.addEventListener('input', () => f.classList.remove('is-error'), { once: true });
                    ok = false;
                }
            });
            if (ok) goStep(2);
        }

        // ── Validation étape 3 ───────────────────────────
        function validateStep2() {
            const em = document.getElementById('email');
            if (!em.value.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(em.value)) {
                em.classList.add('is-error');
                shake(em);
                em.addEventListener('input', () => em.classList.remove('is-error'), { once: true });
                return;
            }
            goStep(3);
        }

        // ── Shake ────────────────────────────────────────
        function shake(el) {
            const steps = ['-6px','6px','-4px','4px','-2px','0'];
            let i = 0;
            const run = () => {
                if (i < steps.length) { el.style.transform = `translateX(${steps[i++]})`; setTimeout(run, 55); }
                else el.style.transform = '';
            };
            run();
        }

        // ── Avatar preview ───────────────────────────────
        function previewAvatar(e) {
            const f = e.target.files[0];
            if (f) document.getElementById('avatar_preview').src = URL.createObjectURL(f);
        }

        // ── Bio counter ──────────────────────────────────
        document.getElementById('bio').addEventListener('input', function() {
            document.getElementById('bio-count').textContent = this.value.length + ' / 500';
        });

        // ── Password strength ────────────────────────────
        const CHECKS = [
            { id:'chk-len',   re:/.{8,}/     },
            { id:'chk-upper', re:/[A-Z]/     },
            { id:'chk-lower', re:/[a-z]/     },
            { id:'chk-num',   re:/[0-9]/     },
        ];
        const LEVELS = [
            { w:'0%',    c:'',         t:'' },
            { w:'25%',   c:'#FF3B30',  t:'Trop court' },
            { w:'50%',   c:'#FF9500',  t:'Faible' },
            { w:'75%',   c:'#FFD60A',  t:'Moyen' },
            { w:'100%',  c:'#00C853',  t:'Fort 💪' },
        ];
        function updateStrength(val) {
            let score = 0;
            CHECKS.forEach(c => {
                const ok  = c.re.test(val);
                if (ok) score++;
                const row  = document.getElementById(c.id);
                const icon = row.querySelector('.chk-icon');
                icon.textContent = ok ? '✓' : '○';
                icon.style.color = ok ? '#00C853' : '#D0E4FF';
                row.querySelector('span:last-child').style.color = ok ? 'var(--dark)' : 'var(--muted)';
            });
            const lv = val ? LEVELS[score] : LEVELS[0];
            const fill   = document.getElementById('strength-fill');
            const slabel = document.getElementById('strength-label');
            fill.style.width      = lv.w;
            fill.style.background = lv.c;
            slabel.textContent    = lv.t;
            slabel.style.color    = lv.c;
        }

        // ── Submit guard ─────────────────────────────────
        document.getElementById('reg-form').addEventListener('submit', function(e) {
            const pw  = document.getElementById('password').value;
            const pwc = document.getElementById('password_confirmation').value;
            const err = document.getElementById('pw-match-err');
            if (pw !== pwc) {
                e.preventDefault();
                err.classList.remove('hidden');
                document.getElementById('password_confirmation').classList.add('is-error');
            }
        });

        // ── Revenir au bon step si Laravel renvoie erreurs ──
        @if($errors->any())
            @if($errors->has('first_name') || $errors->has('last_name') || $errors->has('date_of_birth'))
                goStep(1);
            @elseif($errors->has('email'))
                goStep(2);
            @elseif($errors->has('password'))
                goStep(3);
            @endif
        @endif

        updateNav();
    </script>

</x-auth.auth-layout>
