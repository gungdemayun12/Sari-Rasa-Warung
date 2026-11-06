@extends('layouts.customer')
@section('title', 'Pembayaran Online')

@section('content')
<div class="min-h-[100dvh] bg-slate-50 flex flex-col items-center justify-center px-4 py-8 sm:py-12 safe-area-bottom">

    <!-- Background blobs -->
    <div class="fixed top-0 left-0 w-80 h-80 bg-amber-400/15 blur-[120px] rounded-full pointer-events-none -z-10"></div>
    <div class="fixed bottom-0 right-0 w-80 h-80 bg-blue-400/10 blur-[120px] rounded-full pointer-events-none -z-10"></div>

    <div class="w-full max-w-md">

        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <x-resto-logo size="md" class="drop-shadow-md" />
        </div>

        <!-- Main card -->
        <div class="bg-white/97 backdrop-blur-xl rounded-[2.5rem] shadow-2xl shadow-slate-200/60 border border-white/80 overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-amber-400 via-amber-500 to-amber-600"></div>

            <div class="p-5 sm:p-7">
                <!-- Header -->
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-11 h-11 bg-gradient-to-br from-amber-400 to-amber-600 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/30 shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="font-black text-slate-800 text-base leading-tight">Pembayaran Online</h1>
                        <p class="text-[11px] text-slate-400 font-medium">Order <span class="font-black text-slate-600">#{{ $order->order_number }}</span></p>
                    </div>
                </div>

                <!-- Order summary -->
                <div class="bg-slate-50 rounded-2xl p-4 mb-5 space-y-2.5 border border-slate-100">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500 font-medium flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5z"/></svg>
                            Meja
                        </span>
                        <span class="font-black text-slate-800">{{ $order->table->number }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500 font-medium flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            Jumlah Item
                        </span>
                        <span class="font-bold text-slate-700">{{ $order->items->count() }} item</span>
                    </div>
                    <div class="border-t border-dashed border-slate-200 pt-2.5 flex items-center justify-between">
                        <span class="text-slate-500 font-medium text-sm">Total Tagihan</span>
                        <span class="font-black text-amber-600 text-lg">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if($order->status === 'pending_payment' && $order->snap_token)
                    <!-- Payment button -->
                    <button id="pay-button"
                        class="group w-full flex items-center justify-center gap-3 py-4 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-black text-sm rounded-2xl hover:from-amber-600 hover:to-amber-700 transition-all shadow-xl shadow-amber-500/30 active:scale-[0.98] hover:-translate-y-0.5 mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <span>Lanjutkan Pembayaran</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </button>

                    <!-- Payment methods info -->
                    <div class="flex items-center justify-center gap-1 mb-3">
                        <div class="h-px flex-1 bg-slate-100"></div>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider px-2">Metode Tersedia</span>
                        <div class="h-px flex-1 bg-slate-100"></div>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mb-5">
                        @foreach([
                            ['label' => 'QRIS', 'icon' => 'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z', 'color' => 'text-amber-600 bg-amber-50'],
                            ['label' => 'e-Wallet', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'color' => 'text-blue-600 bg-blue-50'],
                            ['label' => 'Transfer', 'icon' => 'M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z', 'color' => 'text-emerald-600 bg-emerald-50'],
                            ['label' => 'Kartu', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'color' => 'text-purple-600 bg-purple-50'],
                        ] as $pm)
                            <div class="flex flex-col items-center gap-1 p-2.5 rounded-xl border border-slate-100 bg-white">
                                <div class="w-7 h-7 {{ $pm['color'] }} rounded-lg flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $pm['icon'] }}"/></svg>
                                </div>
                                <span class="text-[9px] font-black text-slate-500 uppercase tracking-wide">{{ $pm['label'] }}</span>
                            </div>
                        @endforeach
                    </div>

                @else
                    <!-- Already paid -->
                    <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-5 mb-5">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-emerald-100 rounded-2xl flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <p class="font-black text-emerald-800 text-sm">Pembayaran Berhasil</p>
                                <p class="text-[11px] text-emerald-600 font-medium">Pesanan langsung diproses dapur</p>
                            </div>
                        </div>
                        <a href="{{ route('order.tracking', ['order' => $order->id]) }}"
                            class="flex items-center justify-center gap-2 w-full py-3 bg-emerald-600 text-white font-bold text-sm rounded-xl hover:bg-emerald-700 transition-all active:scale-[0.98]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Pantau Status Pesanan
                        </a>
                    </div>
                @endif

                <!-- Switch to cashier -->
                <a href="{{ route('customer.order.switch-cashier', ['order' => $order->order_number]) }}"
                    class="flex items-center justify-center gap-1.5 text-xs text-slate-400 hover:text-slate-600 font-semibold transition-colors py-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    Ganti ke Pembayaran di Kasir
                </a>
            </div>
        </div>

        <!-- Security badge -->
        <p class="text-center text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-5 flex items-center justify-center gap-2">
            <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            Pembayaran Terenkripsi & Aman
        </p>
    </div>
</div>

@push('scripts')
@if(config('services.midtrans.is_production'))
<script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
@else
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
@endif
<script>
    async function verifyPaymentAndRedirect() {
        try {
            const res = await fetch('{{ route('customer.order.verify-payment', ['order' => $order->order_number]) }}', {
                headers: { 'Accept': 'application/json' }
            });
            if (res.ok) {
                const data = await res.json();
                if (data.status && data.status !== 'pending_payment') {
                    window.location.href = "{{ route('customer.order.success', ['order' => $order->order_number]) }}";
                    return;
                }
            }
        } catch(e) {}
        window.location.href = "{{ route('order.tracking', ['order' => $order->id]) }}";
    }

    const payBtn = document.getElementById('pay-button');
    if (payBtn) {
        payBtn.addEventListener('click', function() {
            window.snap.pay('{{ $order->snap_token ?? '' }}', {
                onSuccess: () => verifyPaymentAndRedirect(),
                onPending: () => verifyPaymentAndRedirect(),
                onError:   () => showToast('Pembayaran gagal. Silakan coba lagi.', 'error'),
                onClose:   () => window.location.href = "{{ route('order.tracking', ['order' => $order->id]) }}"
            });
        });
    }
</script>
@endpush
@endsection
