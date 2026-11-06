@extends('layouts.admin')
@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan performa Anda hari ini')

@section('content')
    <div x-data="{ tab: 'overview', dateFilter: 'today', ...dashboardManager() }" class="space-y-8 pb-10">

        <div class="rounded-2xl border border-blue-100 bg-gradient-to-r from-blue-50 to-slate-50 p-4 text-sm text-blue-700 flex items-start gap-3">
            <div class="w-8 h-8 bg-blue-100 rounded-xl flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="font-bold">Masuk sebagai <span class="text-blue-800">{{ auth()->user()->role_label }}</span></p>
                @if(auth()->user()->isKitchen())
                    <p class="mt-0.5 text-blue-500 text-xs font-medium">Dashboard ini memuat pesanan dapur secara real-time, tanpa perlu refresh.</p>
                @elseif(auth()->user()->isCashier())
                    <p class="mt-0.5 text-blue-500 text-xs font-medium">Dashboard kasir menampilkan pesanan dan meja aktif sesuai hak akses Anda.</p>
                @else
                    <p class="mt-0.5 text-blue-500 text-xs font-medium">Pemilik dapat mengelola role, akses, dan melihat data dashboard secara penuh.</p>
                @endif
            </div>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-0 mt-4">
            <div class="flex items-center gap-1 overflow-x-auto no-scrollbar">
                <button @click="tab = 'overview'" :class="tab === 'overview' ? 'text-amber-600 border-b-2 border-amber-500 font-black bg-amber-50/60 rounded-t-xl' : 'text-slate-400 font-semibold hover:text-slate-700 hover:bg-slate-50 rounded-t-xl'" class="px-5 py-3 text-sm transition-all whitespace-nowrap">Overview</button>
                <button @click="tab = 'audiences'" :class="tab === 'audiences' ? 'text-amber-600 border-b-2 border-amber-500 font-black bg-amber-50/60 rounded-t-xl' : 'text-slate-400 font-semibold hover:text-slate-700 hover:bg-slate-50 rounded-t-xl'" class="px-5 py-3 text-sm transition-all whitespace-nowrap">Penjualan</button>
                <button @click="tab = 'demographics'" :class="tab === 'demographics' ? 'text-amber-600 border-b-2 border-amber-500 font-black bg-amber-50/60 rounded-t-xl' : 'text-slate-400 font-semibold hover:text-slate-700 hover:bg-slate-50 rounded-t-xl'" class="px-5 py-3 text-sm transition-all whitespace-nowrap">Operasional</button>
                <button @click="tab = 'more'" :class="tab === 'more' ? 'text-amber-600 border-b-2 border-amber-500 font-black bg-amber-50/60 rounded-t-xl' : 'text-slate-400 font-semibold hover:text-slate-700 hover:bg-slate-50 rounded-t-xl'" class="px-5 py-3 text-sm transition-all whitespace-nowrap">Lainnya</button>
            </div>
            <div class="flex items-center gap-2 pb-2">
                <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                    <select name="date_range" onchange="this.form.submit()" class="pl-3 pr-8 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl text-sm font-semibold hover:border-amber-300 transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20 cursor-pointer">
                        <option value="today" {{ ($dateRange ?? 'today') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="week" {{ ($dateRange ?? 'today') == 'week' ? 'selected' : '' }}>7 Hari Terakhir</option>
                        <option value="month" {{ ($dateRange ?? 'today') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 border-b border-slate-100 pb-8 pt-2" x-show="tab === 'overview'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
            <!-- Pesanan Masuk -->
            <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all group cursor-pointer hover:border-amber-200">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-9 h-9 bg-amber-50 rounded-xl flex items-center justify-center">
                        <svg class="w-4.5 h-4.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Order</span>
                </div>
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mb-1">Pesanan Masuk</p>
                <h3 class="text-3xl font-black text-slate-800 leading-none tracking-tight mb-2 stat-number group-hover:text-amber-600 transition-colors" id="orders-today-count">{{ $stats['orders_today'] }}</h3>
                <p class="text-[11px] {{ $stats['order_growth'] >= 0 ? 'text-emerald-500' : 'text-red-500' }} font-bold flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="{{ $stats['order_growth'] >= 0 ? 'M5 10l7-7m0 0l7 7m-7-7v18' : 'M19 14l-7 7m0 0l-7-7m7 7V3' }}"/></svg>
                    {{ $stats['order_growth'] >= 0 ? '+' : '' }}{{ $stats['order_growth'] }}% <span class="text-slate-400 font-medium">kemarin</span>
                </p>
            </div>

            <!-- Pendapatan -->
            <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all group cursor-pointer hover:border-amber-200">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-9 h-9 bg-emerald-50 rounded-xl flex items-center justify-center">
                        <svg class="w-4.5 h-4.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">IDR</span>
                </div>
                @php $revFormatted = number_format($stats['revenue_today'] / 1000, 1, ',', '.') . 'K'; @endphp
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mb-1">Pendapatan</p>
                <h3 class="text-3xl font-black text-slate-800 leading-none tracking-tight mb-2 stat-number group-hover:text-amber-600 transition-colors" id="revenue-today-count">{{ $revFormatted }}</h3>
                <p class="text-[11px] {{ $stats['revenue_growth'] >= 0 ? 'text-emerald-500' : 'text-red-500' }} font-bold flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="{{ $stats['revenue_growth'] >= 0 ? 'M5 10l7-7m0 0l7 7m-7-7v18' : 'M19 14l-7 7m0 0l-7-7m7 7V3' }}"/></svg>
                    {{ $stats['revenue_growth'] >= 0 ? '+' : '' }}{{ $stats['revenue_growth'] }}% <span class="text-slate-400 font-medium">kemarin</span>
                </p>
            </div>

            <!-- Meja Aktif -->
            <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all group cursor-pointer hover:border-amber-200">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center">
                        <svg class="w-4.5 h-4.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z"/></svg>
                    </div>
                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Live</span>
                </div>
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mb-1">Meja Aktif</p>
                <h3 class="text-3xl font-black text-slate-800 leading-none tracking-tight mb-2 stat-number group-hover:text-amber-600 transition-colors">
                    <span id="active-tables-count">{{ $stats['active_tables'] }}</span><span class="text-xl text-slate-200 font-bold">/{{ $stats['total_tables'] }}</span>
                </h3>
                <p class="text-[11px] text-slate-400 font-bold">{{ $stats['active_tables'] }} meja terisi</p>
            </div>

            <!-- Reservasi -->
            <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all group cursor-pointer hover:border-amber-200">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-9 h-9 bg-purple-50 rounded-xl flex items-center justify-center">
                        <svg class="w-4.5 h-4.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Booking</span>
                </div>
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mb-1">Reservasi Baru</p>
                <h3 class="text-3xl font-black text-slate-800 leading-none tracking-tight mb-2 stat-number group-hover:text-amber-600 transition-colors" id="reservations-today-count">{{ $stats['reservations_today'] }}</h3>
                <p class="text-[11px] text-slate-400 font-bold">{{ $stats['pending_reservations'] }} menunggu konfirmasi</p>
            </div>

            <!-- Selesai Hari Ini -->
            <div class="hidden lg:block bg-white border border-slate-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all group cursor-pointer hover:border-amber-200">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-9 h-9 bg-slate-100 rounded-xl flex items-center justify-center">
                        <svg class="w-4.5 h-4.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Done</span>
                </div>
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mb-1">Selesai Hari Ini</p>
                <h3 class="text-3xl font-black text-slate-800 leading-none tracking-tight mb-2 stat-number group-hover:text-amber-600 transition-colors">{{ $stats['completed_today'] ?? 0 }}</h3>
                <p class="text-[11px] text-slate-400 font-bold">{{ $stats['avg_prep_time'] ?? 'N/A' }} rata-rata waktu</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-show="tab === 'overview'" x-transition:enter="transition ease-out duration-500 delay-100" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-[0_2px_12px_rgba(0,0,0,0.04)] border border-slate-100 p-4 sm:p-6 relative overflow-hidden">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-5 gap-3">
                    <div>
                        <h3 class="text-base font-black text-slate-800">Tren Pendapatan</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Analisa pendapatan berdasarkan periode waktu</p>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-semibold flex-wrap">
                        <span class="flex items-center gap-1.5 text-slate-700"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Periode ini</span>
                        <span class="flex items-center gap-1.5 text-slate-400"><span class="w-2.5 h-2.5 rounded-full bg-slate-200"></span> Sebelumnya</span>
                    </div>
                </div>
                <div class="h-[260px] sm:h-[320px] w-full relative">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <div class="space-y-4">
                <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl shadow-lg shadow-blue-600/20 p-5 text-white relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 w-36 h-36 bg-blue-500/30 rounded-full blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-black text-blue-100 uppercase tracking-wider">Status Pesanan</h3>
                            <span class="text-xs text-blue-300 font-semibold">Hari Ini</span>
                        </div>
                        @php
                            $totalToday = $orderStatusDistribution->sum('count');
                            $completedCount = $orderStatusDistribution->firstWhere('status', 'completed')?->count ?? 0;
                        @endphp
                        <p class="text-4xl font-black mb-1">{{ $completedCount }}</p>
                        <p class="text-blue-200 text-xs font-medium mb-3">Pesanan Selesai</p>
                        <div class="h-[80px]">
                            <canvas id="miniStatusChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="relative w-14 h-14 shrink-0">
                                <canvas id="statusChart"></canvas>
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    @php $totalStatusCount = $orderStatusDistribution->sum('count'); $completedStatus = $orderStatusDistribution->firstWhere('status', 'completed')?->count ?? 0; $closedStatusPercent = $totalStatusCount > 0 ? round(($completedStatus / $totalStatusCount) * 100, 1) : 0; @endphp
                                    <span class="text-[10px] font-black text-slate-700">{{ $closedStatusPercent }}%</span>
                                </div>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider mb-0.5">Total Pesanan</p>
                                <h4 class="text-xl font-black text-slate-800">{{ $totalStatusCount }}</h4>
                            </div>
                        </div>
                        <div class="w-px h-12 bg-slate-100 shrink-0"></div>
                        <div class="flex items-center gap-3">
                            <div class="relative w-14 h-14 shrink-0">
                                <canvas id="tableChart"></canvas>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider mb-0.5">Total Meja</p>
                                @php $totalTables = $tableUtilizationArray['available'] + ($tableUtilizationArray['occupied'] ?? 0) + ($tableUtilizationArray['reserved'] ?? 0); @endphp
                                <h4 class="text-xl font-black text-slate-800">{{ $totalTables }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" x-show="tab === 'overview'" x-transition:enter="transition ease-out duration-500 delay-200" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
            <div class="bg-white rounded-2xl shadow-[0_2px_12px_rgba(0,0,0,0.04)] border border-slate-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-slate-800">Market Overview</h3>
                    <select class="bg-slate-50 border border-slate-200 text-slate-700 rounded-lg text-sm px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-amber-500/20 font-medium cursor-pointer">
                        <option>This month</option>
                        <option>Last month</option>
                    </select>
                </div>
                <div class="h-[280px]">
                    <canvas id="weeklyOrdersChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-[0_2px_12px_rgba(0,0,0,0.04)] border border-slate-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-slate-800">Menu Terlaris (30 Hari)</h3>
                    <button class="text-amber-600 hover:text-amber-700 text-sm font-semibold">View All</button>
                </div>
                <div class="h-[280px]">
                    <canvas id="topMenuChart"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5" x-show="tab === 'overview'" x-transition:enter="transition ease-out duration-500 delay-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">

            {{-- Recent Orders --}}
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-800 text-sm">Pesanan Terbaru</h3>
                            <p class="text-[10px] text-slate-400 font-medium">Update otomatis real-time</p>
                        </div>
                    </div>
                    <a href="{{ role_route('admin.orders.index') }}"
                        class="inline-flex items-center gap-1.5 text-xs bg-white border border-slate-200 shadow-sm px-3 py-1.5 rounded-xl text-slate-600 hover:text-amber-600 hover:border-amber-300 font-bold transition-all">
                        Lihat Semua
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <div id="recent-orders">
                    @forelse($recentOrders as $order)
                    @php
                        $sc = [
                            'pending'              => 'bg-amber-100 text-amber-700',
                            'payment_success'      => 'bg-emerald-100 text-emerald-700',
                            'processing'           => 'bg-blue-100 text-blue-700',
                            'ready'                => 'bg-teal-100 text-teal-700',
                            'completed'            => 'bg-slate-100 text-slate-500',
                            'cancelled'            => 'bg-red-100 text-red-600',
                            'pending_payment'      => 'bg-amber-50 text-amber-600',
                            'pending_confirmation' => 'bg-orange-100 text-orange-700',
                        ];
                    @endphp
                    <div class="px-4 sm:px-5 py-3.5 border-b border-slate-50 hover:bg-slate-50/70 transition-colors group last:border-0">
                        <div class="flex items-center gap-3">
                            {{-- Table badge --}}
                            <div class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center shrink-0 font-black text-sm shadow-sm group-hover:bg-amber-500 transition-colors">
                                {{ $order->table?->number ?? '?' }}
                            </div>
                            {{-- Order info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-1.5 mb-0.5">
                                    <span class="text-sm font-black text-slate-800 truncate">{{ $order->order_number }}</span>
                                    <span class="px-2 py-0.5 text-[9px] font-black rounded-lg {{ $sc[$order->status] ?? 'bg-slate-100 text-slate-600' }} uppercase tracking-wide">{{ $order->status_label }}</span>
                                </div>
                                <p class="text-[11px] text-slate-400 font-medium truncate">
                                    {{ $order->customer_name ?? 'Tamu' }} · {{ $order->items->count() }} item · {{ $order->created_at->format('H:i') }}
                                </p>
                            </div>
                            {{-- Amount --}}
                            <div class="text-right shrink-0">
                                <p class="text-sm font-black text-slate-800">{{ $order->formatted_total }}</p>
                                <p class="text-[10px] text-slate-400 font-medium">{{ $order->payment_method === 'online' ? '⚡ Online' : '💵 Kasir' }}</p>
                            </div>
                            {{-- Actions --}}
                            <div class="shrink-0 hidden sm:flex items-center gap-1.5">
                                @if($order->status === 'pending' || $order->status === 'payment_success')
                                    <form method="POST" action="{{ role_route('admin.orders.update_status', $order) }}" class="inline-block" onsubmit="return confirmStatusUpdate(event, 'Terima & mulai masak?')">
                                        @csrf
                                        <input type="hidden" name="status" value="processing">
                                        <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-amber-500 text-white text-[10px] font-black rounded-lg hover:bg-amber-600 transition-all shadow-sm active:scale-95 uppercase tracking-wide">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                                            Masak
                                        </button>
                                    </form>
                                @elseif($order->status === 'processing')
                                    <form method="POST" action="{{ role_route('admin.orders.update_status', $order) }}" class="inline-block" onsubmit="return confirmStatusUpdate(event, 'Tandai siap saji?')">
                                        @csrf
                                        <input type="hidden" name="status" value="ready">
                                        <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-emerald-500 text-white text-[10px] font-black rounded-lg hover:bg-emerald-600 transition-all shadow-sm active:scale-95 uppercase tracking-wide">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            Siap
                                        </button>
                                    </form>
                                @elseif($order->status === 'ready')
                                    <form method="POST" action="{{ role_route('admin.orders.update_status', $order) }}" class="inline-block" onsubmit="return confirmStatusUpdate(event, 'Selesaikan pesanan?')">
                                        @csrf
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-slate-800 text-white text-[10px] font-black rounded-lg hover:bg-slate-900 transition-all shadow-sm active:scale-95 uppercase tracking-wide">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Done
                                        </button>
                                    </form>
                                @endif
                                <button type="button" onclick='openOrderDetail("{{ $order->id }}", "{{ role_route("admin.orders.data", $order) }}")'
                                    class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Mobile action row --}}
                        <div class="mt-2 flex sm:hidden items-center gap-2">
                            <button type="button" onclick='openOrderDetail("{{ $order->id }}", "{{ role_route("admin.orders.data", $order) }}")'
                                class="flex-1 py-2 text-[11px] font-bold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-all text-center">
                                Detail
                            </button>
                            @if($order->status === 'pending' || $order->status === 'payment_success')
                                <form method="POST" action="{{ role_route('admin.orders.update_status', $order) }}" class="flex-1" onsubmit="return confirmStatusUpdate(event, 'Terima & mulai masak?')">
                                    @csrf<input type="hidden" name="status" value="processing">
                                    <button type="submit" class="w-full py-2 text-[11px] font-black bg-amber-500 text-white rounded-xl hover:bg-amber-600 transition-all">🔥 Masak</button>
                                </form>
                            @elseif($order->status === 'processing')
                                <form method="POST" action="{{ role_route('admin.orders.update_status', $order) }}" class="flex-1" onsubmit="return confirmStatusUpdate(event, 'Tandai siap?')">
                                    @csrf<input type="hidden" name="status" value="ready">
                                    <button type="submit" class="w-full py-2 text-[11px] font-black bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 transition-all">✅ Siap</button>
                                </form>
                            @elseif($order->status === 'ready')
                                <form method="POST" action="{{ role_route('admin.orders.update_status', $order) }}" class="flex-1" onsubmit="return confirmStatusUpdate(event, 'Selesaikan?')">
                                    @csrf<input type="hidden" name="status" value="completed">
                                    <button type="submit" class="w-full py-2 text-[11px] font-black bg-slate-800 text-white rounded-xl hover:bg-slate-900 transition-all">🏁 Done</button>
                                </form>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="py-14 text-center">
                        <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-7 h-7 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                        </div>
                        <p class="text-slate-400 text-sm font-semibold">Belum ada pesanan hari ini</p>
                    </div>
                    @endforelse
                </div>

                @if($recentOrders->hasPages())
                <div class="px-5 py-3.5 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs text-slate-400 font-medium">{{ $recentOrders->firstItem() }}–{{ $recentOrders->lastItem() }} dari {{ $recentOrders->total() }}</span>
                    <div class="flex items-center gap-1">
                        @if($recentOrders->onFirstPage())
                            <span class="w-8 h-8 flex items-center justify-center bg-slate-100 rounded-lg text-slate-300 cursor-not-allowed"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></span>
                        @else
                            <a href="{{ $recentOrders->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-600 hover:border-amber-300 hover:text-amber-600 transition-all shadow-sm"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></a>
                        @endif
                        <span class="px-3 py-1.5 text-xs font-black text-slate-700 bg-white border border-slate-200 rounded-lg">{{ $recentOrders->currentPage() }}/{{ $recentOrders->lastPage() }}</span>
                        @if($recentOrders->hasMorePages())
                            <a href="{{ $recentOrders->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-600 hover:border-amber-300 hover:text-amber-600 transition-all shadow-sm"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
                        @else
                            <span class="w-8 h-8 flex items-center justify-center bg-slate-100 rounded-lg text-slate-300 cursor-not-allowed"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></span>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            {{-- Reservasi Hari Ini --}}
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-800 text-sm">Reservasi Hari Ini</h3>
                            <p class="text-[10px] text-slate-400 font-medium">{{ $todayReservations->count() }} reservasi</p>
                        </div>
                    </div>
                    <a href="{{ role_route('admin.reservations.index') }}"
                        class="w-8 h-8 flex items-center justify-center bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-amber-600 hover:border-amber-300 transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                <div>
                    @forelse($todayReservations as $res)
                    @php
                        $rs = [
                            'pending'   => ['badge' => 'bg-amber-100 text-amber-700',   'dot' => 'bg-amber-400'],
                            'confirmed' => ['badge' => 'bg-emerald-100 text-emerald-700','dot' => 'bg-emerald-500'],
                            'rejected'  => ['badge' => 'bg-red-100 text-red-600',        'dot' => 'bg-red-400'],
                        ];
                        $rc = $rs[$res->status] ?? ['badge' => 'bg-slate-100 text-slate-600', 'dot' => 'bg-slate-400'];
                    @endphp
                    <div class="px-5 py-4 border-b border-slate-50 last:border-0 hover:bg-slate-50/70 transition-colors">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-slate-700 to-slate-900 flex items-center justify-center text-white text-xs font-black shrink-0 shadow-sm">
                                    {{ strtoupper(substr($res->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-slate-800 truncate">{{ $res->name }}</p>
                                    <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                                        <span class="text-[10px] text-slate-400 font-medium flex items-center gap-0.5">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            {{ $res->formatted_time }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 font-medium flex items-center gap-0.5">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            {{ $res->guests }} tamu
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full {{ $rc['dot'] }} {{ $res->status === 'pending' ? 'animate-pulse' : '' }}"></span>
                                <span class="px-2 py-0.5 text-[9px] font-black rounded-lg {{ $rc['badge'] }} uppercase tracking-wide">{{ $res->status_label }}</span>
                            </div>
                        </div>

                        @if($res->status === 'pending')
                        <div class="mt-2.5 flex gap-2">
                            <form method="POST" action="{{ role_route('admin.reservations.updateStatus', $res) }}" class="flex-1"
                                  onsubmit="return confirmReservationDashboard(event, 'confirmed')">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="confirmed">
                                <button type="submit" class="w-full py-1.5 text-[10px] font-black bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-all active:scale-95 uppercase tracking-wide">✓ Konfirmasi</button>
                            </form>
                            <form method="POST" action="{{ role_route('admin.reservations.updateStatus', $res) }}" class="flex-1"
                                  onsubmit="return confirmReservationDashboard(event, 'rejected')">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="w-full py-1.5 text-[10px] font-black bg-red-50 text-red-600 border border-red-100 rounded-lg hover:bg-red-100 transition-all active:scale-95 uppercase tracking-wide">✕ Tolak</button>
                            </form>
                        </div>
                        @endif
                    </div>
                    @empty
                    <div class="py-12 text-center">
                        <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <p class="text-slate-400 text-sm font-semibold">Belum ada reservasi</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div x-show="tab === 'audiences'" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 text-center" style="display: none;">
            <svg class="w-16 h-16 text-slate-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10a2 2 0 01-2 2h-2a2 2 0 01-2-2zm9 0V5a2 2 0 00-2-2h-2a2 2 0 00-2 2v14a2 2 0 002 2h2a2 2 0 002-2z"/></svg>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Laporan Penjualan</h3>
            <p class="text-slate-400 text-sm mb-4">Lihat laporan lengkap di halaman Laporan Penjualan.</p>
            <a href="{{ role_route('admin.reports.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 transition-all text-sm shadow-lg shadow-amber-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10a2 2 0 01-2 2h-2a2 2 0 01-2-2zm9 0V5a2 2 0 00-2-2h-2a2 2 0 00-2 2v14a2 2 0 002 2h2a2 2 0 002-2z"/></svg>
                Buka Laporan
            </a>
        </div>
        <div x-show="tab === 'demographics'" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 text-center" style="display: none;">
            <svg class="w-16 h-16 text-slate-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Operasional</h3>
            <p class="text-slate-400 text-sm mb-4">Kelola meja dan sesi aktif di halaman Meja & QR Code.</p>
            <a href="{{ role_route('admin.tables.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition-all text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5z"/></svg>
                Meja & QR
            </a>
        </div>
        <div x-show="tab === 'more'" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8" style="display: none;">
            <h3 class="text-base font-black text-slate-800 mb-4">Akses Cepat</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @if(auth()->user()->hasPermission('view_orders'))
                <a href="{{ role_route('admin.orders.scan') }}" class="flex items-center gap-3 p-4 bg-slate-50 hover:bg-amber-50 border border-slate-100 hover:border-amber-200 rounded-2xl transition-all group">
                    <div class="w-9 h-9 bg-amber-100 rounded-xl flex items-center justify-center group-hover:bg-amber-500 transition-colors">
                        <svg class="w-4.5 h-4.5 text-amber-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    </div>
                    <span class="text-sm font-bold text-slate-700 group-hover:text-amber-700">Scan QR</span>
                </a>
                @endif
                @if(auth()->user()->hasPermission('view_sessions'))
                <a href="{{ role_route('admin.sessions.index') }}" class="flex items-center gap-3 p-4 bg-slate-50 hover:bg-blue-50 border border-slate-100 hover:border-blue-200 rounded-2xl transition-all group">
                    <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-500 transition-colors">
                        <svg class="w-4.5 h-4.5 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-sm font-bold text-slate-700 group-hover:text-blue-700">Sesi Aktif</span>
                </a>
                @endif
                @if(auth()->user()->hasPermission('view_reservations'))
                <a href="{{ role_route('admin.reservations.index') }}" class="flex items-center gap-3 p-4 bg-slate-50 hover:bg-emerald-50 border border-slate-100 hover:border-emerald-200 rounded-2xl transition-all group">
                    <div class="w-9 h-9 bg-emerald-100 rounded-xl flex items-center justify-center group-hover:bg-emerald-500 transition-colors">
                        <svg class="w-4.5 h-4.5 text-emerald-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <span class="text-sm font-bold text-slate-700 group-hover:text-emerald-700">Reservasi</span>
                </a>
                @endif
                @if(auth()->user()->hasPermission('view_reports'))
                <a href="{{ role_route('admin.reports.index') }}" class="flex items-center gap-3 p-4 bg-slate-50 hover:bg-purple-50 border border-slate-100 hover:border-purple-200 rounded-2xl transition-all group">
                    <div class="w-9 h-9 bg-purple-100 rounded-xl flex items-center justify-center group-hover:bg-purple-500 transition-colors">
                        <svg class="w-4.5 h-4.5 text-purple-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10a2 2 0 01-2 2h-2a2 2 0 01-2-2zm9 0V5a2 2 0 00-2-2h-2a2 2 0 00-2 2v14a2 2 0 002 2h2a2 2 0 002-2z"/></svg>
                    </div>
                    <span class="text-sm font-bold text-slate-700 group-hover:text-purple-700">Laporan</span>
                </a>
                @endif
                @if(auth()->user()->hasPermission('manage_menu'))
                <a href="{{ role_route('admin.menu.index') }}" class="flex items-center gap-3 p-4 bg-slate-50 hover:bg-orange-50 border border-slate-100 hover:border-orange-200 rounded-2xl transition-all group">
                    <div class="w-9 h-9 bg-orange-100 rounded-xl flex items-center justify-center group-hover:bg-orange-500 transition-colors">
                        <svg class="w-4.5 h-4.5 text-orange-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                    </div>
                    <span class="text-sm font-bold text-slate-700 group-hover:text-orange-700">Menu</span>
                </a>
                @endif
                <a href="{{ role_route('admin.profile') }}" class="flex items-center gap-3 p-4 bg-slate-50 hover:bg-slate-100 border border-slate-100 rounded-2xl transition-all group">
                    <div class="w-9 h-9 bg-slate-200 rounded-xl flex items-center justify-center group-hover:bg-slate-700 transition-colors">
                        <svg class="w-4.5 h-4.5 text-slate-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <span class="text-sm font-bold text-slate-700 group-hover:text-slate-900">Profil</span>
                </a>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
        // ─── Reservasi confirm helper (dashboard) ────────────────────────
        function confirmReservationDashboard(event, newStatus) {
            event.preventDefault();
            const form = event.currentTarget;
            const label = newStatus === 'confirmed' ? 'Konfirmasi' : 'Tolak';
            const color = newStatus === 'confirmed' ? '#10b981' : '#ef4444';
            Swal.fire({
                title: newStatus === 'confirmed' ? 'Konfirmasi Reservasi?' : 'Tolak Reservasi?',
                icon: newStatus === 'confirmed' ? 'question' : 'warning',
                showCancelButton: true,
                confirmButtonColor: color,
                cancelButtonColor: '#64748b',
                confirmButtonText: label,
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-2xl' }
            }).then(async (result) => {
                if (!result.isConfirmed) return;
                Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                try {
                    const fd = new FormData(form);
                    const res = await fetch(form.action, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }, credentials: 'same-origin' });
                    const data = await res.json().catch(() => ({ success: res.ok }));
                    if (res.ok && data.success !== false) {
                        await Swal.fire({ icon: newStatus === 'confirmed' ? 'success' : 'info', title: 'Berhasil!', text: data.message || label + ' berhasil.', timer: 1500, timerProgressBar: true, showConfirmButton: false, customClass: { popup: 'rounded-2xl' } });
                        window.location.reload();
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: data.message || 'Terjadi kesalahan.', customClass: { popup: 'rounded-2xl' } });
                    }
                } catch(e) {
                    Swal.fire({ icon: 'error', title: 'Koneksi Bermasalah', text: 'Coba lagi.', customClass: { popup: 'rounded-2xl' } });
                }
            });
            return false;
        }

        // ─── Alpine.js: Dashboard Manager ───────────────────────────────────
        function dashboardManager() {
            return {
                statsFilters: {
                    menu: { search: '', category: '', status: 'all' },
                    tables: { search: '', location: '', status: 'all' },
                    orders: { search: '', status: 'all', dateRange: 'today' },
                    reservations: { search: '', status: 'all', dateRange: 'upcoming' }
                },
                init() {
                    this.$watch('statsFilters', () => {
                        this.applyFilters();
                    }, { deep: true });
                    
                    if(typeof flatpickr !== 'undefined') {
                        flatpickr('.flatpickr', {
                            dateFormat: 'd/m/Y',
                            defaultDate: 'today'
                        });
                    }
                },
                applyFilters() {
                    const filters = this.statsFilters.orders || {};
                    const dateRange = filters.dateRange || 'today';
                    const params = new URLSearchParams({ date_range: dateRange });
                    const url = `{{ route('admin.dashboard') }}?${params.toString()}`;
                    
                    window.location.href = url;
                },
                resetFilters() {
                    this.statsFilters = {
                        menu: { search: '', category: '', status: 'all' },
                        tables: { search: '', location: '', status: 'all' },
                        orders: { search: '', status: 'all', dateRange: 'today' },
                        reservations: { search: '', status: 'all', dateRange: 'upcoming' }
                    };
                }
            };
        }

        (function() {
            function showToast(message, type = 'info') {
                const icons = { success: 'success', error: 'error', warning: 'warning', info: 'info' };
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: icons[type] || 'info',
                    title: message,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            }

            window.confirmStatusUpdate = function(event, message, icon = 'question') {
                event.preventDefault();
                const form = event.target;
                
                Swal.fire({
                    title: 'Konfirmasi',
                    text: message,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonColor: '#0f172a',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Ya, Lanjutkan',
                    cancelButtonText: 'Batal',
                    customClass: { popup: 'rounded-2xl' }
                }).then(async (result) => {
                    if (!result.isConfirmed) return;

                    Swal.fire({
                        title: 'Memproses...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => Swal.showLoading()
                    });

                    const formData = new FormData(form);
                    fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(async data => {
                        if (data.success) {
                            await Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message || 'Status berhasil diperbarui!',
                                timer: 1800,
                                timerProgressBar: true,
                                showConfirmButton: false,
                                customClass: { popup: 'rounded-2xl' }
                            });
                            window.location.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: data.message || 'Gagal memperbarui status',
                                customClass: { popup: 'rounded-2xl' }
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Koneksi Bermasalah',
                            text: 'Terjadi kesalahan saat memperbarui status',
                            customClass: { popup: 'rounded-2xl' }
                        });
                    });
                });
                return false;
            };

            const ORDER_STATUS_LABELS = {
                pending_payment: 'Menunggu Pembayaran', payment_success: 'Pembayaran Berhasil',
                pending: 'Menunggu', processing: 'Diproses', ready: 'Siap Disajikan',
                completed: 'Selesai', cancelled: 'Dibatalkan'
            };
            const STATUS_COLOR_MAP = {
                pending: '#eab308', processing: '#3b82f6', ready: '#10b981',
                completed: '#6b7280', cancelled: '#ef4444',
                pending_payment: '#f59e0b', payment_success: '#10b981'
            };

            document.addEventListener('DOMContentLoaded', function () {
                
                Chart.defaults.font.family = "'Inter', sans-serif";
                Chart.defaults.color = '#94a3b8';

                const revenueTrendData = @json($revenueTrend);
                const lastWeekData = @json($lastWeekRevenueData);
                const revenueCtx = document.getElementById('revenueChart');
                if (revenueCtx) {
                    const ctx = revenueCtx.getContext('2d');
                    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                    gradient.addColorStop(0, 'rgba(245, 158, 11, 0.4)');
                    gradient.addColorStop(1, 'rgba(245, 158, 11, 0.0)');
                    
                    const gradientBlue = ctx.createLinearGradient(0, 0, 0, 300);
                    gradientBlue.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
                    gradientBlue.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

                    const labels30Days = revenueTrendData.map(d => new Date(d.date).toLocaleDateString('id-ID', { month: 'short', day: 'numeric' }));
                    const thisWeekRevenue = revenueTrendData.map(d => d.revenue);
                    const lastWeekRevenue = labels30Days.map((label, idx) => {
                        const dateKey = new Date(revenueTrendData[idx].date);
                        const prevDate = new Date(dateKey);
                        prevDate.setDate(prevDate.getDate() - 7);
                        const prevKey = prevDate.toISOString().split('T')[0];
                        const found = lastWeekData[prevKey];
                        return found ? found.revenue : null;
                    });

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels30Days,
                            datasets: [
                                {
                                    label: 'Minggu Ini',
                                    data: thisWeekRevenue,
                                    borderColor: '#f59e0b',
                                    backgroundColor: gradient,
                                    borderWidth: 3,
                                    tension: 0.4,
                                    fill: true,
                                    pointBackgroundColor: '#fff',
                                    pointBorderColor: '#f59e0b',
                                    pointBorderWidth: 2,
                                    pointRadius: 4,
                                    pointHoverRadius: 6
                                },
                                {
                                    label: 'Minggu Lalu',
                                    data: lastWeekRevenue,
                                    borderColor: '#cbd5e1',
                                    backgroundColor: gradientBlue,
                                    borderWidth: 2,
                                    borderDash: [5, 5],
                                    tension: 0.4,
                                    fill: true,
                                    pointRadius: 0,
                                    pointHoverRadius: 0
                                }
                            ]
                        },
                        options: {
                            responsive: true, maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: '#0f172a',
                                    padding: 12,
                                    titleFont: { size: 13 },
                                    bodyFont: { size: 14, weight: 'bold' },
                                    callbacks: { label: (ctx) => 'Rp ' + (ctx.raw ? ctx.raw.toLocaleString('id-ID') : '0') }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { color: 'rgba(148,163,184,.22)', drawBorder: false },
                                    ticks: { callback: v => (v/1000) + 'k', font: { size: 11, weight: '600' } }
                                },
                                x: {
                                    grid: { display: false, drawBorder: false },
                                    ticks: { font: { size: 11, weight: '600' } }
                                }
                            },
                            interaction: { intersect: false, mode: 'index' }
                        }
                    });
                }

                const miniStatusCtx = document.getElementById('miniStatusChart');
                const miniChartData = @json($miniChartData);
                if(miniStatusCtx && miniChartData.length > 0) {
                    const hourLabels = miniChartData.map((_, i) => i + 'h');
                    const maxVal = Math.max(...miniChartData);
                    const normalizedData = miniChartData.map(v => maxVal > 0 ? (v / maxVal) * 30 : 0);
                    new Chart(miniStatusCtx.getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: hourLabels,
                            datasets: [{
                                data: normalizedData,
                                borderColor: 'rgba(255, 255, 255, 0.8)',
                                borderWidth: 3,
                                tension: 0.4,
                                pointRadius: 0,
                                pointHoverRadius: 4,
                                pointBackgroundColor: '#fff'
                            }]
                        },
                        options: {
                            responsive: true, maintainAspectRatio: false,
                            plugins: { legend: { display: false }, tooltip: { enabled: false } },
                            scales: { 
                                x: { display: false }, 
                                y: { display: false, min: 0 } 
                            },
                            layout: { padding: 0 }
                        }
                    });
                }

                const statusData = @json($orderStatusDistribution);
                const statusCtx = document.getElementById('statusChart');
                if (statusCtx) {
                    new Chart(statusCtx.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: statusData.map(s => ORDER_STATUS_LABELS[s.status] ?? s.status),
                            datasets: [{
                                data: statusData.length ? statusData.map(s => s.count) : [1],
                                backgroundColor: statusData.length ? statusData.map(s => STATUS_COLOR_MAP[s.status] ?? '#94a3b8') : ['rgba(148,163,184,.35)'],
                                borderWidth: 0,
                                hoverOffset: 4
                            }]
                        },
                        options: {
                            responsive: true, maintainAspectRatio: false,
                            cutout: '75%',
                            plugins: { legend: { display: false }, tooltip: { enabled: statusData.length > 0 } }
                        }
                    });
                }

                const tableData = @json($tableUtilization);
                const tableColors = { available: '#10b981', occupied: '#ef4444', reserved: '#f59e0b' };
                const tableCtx = document.getElementById('tableChart');
                if (tableCtx) {
                    new Chart(tableCtx.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: tableData.map(t => t.status),
                            datasets: [{
                                data: tableData.length ? tableData.map(t => t.count) : [1],
                                backgroundColor: tableData.length ? tableData.map(t => tableColors[t.status] ?? '#94a3b8') : ['rgba(148,163,184,.35)'],
                                borderWidth: 0,
                                hoverOffset: 4
                            }]
                        },
                        options: {
                            responsive: true, maintainAspectRatio: false,
                            cutout: '75%',
                            plugins: { legend: { display: false }, tooltip: { enabled: tableData.length > 0 } }
                        }
                    });
                }

                const weeklyData = @json($weeklyRevenue);
                const weeklyOrdersCtx = document.getElementById('weeklyOrdersChart');
                if (weeklyOrdersCtx) {
                    new Chart(weeklyOrdersCtx.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: weeklyData.map(d => new Date(d.date).toLocaleDateString('id-ID', { weekday: 'short' })),
                            datasets: [{
                                label: 'Orders',
                                data: weeklyData.map(d => d.count),
                                backgroundColor: '#f59e0b', 
                                borderRadius: 4,
                                barPercentage: 0.5
                            }]
                        },
                        options: {
                            responsive: true, maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                x: { grid: { display: false, drawBorder: false }, ticks: { font: { weight: '600' } } },
                                y: { grid: { color: 'rgba(148,163,184,.22)', drawBorder: false }, beginAtZero: true }
                            }
                        }
                    });
                }

                const topMenuData = @json($topMenuItems);
                const topMenuCtx = document.getElementById('topMenuChart');
                if (topMenuCtx) {
                    new Chart(topMenuCtx.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: topMenuData.map(m => m.name),
                            datasets: [{
                                label: 'Terjual',
                                data: topMenuData.map(m => m.total_sold),
                                backgroundColor: '#3b82f6', 
                                borderRadius: 4,
                                barPercentage: 0.6
                            }]
                        },
                        options: {
                            indexAxis: 'y', responsive: true, maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                x: { display: false, beginAtZero: true },
                                y: { grid: { display: false, drawBorder: false }, ticks: { font: { weight: '600' } } }
                            }
                        }
                    });
                }

                if (window.Echo) {
                    const refreshDashboardData = async (message = 'Data dashboard diperbarui') => {
                        try {
                            const response = await fetch('{{ role_route("admin.dashboard.latest") }}?date_range={{ $dateRange ?? "today" }}', {
                                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                                credentials: 'same-origin'
                            });
                            if (!response.ok) return;
                            const data = await response.json();
                            const stats = data.stats || {};
                            const updates = {
                                'orders-today-count': stats.orders_today,
                                'active-tables-count': stats.active_tables,
                                'reservations-today-count': stats.reservations_today
                            };
                            Object.entries(updates).forEach(([id, value]) => {
                                const element = document.getElementById(id);
                                if (element && value !== undefined) element.textContent = value;
                            });
                            if (message) showToast(message, 'info');
                        } catch (error) {
                            console.warn('Dashboard real-time update gagal:', error);
                        }
                    };
                    window.Echo.channel('admin-dashboard').listen('.DashboardUpdated', () => refreshDashboardData());
                    window.Echo.channel('admin-orders').listen('.OrderUpdated', (e) => {
                        const text = e.action === 'created' ? 'Pesanan baru: ' + (e.order?.order_number || '') : 'Pesanan diperbarui';
                        refreshDashboardData(text);
                    });
                }
            });
        })();
        </script>
    @endpush
@endsection
