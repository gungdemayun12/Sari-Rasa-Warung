@extends('layouts.customer')
@section('title', 'Checkout Pesanan')

@section('content')
    @php
        $phoneDigits = preg_replace('/\D+/', '', $customerData['phone'] ?? '');
        if (str_starts_with($phoneDigits, '62')) {
            $phoneDigits = substr($phoneDigits, 2);
        } elseif (str_starts_with($phoneDigits, '0')) {
            $phoneDigits = substr($phoneDigits, 1);
        }
        $prettyPhone = $phoneDigits
            ? '+62 ' . preg_replace('/^(\d{3})(\d{3,4})(\d+)$/', '$1-$2-$3', $phoneDigits)
            : ($customerData['phone'] ?? '-');
    @endphp

    <div x-data="checkoutApp()" x-init="init()" class="min-h-[100dvh] bg-slate-50 flex flex-col pb-36">

        {{-- ── Header ─────────────────────────────────────────────── --}}
        <header class="bg-white/95 backdrop-blur-xl border-b border-slate-100 sticky top-0 z-30 shadow-sm safe-area-top">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 py-3 flex items-center gap-3">
                <a href="{{ route('customer.menu', ['session' => $session->session_token]) }}"
                    aria-label="Kembali ke menu"
                    class="w-10 h-10 -ml-1 flex items-center justify-center text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-2xl transition-all active:scale-90 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>

                <x-resto-logo size="xs" class="shrink-0" />

                <div class="flex-1 min-w-0">
                    <h1 class="font-black text-slate-800 text-base leading-tight">Keranjang Pesanan</h1>
                    <p class="text-[11px] text-slate-400 font-semibold truncate"
                        x-text="cart.length > 0 ? totalItems + ' item · ' + formatRp(totalAmount) : 'Belum ada item'"></p>
                </div>

                <div class="hidden sm:flex items-center gap-2 shrink-0">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-full text-[11px] font-bold border border-emerald-100">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z"/></svg>
                        Meja {{ $session->table->number }}
                    </span>
                </div>
            </div>

            {{-- Mini progress --}}
            <div class="max-w-5xl mx-auto px-4 sm:px-6 pb-2.5">
                <div class="flex items-center gap-1.5 text-[10px] font-bold text-slate-400">
                    <span class="inline-flex items-center gap-1 text-emerald-600">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        Data diri
                    </span>
                    <span class="flex-1 h-px bg-emerald-200"></span>
                    <span class="inline-flex items-center gap-1 text-amber-600">
                        <span class="w-4 h-4 rounded-full bg-amber-500 text-white text-[9px] flex items-center justify-center">2</span>
                        Keranjang
                    </span>
                    <span class="flex-1 h-px bg-slate-200"></span>
                    <span class="inline-flex items-center gap-1">
                        <span class="w-4 h-4 rounded-full bg-slate-200 text-slate-500 text-[9px] flex items-center justify-center">3</span>
                        Bayar
                    </span>
                </div>
            </div>
        </header>

        <div class="flex-1 w-full max-w-5xl mx-auto px-4 sm:px-6 py-5">

            {{-- ── Empty state ───────────────────────────────────── --}}
            <template x-if="cart.length === 0">
                <div class="flex flex-col items-center justify-center py-16 sm:py-24 text-center px-4">
                    <div class="relative mb-6">
                        <div class="w-24 h-24 sm:w-28 sm:h-28 bg-gradient-to-br from-slate-100 to-slate-200 rounded-[2rem] flex items-center justify-center shadow-inner">
                            <svg class="w-12 h-12 sm:w-14 sm:h-14 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                            </svg>
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-amber-500 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/30">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                    </div>
                    <h2 class="text-xl font-black text-slate-800 mb-2">Keranjang Masih Kosong</h2>
                    <p class="text-sm text-slate-400 font-medium mb-7 max-w-[260px] leading-relaxed">Belum ada menu yang dipilih. Yuk, lihat menu favoritmu dulu!</p>
                    <a href="{{ route('customer.menu', ['session' => $session->session_token]) }}"
                        class="inline-flex items-center gap-2 px-7 py-3.5 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-black rounded-2xl hover:from-amber-600 hover:to-amber-700 transition-all shadow-xl shadow-amber-500/30 active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Lihat Menu
                    </a>
                </div>
            </template>

            {{-- ── Main layout ───────────────────────────────────── --}}
            <template x-if="cart.length > 0">
                <div class="grid grid-cols-1 md:grid-cols-[minmax(0,1fr)_20rem] xl:grid-cols-[minmax(0,1fr)_22rem] gap-4 lg:gap-6 items-start">

                    {{-- Left column --}}
                    <div class="space-y-4 min-w-0">

                        {{-- Customer --}}
                        <div class="bg-white rounded-2xl px-4 py-3 shadow-sm border border-slate-100 flex items-center gap-3">
                            <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-slate-800 truncate">{{ $customerData['name'] }}</p>
                                <p class="text-[11px] text-slate-400 truncate">{{ $prettyPhone }}</p>
                            </div>
                            <div class="shrink-0 flex items-center gap-1.5 bg-emerald-50 text-emerald-700 font-bold px-2.5 py-1.5 rounded-xl text-xs border border-emerald-100">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z"/></svg>
                                Meja {{ $session->table->number }}
                            </div>
                        </div>

                        {{-- Items --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                            <div class="px-4 py-3 border-b border-slate-50 flex items-center justify-between">
                                <h2 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    Pesanan Kamu
                                </h2>
                                <span class="text-[10px] font-bold text-slate-400 bg-slate-50 px-2 py-0.5 rounded-md" x-text="totalItems + ' item'"></span>
                            </div>

                            <div class="divide-y divide-slate-50">
                                <template x-for="(item, idx) in cart" :key="'item-' + item.id">
                                    <div class="px-4 py-4 flex gap-3">
                                        <div class="relative shrink-0">
                                            <img :src="item.image || fallbackImage" :alt="item.name" loading="lazy"
                                                x-on:error="$event.target.src = fallbackImage"
                                                class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl object-cover bg-slate-100 border border-slate-100">
                                            <span class="absolute -top-1.5 -right-1.5 min-w-5 h-5 px-1 bg-amber-500 text-white text-[10px] font-black rounded-full flex items-center justify-center shadow-sm border-2 border-white" x-text="item.qty"></span>
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between gap-2">
                                                <h3 class="font-bold text-slate-800 text-sm leading-tight line-clamp-2" x-text="item.name"></h3>
                                                <span class="font-bold text-slate-800 text-sm whitespace-nowrap" x-text="formatRp(item.price * item.qty)"></span>
                                            </div>
                                            <p class="text-[11px] text-slate-400 mt-0.5" x-text="formatRp(item.price) + ' / porsi'"></p>

                                            <label class="sr-only" :for="'note-' + item.id">Catatan item</label>
                                            <input type="text" :id="'note-' + item.id" x-model="item.notes" @change="saveCart()"
                                                maxlength="100" placeholder="Catatan (opsional): pedas, tanpa es..."
                                                class="w-full mt-2 px-2.5 py-2 text-[11px] bg-slate-50 border border-slate-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:bg-white transition-colors placeholder:text-slate-300 text-slate-700">

                                            <div class="flex items-center justify-between mt-3">
                                                <button type="button" @click="removeItem(idx)"
                                                    class="inline-flex items-center gap-1.5 text-[11px] text-red-400 hover:text-red-600 hover:bg-red-50 font-bold px-2 py-1.5 -ml-2 rounded-xl transition-all">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    Hapus
                                                </button>
                                                <div class="flex items-center gap-0.5 bg-slate-100 rounded-2xl p-0.5 border border-slate-200/50">
                                                    <button type="button" @click="updateQty(idx, -1)" aria-label="Kurangi jumlah"
                                                        class="w-8 h-8 bg-white text-slate-600 rounded-xl flex items-center justify-center shadow-sm hover:bg-red-50 hover:text-red-500 active:scale-90 transition-all">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"/></svg>
                                                    </button>
                                                    <span class="w-8 text-center text-sm font-black text-slate-800" x-text="item.qty"></span>
                                                    <button type="button" @click="updateQty(idx, 1)" aria-label="Tambah jumlah"
                                                        class="w-8 h-8 bg-gradient-to-br from-amber-500 to-amber-600 text-white rounded-xl flex items-center justify-center shadow-sm hover:from-amber-600 hover:to-amber-700 active:scale-90 transition-all">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v12m6-6H6"/></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Order notes --}}
                        <div class="bg-white rounded-2xl px-4 py-3.5 shadow-sm border border-slate-100">
                            <div class="flex items-center justify-between mb-2">
                                <label for="order-notes" class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Catatan Pesanan
                                </label>
                                <span class="text-[10px] font-bold text-slate-400" x-text="orderNotes.length + '/300'"></span>
                            </div>
                            <textarea id="order-notes" x-model="orderNotes" maxlength="300" rows="2"
                                placeholder="Contoh: jangan terlalu manis, siapkan alat makan tambahan..."
                                class="w-full px-3 py-2.5 text-sm bg-slate-50 border border-slate-100 rounded-xl focus:ring-2 focus:ring-amber-500/30 focus:bg-white transition-all outline-none placeholder:text-slate-300 resize-none text-slate-700"></textarea>
                        </div>
                    </div>

                    {{-- Right column --}}
                    <div class="space-y-4 md:sticky md:top-36 min-w-0">

                        {{-- Payment method --}}
                        <div class="bg-white rounded-2xl px-4 py-3.5 shadow-sm border border-slate-100">
                            <h2 class="text-xs font-black text-slate-800 mb-3 flex items-center gap-1.5 uppercase tracking-wider">
                                <div class="w-6 h-6 bg-amber-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                Metode Pembayaran
                            </h2>
                            <div class="space-y-2.5">

                                <label class="relative flex items-center p-3.5 border-2 rounded-2xl cursor-pointer transition-all duration-200 group"
                                    :class="paymentMethod === 'online' ? 'border-amber-500 bg-amber-50/50 shadow-sm shadow-amber-100' : 'border-slate-100 hover:border-amber-200 hover:bg-amber-50/30'">
                                    <input type="radio" x-model="paymentMethod" value="online" class="sr-only">
                                    <div class="w-11 h-11 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl flex items-center justify-center text-white mr-3.5 shadow-md shadow-amber-500/25 shrink-0 transition-transform group-hover:scale-105">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <span class="block font-black text-slate-800 text-sm">Bayar Online</span>
                                        <span class="block text-[10px] text-slate-400 font-medium mt-0.5">QRIS · GoPay · OVO · Transfer</span>
                                    </div>
                                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center ml-2 shrink-0 transition-all"
                                        :class="paymentMethod === 'online' ? 'border-amber-500 bg-amber-500' : 'border-slate-300'">
                                        <svg x-show="paymentMethod === 'online'" class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    </div>
                                </label>

                                <label class="relative flex items-center p-3.5 border-2 rounded-2xl cursor-pointer transition-all duration-200 group"
                                    :class="paymentMethod === 'cashier' ? 'border-amber-500 bg-amber-50/50 shadow-sm shadow-amber-100' : 'border-slate-100 hover:border-amber-200 hover:bg-amber-50/30'">
                                    <input type="radio" x-model="paymentMethod" value="cashier" class="sr-only">
                                    <div class="w-11 h-11 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-2xl flex items-center justify-center text-white mr-3.5 shadow-md shadow-emerald-500/25 shrink-0 transition-transform group-hover:scale-105">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <span class="block font-black text-slate-800 text-sm">Bayar di Kasir</span>
                                        <span class="block text-[10px] text-slate-400 font-medium mt-0.5">Tunai · Debit · Kredit</span>
                                    </div>
                                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center ml-2 shrink-0 transition-all"
                                        :class="paymentMethod === 'cashier' ? 'border-amber-500 bg-amber-500' : 'border-slate-300'">
                                        <svg x-show="paymentMethod === 'cashier'" class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Summary --}}
                        <div class="bg-white rounded-2xl px-4 py-4 shadow-sm border border-slate-100 relative overflow-hidden">
                            <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-50 rounded-full blur-2xl pointer-events-none"></div>
                            <h2 class="text-xs font-bold text-slate-800 mb-3 flex items-center gap-1.5 relative">
                                <div class="w-6 h-6 bg-amber-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                </div>
                                Ringkasan Pembayaran
                            </h2>
                            <div class="space-y-2 relative">
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-400 font-medium" x-text="'Subtotal (' + totalItems + ' item)'"></span>
                                    <span class="font-semibold text-slate-700" x-text="formatRp(subtotal)"></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-400 font-medium">Pajak (11%)</span>
                                    <span class="font-semibold text-slate-700" x-text="formatRp(tax)"></span>
                                </div>
                            </div>
                            <div class="border-t border-dashed border-slate-200 mt-3.5 pt-3.5 flex justify-between items-center relative">
                                <span class="font-black text-slate-800 text-sm">Total Tagihan</span>
                                <span class="text-2xl font-black text-amber-600" x-text="formatRp(totalAmount)"></span>
                            </div>
                            <p class="mt-3 text-[10px] text-slate-400 font-medium leading-relaxed flex items-start gap-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0 mt-px" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Harga sudah termasuk pajak. Pembayaran diproses aman.
                            </p>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        {{-- ── Sticky action bar ─────────────────────────────────── --}}
        <div x-show="cart.length > 0"
            class="fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-xl border-t border-slate-100 px-4 py-3.5 safe-area-bottom shadow-[0_-8px_30px_rgba(0,0,0,0.08)]"
            x-cloak>
            <div class="max-w-5xl mx-auto flex items-center gap-3 sm:gap-4">
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Total Pembayaran</p>
                    <p class="text-lg sm:text-xl font-black text-amber-600 leading-tight" x-text="formatRp(totalAmount)"></p>
                </div>
                <button type="button" @click="confirmOrder()" :disabled="submitting || cart.length === 0"
                    class="shrink-0 flex items-center justify-center gap-2.5 px-5 sm:px-7 py-3.5 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-black text-sm rounded-2xl hover:from-amber-600 hover:to-amber-700 transition-all shadow-xl shadow-amber-500/35 disabled:opacity-50 disabled:cursor-wait active:scale-[0.98] min-w-[9.5rem]">
                    <svg x-show="!submitting" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <svg x-show="submitting" x-cloak class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    <span x-show="!submitting">Pesan Sekarang</span>
                    <span x-show="submitting" x-cloak>Memproses...</span>
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function checkoutApp() {
                return {
                    cart: [],
                    orderNotes: '',
                    paymentMethod: 'online',
                    submitting: false,
                    fallbackImage: 'data:image/svg+xml;utf8,' + encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" width="96" height="96"><rect width="96" height="96" fill="#f1f5f9"/><path d="M30 56h36M38 44h20" stroke="#cbd5e1" stroke-width="4" stroke-linecap="round"/></svg>'),

                    init() {
                        let stored = [];
                        try {
                            stored = JSON.parse(localStorage.getItem('cart_{{ $session->session_token }}') || '[]');
                        } catch (_) {
                            stored = [];
                        }

                        // Sanitize stored cart so a broken localStorage entry cannot crash the page.
                        this.cart = (Array.isArray(stored) ? stored : [])
                            .filter(i => i && i.id && Number.isFinite(Number(i.price)) && Number(i.price) > 0)
                            .map(i => ({
                                id: i.id,
                                name: String(i.name || 'Menu'),
                                price: Number(i.price),
                                image: i.image || null,
                                qty: Math.min(99, Math.max(1, parseInt(i.qty, 10) || 1)),
                                notes: String(i.notes || '').slice(0, 100),
                            }));

                        this.saveCart();
                    },

                    get totalItems() { return this.cart.reduce((s, i) => s + i.qty, 0); },
                    get subtotal() { return this.cart.reduce((s, i) => s + (i.price * i.qty), 0); },
                    get tax() { return Math.round(this.subtotal * 0.11); },
                    get totalAmount() { return this.subtotal + this.tax; },

                    formatRp(n) { return 'Rp ' + Math.round(Number(n) || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); },

                    escapeHtml(value) {
                        return String(value ?? '')
                            .replace(/&/g, '&amp;')
                            .replace(/</g, '&lt;')
                            .replace(/>/g, '&gt;')
                            .replace(/"/g, '&quot;')
                            .replace(/'/g, '&#039;');
                    },

                    async updateQty(idx, delta) {
                        if (!this.cart[idx]) return;
                        const next = this.cart[idx].qty + delta;

                        if (next <= 0) {
                            await this.removeItem(idx);
                            return;
                        }

                        this.cart[idx].qty = Math.min(99, next);
                        this.saveCart();
                    },

                    async removeItem(idx) {
                        if (!this.cart[idx]) return;

                        if (this.cart.length === 1) {
                            const result = await Swal.fire({
                                title: 'Kosongkan Keranjang?',
                                text: 'Ini item terakhir di keranjangmu.',
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#ef4444',
                                cancelButtonColor: '#64748b',
                                confirmButtonText: 'Ya, kosongkan',
                                cancelButtonText: 'Batal',
                                reverseButtons: true,
                            });

                            if (result.isConfirmed) {
                                this.cart.splice(idx, 1);
                                this.saveCart();
                            }
                            return;
                        }

                        this.cart.splice(idx, 1);
                        this.saveCart();
                    },

                    saveCart() {
                        localStorage.setItem('cart_{{ $session->session_token }}', JSON.stringify(this.cart));
                    },

                    validateCart() {
                        if (!Array.isArray(this.cart) || this.cart.length === 0) {
                            return 'Keranjang masih kosong.';
                        }

                        for (const item of this.cart) {
                            if (!item.id || item.qty < 1 || item.qty > 99) {
                                return 'Ada jumlah item yang tidak valid (1–99).';
                            }
                            if (!Number.isFinite(Number(item.price)) || Number(item.price) <= 0) {
                                return 'Harga item tidak valid. Silakan kembali ke menu.';
                            }
                        }

                        if ((this.orderNotes || '').length > 300) {
                            return 'Catatan pesanan maksimal 300 karakter.';
                        }

                        return null;
                    },

                    async confirmOrder() {
                        if (this.submitting) return;

                        const invalid = this.validateCart();
                        if (invalid) {
                            showToast(invalid, 'error');
                            return;
                        }

                        const itemsHtml = this.cart.map(item => `
                            <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid #f1f5f9;">
                                <img src="${this.escapeHtml(item.image || this.fallbackImage)}" alt="" style="width:44px;height:44px;border-radius:10px;object-fit:cover;flex-shrink:0;background:#f8fafc;">
                                <div style="flex:1;min-width:0;text-align:left;">
                                    <p style="font-size:13px;font-weight:700;color:#1e293b;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${this.escapeHtml(item.name)}</p>
                                    <p style="font-size:11px;color:#94a3b8;margin:2px 0 0;">${this.formatRp(item.price)} × ${item.qty}</p>
                                    ${item.notes ? `<p style="font-size:10px;color:#d97706;margin:2px 0 0;font-style:italic;">📝 ${this.escapeHtml(item.notes)}</p>` : ''}
                                </div>
                                <span style="font-size:13px;font-weight:800;color:#0f172a;flex-shrink:0;">${this.formatRp(item.price * item.qty)}</span>
                            </div>
                        `).join('');

                        const payLabel = this.paymentMethod === 'online' ? 'Bayar Online (QRIS/e-Wallet)' : 'Bayar di Kasir';

                        const html = `
                            <div style="text-align:left;font-family:'Plus Jakarta Sans',sans-serif;">
                                <div style="background:#f8fafc;border-radius:16px;padding:4px 12px;margin-bottom:12px;max-height:240px;overflow-y:auto;">
                                    ${itemsHtml}
                                </div>
                                <div style="background:#fffbeb;border-radius:12px;padding:12px 14px;border:1px solid #fde68a;">
                                    <div style="display:flex;justify-content:space-between;font-size:12px;color:#78716c;margin-bottom:4px;">
                                        <span>Subtotal (${this.totalItems} item)</span>
                                        <span style="font-weight:600;">${this.formatRp(this.subtotal)}</span>
                                    </div>
                                    <div style="display:flex;justify-content:space-between;font-size:12px;color:#78716c;margin-bottom:8px;">
                                        <span>Pajak (11%)</span>
                                        <span style="font-weight:600;">${this.formatRp(this.tax)}</span>
                                    </div>
                                    <div style="display:flex;justify-content:space-between;align-items:center;border-top:1px dashed #fde68a;padding-top:8px;">
                                        <span style="font-size:12px;font-weight:800;color:#1e293b;text-transform:uppercase;letter-spacing:0.05em;">Total</span>
                                        <span style="font-size:22px;font-weight:900;color:#d97706;">${this.formatRp(this.totalAmount)}</span>
                                    </div>
                                </div>
                                <div style="margin-top:10px;display:flex;align-items:center;gap:6px;font-size:11px;color:#64748b;background:#f1f5f9;border-radius:10px;padding:8px 12px;">
                                    <span>${this.paymentMethod === 'online' ? '⚡' : '💵'}</span>
                                    <span>${this.escapeHtml(payLabel)}</span>
                                </div>
                                ${this.orderNotes ? `<div style="margin-top:8px;font-size:11px;color:#92400e;background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:8px 12px;">Catatan: ${this.escapeHtml(this.orderNotes)}</div>` : ''}
                            </div>
                        `;

                        const result = await Swal.fire({
                            title: 'Konfirmasi Pesanan',
                            html: html,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, Pesan Sekarang',
                            cancelButtonText: 'Cek Lagi',
                            confirmButtonColor: '#f59e0b',
                            cancelButtonColor: '#64748b',
                            reverseButtons: true,
                            width: '400px',
                        });

                        if (result.isConfirmed) {
                            this.submitOrder();
                        }
                    },

                    async submitOrder() {
                        if (this.submitting) return;

                        const invalid = this.validateCart();
                        if (invalid) {
                            showToast(invalid, 'error');
                            return;
                        }

                        this.submitting = true;

                        try {
                            // Refresh CSRF token
                            try {
                                const tokenResponse = await fetch('/csrf-token', {
                                    method: 'GET',
                                    headers: { 'Accept': 'application/json' }
                                });
                                if (tokenResponse.ok) {
                                    const tokenData = await tokenResponse.json();
                                    document.querySelector('meta[name="csrf-token"]').content = tokenData.token;
                                }
                            } catch (_) {}

                            const response = await fetch('{{ route('customer.checkout.process') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    session_token: '{{ $session->session_token }}',
                                    items: this.cart.map(i => ({
                                        menu_item_id: i.id,
                                        quantity: i.qty,
                                        notes: (i.notes || '').trim() || null
                                    })),
                                    notes: (this.orderNotes || '').trim() || null,
                                    payment_method: this.paymentMethod
                                })
                            });

                            if (response.status === 419) {
                                showToast('Sesi halaman kedaluwarsa. Muat ulang halaman lalu coba lagi.', 'error');
                                this.submitting = false;
                                return;
                            }

                            let data = {};
                            try { data = await response.json(); } catch (_) {}

                            if (!response.ok) {
                                const firstError = data.errors ? Object.values(data.errors)[0]?.[0] : null;
                                showToast(firstError || data.message || 'Gagal mengirim pesanan. Coba lagi.', 'error');
                                this.submitting = false;
                                return;
                            }

                            if (data.success) {
                                localStorage.removeItem('cart_{{ $session->session_token }}');
                                this.cart = [];
                                window.location.href = data.redirect;
                                return;
                            }

                            showToast(data.message || 'Gagal mengirim pesanan', 'error');
                            this.submitting = false;
                        } catch (e) {
                            showToast('Terjadi kesalahan koneksi. Coba lagi.', 'error');
                            this.submitting = false;
                        }
                    }
                };
            }
        </script>
    @endpush
@endsection
