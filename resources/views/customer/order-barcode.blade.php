<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Konfirmasi Kasir - {{ $order->order_number }} | Sari Rasa</title>
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

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-in { animation: fadeIn 0.5s ease-out forwards; }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-8px); }
        }
        .float { animation: float 3s ease-in-out infinite; }

        /* Pulse ring for QR */
        @keyframes pulseRing {
            0%   { transform: scale(1);   opacity: 0.6; }
            100% { transform: scale(1.6); opacity: 0; }
        }
        .pulse-ring {
            animation: pulseRing 2s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
        }

        /* Waiting dots */
        @keyframes dotBounce {
            0%, 80%, 100% { transform: scale(0.7); opacity: 0.4; }
            40%            { transform: scale(1.1); opacity: 1; }
        }
        .dot { animation: dotBounce 1.4s infinite ease-in-out both; }
        .dot:nth-child(1) { animation-delay: 0ms; }
        .dot:nth-child(2) { animation-delay: 160ms; }
        .dot:nth-child(3) { animation-delay: 320ms; }

        /* Progress bar */
        @keyframes progressPulse {
            0%, 100% { opacity: 0.5; }
            50%       { opacity: 1; }
        }
        .progress-pulse { animation: progressPulse 2s ease-in-out infinite; }

        /* Subtle QR scan line */
        @keyframes scanLine {
            0%   { top: 8px; }
            50%  { top: calc(100% - 8px); }
            100% { top: 8px; }
        }
        .scan-line { animation: scanLine 2.5s ease-in-out infinite; }
    </style>
</head>

<body class="min-h-screen">

    <!-- Background blobs -->
    <div class="fixed top-0 left-0 w-80 h-80 bg-amber-400/20 blur-[100px] rounded-full pointer-events-none -z-10"></div>
    <div class="fixed bottom-0 right-0 w-80 h-80 bg-blue-400/10 blur-[100px] rounded-full pointer-events-none -z-10"></div>

    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-10">
        <div class="w-full max-w-sm fade-in">

            <!-- Brand logo -->
            <div class="flex justify-center mb-5">
                <x-resto-logo size="md" class="drop-shadow-sm" />
            </div>

            <!-- Header badge -->
            <div class="text-center mb-6">
                <div class="inline-flex items-center gap-2 bg-amber-100 border border-amber-200 text-amber-800 px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest">
                    <span class="w-2 h-2 bg-amber-500 rounded-full animate-ping"></span>
                    Menunggu Konfirmasi Kasir
                </div>
            </div>

            <!-- Main card -->
            <div class="bg-white/97 backdrop-blur-xl rounded-[2.5rem] shadow-2xl shadow-slate-200/60 border border-white/80 overflow-hidden">

                <!-- Top accent -->
                <div class="h-1.5 bg-gradient-to-r from-amber-400 via-amber-500 to-amber-600"></div>

                <!-- Order info strip -->
                <div class="px-6 py-4 bg-gradient-to-r from-slate-50 to-white border-b border-slate-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 bg-amber-500 rounded-xl flex items-center justify-center shadow-md shadow-amber-500/30">
                                <svg class="w-4.5 h-4.5 w-[18px] h-[18px] text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Nomor Order</p>
                                <p class="text-sm font-black text-slate-800 tracking-wide">#{{ $order->order_number }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Meja</p>
                            <p class="text-sm font-black text-slate-800">{{ $order->table->number ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- QR Code section -->
                <div class="px-6 py-7 text-center">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em] mb-5">Tunjukkan ke Kasir</p>

                    <!-- QR wrapper with scan animation -->
                    <div class="relative inline-block mb-5">
                        <!-- Pulse rings -->
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <div class="w-52 h-52 rounded-[2rem] border-2 border-amber-400/40 pulse-ring absolute"></div>
                        </div>

                        <!-- QR card -->
                        <div class="relative bg-white p-4 rounded-[1.75rem] shadow-xl shadow-slate-200/70 border-2 border-amber-200/60 overflow-hidden">
                            <!-- Scan line animation -->
                            <div class="scan-line absolute left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-amber-400 to-transparent opacity-70 pointer-events-none z-10"></div>

                            {!! QrCode::size(180)->margin(1)->color(30, 41, 59)->generate($order->order_number) !!}

                            <!-- Corner decorators -->
                            <div class="absolute top-3 left-3 w-5 h-5 border-t-2 border-l-2 border-amber-500 rounded-tl-md"></div>
                            <div class="absolute top-3 right-3 w-5 h-5 border-t-2 border-r-2 border-amber-500 rounded-tr-md"></div>
                            <div class="absolute bottom-3 left-3 w-5 h-5 border-b-2 border-l-2 border-amber-500 rounded-bl-md"></div>
                            <div class="absolute bottom-3 right-3 w-5 h-5 border-b-2 border-r-2 border-amber-500 rounded-br-md"></div>
                        </div>
                    </div>

                    <!-- Waiting indicator -->
                    <div class="flex items-center justify-center gap-2 mb-1.5">
                        <div class="dot w-2.5 h-2.5 bg-amber-500 rounded-full"></div>
                        <div class="dot w-2.5 h-2.5 bg-amber-500 rounded-full"></div>
                        <div class="dot w-2.5 h-2.5 bg-amber-500 rounded-full"></div>
                    </div>
                    <p class="text-xs font-semibold text-slate-400">Menunggu konfirmasi dari kasir...</p>
                </div>

                <!-- Divider -->
                <div class="mx-6 border-t border-dashed border-slate-100"></div>

                <!-- Order summary -->
                <div class="px-6 py-5">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3.5">Ringkasan Pesanan</h3>
                    <div class="space-y-2.5">
                        @foreach($order->items as $item)
                            <div class="flex items-center gap-3">
                                <span class="w-7 h-7 bg-amber-50 border border-amber-100 rounded-lg flex items-center justify-center text-[10px] font-black text-amber-700 shrink-0">{{ $item->quantity }}x</span>
                                <span class="flex-1 text-sm font-semibold text-slate-700 truncate">{{ $item->menuItem->name }}</span>
                                <span class="text-sm font-bold text-slate-800 shrink-0">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Total -->
                    <div class="mt-4 pt-4 border-t border-dashed border-slate-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Total Tagihan</p>
                                <p class="text-2xl font-black text-amber-600 leading-tight">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-900 text-white text-[10px] font-black rounded-xl uppercase tracking-wider">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    {{ $order->payment_method === 'cashier' ? 'Kasir' : 'Online' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Progress bar -->
                <div class="px-6 pb-5">
                    <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-amber-400 to-amber-600 rounded-full progress-pulse w-2/3"></div>
                    </div>
                    <p class="text-[10px] text-slate-400 font-medium mt-1.5 text-center">Halaman ini akan otomatis berpindah setelah kasir konfirmasi</p>
                </div>

            </div>

            <!-- Help text -->
            <p class="text-center text-[11px] text-slate-400 font-medium mt-5 leading-relaxed px-4">
                Ada masalah? Panggil staff kami atau tunjukkan nomor order <span class="font-black text-slate-600">#{{ $order->order_number }}</span> ke kasir.
            </p>

        </div>
    </div>

    <script>
        // Poll every 3s to check cashier confirmation
        let pollInterval = setInterval(async () => {
            try {
                const res = await fetch('{{ route("customer.order.check-confirmation", $order->order_number) }}');
                const data = await res.json();
                if (data.confirmed) {
                    clearInterval(pollInterval);
                    window.location.href = '{{ route("customer.order.success", $order->order_number) }}';
                }
            } catch (e) {
                // silent fail, keep polling
            }
        }, 3000);

        window.addEventListener('beforeunload', () => clearInterval(pollInterval));
    </script>
</body>
</html>
