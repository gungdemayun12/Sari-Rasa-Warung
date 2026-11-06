<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Login - Sari Rasa Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @vite(['resources/css/app.css'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        .glass-card {
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
        }

        /* Left panel image animations */
        @keyframes floatUp {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-12px) scale(1.01); }
        }
        @keyframes shimmerOverlay {
            0% { opacity: 0.3; }
            50% { opacity: 0.6; }
            100% { opacity: 0.3; }
        }
        @keyframes fadeSlideIn {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes fadeSlideRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes subtlePan {
            0% { transform: scale(1.08) translate(0, 0); }
            33% { transform: scale(1.08) translate(-1%, 1%); }
            66% { transform: scale(1.08) translate(1%, -1%); }
            100% { transform: scale(1.08) translate(0, 0); }
        }

        .login-image {
            animation: subtlePan 18s ease-in-out infinite;
            transform-origin: center;
        }
        .left-panel { animation: fadeSlideIn 0.7s ease-out forwards; }
        .right-panel { animation: fadeSlideRight 0.7s ease-out 0.15s both; }

        .badge-float {
            animation: floatUp 4s ease-in-out infinite;
        }
        .badge-float-delay {
            animation: floatUp 4s ease-in-out 1.5s infinite;
        }

        /* Input styles */
        .input-field {
            width: 100%;
            padding: 0.875rem 1.25rem 0.875rem 3rem;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            font-size: 0.9375rem;
            font-weight: 600;
            color: #1e293b;
            outline: none;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .input-field:focus {
            border-color: #f59e0b;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(245,158,11,0.1), 0 1px 3px rgba(0,0,0,0.04);
        }
        .input-field.error {
            border-color: #fca5a5;
            background: #fff5f5;
        }
        .input-field.error:focus {
            box-shadow: 0 0 0 4px rgba(239,68,68,0.08);
        }
        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            width: 18px;
            height: 18px;
            pointer-events: none;
            transition: color 0.2s;
        }
        .input-wrapper:focus-within .input-icon { color: #f59e0b; }

        /* Password toggle */
        .eye-btn {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            cursor: pointer;
            padding: 4px;
            border-radius: 8px;
            transition: all 0.2s;
            background: none;
            border: none;
        }
        .eye-btn:hover { color: #f59e0b; background: rgba(245,158,11,0.08); }

        /* Submit button */
        .btn-submit {
            width: 100%;
            padding: 1rem 1.5rem;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            border: none;
            border-radius: 14px;
            font-weight: 800;
            font-size: 0.9375rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
            transition: all 0.25s ease;
            box-shadow: 0 8px 24px rgba(245,158,11,0.35), 0 2px 6px rgba(0,0,0,0.08);
        }
        .btn-submit::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, transparent 60%);
            opacity: 0;
            transition: opacity 0.25s;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(245,158,11,0.45), 0 4px 12px rgba(0,0,0,0.1);
        }
        .btn-submit:hover::before { opacity: 1; }
        .btn-submit:active { transform: translateY(0) scale(0.98); box-shadow: 0 4px 12px rgba(245,158,11,0.3); }
        .btn-submit .arrow-icon { transition: transform 0.25s; }
        .btn-submit:hover .arrow-icon { transform: translateX(4px); }

        /* Checkbox custom */
        .custom-check { accent-color: #f59e0b; width: 18px; height: 18px; border-radius: 6px; cursor: pointer; }

        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4">

    <!-- Background blobs -->
    <div class="fixed top-0 left-0 w-96 h-96 bg-amber-400/20 blur-[120px] rounded-full pointer-events-none -z-10"></div>
    <div class="fixed bottom-0 right-0 w-96 h-96 bg-blue-400/15 blur-[120px] rounded-full pointer-events-none -z-10"></div>
    <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[400px] bg-amber-300/10 blur-[150px] rounded-full pointer-events-none -z-10"></div>

    <div class="w-full max-w-5xl">
        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-300/40 overflow-hidden flex min-h-[580px] border border-white/80">

            <!-- LEFT PANEL — Image -->
            <div class="hidden lg:flex left-panel relative w-[52%] flex-col overflow-hidden bg-slate-900">
                <!-- Image with subtle animation -->
                <img
                    src="{{ asset('storage/login.png') }}"
                    alt="Sari Rasa"
                    class="login-image absolute inset-0 w-full h-full object-cover"
                >
                <!-- Gradient overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/30 to-slate-900/10"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-transparent to-slate-900/20"></div>

                <!-- Content over image -->
                <div class="relative z-10 flex flex-col h-full p-10">
                    <!-- Brand logo -->
                    <div class="flex items-start justify-start">
                        <div class="rounded-2xl bg-white/95 backdrop-blur-sm px-4 py-3 shadow-2xl shadow-black/25 ring-1 ring-white/60">
                            <x-resto-logo size="md" />
                        </div>
                    </div>

                    <div class="flex-1 flex flex-col justify-end">
                        <div class="badge-float inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm border border-white/20 text-white text-xs font-bold px-4 py-2 rounded-full w-fit mb-6">
                            <span class="w-2 h-2 bg-amber-400 rounded-full animate-pulse"></span>
                            Panel Admin Aktif
                        </div>
                        <h2 class="text-white text-3xl font-black leading-tight mb-3">
                            Selamat Datang<br>di Dapur Digital
                        </h2>
                        <p class="text-white/70 text-sm font-medium leading-relaxed">
                            Kelola menu, pantau pesanan, dan analisis performa restoran Anda dari satu tempat.
                        </p>

                        <!-- Stats badges -->
                        <div class="flex gap-3 mt-8">
                            <div class="badge-float-delay bg-white/15 backdrop-blur-sm border border-white/20 rounded-2xl px-4 py-3 text-center">
                                <p class="text-amber-300 text-xl font-black">99%</p>
                                <p class="text-white/60 text-[10px] font-bold uppercase tracking-wider mt-0.5">Uptime</p>
                            </div>
                            <div class="badge-float bg-white/15 backdrop-blur-sm border border-white/20 rounded-2xl px-4 py-3 text-center">
                                <p class="text-emerald-300 text-xl font-black">Live</p>
                                <p class="text-white/60 text-[10px] font-bold uppercase tracking-wider mt-0.5">Real-time</p>
                            </div>
                            <div class="badge-float-delay bg-white/15 backdrop-blur-sm border border-white/20 rounded-2xl px-4 py-3 text-center">
                                <p class="text-blue-300 text-xl font-black">SSL</p>
                                <p class="text-white/60 text-[10px] font-bold uppercase tracking-wider mt-0.5">Secure</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT PANEL — Form -->
            <div class="right-panel flex-1 flex flex-col justify-center px-8 md:px-12 py-12">
                <!-- Logo (mobile only) -->
                <div class="flex lg:hidden justify-center mb-8">
                    <x-resto-logo size="lg" class="drop-shadow-md" />
                </div>

                <!-- Header -->
                <div class="mb-8">
                    <div class="hidden lg:flex items-center gap-3 mb-6">
                        <x-resto-logo size="md" />
                    </div>
                    <h1 class="text-2xl md:text-3xl font-black text-slate-800 leading-tight">Masuk ke Dashboard</h1>
                    <p class="text-slate-400 text-sm font-medium mt-1.5">Masukkan kredensial admin Anda untuk melanjutkan</p>
                </div>

                <!-- Error alert -->
                @if($errors->has('email') && !$errors->has('email_field'))
                    <div class="mb-5 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 rounded-2xl px-4 py-3.5 text-sm font-medium">
                        <svg class="w-5 h-5 shrink-0 mt-0.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ $errors->first('email') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5" novalidate>
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">
                            Alamat Email
                        </label>
                        <div class="input-wrapper relative">
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="admin@sarirasa.com"
                                autocomplete="email"
                                class="input-field @error('email') error @enderror"
                            >
                        </div>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1.5 ml-1 flex items-center gap-1 font-medium">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">
                            Kata Sandi
                        </label>
                        <div class="input-wrapper relative" x-data="{ show: false }">
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <input
                                :type="show ? 'text' : 'password'"
                                id="password"
                                name="password"
                                placeholder="••••••••••"
                                autocomplete="current-password"
                                class="input-field pr-12 @error('password') error @enderror"
                            >
                            <button type="button" class="eye-btn" @click="show = !show" :title="show ? 'Sembunyikan' : 'Tampilkan'">
                                <svg x-show="!show" class="w-4.5 h-4.5 w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="show" x-cloak class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1.5 ml-1 flex items-center gap-1 font-medium">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Remember me -->
                    <div class="flex items-center gap-2.5">
                        <input type="checkbox" name="remember" id="remember" class="custom-check">
                        <label for="remember" class="text-sm text-slate-600 font-semibold cursor-pointer select-none">Ingat saya di perangkat ini</label>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn-submit mt-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        <span>Masuk ke Dashboard</span>
                        <svg class="arrow-icon w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </button>
                </form>

                <p class="text-center text-slate-400 text-xs mt-8 font-medium">
                    © {{ date('Y') }} Sari Rasa · Sistem POS Digital
                </p>
            </div>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>
