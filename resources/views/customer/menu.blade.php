@extends('layouts.customer')
@section('title', 'Menu Utama')

@section('content')
    <div x-data="menuListApp()" class="min-h-[100dvh] bg-slate-50 pb-40">

            <div class="sticky top-0 z-40">
            <header class="bg-white/90 backdrop-blur-xl border-b border-slate-100 safe-area-top shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <x-resto-logo size="menu-header" class="flex-shrink-0" />
                        <div class="flex flex-wrap items-center gap-1.5 min-w-0">
                            <span
                                class="inline-flex items-center gap-1 max-w-[9.5rem] sm:max-w-[14rem] text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-1 rounded-full border border-amber-100">
                                <svg class="w-2.5 h-2.5 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z" />
                                </svg>
                                <span class="truncate">{{ $customerData['name'] }}</span>
                            </span>
                            <span
                                class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-1 rounded-full border border-emerald-100">
                                Meja {{ $table->number }}
                            </span>
                        </div>
                    </div>

                    <a href="{{ route('customer.checkout', ['session' => $session->session_token]) }}" id="cart-button-top"
                        aria-label="Buka keranjang"
                        class="relative flex items-center justify-center w-11 h-11 sm:w-12 sm:h-12 bg-white border border-slate-200 shadow-lg shadow-slate-200/50 rounded-2xl text-amber-600 hover:bg-amber-50 hover:border-amber-300 hover:shadow-amber-100 transition-all active:scale-90 group z-50 shrink-0">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span x-show="totalItems > 0"
                            class="absolute -top-2 -right-2 min-w-5 h-5 px-1 bg-red-500 text-white text-[9px] font-black rounded-full flex items-center justify-center shadow-lg border-2 border-white"
                            x-text="totalItems" x-cloak></span>
                    </a>
                </div>
            </header>

            <div class="bg-white/95 backdrop-blur-md border-b border-slate-100 shadow-sm">

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-3 pb-1.5">
                    <div class="relative group max-w-xl">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" x-model="search" @input.debounce.300ms="startLoading()"
                            placeholder="Cari menu kesukaanmu..."
                            class="w-full pl-10 pr-10 py-2.5 bg-slate-100 border-none rounded-xl text-sm font-semibold focus:ring-4 focus:ring-amber-500/10 focus:bg-white transition-all placeholder-slate-400 text-slate-700 shadow-inner">

                        <button x-show="search.length > 0" @click="search = ''; startLoading()"
                            aria-label="Hapus pencarian"
                            class="absolute right-3 top-1/2 -translate-y-1/2 w-6 h-6 flex items-center justify-center bg-slate-200 text-slate-500 rounded-full hover:bg-slate-300 transition-colors"
                            x-cloak>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 flex gap-2 overflow-x-auto no-scrollbar">
                    <button type="button" @click="selectCategory('all')"
                        :class="activeCategory === 'all' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/25 ring-2 ring-amber-500/10' : 'bg-white text-slate-500 border border-slate-200 hover:border-amber-300 hover:text-amber-600 shadow-sm'"
                        class="min-w-fit px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-[0.14em] whitespace-nowrap transition-all duration-200">Semua</button>
                    @foreach($categories as $cat)
                        <button type="button" @click="selectCategory('{{ $cat->id }}')"
                            :class="activeCategory === '{{ $cat->id }}' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/25 ring-2 ring-amber-500/10' : 'bg-white text-slate-500 border border-slate-200 hover:border-amber-300 hover:text-amber-600 shadow-sm'"
                            class="min-w-fit px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-[0.14em] whitespace-nowrap transition-all duration-200">
                            {{ $cat->icon }} {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
            </div>
            </div>

            @if($activeOrder)
                @php
                    $activeOrderRoute = match ($activeOrder->status) {
                        'pending_confirmation' => route('customer.order.barcode', ['order' => $activeOrder->order_number]),
                        'pending_payment' => route('customer.payment', ['order' => $activeOrder->order_number]),
                        default => route('order.tracking', ['order' => $activeOrder->id]),
                    };
                    $orderTheme = $activeOrder->status_theme;
                @endphp
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <a href="{{ $activeOrderRoute }}"
                        class="flex items-center gap-4 bg-white border {{ $orderTheme['landing_border'] }} p-4 rounded-3xl shadow-xl {{ $orderTheme['landing_shadow'] }} {{ $orderTheme['landing_hover_border'] }} transition-all group overflow-hidden relative">
                        <div class="absolute top-0 right-0 p-2 opacity-5">
                            <x-order-status-icon :icon="$orderTheme['icon']" class="w-20 h-20 {{ $orderTheme['landing_accent'] }}" />
                        </div>
                        <div
                            class="w-12 h-12 {{ $orderTheme['landing_icon_bg'] }} rounded-2xl flex items-center justify-center text-white shadow-lg {{ $orderTheme['landing_icon_shadow'] }} group-hover:rotate-12 transition-transform">
                            <x-order-status-icon :icon="$orderTheme['icon']" class="w-7 h-7 text-white animate-pulse" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] font-black {{ $orderTheme['landing_accent'] }} uppercase tracking-[0.2em]">
                                    Pesanan Berlangsung</p>
                                <span class="text-[10px] font-bold text-slate-400">#{{ $activeOrder->order_number }}</span>
                            </div>
                            <p class="text-sm font-black text-slate-800 mt-0.5">
                                Status: <span
                                    class="{{ $orderTheme['landing_status'] }} uppercase">{{ $activeOrder->status_label }}</span>
                            </p>
                        </div>
                        <div
                            class="p-2 text-slate-300 {{ $orderTheme['landing_hover_arrow'] }} group-hover:translate-x-1 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </a>
                </div>
            @endif

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 sm:py-7">

                <template x-if="isLoading">
                    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4 lg:gap-5">
                        @for($i = 0; $i < 6; $i++)
                            <div class="bg-white rounded-2xl p-3 shadow-sm border border-slate-100">
                                <div class="aspect-[4/3] rounded-xl skeleton mb-3"></div>
                                <div class="h-4 w-3/4 skeleton rounded-full mb-2"></div>
                                <div class="h-3 w-1/2 skeleton rounded-full mb-3"></div>
                                <div class="flex justify-between items-center">
                                    <div class="h-5 w-1/3 skeleton rounded-full"></div>
                                    <div class="h-9 w-9 skeleton rounded-xl"></div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </template>

                <div x-show="!isLoading" x-cloak>
                    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4 lg:gap-5 menu-grid">
                        @foreach($menuItems as $item)
                            <div x-show="'{{ strtolower(addslashes($item->name)) }}'.includes(search.toLowerCase())"
                                x-transition:enter="transition ease-out duration-400"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                class="menu-item bg-white rounded-3xl border border-slate-100/80 shadow-sm overflow-hidden transition-all duration-300 group relative {{ !$item->is_available ? 'grayscale opacity-50' : 'hover:shadow-2xl hover:shadow-amber-500/15 hover:-translate-y-1.5 hover:border-amber-200/80 active:scale-[0.98] cursor-pointer' }}">

                                {{-- Image area --}}
                                <div class="relative aspect-[4/3] overflow-hidden bg-gradient-to-br from-slate-100 to-slate-200">
                                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}" loading="lazy"
                                        class="w-full h-full object-cover {{ $item->is_available ? 'group-hover:scale-110' : '' }} transition-transform duration-700 ease-out">

                                    {{-- Subtle gradient overlay on hover --}}
                                    @if($item->is_available)
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    @endif

                                    {{-- Labels --}}
                                    @if($item->is_available && $item->labels && count($item->labels) > 0)
                                        <div class="absolute top-2.5 left-2.5 flex flex-wrap gap-1">
                                            @foreach(array_slice($item->labels, 0, 2) as $label)
                                                @php
                                                    $lc = [
                                                        'best_seller' => 'bg-amber-500',
                                                        'recommended' => 'bg-blue-500',
                                                        'vegetarian'  => 'bg-green-500',
                                                        'spicy'       => 'bg-red-500',
                                                        'halal'       => 'bg-emerald-600'
                                                    ];
                                                    $icons = [
                                                        'best_seller' => '⭐',
                                                        'recommended' => '👍',
                                                        'vegetarian'  => '🌿',
                                                        'spicy'       => '🌶️',
                                                        'halal'       => '✓'
                                                    ];
                                                @endphp
                                                <span class="inline-flex items-center gap-0.5 px-2 py-0.5 text-[9px] font-black text-white rounded-lg shadow-lg {{ $lc[$label] ?? 'bg-slate-500' }} uppercase tracking-wider backdrop-blur-sm">
                                                    <span>{{ $icons[$label] ?? '' }}</span>
                                                    <span>{{ str_replace('_', ' ', $label) }}</span>
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif

                                    {{-- Sold out overlay --}}
                                    @if(!$item->is_available)
                                        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px] flex items-center justify-center">
                                            <div class="-rotate-12">
                                                <div class="border-2 border-red-400 rounded-lg px-3 py-1.5 shadow-lg">
                                                    <span class="text-red-400 font-black text-xs uppercase tracking-[0.2em]">Habis</span>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        {{-- Est. time badge --}}
                                        <div class="absolute bottom-2.5 right-2.5 flex items-center gap-1 px-2 py-1 bg-black/60 backdrop-blur-md text-white text-[9px] font-bold rounded-lg border border-white/10">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $item->estimated_time }}m
                                        </div>
                                    @endif
                                </div>

                                {{-- Card body --}}
                                <div class="p-3 sm:p-4">
                                    <h3 class="text-[13px] sm:text-sm font-bold {{ $item->is_available ? 'text-slate-800' : 'text-slate-400' }} leading-snug line-clamp-1 mb-0.5 group-hover:text-amber-700 transition-colors">{{ $item->name }}</h3>
                                    <p class="text-[11px] text-slate-400 line-clamp-1 mb-3 leading-relaxed">{{ $item->description }}</p>

                                    <div class="flex items-center justify-between gap-2">
                                        <div>
                                            <p class="text-[14px] font-black {{ $item->is_available ? 'text-amber-600' : 'text-slate-400' }} leading-none">{{ $item->formatted_price }}</p>
                                        </div>

                                        @if(!$item->is_available)
                                            <div class="w-9 h-9 bg-slate-100 text-slate-300 rounded-2xl flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                            </div>
                                        @else
                                            <div class="relative shrink-0">
                                                {{-- Add button --}}
                                                <button x-show="getItemQty({{ $item->id }}) === 0"
                                                    @click="addToCart({{ json_encode(['id' => $item->id, 'name' => $item->name, 'price' => $item->price, 'image' => $item->image_url]) }})"
                                                    class="w-9 h-9 bg-slate-900 text-white rounded-2xl flex items-center justify-center hover:bg-gradient-to-br hover:from-amber-500 hover:to-amber-600 hover:shadow-lg hover:shadow-amber-500/40 transition-all duration-200 active:scale-75 group/btn">
                                                    <svg class="w-4 h-4 group-hover/btn:rotate-90 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v12m6-6H6"/></svg>
                                                </button>

                                                {{-- Qty control --}}
                                                <div x-show="getItemQty({{ $item->id }}) > 0"
                                                    class="flex items-center bg-slate-100 rounded-2xl overflow-hidden shadow-inner border border-slate-200/50" x-cloak>
                                                    <button @click="updateQty({{ $item->id }}, -1)"
                                                        class="w-8 h-8 bg-white text-slate-600 flex items-center justify-center font-black text-base active:scale-90 transition-all hover:bg-red-50 hover:text-red-500 rounded-l-2xl">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"/></svg>
                                                    </button>
                                                    <span class="text-[11px] font-black text-slate-800 w-6 text-center" x-text="getItemQty({{ $item->id }})"></span>
                                                    <button @click="updateQty({{ $item->id }}, 1)"
                                                        class="w-8 h-8 bg-gradient-to-br from-amber-500 to-amber-600 text-white flex items-center justify-center font-black text-sm active:scale-90 transition-all hover:from-amber-600 hover:to-amber-700 rounded-r-2xl shadow-sm">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v12m6-6H6"/></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($menuItems->hasPages())
                    <div class="mt-8 flex justify-center" x-show="search.length === 0" x-cloak>
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-4 inline-flex items-center gap-2">
                            @if ($menuItems->onFirstPage())
                                <span class="px-4 py-2 bg-slate-100 text-slate-400 rounded-xl text-sm font-medium cursor-not-allowed">
                                    ←
                                </span>
                            @else
                                <a href="{{ $menuItems->previousPageUrl() }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl text-sm font-medium hover:bg-slate-50 transition-all">
                                    ←
                                </a>
                            @endif

                            <div class="flex items-center gap-1">
                                @foreach ($menuItems->getUrlRange(1, $menuItems->lastPage()) as $page => $url)
                                    @if ($page == $menuItems->currentPage())
                                        <span class="px-4 py-2 bg-amber-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-amber-600/20">
                                            {{ $page }}
                                        </span>
                                    @elseif ($page == 1 || $page == $menuItems->lastPage() || abs($page - $menuItems->currentPage()) <= 1)
                                        <a href="{{ $url }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl text-sm font-medium hover:bg-slate-50 transition-all">
                                            {{ $page }}
                                        </a>
                                    @elseif (abs($page - $menuItems->currentPage()) == 2)
                                        <span class="px-2 text-slate-400">...</span>
                                    @endif
                                @endforeach
                            </div>

                            @if ($menuItems->hasMorePages())
                                <a href="{{ $menuItems->nextPageUrl() }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl text-sm font-medium hover:bg-slate-50 transition-all">
                                    →
                                </a>
                            @else
                                <span class="px-4 py-2 bg-slate-100 text-slate-400 rounded-xl text-sm font-medium cursor-not-allowed">
                                    →
                                </span>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div x-show="nothingFound()" class="py-20 text-center" x-cloak>
                        <div class="w-24 h-24 bg-slate-100 rounded-[2.5rem] flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-black text-slate-800"
                            x-text="search.length > 0 ? 'Menu tidak ditemukan' : 'Menu tidak tersedia'"></h4>
                        <p class="text-sm text-slate-500 mt-2"
                            x-text="search.length > 0 ? 'Coba kata kunci lain atau pilih kategori berbeda' : 'Menu belum tersedia di kategori ini'">
                        </p>
                    </div>
                </div>
            </div>

            <div id="floating-cart-bar" x-show="totalItems > 0" x-cloak
                x-transition:enter="transition transform ease-out duration-300"
                x-transition:enter-start="translate-y-full opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
                class="fixed bottom-0 left-0 right-0 z-[100] px-4 pt-2 pb-4 safe-area-bottom">
                <div class="max-w-2xl mx-auto">
                    <a href="{{ route('customer.checkout', ['session' => $session->session_token]) }}"
                        class="group flex items-center justify-between gap-3 w-full bg-gradient-to-r from-slate-900 to-slate-800 text-white px-5 py-4 rounded-3xl shadow-2xl shadow-slate-900/40 transition-all active:scale-[0.98] border border-slate-700/50 hover:from-slate-800 hover:to-slate-700 hover:shadow-2xl hover:shadow-slate-900/50">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="relative shrink-0">
                                <div class="w-10 h-10 bg-amber-500 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/40 group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                </div>
                                <span class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white text-[9px] font-black rounded-full flex items-center justify-center shadow-lg border-2 border-slate-900 animate-bounce" x-text="totalItems"></span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Keranjang kamu</p>
                                <p class="text-sm font-black text-white leading-tight" x-text="totalItems + ' item dipilih'"></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <div class="text-right">
                                <p class="text-[10px] text-slate-400 font-medium">Total</p>
                                <p class="text-sm font-black text-amber-400 leading-tight" x-text="formatRp(totalPrice)"></p>
                            </div>
                            <div class="w-9 h-9 bg-amber-500 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/30 group-hover:translate-x-1 transition-transform">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                function menuListApp() {
                    return {
                        activeCategory: @json((string) $activeCategory),
                        search: '',
                        isLoading: false,
                        itemNames: @json($menuItems->map(fn ($item) => mb_strtolower($item->name))->values()),
                        cart: (() => {
                            try {
                                const stored = JSON.parse(localStorage.getItem('cart_{{ $session->session_token }}') || '[]');
                                return Array.isArray(stored) ? stored.filter(i => i && i.id && i.qty > 0) : [];
                            } catch (_) {
                                return [];
                            }
                        })(),

                        init() {

                        },

                        selectCategory(categoryId) {
                            const url = new URL(window.location.href);
                            url.searchParams.set('session', @json($session->session_token));
                            url.searchParams.delete('page');

                            if (categoryId === 'all') {
                                url.searchParams.delete('category');
                            } else {
                                url.searchParams.set('category', categoryId);
                            }

                            window.location.href = url.toString();
                        },

                        startLoading() {
                            this.isLoading = true;
                            setTimeout(() => {
                                this.isLoading = false;
                            }, 800);
                        },

                        get totalItems() { return this.cart.reduce((s, i) => s + i.qty, 0); },
                        get totalPrice() { return this.cart.reduce((s, i) => s + (i.price * i.qty), 0); },

                        formatRp(n) { return 'Rp ' + Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); },

                        visibleCount() {
                            const q = this.search.trim().toLowerCase();
                            if (!q) return this.itemNames.length;
                            return this.itemNames.filter(name => name.includes(q)).length;
                        },

                        nothingFound() {
                            return this.visibleCount() === 0;
                        },

                        getItemQty(id) {
                            const item = this.cart.find(i => i.id === id);
                            return item ? item.qty : 0;
                        },

                        async addToCart(item) {
                            try {
                                const response = await fetch('{{ route('customer.cart.add') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                    },
                                    body: JSON.stringify({
                                        session_token: '{{ $session->session_token }}',
                                        menu_item_id: item.id,
                                        quantity: 1,
                                        notes: ''
                                    })
                                });

                                const result = await response.json();

                                if (!response.ok || !result.success) {
                                    showToast(result.message || 'Menu gagal ditambahkan', 'error');
                                    return;
                                }

                                const existing = this.cart.find(i => i.id === item.id);
                                if (existing) {
                                    existing.qty += 1;
                                } else {
                                    this.cart.push({ ...item, qty: 1, notes: '' });
                                }
                                this.saveCart();
                                showToast(item.name + ' ditambahkan', 'cart');
                            } catch (e) {
                                showToast('Gagal menambah ke keranjang. Coba lagi.', 'error');
                            }
                        },

                        async updateQty(id, delta) {
                            const idx = this.cart.findIndex(i => i.id === id);
                            if (idx > -1) {
                                const newQty = this.cart[idx].qty + delta;

                                if (newQty <= 0) {
                                    if (this.cart.length === 1) {
                                        const result = await Swal.fire({
                                            title: 'Keranjang Bakal Kosong',
                                            text: 'Ini barang terakhirmu, kamu mau kosongin keranjangnya?',
                                            icon: 'question',
                                            showCancelButton: true,
                                            confirmButtonColor: '#ef4444',
                                            cancelButtonColor: '#64748b',
                                            confirmButtonText: 'Ya, kosongin',
                                            cancelButtonText: 'Batal',
                                            customClass: {
                                                popup: 'rounded-2xl',
                                                title: 'font-bold text-slate-800',
                                            }
                                        });

                                        if (!result.isConfirmed) {
                                            return;
                                        }
                                    }
                                }

                                try {
                                    const response = await fetch('{{ route('customer.cart.update') }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                        },
                                        body: JSON.stringify({
                                            session_token: '{{ $session->session_token }}',
                                            menu_item_id: id,
                                            quantity: newQty
                                        })
                                    });

                                    if (!response.ok) {
                                        showToast('Gagal update keranjang. Coba lagi.', 'error');
                                        return;
                                    }

                                    this.cart[idx].qty = newQty;
                                    if (this.cart[idx].qty <= 0) this.cart.splice(idx, 1);
                                    this.saveCart();
                                } catch (e) {
                                    showToast('Gagal update keranjang', 'error');
                                }
                            }
                        },

                        saveCart() {
                            localStorage.setItem('cart_{{ $session->session_token }}', JSON.stringify(this.cart));
                        },
                    };
                }
            </script>
        @endpush
@endsection