<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#f8fafc">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Menu') - Sari Rasa</title>
    <meta name="description" content="Pesan makanan dan minuman favorit Anda di Sari Rasa. Menu digital dengan pemesanan mudah langsung dari meja Anda.">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }

        /* Smooth scrollbar hide */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Customer flow tuning */
        html { -webkit-text-size-adjust: 100%; scroll-behavior: smooth; }
        body { overflow-x: hidden; }
        button, a, [role="button"] { touch-action: manipulation; }
        [x-cloak] { display: none !important; }
        img, svg { max-width: 100%; }

        /* Prevent iOS zoom on focus while keeping compact text on larger screens */
        @media (max-width: 767px) {
            input:not([type="checkbox"]):not([type="radio"]),
            select,
            textarea {
                font-size: 16px !important;
            }
        }

        /* Unified focus ring for the customer flow */
        .c-input:focus {
            outline: none;
            border-color: #f59e0b;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.14);
            background-color: #fff;
        }

        /* SweetAlert2: fits small phones */
        .swal2-popup {
            border-radius: 1.5rem !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            padding: 1.25rem 1rem 1.15rem !important;
        }
        .swal2-title { font-size: 1.1rem !important; font-weight: 800 !important; }
        .swal2-html-container { font-size: 0.85rem !important; }
        .swal2-icon {
            width: 3.5rem !important;
            height: 3.5rem !important;
            margin: .5rem auto 1rem !important;
        }
        .swal2-icon .swal2-icon-content { font-size: 2.25rem !important; }
        .swal2-actions button {
            font-size: .8rem !important;
            white-space: nowrap !important;
            min-height: 2.75rem !important;
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }
        @media (max-width: 480px) {
            .swal2-popup { width: calc(100vw - 2rem) !important; }
            .swal2-actions { width: 100% !important; gap: .5rem !important; }
            .swal2-actions button { flex: 1 1 0 !important; margin: 0 !important; }
        }

        .slide-up-enter { transform: translateY(100%); }
        .slide-up-enter-active { transform: translateY(0); transition: transform 0.3s ease-out; }

        @keyframes badgePulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }
        .badge-pulse { animation: badgePulse 0.3s ease-out; }

        .skeleton {
            background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Premium card hover ring */
        .menu-item:not(.grayscale):hover {
            box-shadow: 0 20px 40px rgba(245,158,11,0.12), 0 4px 12px rgba(0,0,0,0.06);
        }

        /* Safe area for notch phones */
        .safe-area-top    { padding-top:    env(safe-area-inset-top, 0); }
        .safe-area-bottom { padding-bottom: env(safe-area-inset-bottom, 0); }

        /* Touch tap highlight remove */
        * { -webkit-tap-highlight-color: transparent; }

        /* Input focus glow */
        input:focus-visible, select:focus-visible, textarea:focus-visible {
            outline: none;
        }

        /* Smooth scrollbar hide */
    </style>
</head>
<body class="h-full bg-slate-50">

    <div id="toast-container" class="fixed top-5 left-1/2 -translate-x-1/2 z-[200] space-y-2 w-[90vw] max-w-sm pointer-events-none"></div>

    @yield('content')

    <script>
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');

            const configs = {
                success: {
                    bg: 'linear-gradient(135deg, #059669, #047857)',
                    shadow: 'rgba(5,150,105,0.4)',
                    icon: `<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>`
                },
                error: {
                    bg: 'linear-gradient(135deg, #dc2626, #b91c1c)',
                    shadow: 'rgba(220,38,38,0.4)',
                    icon: `<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>`
                },
                info: {
                    bg: 'linear-gradient(135deg, #2563eb, #1d4ed8)',
                    shadow: 'rgba(37,99,235,0.4)',
                    icon: `<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`
                },
                cart: {
                    bg: 'linear-gradient(135deg, #d97706, #b45309)',
                    shadow: 'rgba(217,119,6,0.4)',
                    icon: `<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/><circle cx="9" cy="21" r="1" fill="currentColor" stroke="none"/><circle cx="20" cy="21" r="1" fill="currentColor" stroke="none"/></svg>`
                },
                warning: {
                    bg: 'linear-gradient(135deg, #f59e0b, #d97706)',
                    shadow: 'rgba(245,158,11,0.4)',
                    icon: `<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>`
                }
            };

            const cfg = configs[type] || configs.success;
            toast.style.cssText = `
                background: ${cfg.bg};
                box-shadow: 0 8px 32px ${cfg.shadow}, 0 2px 8px rgba(0,0,0,0.12);
                color: white;
                padding: 14px 18px;
                border-radius: 16px;
                display: flex;
                align-items: center;
                gap: 12px;
                font-size: 13px;
                font-weight: 700;
                pointer-events: auto;
                transform: translateY(-16px) scale(0.95);
                opacity: 0;
                transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
                position: relative;
                overflow: hidden;
                border: 1px solid rgba(255,255,255,0.2);
            `;
            toast.innerHTML = `
                <div style="background:rgba(255,255,255,0.2);border-radius:10px;padding:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    ${cfg.icon}
                </div>
                <span style="flex:1;line-height:1.4;">${message}</span>
                <button onclick="this.closest('div').remove()" style="background:rgba(255,255,255,0.15);border:none;color:white;cursor:pointer;padding:4px;border-radius:8px;display:flex;align-items:center;flex-shrink:0;transition:background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div style="position:absolute;bottom:0;left:0;height:3px;background:rgba(255,255,255,0.4);border-radius:0 0 16px 16px;animation:toastProgress 3s linear forwards;"></div>
            `;

            container.appendChild(toast);
            requestAnimationFrame(() => {
                toast.style.transform = 'translateY(0) scale(1)';
                toast.style.opacity = '1';
            });

            setTimeout(() => {
                toast.style.transform = 'translateY(-8px) scale(0.95)';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 3200);
        }
    </script>

    <style>
        @keyframes toastProgress {
            from { width: 100%; }
            to { width: 0%; }
        }
    </style>

    @stack('scripts')
</body>
</html>