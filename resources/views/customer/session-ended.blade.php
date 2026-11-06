<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesi Berakhir - Sari Rasa</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @vite(['resources/css/app.css'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body {
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 0.5px, transparent 0.5px);
            background-size: 24px 24px;
        }
        @keyframes floatUp {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .float-anim { animation: floatUp 3s ease-in-out infinite; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fadeIn 0.6s ease-out forwards; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 py-10">

    <div class="fixed top-0 left-0 w-96 h-96 bg-amber-400/20 blur-[120px] rounded-full pointer-events-none -z-10"></div>
    <div class="fixed bottom-0 right-0 w-96 h-96 bg-blue-400/10 blur-[120px] rounded-full pointer-events-none -z-10"></div>

    <div class="max-w-sm w-full fade-in">
        <div class="bg-white/97 backdrop-blur-2xl rounded-[2.5rem] shadow-2xl shadow-slate-200/60 border border-white/80 p-10 text-center">

            <!-- Animated icon -->
            <div class="float-anim mb-8 inline-flex">
                <div class="w-24 h-24 bg-gradient-to-br from-amber-400 to-amber-600 rounded-[2rem] flex items-center justify-center shadow-2xl shadow-amber-500/40">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Logo -->
            <div class="flex justify-center mb-5">
                <x-resto-logo size="sm" />
            </div>

            <h1 class="text-2xl font-black text-slate-800 mb-3 tracking-tight">Sesi Telah Berakhir</h1>
            <p class="text-slate-500 text-sm font-medium leading-relaxed mb-8">
                Terima kasih telah berkunjung ke Sari Rasa. Sesi meja Anda telah selesai. Kami tunggu kunjungan Anda berikutnya!
            </p>

            <!-- Divider -->
            <div class="border-t border-slate-100 mb-6"></div>

            <div class="space-y-3">
                <p class="text-[11px] text-slate-400 font-medium leading-relaxed">
                    Jika ini kesalahan, silakan scan QR Code di meja Anda kembali untuk memulai sesi baru.
                </p>
            </div>

        </div>

        <p class="text-center text-[11px] text-slate-400 mt-6 font-medium">
            © {{ date('Y') }} Sari Rasa · Sistem Pemesanan Digital
        </p>
    </div>
</body>
</html>
