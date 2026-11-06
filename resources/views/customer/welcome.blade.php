<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#f8fafc">
    <title>Selamat Datang - Sari Rasa</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        body {
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 0.5px, transparent 0.5px);
            background-size: 24px 24px;
        }

        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-in     { animation: fadeSlideUp 0.6s ease-out forwards; }
        .fade-in-d1  { animation: fadeSlideUp 0.6s 0.1s ease-out both; }
        .fade-in-d2  { animation: fadeSlideUp 0.6s 0.2s ease-out both; }

        @keyframes scanLine {
            0%   { top: 12px; opacity: 1; }
            48%  { top: calc(100% - 12px); opacity: 1; }
            50%  { opacity: 0; }
            52%  { top: 12px; opacity: 0; }
            54%  { opacity: 1; }
            100% { top: 12px; opacity: 1; }
        }
        .scan-anim { animation: scanLine 3s ease-in-out infinite; }

        @keyframes cornerPulse {
            0%, 100% { opacity: 0.5; }
            50%       { opacity: 1; }
        }
        .corner-pulse { animation: cornerPulse 2s ease-in-out infinite; }
    </style>
</head>

<body class="min-h-[100dvh] flex flex-col items-center justify-center px-4 py-8 sm:py-12">

    <!-- Background blobs -->
    <div class="fixed top-0 left-0 w-96 h-96 bg-amber-400/20 blur-[120px] rounded-full pointer-events-none -z-10"></div>
    <div class="fixed bottom-0 right-0 w-96 h-96 bg-blue-400/10 blur-[120px] rounded-full pointer-events-none -z-10"></div>

    <div class="w-full max-w-md">

        <!-- Logo & tagline -->
        <div class="text-center mb-8 fade-in">
            <div class="inline-flex items-center justify-center mb-4">
                <x-resto-logo size="xl" class="drop-shadow-lg" />
            </div>
            <p class="text-slate-500 font-medium text-sm italic">Nikmati hidangan lezat langsung dari meja Anda</p>
        </div>

        <!-- Main card -->
        <div class="bg-white/97 backdrop-blur-xl rounded-[2.5rem] shadow-2xl shadow-slate-200/60 border border-white/80 overflow-hidden fade-in-d1">

            <!-- Top accent -->
            <div class="h-1.5 bg-gradient-to-r from-amber-400 via-amber-500 to-amber-600"></div>

            <div class="p-6 sm:p-8 text-center">

                <!-- QR icon or actual QR -->
                @php
                    $testTable = \App\Models\RestaurantTable::first();
                    $qrUrl = $testTable ? route('qr.scan', ['qrToken' => $testTable->qr_token]) : '#';
                @endphp

                @if($testTable)
                    <!-- QR with animated scan line -->
                    <div class="relative inline-block mb-5">
                        <div class="relative bg-white p-4 rounded-[1.5rem] border-2 border-amber-200/60 shadow-lg overflow-hidden">
                            <!-- Scan line -->
                            <div class="scan-anim absolute left-2 right-2 h-0.5 bg-gradient-to-r from-transparent via-amber-500 to-transparent z-10 pointer-events-none"></div>

                            {!! QrCode::size(150)->margin(1)->color(30, 41, 59)->generate($qrUrl) !!}

                            <!-- Corner decorators -->
                            <div class="corner-pulse absolute top-2.5 left-2.5 w-4 h-4 border-t-2 border-l-2 border-amber-500 rounded-tl"></div>
                            <div class="corner-pulse absolute top-2.5 right-2.5 w-4 h-4 border-t-2 border-r-2 border-amber-500 rounded-tr"></div>
                            <div class="corner-pulse absolute bottom-2.5 left-2.5 w-4 h-4 border-b-2 border-l-2 border-amber-500 rounded-bl"></div>
                            <div class="corner-pulse absolute bottom-2.5 right-2.5 w-4 h-4 border-b-2 border-r-2 border-amber-500 rounded-br"></div>
                        </div>
                        <span class="absolute -bottom-2 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-wider whitespace-nowrap">
                            Test · Meja #{{ $testTable->number }}
                        </span>
                    </div>
                @else
                    <div class="w-20 h-20 bg-amber-50 border-2 border-dashed border-amber-200 rounded-[1.5rem] flex items-center justify-center mx-auto mb-5">
                        <svg class="w-10 h-10 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                    </div>
                @endif

                <div class="mt-7">
                    <h2 class="text-xl font-black text-slate-800 mb-2 tracking-tight">Scan QR Code di Meja</h2>
                    <p class="text-sm text-slate-400 font-medium leading-relaxed">
                        Arahkan kamera ponsel Anda ke QR Code yang tersedia di setiap meja untuk mulai memesan.
                    </p>
                </div>

                <!-- Steps -->
                <div class="mt-7 grid grid-cols-3 gap-3 text-center">
                    <div class="space-y-2">
                        <div class="w-10 h-10 bg-amber-500 rounded-2xl flex items-center justify-center mx-auto shadow-md shadow-amber-500/30">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4"/>
                            </svg>
                        </div>
                        <p class="text-[10px] font-bold text-slate-500 leading-tight">Scan QR di Meja</p>
                    </div>
                    <div class="space-y-2">
                        <div class="w-10 h-10 bg-slate-900 rounded-2xl flex items-center justify-center mx-auto">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                        <p class="text-[10px] font-bold text-slate-500 leading-tight">Pilih Menu</p>
                    </div>
                    <div class="space-y-2">
                        <div class="w-10 h-10 bg-emerald-500 rounded-2xl flex items-center justify-center mx-auto shadow-md shadow-emerald-500/25">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="text-[10px] font-bold text-slate-500 leading-tight">Nikmati Hidangan</p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Reservation shortcut -->
        <a href="{{ route('reservasi.index') }}"
            class="mt-5 flex items-center justify-center gap-2 w-full py-3.5 bg-white border-2 border-slate-200 text-slate-700 font-bold text-sm rounded-2xl hover:bg-slate-50 hover:border-amber-300 hover:text-amber-700 transition-all shadow-sm active:scale-[0.98] fade-in-d2">
            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Reservasi Meja
        </a>

        <!-- Footer -->
        <p class="text-center text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-6 fade-in-d2">
            Aman · Cepat · Modern
        </p>

    </div>
</body>
</html>
