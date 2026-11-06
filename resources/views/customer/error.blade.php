<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Terjadi Kesalahan' }} - Sari Rasa</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body {
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 0.5px, transparent 0.5px);
            background-size: 24px 24px;
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fadeIn 0.6s ease-out forwards; }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-5px); }
            40%, 80% { transform: translateX(5px); }
        }
        .shake { animation: shake 0.5s ease-in-out; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 py-10">

    <div class="fixed top-0 left-0 w-96 h-96 bg-red-400/10 blur-[120px] rounded-full pointer-events-none -z-10"></div>
    <div class="fixed bottom-0 right-0 w-96 h-96 bg-amber-400/10 blur-[120px] rounded-full pointer-events-none -z-10"></div>

    <div class="max-w-sm w-full fade-in">
        <div class="bg-white/97 backdrop-blur-2xl rounded-[2.5rem] shadow-2xl shadow-slate-200/60 border border-white/80 p-10 text-center">

            <!-- Icon -->
            <div class="shake inline-flex mb-6">
                <div class="w-20 h-20 bg-gradient-to-br from-red-400 to-red-600 rounded-[1.75rem] flex items-center justify-center shadow-xl shadow-red-500/30">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>

            <!-- Logo -->
            <div class="flex justify-center mb-5">
                <x-resto-logo size="sm" />
            </div>

            <h1 class="text-xl font-black text-slate-800 mb-2.5 tracking-tight">
                {{ $title ?? 'Terjadi Kesalahan' }}
            </h1>
            <p class="text-slate-500 text-sm font-medium leading-relaxed mb-8">
                {{ $message ?? 'Maaf, terjadi kesalahan yang tidak terduga. Silakan coba lagi atau hubungi staff kami.' }}
            </p>

            <div class="border-t border-slate-100 mb-6"></div>

            <div class="space-y-2.5">
                <button onclick="window.history.back()"
                    class="flex items-center justify-center gap-2 w-full py-3.5 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-bold rounded-2xl hover:from-amber-600 hover:to-amber-700 transition-all shadow-lg shadow-amber-500/25 active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali
                </button>
            </div>

            <p class="mt-5 text-[11px] text-slate-400 font-medium">
                Butuh bantuan? Silakan hubungi staff kami di meja kasir.
            </p>
        </div>
    </div>
</body>
</html>
