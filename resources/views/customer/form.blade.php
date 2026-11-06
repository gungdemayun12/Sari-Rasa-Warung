<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#f8fafc">
    <title>Data Diri - Meja #{{ $tableNumber }} | Sari Rasa</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        html { -webkit-text-size-adjust: 100%; }

        body {
            background-color: #f8fafc;
            background-image:
                radial-gradient(circle at 10% 0%, rgba(251, 191, 36, .16), transparent 42%),
                radial-gradient(circle at 95% 100%, rgba(59, 130, 246, .10), transparent 45%),
                radial-gradient(#cbd5e1 0.5px, transparent 0.5px);
            background-size: 100% 100%, 100% 100%, 24px 24px;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.9);
        }

        /* ── Field shell ─────────────────────────────────────────── */
        .field-shell { position: relative; }

        .input-field {
            width: 100%;
            padding: 1rem 1.1rem 1rem 1.1rem;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            font-size: 1rem;
            font-weight: 600;
            line-height: 1.4;
            color: #1e293b;
            outline: none;
            transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .04);
        }

        /* Explicit paddings (no shorthand conflict) so the icon never overlaps text */
        .field-shell.has-icon .input-field { padding-left: 2.9rem; }
        .field-shell.has-prefix .input-field { padding-left: 5.35rem; }

        .input-field::placeholder { color: #94a3b8; font-weight: 500; }

        .input-field:focus {
            border-color: #f59e0b;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, .12), 0 1px 3px rgba(0, 0, 0, .04);
        }

        .input-field.input-error {
            border-color: #fca5a5;
            background: #fff7f7;
        }
        .input-field.input-error:focus {
            border-color: #ef4444;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, .10);
        }
        .input-field.input-success { border-color: #6ee7b7; background: #f6fef9; }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            width: 18px;
            height: 18px;
            pointer-events: none;
            transition: color .2s;
            z-index: 2;
        }
        .field-shell:focus-within .input-icon { color: #d97706; }

        /* ── +62 prefix ──────────────────────────────────────────── */
        .phone-prefix {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 0 12px;
            font-weight: 800;
            font-size: .95rem;
            color: #64748b;
            border-right: 1.5px solid #e2e8f0;
            background: #f1f5f9;
            border-radius: 14px 0 0 14px;
            pointer-events: none;
            transition: all .2s;
            z-index: 2;
        }
        .field-shell:focus-within .phone-prefix {
            border-color: #fbd38d;
            background: #fffbeb;
            color: #92400e;
        }

        /* ── Button ──────────────────────────────────────────────── */
        .btn-submit {
            width: 100%;
            min-height: 3.35rem;
            padding: .95rem 1.25rem;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: #fff;
            border: none;
            border-radius: 15px;
            font-weight: 800;
            font-size: .9rem;
            letter-spacing: .06em;
            text-transform: uppercase;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
            transition: transform .2s ease, box-shadow .2s ease, opacity .2s ease;
            box-shadow: 0 10px 26px rgba(245, 158, 11, .32), 0 2px 6px rgba(0, 0, 0, .06);
        }
        .btn-submit:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 14px 32px rgba(245, 158, 11, .40), 0 4px 12px rgba(0, 0, 0, .08);
        }
        .btn-submit:active:not(:disabled) { transform: translateY(0) scale(.99); }
        .btn-submit:disabled { opacity: .72; cursor: not-allowed; }
        .btn-submit .arrow-icon { transition: transform .25s; }
        .btn-submit:hover:not(:disabled) .arrow-icon { transform: translateX(4px); }

        /* ── Misc ────────────────────────────────────────────────── */
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fadeIn .5s ease-out both; }

        .error-message {
            color: #dc2626;
            font-size: .75rem;
            margin-top: 6px;
            display: flex;
            align-items: flex-start;
            gap: 5px;
            font-weight: 600;
            line-height: 1.35;
        }
        .error-message.hidden { display: none; }

        .char-counter { font-size: .6875rem; font-weight: 700; color: #94a3b8; }
        .char-counter.warn { color: #d97706; }
        .char-counter.max { color: #dc2626; }

        .step-line { flex: 1; height: 2px; background: #e2e8f0; border-radius: 999px; margin: 0 .4rem; }
        .step-line.done { background: #fbbf24; }

        @media (min-width: 640px) {
            .input-field { padding-top: 1.05rem; padding-bottom: 1.05rem; }
        }
    </style>
</head>

<body class="min-h-[100dvh] antialiased">

    <div class="min-h-[100dvh] flex flex-col items-center justify-center px-4 py-8 sm:py-12">
        <div class="w-full max-w-md sm:max-w-lg fade-in">

            <!-- Brand + table -->
            <div class="text-center mb-6 sm:mb-7">
                <div class="flex items-center justify-center mb-4">
                    <x-resto-logo size="lg" class="drop-shadow-lg" />
                </div>
                <div class="flex items-center justify-center">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/90 text-amber-800 rounded-full text-xs font-bold border border-amber-200 shadow-sm">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                        </span>
                        Meja #{{ $tableNumber }} · Sesi Aktif
                    </div>
                </div>
            </div>

            <!-- Steps -->
            <div class="flex items-center justify-center mb-5 px-2" aria-hidden="true">
                <div class="flex items-center gap-2">
                    <span class="w-7 h-7 rounded-full bg-amber-500 text-white text-[11px] font-black flex items-center justify-center shadow-md shadow-amber-500/30">1</span>
                    <span class="text-[11px] font-bold text-amber-700">Data diri</span>
                </div>
                <div class="step-line done"></div>
                <div class="flex items-center gap-2 opacity-45">
                    <span class="w-7 h-7 rounded-full bg-white border-2 border-slate-200 text-slate-400 text-[11px] font-black flex items-center justify-center">2</span>
                    <span class="text-[11px] font-bold text-slate-500">Pilih menu</span>
                </div>
                <div class="step-line"></div>
                <div class="flex items-center gap-2 opacity-45">
                    <span class="w-7 h-7 rounded-full bg-white border-2 border-slate-200 text-slate-400 text-[11px] font-black flex items-center justify-center">3</span>
                    <span class="text-[11px] font-bold text-slate-500">Bayar</span>
                </div>
            </div>

            <!-- Card -->
            <div class="glass-card rounded-[1.75rem] sm:rounded-[2rem] shadow-2xl shadow-slate-200/60 p-5 sm:p-8">

                <div class="flex items-start gap-3 mb-6 pb-5 border-b border-slate-100">
                    <div class="w-11 h-11 bg-gradient-to-br from-amber-400 to-amber-600 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/30 shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h1 class="font-black text-slate-800 text-base sm:text-lg leading-tight">Perkenalkan Diri Anda</h1>
                        <p class="text-slate-500 text-xs sm:text-sm font-medium mt-0.5 leading-relaxed">Data ini dipakai untuk memproses pesanan Anda di meja.</p>
                    </div>
                </div>

                <!-- Error summary -->
                <div id="form-alert" class="hidden mb-5 p-3.5 bg-red-50 text-red-700 rounded-xl text-xs font-semibold border border-red-200 flex items-start gap-2" role="alert" aria-live="polite">
                    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    <span id="form-alert-text">Periksa kembali data Anda.</span>
                </div>

                <form id="customer-form" class="space-y-5" novalidate>
                    <input type="hidden" name="session_token" value="{{ $session->session_token }}">
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">

                    <!-- Name -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between gap-2">
                            <label for="customer_name" class="block text-[11px] sm:text-xs font-black text-slate-500 uppercase tracking-widest">
                                Nama Lengkap <span class="text-red-400">*</span>
                            </label>
                            <span id="name-counter" class="char-counter">0/50</span>
                        </div>
                        <div class="field-shell has-icon">
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <input
                                type="text"
                                id="customer_name"
                                name="customer_name"
                                placeholder="Contoh: Budi Santoso"
                                maxlength="50"
                                autocomplete="name"
                                autocapitalize="words"
                                enterkeyhint="next"
                                aria-describedby="error-customer_name"
                                class="input-field"
                            >
                        </div>
                        <p id="error-customer_name" class="error-message hidden" aria-live="polite">
                            <svg class="w-3.5 h-3.5 shrink-0 mt-px" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span></span>
                        </p>
                    </div>

                    <!-- Phone -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between gap-2">
                            <label for="customer_phone" class="block text-[11px] sm:text-xs font-black text-slate-500 uppercase tracking-widest">
                                Nomor WhatsApp <span class="text-red-400">*</span>
                            </label>
                            <span id="phone-counter" class="char-counter">0/13</span>
                        </div>
                        <div class="field-shell has-prefix">
                            <div class="phone-prefix" aria-hidden="true">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span>+62</span>
                            </div>
                            <input
                                type="tel"
                                id="customer_phone"
                                name="customer_phone"
                                placeholder="812 3456 7890"
                                maxlength="13"
                                autocomplete="tel-national"
                                inputmode="numeric"
                                enterkeyhint="done"
                                aria-describedby="error-customer_phone phone-hint"
                                class="input-field"
                            >
                        </div>
                        <p id="error-customer_phone" class="error-message hidden" aria-live="polite">
                            <svg class="w-3.5 h-3.5 shrink-0 mt-px" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span></span>
                        </p>
                        <p id="phone-hint" class="text-[11px] text-slate-400 font-medium leading-relaxed flex items-start gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-300 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Cukup angka setelah +62, tanpa spasi. Contoh: 81234567890
                        </p>
                    </div>

                    <!-- Location status -->
                    <div id="location-status"
                        class="hidden items-center gap-2 p-3 bg-emerald-50 text-emerald-700 rounded-xl text-[11px] font-bold border border-emerald-100">
                        <svg class="w-4 h-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Lokasi terverifikasi</span>
                        <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse ml-auto"></div>
                    </div>

                    <!-- Submit -->
                    <button type="submit" id="submit-btn" class="btn-submit mt-1">
                        <svg id="btn-icon-user" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span id="btn-text">Lanjutkan ke Menu</span>
                        <div id="btn-loading" class="hidden items-center gap-2">
                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Memproses...</span>
                        </div>
                        <svg id="btn-arrow" class="arrow-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </button>

                    <p class="text-center text-[10px] text-slate-400 font-medium leading-relaxed pt-1">
                        Dengan melanjutkan, Anda menyetujui data digunakan hanya untuk keperluan pesanan.
                    </p>
                </form>
            </div>

            <!-- Payment methods -->
            <div class="mt-6 flex flex-col items-center gap-3">
                <div class="flex flex-wrap items-center justify-center gap-1.5">
                    @foreach(['QRIS', 'GoPay', 'OVO', 'Dana', 'Kartu', 'Tunai'] as $pm)
                        <span class="px-2.5 py-1 bg-white/80 border border-slate-200 text-slate-500 text-[10px] font-bold rounded-lg shadow-sm">{{ $pm }}</span>
                    @endforeach
                </div>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.16em] flex items-center gap-1.5 text-center">
                    <svg class="w-3 h-3 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Aman · Cepat · Tanpa Antre
                </p>
            </div>

        </div>
    </div>

    <script>
        // ─── Geolocation (non-blocking) ───────────────────────────────
        window.addEventListener('load', () => {
            if (!navigator.geolocation) return;
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    document.getElementById('latitude').value = pos.coords.latitude;
                    document.getElementById('longitude').value = pos.coords.longitude;
                    const el = document.getElementById('location-status');
                    el.classList.remove('hidden');
                    el.classList.add('flex');
                },
                () => {},
                { enableHighAccuracy: true, timeout: 8000, maximumAge: 60000 }
            );
        });

        const nameInput = document.getElementById('customer_name');
        const phoneInput = document.getElementById('customer_phone');
        const form = document.getElementById('customer-form');
        const submitBtn = document.getElementById('submit-btn');

        const NAME_REGEX = /^[\p{L}\p{M}][\p{L}\p{M}\s.'’-]*$/u;

        function normalizeName(value) {
            return (value || '').replace(/\s+/g, ' ').trim();
        }

        function normalizePhone(value) {
            let v = (value || '').replace(/\D/g, '');
            if (v.startsWith('62')) v = v.slice(2);
            else if (v.startsWith('0')) v = v.slice(1);
            return v;
        }

        function updateCounter(id, len, max) {
            const el = document.getElementById(id);
            if (!el) return;
            el.textContent = len + '/' + max;
            el.className = 'char-counter' + (len >= max ? ' max' : len >= max * 0.8 ? ' warn' : '');
        }

        function setError(field, msg) {
            const errEl = document.getElementById('error-' + field);
            const input = document.getElementById(field);
            if (errEl) {
                errEl.querySelector('span').textContent = msg;
                errEl.classList.remove('hidden');
            }
            if (input) {
                input.classList.add('input-error');
                input.classList.remove('input-success');
                input.setAttribute('aria-invalid', 'true');
            }
        }

        function clearError(field) {
            const errEl = document.getElementById('error-' + field);
            const input = document.getElementById(field);
            if (errEl) errEl.classList.add('hidden');
            if (input) {
                input.classList.remove('input-error');
                input.removeAttribute('aria-invalid');
            }
        }

        function showSummary(message) {
            const alert = document.getElementById('form-alert');
            document.getElementById('form-alert-text').textContent = message;
            alert.classList.remove('hidden');
        }

        function hideSummary() {
            document.getElementById('form-alert').classList.add('hidden');
        }

        function validateName(showErrors = true) {
            const raw = nameInput.value;
            const name = normalizeName(raw);
            let msg = '';

            if (!name) msg = 'Nama lengkap wajib diisi.';
            else if (name.length < 2) msg = 'Nama minimal 2 karakter.';
            else if (name.length > 50) msg = 'Nama maksimal 50 karakter.';
            else if (!NAME_REGEX.test(name)) msg = 'Nama hanya boleh berisi huruf, spasi, titik, apostrof, dan tanda hubung.';

            if (msg) {
                if (showErrors) setError('customer_name', msg);
                return false;
            }
            clearError('customer_name');
            if (nameInput.value.trim()) nameInput.classList.add('input-success');
            return true;
        }

        function validatePhone(showErrors = true) {
            const phone = normalizePhone(phoneInput.value);
            let msg = '';

            if (!phone) msg = 'Nomor WhatsApp wajib diisi.';
            else if (phone.length < 9) msg = 'Nomor terlalu pendek. Minimal 9 digit setelah +62.';
            else if (phone.length > 13) msg = 'Nomor terlalu panjang. Maksimal 13 digit setelah +62.';
            else if (!/^8\d+$/.test(phone)) msg = 'Nomor harus diawali angka 8. Contoh: 81234567890.';

            if (msg) {
                if (showErrors) setError('customer_phone', msg);
                return false;
            }
            clearError('customer_phone');
            if (phoneInput.value.trim()) phoneInput.classList.add('input-success');
            return true;
        }

        // ─── Inputs ───────────────────────────────────────────────────
        nameInput.addEventListener('input', function () {
            updateCounter('name-counter', this.value.length, 50);
            clearError('customer_name');
            hideSummary();
        });
        nameInput.addEventListener('blur', function () {
            if (this.value.trim()) {
                this.value = normalizeName(this.value);
                updateCounter('name-counter', this.value.length, 50);
                validateName(false);
            }
        });

        phoneInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 13);
            updateCounter('phone-counter', this.value.length, 13);
            clearError('customer_phone');
            hideSummary();
        });
        phoneInput.addEventListener('blur', function () {
            if (this.value.trim()) validatePhone(false);
        });

        // ─── Submit ───────────────────────────────────────────────────
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            hideSummary();
            const nameOk = validateName();
            const phoneOk = validatePhone();

            if (!nameOk || !phoneOk) {
                showSummary('Periksa kembali data yang ditandai merah.');
                const firstInvalid = form.querySelector('.input-error');
                if (firstInvalid) firstInvalid.focus({ preventScroll: true });
                return;
            }

            const name = normalizeName(nameInput.value);
            const rawPhone = normalizePhone(phoneInput.value);

            const btnText = document.getElementById('btn-text');
            const btnLoading = document.getElementById('btn-loading');
            const btnIcon = document.getElementById('btn-icon-user');
            const btnArrow = document.getElementById('btn-arrow');

            submitBtn.disabled = true;
            btnText.classList.add('hidden');
            btnIcon.classList.add('hidden');
            btnArrow.classList.add('hidden');
            btnLoading.classList.remove('hidden');
            btnLoading.classList.add('flex');

            const payload = {
                session_token: form.querySelector('input[name="session_token"]').value,
                customer_name: name,
                customer_phone: '62' + rawPhone,
                latitude: document.getElementById('latitude').value || null,
                longitude: document.getElementById('longitude').value || null
            };

            function resetButton() {
                submitBtn.disabled = false;
                btnText.classList.remove('hidden');
                btnIcon.classList.remove('hidden');
                btnArrow.classList.remove('hidden');
                btnLoading.classList.add('hidden');
                btnLoading.classList.remove('flex');
            }

            try {
                const response = await fetch('{{ route("customer.form.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(payload)
                });

                let result = {};
                try { result = await response.json(); } catch (_) {}

                if (response.ok && result.success) {
                    btnLoading.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg><span>Berhasil</span>';
                    setTimeout(() => { window.location.href = result.redirect; }, 350);
                    return;
                }

                if (response.status === 422 && result.errors) {
                    if (result.errors.customer_name) setError('customer_name', result.errors.customer_name[0]);
                    if (result.errors.customer_phone) setError('customer_phone', result.errors.customer_phone[0]);
                    const message = result.errors.session_token?.[0] || result.message || 'Periksa kembali data Anda.';
                    showSummary(message);
                    resetButton();
                    return;
                }

                if (response.status === 419) {
                    showSummary('Sesi halaman kedaluwarsa. Silakan muat ulang halaman ini.');
                    resetButton();
                    return;
                }

                showSummary(result.message || 'Terjadi kesalahan. Silakan coba lagi.');
                resetButton();
            } catch (error) {
                showSummary('Koneksi terputus. Periksa internet Anda lalu coba lagi.');
                resetButton();
            }
        });

        // Focus polish without scaling the layout
        document.querySelectorAll('.input-field').forEach(inp => {
            inp.addEventListener('focus', () => inp.closest('.field-shell').classList.add('is-focused'));
            inp.addEventListener('blur', () => inp.closest('.field-shell').classList.remove('is-focused'));
        });
    </script>
</body>
</html>
