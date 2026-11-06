<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice — {{ $order->order_number }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #e5e7eb;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 24px 12px 48px;
            color: #0f172a;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* ─── Receipt card ─── */
        .receipt-wrap {
            width: 100%;
            max-width: 400px;
        }

        .receipt {
            background: #fff;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,0.12);
        }

        /* ─── Header ─── */
        .receipt-header {
            text-align: center;
            padding: 32px 28px 24px;
            border-bottom: 1px solid #f1f5f9;
        }

        .logo-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
        }

        .logo-img {
            width: 136px;
            height: auto;
            max-height: 66px;
            object-fit: contain;
        }

        .resto-name {
            font-size: 20px;
            font-weight: 900;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .resto-tagline {
            font-size: 11px;
            color: #64748b;
            font-weight: 500;
            margin-bottom: 6px;
        }

        .resto-contact {
            font-size: 11px;
            color: #64748b;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        /* ─── Status stamp ─── */
        .stamp-wrap {
            display: flex;
            justify-content: center;
            padding: 14px 0 10px;
            border-bottom: 1px dashed #e2e8f0;
        }

        .stamp {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 2px solid;
            padding: 5px 16px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 900;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            transform: rotate(-2deg);
        }

        .stamp-paid   { border-color: #16a34a; color: #16a34a; background: #f0fdf4; }
        .stamp-unpaid { border-color: #dc2626; color: #dc2626; background: #fef2f2; }

        /* ─── Info rows ─── */
        .info-section {
            padding: 16px 24px;
            border-bottom: 1px dashed #e2e8f0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            padding: 4px 0;
            font-size: 12.5px;
        }

        .info-row .label { color: #64748b; font-weight: 500; }
        .info-row .value { font-weight: 700; color: #0f172a; text-align: right; }
        .info-row .value.highlight { color: #2563eb; font-weight: 700; }

        /* ─── Items ─── */
        .items-section {
            padding: 16px 24px;
            border-bottom: 1px dashed #e2e8f0;
        }

        .items-header {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #94a3b8;
            padding-bottom: 10px;
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: 2px;
        }

        .item-row {
            padding: 9px 0;
            border-bottom: 1px dashed #f8fafc;
        }

        .item-row:last-child { border-bottom: none; }

        .item-name {
            font-size: 13px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 2px;
        }

        .item-detail {
            font-size: 11px;
            color: #94a3b8;
            font-weight: 500;
        }

        .item-note {
            font-size: 10.5px;
            color: #d97706;
            font-style: italic;
            margin-top: 2px;
        }

        .item-total {
            font-size: 13px;
            font-weight: 800;
            color: #0f172a;
            text-align: right;
            white-space: nowrap;
        }

        .item-flex {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
        }

        /* ─── Totals ─── */
        .totals-section {
            padding: 14px 24px;
            border-bottom: 2px solid #0f172a;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 3px 0;
            font-size: 12.5px;
        }

        .total-row .label { color: #64748b; font-weight: 500; }
        .total-row .value { font-weight: 600; color: #334155; }

        .grand-total-section {
            padding: 14px 24px 16px;
            border-bottom: 1px dashed #e2e8f0;
        }

        .grand-total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .grand-total-label {
            font-size: 16px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #0f172a;
        }

        .grand-total-amount {
            font-size: 22px;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: -0.02em;
        }

        /* ─── QR & Footer ─── */
        .qr-section {
            padding: 20px 24px;
            text-align: center;
            border-bottom: 1px dashed #e2e8f0;
        }

        .qr-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 12px;
        }

        .qr-box {
            display: inline-flex;
            padding: 10px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            background: #fff;
        }

        .footer-section {
            padding: 16px 24px 24px;
            text-align: center;
        }

        .footer-text {
            font-size: 11px;
            color: #94a3b8;
            line-height: 1.6;
        }

        /* ─── Barcode decoration ─── */
        .barcode-wrap {
            display: flex;
            justify-content: center;
            gap: 1px;
            margin-top: 14px;
            opacity: 0.15;
        }

        .barcode-wrap span {
            display: block;
            width: 2px;
            background: #0f172a;
        }

        /* ─── Action buttons ─── */
        .actions {
            width: 100%;
            max-width: 400px;
            margin-top: 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 16px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn:active { transform: scale(0.97); }
        .btn-print  { background: #0f172a; color: #fff; box-shadow: 0 4px 16px rgba(15,23,42,0.2); }
        .btn-print:hover  { background: #1e293b; }
        .btn-amber  { background: #f59e0b; color: #fff; box-shadow: 0 4px 16px rgba(245,158,11,0.35); }
        .btn-amber:hover  { background: #d97706; }
        .btn-back   { background: #fff; color: #334155; border: 1.5px solid #e2e8f0; }
        .btn-back:hover   { border-color: #94a3b8; }

        /* ─── Print media ─── */
        @media print {
            @page { size: A5 portrait; margin: 10mm 8mm; }
            body { background: #fff; padding: 0; display: block; }
            .receipt { box-shadow: none; border: none; }
            .actions { display: none !important; }
            .barcode-wrap { opacity: 0.2; }
        }

        /* ─── Mobile ─── */
        @media (max-width: 480px) {
            body { padding: 12px 8px 40px; }
            .receipt-header { padding: 24px 20px 18px; }
            .info-section, .items-section, .totals-section,
            .grand-total-section, .qr-section, .footer-section { padding-left: 18px; padding-right: 18px; }
        }
    </style>
</head>
<body>

<div class="receipt-wrap" id="receipt-capture">
    <div class="receipt">

        {{-- ── Header ── --}}
        <div class="receipt-header">
            <div class="logo-wrap">
                @if(file_exists(public_path('storage/logo.png')))
                    <img src="{{ asset('storage/logo.png') }}" class="logo-img" alt="Sari Rasa">
                @else
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="#0f172a">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/>
                    </svg>
                @endif
            </div>
            <div class="resto-tagline">Masakan Autentik Indonesia</div>
            <div class="resto-contact">
                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Sistem Pemesanan Digital
            </div>
        </div>

        {{-- ── Status Stamp ── --}}
        <div class="stamp-wrap">
            @php $isPaid = in_array($order->status, ['processing', 'ready', 'completed', 'payment_success']); @endphp
            <div class="stamp {{ $isPaid ? 'stamp-paid' : 'stamp-unpaid' }}">
                @if($isPaid)
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    ★ LUNAS ★
                @else
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Belum Lunas
                @endif
            </div>
        </div>

        {{-- ── Info ── --}}
        <div class="info-section">
            <div class="info-row">
                <span class="label">Invoice</span>
                <span class="value">#{{ $order->order_number }}</span>
            </div>
            <div class="info-row">
                <span class="label">Tanggal</span>
                <span class="value">{{ $order->created_at->format('d M Y') }}</span>
            </div>
            <div class="info-row">
                <span class="label">Waktu</span>
                <span class="value">{{ $order->created_at->format('H:i') }} WIB</span>
            </div>
            <div class="info-row">
                <span class="label">Meja</span>
                <span class="value">Meja #{{ $order->table?->number ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="label">Pelanggan</span>
                <span class="value">{{ $order->customer_name ?? 'Tamu' }}</span>
            </div>
            @if($order->customer_phone)
            <div class="info-row">
                <span class="label">Telepon</span>
                <span class="value">{{ $order->customer_phone }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="label">Pembayaran</span>
                <span class="value">{{ $order->payment_method === 'cashier' ? 'Tunai / Kasir' : 'Online (QRIS/e-Wallet)' }}</span>
            </div>
            <div class="info-row">
                <span class="label">Status</span>
                <span class="value {{ $isPaid ? '' : 'highlight' }}">{{ $order->status_label }}</span>
            </div>
        </div>

        {{-- ── Items ── --}}
        <div class="items-section">
            <div class="items-header">
                <span>Item</span>
                <span>Total</span>
            </div>

            @foreach($order->items as $item)
            <div class="item-row">
                <div class="item-flex">
                    <div style="flex:1;min-width:0;">
                        <div class="item-name">{{ $item->menuItem?->name ?? 'Menu Dihapus' }}</div>
                        <div class="item-detail">{{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                        @if($item->notes)
                            <div class="item-note">↳ {{ $item->notes }}</div>
                        @endif
                    </div>
                    <div class="item-total">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                </div>
            </div>
            @endforeach

            @if($order->notes)
            <div style="margin-top:10px;padding:8px 12px;background:#fffbeb;border-radius:8px;border:1px solid #fde68a;">
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#92400e;margin-bottom:3px;">Catatan Pesanan</div>
                <div style="font-size:11.5px;color:#b45309;font-style:italic;">{{ $order->notes }}</div>
            </div>
            @endif
        </div>

        {{-- ── Totals ── --}}
        <div class="totals-section">
            <div class="total-row">
                <span class="label">Subtotal ({{ $order->items->sum('quantity') }} item)</span>
                <span class="value">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span class="label">PPN (11%)</span>
                <span class="value">Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
            </div>
            <div class="total-row" style="margin-top:4px;padding-top:6px;border-top:1px dotted #e2e8f0;">
                <span style="font-size:12px;font-weight:700;color:#16a34a;">
                    Status Pembayaran
                </span>
                <span style="font-size:12px;font-weight:900;color:{{ $isPaid ? '#16a34a' : '#dc2626' }};">
                    {{ $isPaid ? 'LUNAS' : 'BELUM LUNAS' }}
                </span>
            </div>
        </div>

        {{-- ── Grand Total ── --}}
        <div class="grand-total-section">
            <div class="grand-total-row">
                <span class="grand-total-label">TOTAL</span>
                <span class="grand-total-amount">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- ── QR Code ── --}}
        <div class="qr-section">
            <div class="qr-label">Scan untuk melacak pesanan</div>
            <div class="qr-box">
                {!! QrCode::size(110)->margin(0)->color(15, 23, 42)->generate(route('order.tracking', $order->id)) !!}
            </div>
        </div>

        {{-- ── Footer ── --}}
        <div class="footer-section">
            <div class="footer-text">
                Terima kasih telah berkunjung ke Sari Rasa.<br>
                Selamat menikmati hidangan Anda!
            </div>

            {{-- Decorative barcode --}}
            <div class="barcode-wrap">
                @php $seed = crc32($order->order_number); @endphp
                @for($i = 0; $i < 50; $i++)
                    @php $h = 18 + (($seed * ($i + 1)) % 14); @endphp
                    <span style="height:{{ $h }}px;{{ $i % 3 === 0 ? 'width:1.5px;' : 'width:2px;' }}"></span>
                @endfor
            </div>
        </div>

    </div>
</div>

{{-- ── Action buttons ── --}}
<div class="actions" id="action-bar">
    <button class="btn btn-print" onclick="window.print()">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z"/>
        </svg>
        Cetak / Simpan PDF
    </button>

    <button class="btn btn-amber" id="btn-png" onclick="downloadPNG()">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
        </svg>
        Simpan sebagai Gambar
    </button>

    <a href="javascript:history.back()" class="btn btn-back">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
    </a>
</div>

<script>
    async function downloadPNG() {
        const btn = document.getElementById('btn-png');
        const orig = btn.innerHTML;
        btn.innerHTML = '<span>Membuat gambar...</span>';
        btn.style.opacity = '0.6';
        btn.style.pointerEvents = 'none';
        document.getElementById('action-bar').style.display = 'none';

        try {
            const target = document.getElementById('receipt-capture');
            const canvas = await html2canvas(target, {
                scale: 3,
                useCORS: true,
                backgroundColor: '#e5e7eb',
                logging: false,
            });
            const link = document.createElement('a');
            link.download = 'invoice-{{ $order->order_number }}.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        } catch (e) {
            alert('Gagal membuat gambar. Coba lagi.');
        } finally {
            document.getElementById('action-bar').style.display = '';
            btn.innerHTML = orig;
            btn.style.opacity = '';
            btn.style.pointerEvents = '';
        }
    }
</script>
</body>
</html>
