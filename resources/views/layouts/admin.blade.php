<!DOCTYPE html>
<html lang="id" class="h-full" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Sari Rasa Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script>
        // Apply saved theme before the page paints (light is the default).
        (function () {
            const theme = localStorage.getItem('resto-theme') || 'light';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    <script>
        // Keep Chart.js colors readable in both themes.
        (function () {
            function syncChartTheme() {
                if (!window.Chart) return;
                const dark = document.documentElement.getAttribute('data-theme') === 'dark';
                Chart.defaults.color = dark ? '#94a3b8' : '#64748b';
                Chart.defaults.borderColor = dark ? 'rgba(148,163,184,.18)' : 'rgba(148,163,184,.28)';
                document.querySelectorAll('canvas').forEach(function (cv) {
                    try {
                        const chart = Chart.getChart(cv);
                        if (chart) chart.update('none');
                    } catch (e) { /* ignore */ }
                });
            }
            window.addEventListener('theme-changed', syncChartTheme);
            document.addEventListener('DOMContentLoaded', syncChartTheme);
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        amber: { 400: '#fbbf24', 500: '#f59e0b', 600: '#d97706' }
                    }
                }
            }
        }
    </script>

    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border-primary); border-radius: 99px; }
        [data-theme="dark"] ::-webkit-scrollbar-thumb { background: #475569; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* ═══ SIDEBAR ═══════════════════════════════════════════════ */
        .sidebar-bg {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 50%, #ffffff 100%);
            border-right: 1px solid #e2e8f0;
        }
        
        [data-theme="dark"] .sidebar-bg {
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            border-right: 1px solid #334155;
        }

        .sidebar-section-label {
            padding: 0 20px 6px;
            font-size: 9.5px;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #94a3b8;
        }
        
        [data-theme="dark"] .sidebar-section-label {
            color: #64748b;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 13px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            color: #475569;
            transition: all 0.2s ease;
            margin: 0 8px;
            border: 1px solid transparent;
            text-decoration: none;
            position: relative;
            overflow: hidden;
            letter-spacing: -0.01em;
        }
        
        [data-theme="dark"] .sidebar-link {
            color: #cbd5e1;
        }

        .sidebar-link:hover {
            background: rgba(245, 158, 11, 0.07);
            color: #fbbf24;
            border-color: rgba(245, 158, 11, 0.12);
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, rgba(245,158,11,0.18) 0%, rgba(217,119,6,0.12) 100%);
            color: #fbbf24 !important;
            border-color: rgba(245, 158, 11, 0.25);
            font-weight: 700;
        }

        .sidebar-link.active .sidebar-icon-wrap {
            background: linear-gradient(135deg, #f59e0b, #d97706) !important;
            box-shadow: 0 4px 12px rgba(245,158,11,0.4);
        }

        .sidebar-link.active .sidebar-icon-wrap svg {
            color: #fff !important;
        }

        [data-theme="dark"] .sidebar-link.active {
            color: #fbbf24 !important;
        }

        .sidebar-link.active::before {
            content: '';
            position: absolute;
            right: 0; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 60%;
            background: linear-gradient(180deg, #f59e0b, #d97706);
            border-radius: 999px 0 0 999px;
        }

        .sidebar-icon-wrap {
            width: 34px; height: 34px;
            background: #f1f5f9;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            transition: all 0.2s;
            border: 1px solid #e2e8f0;
        }
        
        [data-theme="dark"] .sidebar-icon-wrap {
            background: #334155;
            border-color: #475569;
        }

        .sidebar-link:hover .sidebar-icon-wrap {
            background: rgba(245, 158, 11, 0.15);
            border-color: rgba(245, 158, 11, 0.2);
        }

        .sidebar-link svg {
            width: 16px; height: 16px;
            display: block;
            color: #475569;
            transition: color 0.2s;
            stroke-width: 2;
            fill: none;
            stroke: currentColor;
        }
        
        [data-theme="dark"] .sidebar-link svg {
            color: #cbd5e1;
        }

        .sidebar-logo-card {
            background: #ffffff;
            transition: background-color .25s ease, box-shadow .25s ease;
        }
        [data-theme="dark"] .sidebar-logo-card {
            background: rgba(248, 250, 252, 0.04);
            box-shadow: 0 10px 30px rgba(2, 6, 23, 0.35);
        }
        .sidebar-logo-dark { display: none; }
        [data-theme="dark"] .sidebar-logo-dark { display: inline-block; }
        [data-theme="dark"] .sidebar-logo-light { display: none; }

        .sidebar-brand-title { color: #0f172a; }
        .sidebar-brand-panel { color: #b45309; }
        .sidebar-user-area { background: rgba(241, 245, 249, 0.8); border-color: #e2e8f0; }
        .sidebar-user-name { color: #0f172a; }
        .sidebar-user-email { color: #64748b; }
        .sidebar-divider { border-color: #e2e8f0; }
        .sidebar-online { border-color: #ffffff; }

        [data-theme="dark"] .sidebar-brand-title { color: #f8fafc; }
        [data-theme="dark"] .sidebar-brand-panel { color: #fbbf24; }
        [data-theme="dark"] .sidebar-user-area { background: rgba(2, 6, 23, 0.35); border-color: rgba(51, 65, 85, 0.6); }
        [data-theme="dark"] .sidebar-user-name { color: #f8fafc; }
        [data-theme="dark"] .sidebar-user-email { color: #94a3b8; }
        [data-theme="dark"] .sidebar-divider { border-color: rgba(51, 65, 85, 0.6); }
        [data-theme="dark"] .sidebar-online { border-color: #0f172a; }

        .sidebar-link:hover svg { color: #fbbf24; }
        .sidebar-link.active svg { color: #fff !important; }

        /* Badge */
        @keyframes badge-pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.8; transform: scale(1.15); }
        }
        .badge-pulse { animation: badge-pulse 1.8s ease-in-out infinite; }

        /* ═══ TOPBAR ═════════════════════════════════════════════════ */
        .topbar-blur {
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.7);
            box-shadow: 0 1px 8px rgba(0,0,0,0.04);
        }
        
        [data-theme="dark"] .topbar-blur {
            background: rgba(15, 23, 42, 0.95);
            border-bottom-color: rgba(51, 65, 85, 0.5);
            box-shadow: 0 1px 8px rgba(0,0,0,0.3);
        }

        /* ═══ NOTIFICATION PANEL ═════════════════════════════════════ */
        .notif-panel {
            position: fixed;
            top: 0; right: 0;
            height: 100vh;
            width: 360px;
            background: #fff;
            border-left: 1px solid #e2e8f0;
            box-shadow: -8px 0 32px rgba(0,0,0,0.1);
            z-index: 60;
            display: flex;
            flex-direction: column;
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        [data-theme="dark"] .notif-panel {
            background: #0f172a;
            border-left-color: #334155;
            box-shadow: -8px 0 32px rgba(0,0,0,0.5);
        }
        
        .notif-panel.open { transform: translateX(0); }

        @media (max-width: 1023px) {
            .notif-panel {
                width: 100%;
                max-width: 400px;
            }
        }

        /* ═══ MISC ═══════════════════════════════════════════════════ */
        .stat-number { font-variant-numeric: tabular-nums; }
        .admin-card { transition: box-shadow 0.2s ease, transform 0.2s ease; }
        .admin-card:hover { box-shadow: 0 8px 28px rgba(0,0,0,0.08); transform: translateY(-1px); }
        [data-theme="dark"] .admin-card:hover { box-shadow: 0 8px 28px rgba(0,0,0,0.5); }

        [x-cloak] { display: none !important; }

        /* Notif dot ring */
        @keyframes notif-ring {
            0%, 100% { transform: rotate(0deg); }
            10%, 30% { transform: rotate(-8deg); }
            20%, 40% { transform: rotate(8deg); }
            50% { transform: rotate(0deg); }
        }
        .notif-bell-ring { animation: notif-ring 2s ease-in-out infinite; }
        
        /* Theme transition */
        html, body, .theme-card, .theme-bg, .sidebar-link, .topbar-blur, .notif-panel {
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }
    </style>
</head>

<body class="h-full transition-colors duration-300 overflow-x-hidden" style="background-color: var(--bg-secondary);" x-data="adminApp()">

    <div id="toast-container" class="fixed top-5 right-5 z-[200] flex flex-col gap-3 pointer-events-none"></div>

    {{-- Mobile sidebar overlay --}}
    <div x-show="mobileMenuOpen" x-transition:enter="transition-opacity duration-300" x-transition:leave="transition-opacity duration-300"
        class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-40 lg:hidden"
        @click="mobileMenuOpen = false" style="display: none;"></div>

    {{-- Notification panel overlay (mobile) --}}
    <div x-show="notifOpen" x-transition:enter="transition-opacity duration-300" x-transition:leave="transition-opacity duration-300"
        class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm z-50 lg:hidden"
        @click="notifOpen = false" style="display: none;"></div>

    {{-- ═══════════════════ SIDEBAR ═══════════════════ --}}
    <aside class="sidebar-bg fixed top-0 left-0 h-full w-64 z-50 flex flex-col transition-transform duration-300 ease-out"
        :class="{ '-translate-x-full': !mobileMenuOpen, 'translate-x-0': mobileMenuOpen, 'lg:translate-x-0': true }" x-cloak>

        {{-- Subtle top glow --}}
        <div class="absolute top-0 left-0 right-0 h-48 pointer-events-none" style="background: radial-gradient(ellipse at 50% -10%, rgba(245,158,11,0.12) 0%, transparent 70%);"></div>

        {{-- Logo area --}}
        <div class="relative z-10 px-5 pt-6 pb-5 flex flex-col items-center text-center">
            {{-- Brand logo --}}
            <div class="relative mb-3">
                <div class="sidebar-logo-card w-[176px] rounded-2xl px-3.5 py-3 shadow-xl shadow-slate-300/40 ring-1 ring-amber-500/25">
                    <x-resto-logo size="sidebar" class="sidebar-logo-light mx-auto" />
                    <x-resto-logo size="sidebar" variant="light" class="sidebar-logo-dark mx-auto" />
                </div>
                {{-- Online indicator --}}
                <span class="sidebar-online absolute -bottom-1 -right-1 w-3 h-3 bg-emerald-500 rounded-full border-2 shadow-sm"></span>
            </div>
            <p class="sidebar-brand-panel text-[10px] font-bold uppercase tracking-[0.2em] mt-0.5">{{ auth()->user()->role_label }} Panel</p>
        </div>

        {{-- Divider --}}
        <div class="sidebar-divider mx-5 border-t mb-3"></div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-0 pb-4">
            @php
                $urlPrefix = match (auth()->user()->role) {
                    'owner'   => 'admin',
                    'cashier' => 'kasir',
                    'kitchen' => 'dapur',
                    default   => 'admin',
                };
                $p        = fn(string $path) => url($urlPrefix . '/' . $path);
                $isActive = fn(string $path) => request()->is($urlPrefix . '/' . $path) || request()->is($urlPrefix . '/' . $path . '/*');
            @endphp

            {{-- Section: Utama --}}
            <div class="mb-5">
                <p class="sidebar-section-label">Utama</p>
                <div class="flex flex-col gap-1">

                    @if(auth()->user()->hasPermission('view_dashboard'))
                    <a href="{{ $p('dashboard') }}" class="sidebar-link {{ $isActive('dashboard') ? 'active' : '' }}">
                        <span class="sidebar-icon-wrap">
                            <svg viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10a2 2 0 01-2 2h-2a2 2 0 01-2-2zm9 0V5a2 2 0 00-2-2h-2a2 2 0 00-2 2v14a2 2 0 002 2h2a2 2 0 002-2z"/></svg>
                        </span>
                        <span class="flex-1 truncate">Dashboard</span>
                    </a>
                    @endif

                    @if(auth()->user()->hasPermission('view_orders'))
                    <a href="{{ $p('orders') }}" class="sidebar-link {{ ($isActive('orders') && !request()->is($urlPrefix.'/orders/history') && !request()->is($urlPrefix.'/orders/scan')) ? 'active' : '' }}">
                        <span class="sidebar-icon-wrap">
                            <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M17 17h.01"/></svg>
                        </span>
                        <span class="flex-1 truncate">Pesanan Masuk</span>
                        <span id="order-badge" style="display:none;" class="badge-pulse text-[9px] font-black px-1.5 py-0.5 bg-red-500 text-white rounded-full shadow-lg shadow-red-500/40">0</span>
                    </a>

                    <a href="{{ $p('orders/history') }}" class="sidebar-link {{ request()->is($urlPrefix.'/orders/history') ? 'active' : '' }}">
                        <span class="sidebar-icon-wrap">
                            <svg viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        <span class="flex-1 truncate">Riwayat Pesanan</span>
                    </a>

                    @if(auth()->user()->canScanOrders())
                    <a href="{{ $p('orders/scan') }}" class="sidebar-link {{ request()->is($urlPrefix.'/orders/scan') ? 'active' : '' }}">
                        <span class="sidebar-icon-wrap">
                            <svg viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 012-2h2M17 3h2a2 2 0 012 2v2m0 10v2a2 2 0 01-2 2h-2M7 21H5a2 2 0 01-2-2v-2"/><rect x="7" y="7" width="10" height="10" rx="1.5"/></svg>
                        </span>
                        <span class="flex-1 truncate">Scan QR / Barcode</span>
                    </a>
                    @endif
                    @endif

                </div>
            </div>

            {{-- Section: Manajemen --}}
            <div class="mb-5">
                <p class="sidebar-section-label">Manajemen</p>
                <div class="flex flex-col gap-1">

                    @if(auth()->user()->hasPermission('manage_menu'))
                    <a href="{{ $p('menu') }}" class="sidebar-link {{ $isActive('menu') ? 'active' : '' }}">
                        <span class="sidebar-icon-wrap">
                            <svg viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h7"/><path d="M15 14l3 3 5-5"/></svg>
                        </span>
                        <span class="flex-1 truncate">Menu Restoran</span>
                    </a>
                    @endif

                    @if(auth()->user()->hasPermission('view_tables'))
                    <a href="{{ $p('tables') }}" class="sidebar-link {{ $isActive('tables') ? 'active' : '' }}">
                        <span class="sidebar-icon-wrap">
                            <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><path d="M3 14h3v3H3v3h3M7 17h.01M7 20h.01"/></svg>
                        </span>
                        <span class="flex-1 truncate">Meja & QR Code</span>
                    </a>
                    @endif

                    @if(auth()->user()->hasPermission('view_sessions'))
                    <a href="{{ $p('sessions') }}" class="sidebar-link {{ $isActive('sessions') ? 'active' : '' }}">
                        <span class="sidebar-icon-wrap">
                            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 8v4l3 3"/></svg>
                        </span>
                        <span class="flex-1 truncate">Sesi Aktif</span>
                    </a>
                    @endif

                    @if(auth()->user()->hasPermission('view_reservations'))
                    <a href="{{ $p('reservations') }}" class="sidebar-link {{ $isActive('reservations') ? 'active' : '' }}">
                        <span class="sidebar-icon-wrap">
                            <svg viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </span>
                        <span class="flex-1 truncate">Reservasi</span>
                    </a>
                    @endif

                    @if(auth()->user()->hasPermission('manage_users'))
                    <a href="{{ $p('employees') }}" class="sidebar-link {{ $isActive('employees') ? 'active' : '' }}">
                        <span class="sidebar-icon-wrap">
                            <svg viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </span>
                        <span class="flex-1 truncate">Karyawan</span>
                    </a>
                    @endif

                </div>
            </div>

            {{-- Section: Laporan & Pengaturan --}}
            <div>
                <p class="sidebar-section-label">Laporan & Sistem</p>
                <div class="flex flex-col gap-1">

                    @if(auth()->user()->hasPermission('view_reports'))
                    <a href="{{ $p('reports') }}" class="sidebar-link {{ $isActive('reports') ? 'active' : '' }}">
                        <span class="sidebar-icon-wrap">
                            <svg viewBox="0 0 24 24"><path d="M18 20V10M12 20V4M6 20v-6"/></svg>
                        </span>
                        <span class="flex-1 truncate">Laporan Penjualan</span>
                    </a>
                    @endif

                    @if(auth()->user()->hasPermission('manage_users'))
                    <a href="{{ $p('settings') }}" class="sidebar-link {{ ($isActive('settings') && !$isActive('employees')) ? 'active' : '' }}">
                        <span class="sidebar-icon-wrap">
                            <svg viewBox="0 0 24 24"><path d="M12 15a3 3 0 100-6 3 3 0 000 6z"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
                        </span>
                        <span class="flex-1 truncate">Pengaturan Akses</span>
                    </a>
                    @endif

                    <a href="{{ $p('profile') }}" class="sidebar-link {{ $isActive('profile') ? 'active' : '' }}">
                        <span class="sidebar-icon-wrap">
                            <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </span>
                        <span class="flex-1 truncate">Profil Saya</span>
                    </a>

                </div>
            </div>
        </nav>

        {{-- Bottom user card --}}
        <div class="sidebar-user-area border-t px-4 py-3.5">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white font-black text-sm shadow-lg shadow-amber-500/30 shrink-0">
                    {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="sidebar-user-name text-[12px] font-bold truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="sidebar-user-email text-[10px] truncate">{{ auth()->user()->email ?? '' }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-1.5 text-slate-500 hover:text-red-400 hover:bg-red-400/10 rounded-lg transition-all" title="Logout">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ═══════════════════ NOTIFICATION PANEL ═══════════════════ --}}
    <div class="notif-panel" :class="notifOpen ? 'open' : ''" id="notif-panel" style="z-index:55;">
        <div class="flex items-center justify-between px-5 py-4 transition-colors" style="border-bottom: 1px solid var(--border-primary);">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-black text-sm" style="color: var(--text-primary);">Notifikasi</h3>
                    <p class="text-[10px] font-medium" style="color: var(--text-tertiary);" x-text="notifUnread > 0 ? notifUnread + ' belum dibaca' : 'Semua sudah dibaca'"></p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button @click="markAllNotifRead()" x-show="notifUnread > 0" class="text-[11px] text-amber-600 font-bold hover:text-amber-700 transition-colors">Tandai semua</button>
                <button @click="notifOpen = false" class="w-8 h-8 flex items-center justify-center rounded-xl transition-all hover:bg-slate-100" style="color: var(--text-tertiary);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto" id="notif-list">
            <template x-if="notifLoading">
                <div class="flex items-center justify-center py-16">
                    <div class="w-6 h-6 border-2 border-amber-500 border-t-transparent rounded-full animate-spin"></div>
                </div>
            </template>
            <template x-if="!notifLoading && notifications.length === 0">
                <div class="flex flex-col items-center justify-center py-16 text-center px-6">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3" style="background-color: var(--bg-tertiary);">
                        <svg class="w-7 h-7" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold" style="color: var(--text-tertiary);">Tidak ada notifikasi</p>
                    <p class="text-xs mt-1" style="color: var(--text-muted);">Semua sudah bersih</p>
                </div>
            </template>
            <template x-for="notif in notifications" :key="notif.id">
                <div class="px-4 py-3.5 transition-colors cursor-pointer hover:opacity-80"
                    :class="!notif.is_read ? 'bg-amber-50/40' : ''"
                    :style="!notif.is_read && document.documentElement.getAttribute('data-theme') === 'dark' ? 'background-color: rgba(245, 158, 11, 0.05);' : ''"
                    style="border-bottom: 1px solid var(--border-primary);"
                    @click="readNotif(notif)">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 mt-0.5"
                            :class="notif.type === 'new_order' ? 'bg-amber-100' : notif.type === 'order_ready' ? 'bg-emerald-100' : 'bg-blue-100'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                :class="notif.type === 'new_order' ? 'text-amber-600' : notif.type === 'order_ready' ? 'text-emerald-600' : 'text-blue-600'">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold leading-tight" style="color: var(--text-primary);" x-text="notif.title || notif.message"></p>
                            <p class="text-xs mt-0.5 line-clamp-2" style="color: var(--text-secondary);" x-text="notif.message || notif.data?.message || ''"></p>
                            <p class="text-[10px] mt-1 font-medium" style="color: var(--text-tertiary);" x-text="notif.created_at_human || notif.created_at"></p>
                        </div>
                        <div x-show="!notif.is_read" class="w-2 h-2 bg-amber-500 rounded-full mt-1.5 shrink-0"></div>
                    </div>
                </div>
            </template>
        </div>

        <div class="px-4 py-3 transition-colors" style="border-top: 1px solid var(--border-primary); background-color: var(--bg-tertiary);">
            <a href="{{ role_route('admin.notifications.index') }}" class="flex items-center justify-center gap-2 text-sm font-bold text-amber-600 hover:text-amber-700 transition-colors py-1">
                Lihat Semua Notifikasi
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>

    {{-- ═══════════════════ MAIN CONTENT ═══════════════════ --}}
    <div class="lg:ml-64 min-h-screen flex flex-col">

        {{-- Topbar --}}
        <header class="topbar-blur sticky top-0 z-30">
            <div class="px-4 sm:px-6 py-3 flex items-center justify-between gap-3">

                {{-- Left: hamburger + title --}}
                <div class="flex items-center gap-3 min-w-0 flex-1">
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="lg:hidden p-2 rounded-xl transition-all active:scale-90 shrink-0 hover:bg-slate-100"
                        style="color: var(--text-secondary);">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <img src="{{ asset('storage/logo-mark.png') }}" alt="Sari Rasa"
                        class="lg:hidden w-8 h-8 object-contain shrink-0">
                    <div class="min-w-0">
                        @php
                            $hour = (int) now()->format('H');
                            $timeGreeting = $hour >= 18 ? 'Selamat Malam,' : ($hour >= 15 ? 'Selamat Sore,' : ($hour >= 12 ? 'Selamat Siang,' : 'Selamat Pagi,'));
                        @endphp
                        <h2 class="text-base sm:text-lg font-black leading-tight tracking-tight truncate" style="color: var(--text-primary);">
                            @yield('greeting', $timeGreeting) <span>{{ auth()->user()->name ?? 'Admin' }}</span>
                        </h2>
                        <p class="text-[11px] font-medium hidden sm:block truncate" style="color: var(--text-tertiary);">
                            @yield('subtitle', 'Ringkasan performa Anda hari ini')
                        </p>
                    </div>
                </div>

                {{-- Right: search + date + theme toggle + notif + profile --}}
                <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">

                    {{-- Search --}}
                    <div x-data="{ showSearch: false, searchQuery: '', searchResults: [], searching: false }" class="relative hidden sm:block">
                        <div class="relative">
                            <input type="text" x-model="searchQuery" @input.debounce.300ms="searchGlobal()" @focus="showSearch = true" @click.outside="showSearch = false"
                                class="w-36 md:w-48 h-8 pl-8 pr-3 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 shadow-sm transition-all placeholder:text-slate-400"
                                style="background-color: var(--input-bg); border: 1px solid var(--input-border); color: var(--text-primary);"
                                placeholder="Cari...">
                            <svg class="w-3.5 h-3.5 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none" style="color: var(--text-tertiary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <div x-show="showSearch && searchQuery.length >= 2" x-transition.opacity.duration.200ms
                            class="absolute right-0 mt-2 w-72 rounded-2xl shadow-xl py-2 z-50" 
                            style="background-color: var(--card-bg); border: 1px solid var(--card-border); display: none;" x-cloak>
                            <div class="px-4 py-2" style="border-bottom: 1px solid var(--border-primary);">
                                <h4 class="text-[10px] font-black uppercase tracking-wider" style="color: var(--text-tertiary);">Hasil Pencarian</h4>
                            </div>
                            <template x-if="searching">
                                <div class="py-6 flex justify-center"><div class="w-5 h-5 border-2 border-amber-500 border-t-transparent rounded-full animate-spin"></div></div>
                            </template>
                            <template x-if="!searching && searchResults.length === 0 && searchQuery.length >= 2">
                                <div class="py-5 text-center"><p class="text-sm" style="color: var(--text-tertiary);">Tidak ditemukan</p></div>
                            </template>
                            <template x-for="result in searchResults" :key="result.title">
                                <a :href="result.url" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50 transition-colors">
                                    <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 bg-amber-100 text-amber-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold truncate" style="color: var(--text-primary);" x-text="result.title"></p>
                                        <p class="text-xs truncate" style="color: var(--text-tertiary);" x-text="result.subtitle"></p>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </div>

                    {{-- Date picker (desktop only) --}}
                    <div class="hidden lg:flex items-center rounded-xl px-2.5 h-8 shadow-sm hover:border-amber-400 transition-colors cursor-pointer gap-1.5" 
                         style="background-color: var(--input-bg); border: 1px solid var(--input-border);" 
                         onclick="this.querySelector('input').focus()">
                        <svg class="w-3.5 h-3.5 shrink-0" style="color: var(--text-tertiary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <input type="text" id="global-date-picker"
                            class="global-flatpickr text-xs font-semibold focus:outline-none w-20 bg-transparent cursor-pointer"
                            style="color: var(--text-primary);"
                            placeholder="Tanggal" value="{{ date('d/m/Y') }}">
                    </div>

                    {{-- Theme Toggle Button --}}
                    <button @click="toggleTheme()" 
                        class="relative flex items-center justify-center w-8 h-8 rounded-xl shadow-sm transition-all active:scale-90 hover:border-amber-400"
                        style="background-color: var(--card-bg); border: 1px solid var(--border-primary);"
                        title="Toggle Dark/Light Mode">
                        <svg x-show="!isDark" class="w-4 h-4" style="color: var(--text-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <svg x-show="isDark" class="w-4 h-4" style="color: var(--text-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </button>

                    {{-- Notification Bell --}}
                    <button @click="toggleNotif()"
                        class="relative flex items-center justify-center w-8 h-8 rounded-xl shadow-sm hover:border-amber-400 hover:bg-amber-50 transition-all active:scale-90"
                        style="background-color: var(--card-bg); border: 1px solid var(--border-primary);"
                        title="Notifikasi">
                        <svg class="w-4 h-4" :class="notifUnread > 0 ? 'notif-bell-ring text-amber-500' : ''" style="color: var(--text-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span x-show="notifUnread > 0" x-text="notifUnread > 99 ? '99+' : notifUnread"
                            class="badge-pulse absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] bg-red-500 text-white text-[9px] font-black rounded-full flex items-center justify-center px-1 shadow-lg border-2 border-white"></span>
                    </button>

                    {{-- Profile avatar --}}
                    <div x-data="{ open: false }" class="relative">
                        <button type="button" @click="open = !open" @click.outside="open = false"
                            class="w-8 h-8 rounded-full overflow-hidden border-2 border-white shadow-sm cursor-pointer hover:ring-2 hover:ring-amber-500 transition-all shrink-0">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=f59e0b&color=fff&bold=true&size=32"
                                alt="Profile" class="w-full h-full object-cover">
                        </button>
                        <div x-show="open" x-transition.opacity.duration.200ms
                            class="absolute right-0 mt-2.5 w-52 rounded-2xl shadow-xl py-2 z-50" 
                            style="background-color: var(--card-bg); border: 1px solid var(--card-border); display: none;" x-cloak>
                            <div class="px-4 py-3 mb-1" style="border-bottom: 1px solid var(--border-primary);">
                                <p class="text-sm font-bold truncate" style="color: var(--text-primary);">{{ auth()->user()->name ?? 'Admin' }}</p>
                                <p class="text-xs truncate" style="color: var(--text-secondary);">{{ auth()->user()->email ?? '' }}</p>
                                <span class="inline-block mt-1.5 px-2 py-0.5 text-[9px] font-black rounded-full uppercase tracking-wider {{ auth()->user()->role === 'owner' ? 'bg-amber-100 text-amber-700' : (auth()->user()->role === 'cashier' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700') }}">{{ auth()->user()->role_label }}</span>
                            </div>
                            <a href="{{ role_route('admin.profile') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm transition-colors hover:bg-amber-50" style="color: var(--text-secondary);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Profil Saya
                            </a>
                            <a href="{{ role_route('admin.notifications.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm transition-colors hover:bg-amber-50" style="color: var(--text-secondary);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                Notifikasi
                            </a>
                            <div style="border-top: 1px solid var(--border-primary);" class="mt-1 pt-1"></div>
                            <form method="POST" action="{{ route('logout') }}" class="block w-full">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 font-medium transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6" id="main-content">

            @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: '{{ session("success") }}', timer: 3000, showConfirmButton: false, toast: true, position: 'top-end' });
                    });
                </script>
            @endif

            @if(session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({ icon: 'error', title: 'Oops...', text: '{{ session("error") }}', timer: 4000, showConfirmButton: false, toast: true, position: 'top-end' });
                    });
                </script>
            @endif

            @yield('content')
        </main>
    </div>

    {{-- ═══ GLOBAL SCRIPTS ═══════════════════════════════════ --}}
    <script>
    function adminApp() {
        return {
            mobileMenuOpen: false,
            notifOpen: false,
            notifUnread: 0,
            notifLoading: false,
            notifications: [],
            _notifFetched: false,
            isDark: false,

            init() {
                // Initialize theme from ThemeManager
                this.isDark = window.themeManager && window.themeManager.isDark();
                
                // Listen for theme changes
                window.addEventListener('theme-changed', (e) => {
                    this.isDark = e.detail.theme === 'dark';
                });
                
                // Fetch unread count on load
                this._pollUnreadCount();
                // Poll every 30s for real-time badge
                setInterval(() => this._pollUnreadCount(), 30000);

                // Real-time via Echo
                if (window.Echo) {
                    window.Echo.channel('admin-orders').listen('.OrderUpdated', (e) => {
                        // Update order badge
                        const badge = document.getElementById('order-badge');
                        if (badge && e.pending_count > 0) {
                            badge.textContent = e.pending_count > 99 ? '99+' : e.pending_count;
                            badge.style.display = 'flex';
                        } else if (badge) {
                            badge.style.display = 'none';
                        }
                        // Refresh notif count
                        this._pollUnreadCount();
                        // If notif panel is open, refresh it
                        if (this.notifOpen) this.fetchNotifications();
                        // Dispatch for orders page
                        window.dispatchEvent(new CustomEvent('order-updated', { detail: e }));
                    });
                }
            },

            toggleTheme() {
                if (window.themeManager) {
                    window.themeManager.toggle();
                    this.isDark = window.themeManager.isDark();
                }
            },

            toggleNotif() {
                this.notifOpen = !this.notifOpen;
                if (this.notifOpen && !this._notifFetched) {
                    this.fetchNotifications();
                }
            },

            async fetchNotifications() {
                this.notifLoading = true;
                try {
                    const res = await fetch('{{ role_route("admin.notifications.latest") }}', {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        credentials: 'same-origin'
                    });
                    const data = await res.json();
                    this.notifications = data.notifications || [];
                    this.notifUnread = data.unread_count || 0;
                    this._notifFetched = true;
                } catch(e) {}
                this.notifLoading = false;
            },

            async readNotif(notif) {
                if (!notif.is_read) {
                    try {
                        await fetch('{{ role_route("admin.notifications.read", ["notification" => "__ID__"]) }}'.replace('__ID__', notif.id), {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                            credentials: 'same-origin'
                        });
                        notif.is_read = true;
                        this.notifUnread = Math.max(0, this.notifUnread - 1);
                    } catch(e) {}
                }
                if (notif.url) window.location.href = notif.url;
            },

            async markAllNotifRead() {
                try {
                    await fetch('{{ role_route("admin.notifications.read-all") }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        credentials: 'same-origin'
                    });
                    this.notifications = this.notifications.map(n => ({ ...n, is_read: true }));
                    this.notifUnread = 0;
                } catch(e) {}
            },

            async _pollUnreadCount() {
                try {
                    const res = await fetch('{{ role_route("admin.notifications.unread-count") }}', {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        credentials: 'same-origin'
                    });
                    const data = await res.json();
                    this.notifUnread = data.unread_count || 0;
                } catch(e) {}
            }
        };
    }

    // ─── Toast ─────────────────────────────────────────────────────────
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const configs = {
            success: { bg: 'linear-gradient(135deg,#059669,#047857)', shadow: 'rgba(5,150,105,0.4)', icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>' },
            error:   { bg: 'linear-gradient(135deg,#dc2626,#b91c1c)', shadow: 'rgba(220,38,38,0.4)',  icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>' },
            info:    { bg: 'linear-gradient(135deg,#2563eb,#1d4ed8)', shadow: 'rgba(37,99,235,0.4)',  icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>' },
            warning: { bg: 'linear-gradient(135deg,#f59e0b,#d97706)', shadow: 'rgba(245,158,11,0.4)', icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>' },
        };
        const cfg = configs[type] || configs.success;
        const toast = document.createElement('div');
        toast.style.cssText = `background:${cfg.bg};box-shadow:0 8px 24px ${cfg.shadow};color:white;padding:12px 16px;border-radius:14px;display:flex;align-items:center;gap:10px;font-size:13px;font-weight:700;pointer-events:auto;transform:translateX(120%);transition:transform 0.35s cubic-bezier(0.34,1.56,0.64,1);min-width:200px;max-width:320px;border:1px solid rgba(255,255,255,0.15);`;
        toast.innerHTML = `<svg style="width:18px;height:18px;flex-shrink:0;" fill="none" stroke="white" viewBox="0 0 24 24">${cfg.icon}</svg><span style="flex:1;">${message}</span>`;
        container.appendChild(toast);
        requestAnimationFrame(() => requestAnimationFrame(() => { toast.style.transform = 'translateX(0)'; }));
        setTimeout(() => { toast.style.transform = 'translateX(120%)'; setTimeout(() => toast.remove(), 350); }, 4000);
    }
    window.showToast = showToast;

    // ─── confirmStatusUpdate ───────────────────────────────────────────
    function confirmStatusUpdate(event, title, icon = 'question') {
        event.preventDefault();
        const form = event.currentTarget;
        Swal.fire({
            title: title, icon: icon,
            showCancelButton: true,
            confirmButtonColor: '#0f172a', confirmButtonText: 'Ya, Lanjutkan!',
            cancelButtonText: 'Batal',
            customClass: { popup: 'rounded-2xl' }
        }).then(async (result) => {
            if (!result.isConfirmed) return;
            Swal.fire({ title: 'Memproses...', allowOutsideClick: false, allowEscapeKey: false, didOpen: () => Swal.showLoading() });
            const formData = new FormData(form);
            try {
                const response = await fetch(form.action, { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }, credentials: 'same-origin' });
                const data = await response.json().catch(() => ({}));
                if (response.ok) {
                    await Swal.fire({ icon: 'success', title: 'Berhasil!', text: data.message || 'Status berhasil diperbarui!', timer: 1800, timerProgressBar: true, showConfirmButton: false, customClass: { popup: 'rounded-2xl' } });
                    if (typeof window.refreshOrdersList === 'function') { window.refreshOrdersList(false); } else { window.location.reload(); }
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: data.message || 'Gagal memperbarui status', customClass: { popup: 'rounded-2xl' } });
                }
            } catch(e) {
                Swal.fire({ icon: 'error', title: 'Koneksi Bermasalah', text: 'Periksa jaringan lalu coba lagi.', customClass: { popup: 'rounded-2xl' } });
            }
        });
        return false;
    }

    function submitOrderStatus(status) {
        const form = document.getElementById('statusForm');
        if (!form) return;
        const titles = { processing: 'Terima pesanan ini dan mulai masak?', ready: 'Pesanan sudah siap saji?', completed: 'Selesaikan pesanan ini?', cancelled: 'Tolak pesanan ini?' };
        document.getElementById('statusInput').value = status;
        confirmStatusUpdate({ preventDefault: () => {}, currentTarget: form }, titles[status] || 'Update status?', status === 'cancelled' ? 'warning' : 'question');
    }

    // ─── openOrderDetail ───────────────────────────────────────────────
    window.openOrderDetail = function(orderId, url) {
        if (!Swal) return;
        Swal.fire({ title: 'Memuat...', didOpen: () => Swal.showLoading() });
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
        .then(res => res.json())
        .then(data => {
            const statusColors = { pending:'background:#fef9c3;color:#a16207', payment_success:'background:#d1fae5;color:#047857', processing:'background:#dbeafe;color:#1d4ed8', ready:'background:#d1fae5;color:#047857', completed:'background:#f1f5f9;color:#475569', cancelled:'background:#fee2e2;color:#b91c1c', pending_confirmation:'background:#ffedd5;color:#c2410c', pending_payment:'background:#fef9c3;color:#a16207' };
            const sc = statusColors[data.status] || statusColors.pending;
            let itemsHtml = data.items.map(item => `<div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid #f1f5f9;"><div style="width:42px;height:42px;background:#f8fafc;border-radius:10px;overflow:hidden;flex-shrink:0;">${item.image_url ? `<img src="${item.image_url}" style="width:100%;height:100%;object-fit:cover;">` : '<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:16px;">🍽️</div>'}</div><div style="flex:1;min-width:0;"><p style="font-size:13px;font-weight:700;color:#1e293b;margin:0;">${item.name}</p><p style="font-size:11px;color:#94a3b8;margin:2px 0 0;"><span style="background:#f59e0b;color:#fff;padding:1px 6px;border-radius:5px;font-weight:800;">${item.quantity}x</span> @ Rp ${Number(item.price).toLocaleString('id-ID')}</p>${item.notes ? `<p style="font-size:10px;color:#d97706;margin:3px 0 0;font-style:italic;">📝 ${item.notes}</p>` : ''}</div><p style="font-size:13px;font-weight:800;color:#0f172a;white-space:nowrap;">Rp ${Number(item.subtotal).toLocaleString('id-ID')}</p></div>`).join('');
            const html = `<div style="text-align:left;"><div style="background:#f8fafc;padding:14px;border-radius:12px;margin-bottom:16px;"><div style="display:flex;align-items:center;justify-content:between;margin-bottom:10px;"><div style="flex:1;"><p style="font-size:20px;font-weight:800;color:#0f172a;margin:0 0 4px;">${data.order_number}</p><p style="font-size:12px;color:#64748b;margin:0;">Meja ${data.table_number || '?'} • ${data.created_at}</p></div><span style="${sc};padding:6px 12px;border-radius:10px;font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:0.05em;">${data.status_label}</span></div></div><div style="margin-bottom:16px;"><h4 style="font-size:11px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:10px;">Item Pesanan</h4>${itemsHtml}</div><div style="background:#f8fafc;padding:14px;border-radius:12px;"><div style="display:flex;justify-content:space-between;margin-bottom:6px;"><span style="font-size:13px;color:#64748b;">Subtotal</span><span style="font-size:13px;font-weight:700;color:#1e293b;">Rp ${Number(data.subtotal).toLocaleString('id-ID')}</span></div>${data.tax_amount ? `<div style="display:flex;justify-content:space-between;margin-bottom:6px;"><span style="font-size:13px;color:#64748b;">Pajak</span><span style="font-size:13px;font-weight:700;color:#1e293b;">Rp ${Number(data.tax_amount).toLocaleString('id-ID')}</span></div>` : ''}<div style="border-top:1px dashed #cbd5e1;margin:8px 0;"></div><div style="display:flex;justify-content:space-between;"><span style="font-size:15px;font-weight:800;color:#0f172a;">Total</span><span style="font-size:17px;font-weight:800;color:#f59e0b;">Rp ${Number(data.total).toLocaleString('id-ID')}</span></div></div></div>`;
            Swal.fire({ html, showConfirmButton: false, showCloseButton: true, width: '520px', customClass: { popup: 'rounded-2xl', closeButton: 'hover:bg-slate-100 rounded-lg transition-all' } });
        })
        .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal memuat detail pesanan' }));
    };
    </script>

    @stack('scripts')
</body>
</html>
