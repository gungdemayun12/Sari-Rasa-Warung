@forelse($orders as $order)
    <div x-data="{ expanded: false }"
        class="rounded-2xl border overflow-hidden hover:shadow-lg transition-all duration-200"
        style="background-color: var(--card-bg); border-color: var(--card-border);"
        data-order-id="{{ $order->id }}">

        {{-- Header row --}}
        <div class="px-5 py-4 cursor-pointer select-none" @click="expanded = !expanded">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0 flex-1">
                    @php
                        $statusStyles = [
                            'pending'              => ['bar' => 'bg-amber-400',   'dot' => 'bg-amber-400'],
                            'payment_success'      => ['bar' => 'bg-emerald-400', 'dot' => 'bg-emerald-400'],
                            'processing'           => ['bar' => 'bg-blue-400',    'dot' => 'bg-blue-400'],
                            'ready'                => ['bar' => 'bg-emerald-500', 'dot' => 'bg-emerald-500'],
                            'completed'            => ['bar' => 'bg-slate-300',   'dot' => 'bg-slate-300'],
                            'cancelled'            => ['bar' => 'bg-red-400',     'dot' => 'bg-red-400'],
                            'pending_payment'      => ['bar' => 'bg-amber-300',   'dot' => 'bg-amber-300'],
                            'pending_confirmation' => ['bar' => 'bg-orange-400',  'dot' => 'bg-orange-400'],
                        ];
                        $st = $statusStyles[$order->status] ?? ['bar' => 'bg-slate-200', 'dot' => 'bg-slate-200'];
                        $sc = [
                            'pending'              => 'bg-amber-100 text-amber-700 border-amber-200',
                            'payment_success'      => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                            'processing'           => 'bg-blue-100 text-blue-700 border-blue-200',
                            'ready'                => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                            'completed'            => 'bg-slate-100 text-slate-500 border-slate-200',
                            'cancelled'            => 'bg-red-100 text-red-600 border-red-200',
                            'pending_payment'      => 'bg-amber-50 text-amber-600 border-amber-100',
                            'pending_confirmation' => 'bg-orange-100 text-orange-700 border-orange-200',
                        ];
                    @endphp

                    {{-- Table number badge --}}
                    <div class="w-11 h-11 bg-slate-900 rounded-xl flex items-center justify-center relative overflow-hidden shrink-0 shadow-sm">
                        <span class="text-base font-black text-white relative z-10">{{ $order->table->number }}</span>
                        <div class="absolute bottom-0 left-0 right-0 h-1 {{ $st['bar'] }}"></div>
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-1.5 mb-0.5">
                            <h3 class="text-sm font-black tracking-tight" style="color: var(--text-primary);">{{ $order->order_number }}</h3>
                            <span class="px-2 py-0.5 text-[9px] font-black rounded-lg border {{ $sc[$order->status] ?? '' }} uppercase tracking-wider">{{ $order->status_label }}</span>
                        </div>
                        <p class="text-[11px] font-medium flex flex-wrap items-center gap-1.5" style="color: var(--text-tertiary);">
                            <span class="font-semibold" style="color: var(--text-secondary);">Meja {{ $order->table->number }}</span>
                            <span style="color: var(--text-muted);">·</span>
                            <span>{{ $order->items->count() }} item</span>
                            <span style="color: var(--text-muted);">·</span>
                            <span>{{ $order->created_at->format('H:i') }}</span>
                            @if($order->customer_name)
                                <span class="text-slate-200">·</span>
                                <span class="truncate max-w-[120px]">{{ $order->customer_name }}</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-black leading-none" style="color: var(--text-primary);">{{ $order->formatted_total }}</p>
                        <span class="text-[10px] font-bold mt-0.5 inline-block {{ $order->payment_method === 'online' ? 'text-blue-500' : 'text-emerald-600' }}">
                            {{ $order->payment_method === 'online' ? '⚡ Online' : '💵 Kasir' }}
                        </span>
                    </div>
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center border transition-transform duration-200" 
                         style="background-color: var(--bg-tertiary); border-color: var(--border-primary);"
                         :class="expanded ? 'rotate-180' : ''">
                        <svg class="w-4 h-4" style="color: var(--text-tertiary);" :class="expanded ? 'text-amber-500' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Expanded detail --}}
        <div x-show="expanded" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="border-t" style="border-color: var(--border-primary);">
            <div class="px-5 py-4 space-y-3">
                @foreach($order->items as $oi)
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 bg-amber-50 border border-amber-100 rounded-lg flex items-center justify-center text-[10px] font-black text-amber-700 shrink-0">{{ $oi->quantity }}x</span>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold truncate" style="color: var(--text-primary);">{{ $oi->menuItem->name }}</p>
                                @if($oi->notes)
                                    <p class="text-[10px] text-amber-600 italic">📝 {{ $oi->notes }}</p>
                                @endif
                            </div>
                        </div>
                        <p class="text-sm font-bold shrink-0" style="color: var(--text-secondary);">{{ $oi->formatted_subtotal }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Summary --}}
            <div class="mx-5 mb-4 rounded-xl p-3.5 border" style="background-color: var(--bg-tertiary); border-color: var(--border-primary);">
                <div class="flex justify-between text-xs mb-1.5" style="color: var(--text-secondary);">
                    <span>Subtotal</span>
                    <span class="font-semibold" style="color: var(--text-primary);">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-xs mb-2" style="color: var(--text-secondary);">
                    <span>PPN 11%</span>
                    <span class="font-semibold" style="color: var(--text-primary);">Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm font-black border-t pt-2" style="border-color: var(--border-secondary);">
                    <span style="color: var(--text-primary);">Total</span>
                    <span class="text-amber-600">{{ $order->formatted_total }}</span>
                </div>
            </div>

            @if($order->notes)
                <div class="mx-5 mb-4 flex items-start gap-2 text-xs text-amber-700 bg-amber-50 border border-amber-100 rounded-xl px-3 py-2.5">
                    <svg class="w-3.5 h-3.5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span>{{ $order->notes }}</span>
                </div>
            @endif

            {{-- Action buttons --}}
            @if(!in_array($order->status, ['completed', 'cancelled', 'pending_payment', 'pending_confirmation']))
                <div class="px-5 pb-4 pt-1 border-t border-slate-50 flex flex-wrap gap-2">
                    @if(in_array($order->status, ['pending', 'payment_success']))
                        <form method="POST" action="{{ role_route('admin.orders.update_status', $order) }}" class="inline-flex" onsubmit="return confirmStatusUpdate(event, 'Terima pesanan ini dan mulai masak?')">
                            @csrf
                            <input type="hidden" name="status" value="processing">
                            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-500 text-white text-[11px] font-black rounded-xl hover:bg-amber-600 transition-all shadow-sm shadow-amber-500/25 active:scale-95 uppercase tracking-wide">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                                Terima & Masak
                            </button>
                        </form>
                    @endif
                    @if(in_array($order->status, ['pending', 'payment_success', 'processing']))
                        <form method="POST" action="{{ role_route('admin.orders.update_status', $order) }}" class="inline-flex" onsubmit="return confirmStatusUpdate(event, 'Pesanan sudah siap saji?')">
                            @csrf
                            <input type="hidden" name="status" value="ready">
                            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-500 text-white text-[11px] font-black rounded-xl hover:bg-emerald-600 transition-all shadow-sm shadow-emerald-500/25 active:scale-95 uppercase tracking-wide">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                Siap Saji
                            </button>
                        </form>
                    @endif
                    @if(in_array($order->status, ['pending', 'processing', 'ready']))
                        <form method="POST" action="{{ role_route('admin.orders.update_status', $order) }}" class="inline-flex" onsubmit="return confirmStatusUpdate(event, 'Selesaikan pesanan ini?')">
                            @csrf
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-800 text-white text-[11px] font-black rounded-xl hover:bg-slate-900 transition-all shadow-sm active:scale-95 uppercase tracking-wide">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Selesai
                            </button>
                        </form>
                    @endif
                    @if(!in_array($order->status, ['completed', 'cancelled']))
                        <form method="POST" action="{{ role_route('admin.orders.update_status', $order) }}" class="inline-flex" onsubmit="return confirmStatusUpdate(event, 'Tolak pesanan ini?', 'warning')">
                            @csrf
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white text-red-500 border border-red-100 text-[11px] font-black rounded-xl hover:bg-red-50 hover:border-red-200 transition-all active:scale-95 uppercase tracking-wide">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                Tolak
                            </button>
                        </form>
                    @endif
                    <button type="button" onclick="openOrderDetail('{{ $order->id }}', '{{ role_route('admin.orders.data', $order) }}')" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 text-slate-600 text-[11px] font-black rounded-xl hover:bg-slate-200 transition-all active:scale-95 uppercase tracking-wide">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Detail
                    </button>
                </div>
            @elseif(in_array($order->status, ['pending_payment', 'pending_confirmation']))
                <div class="px-5 pb-4">
                    <div class="flex items-center gap-2 text-xs bg-amber-50 border border-amber-100 px-3 py-2.5 rounded-xl text-amber-700 font-semibold">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $order->status === 'pending_payment' ? 'Menunggu customer menyelesaikan pembayaran online' : 'Menunggu validasi kasir — scan barcode untuk proses' }}
                    </div>
                </div>
            @else
                <div class="px-5 pb-4">
                    <button type="button" onclick="openOrderDetail('{{ $order->id }}', '{{ role_route('admin.orders.data', $order) }}')" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 text-slate-500 text-[11px] font-black rounded-xl hover:bg-slate-200 transition-all active:scale-95 uppercase tracking-wide">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                        Lihat Detail
                    </button>
                </div>
            @endif
        </div>
    </div>
@empty
    <div class="bg-white rounded-2xl border border-slate-100 px-6 py-16 text-center col-span-full">
        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
        </div>
        <p class="text-slate-400 text-sm font-semibold">Tidak ada pesanan yang cocok</p>
        <p class="text-slate-300 text-xs mt-1">Coba ubah filter atau cari kata kunci lain</p>
    </div>
@endforelse
