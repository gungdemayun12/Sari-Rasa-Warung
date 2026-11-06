<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\TableSession;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $sessionToken = $request->query('session');

        $session = TableSession::where('session_token', $sessionToken)
            ->with('table')
            ->first();

        if (!$session || !$session->isActive()) {
            return view('customer.error', [
                'title' => 'Session Tidak Valid',
                'message' => 'Session Anda tidak valid atau sudah expired.',
            ]);
        }

        $customerData = session()->get('customer_data_' . $sessionToken);
        if (!$customerData) {
            return redirect()->route('customer.form', ['session' => $sessionToken]);
        }

        $cart = session()->get('cart_' . $sessionToken, []);

        $subtotal = collect($cart)->sum('subtotal');
        $tax = round($subtotal * 0.11);
        $total = $subtotal + $tax;

        return view('customer.checkout', [
            'session' => $session,
            'customerData' => $customerData,
            'cart' => $cart,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
        ]);
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'session_token' => ['required', 'string', 'max:100'],
            'payment_method' => ['required', 'string', 'in:cashier,online'],
            'notes' => ['nullable', 'string', 'max:300'],
            'items' => ['required', 'array', 'min:1', 'max:50'],
            'items.*.menu_item_id' => ['required', 'integer', 'distinct', 'exists:menu_items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:99'],
            'items.*.notes' => ['nullable', 'string', 'max:100'],
        ], [
            'session_token.required' => 'Sesi meja tidak ditemukan.',
            'payment_method.required' => 'Pilih metode pembayaran terlebih dahulu.',
            'payment_method.in' => 'Metode pembayaran tidak valid.',
            'notes.max' => 'Catatan pesanan maksimal 300 karakter.',
            'items.required' => 'Keranjang masih kosong.',
            'items.min' => 'Keranjang masih kosong.',
            'items.max' => 'Terlalu banyak jenis item dalam satu pesanan.',
            'items.*.menu_item_id.required' => 'Ada item pesanan yang tidak valid.',
            'items.*.menu_item_id.exists' => 'Ada menu yang sudah tidak tersedia.',
            'items.*.menu_item_id.distinct' => 'Ada menu yang terduplikasi di keranjang.',
            'items.*.quantity.required' => 'Jumlah item wajib diisi.',
            'items.*.quantity.min' => 'Jumlah item minimal 1.',
            'items.*.quantity.max' => 'Jumlah item maksimal 99.',
            'items.*.notes.max' => 'Catatan per item maksimal 100 karakter.',
        ]);

        try {
            DB::beginTransaction();

            $session = TableSession::where('session_token', $request->session_token)
                ->with('table')
                ->first();

            if (!$session || !$session->isActive()) {
                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => 'Sesi meja tidak valid atau sudah berakhir. Silakan scan ulang QR code.',
                ], 403);
            }

            $customerData = session()->get('customer_data_' . $request->session_token);
            if (!$customerData) {
                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => 'Data pelanggan tidak ditemukan. Silakan isi data diri kembali.',
                ], 400);
            }

            $items = $validated['items'];

            $subtotal = 0;
            $orderItemsData = [];

            foreach ($items as $item) {
                $menuItem = MenuItem::whereKey($item['menu_item_id'])
                    ->where('is_available', true)
                    ->first();

                if (!$menuItem) {
                    DB::rollBack();

                    return response()->json([
                        'success' => false,
                        'message' => 'Salah satu menu sudah tidak tersedia. Silakan perbarui keranjang Anda.',
                        'errors' => [
                            'items' => ['Salah satu menu sudah tidak tersedia. Silakan perbarui keranjang Anda.'],
                        ],
                    ], 422);
                }

                $itemSubtotal = $menuItem->price * $item['quantity'];
                $subtotal += $itemSubtotal;

                $orderItemsData[] = [
                    'menu_item_id' => $menuItem->id,
                    'quantity' => $item['quantity'],
                    'price' => $menuItem->price,
                    'subtotal' => $itemSubtotal,
                    'notes' => isset($item['notes']) ? trim($item['notes']) : null,
                ];
            }

            $tax = round($subtotal * 0.11);
            $total = $subtotal + $tax;

            $order = Order::create([
                'table_id' => $session->table_id,
                'session_token' => $session->session_token,
                'order_number' => Order::generateOrderNumber(),
                'customer_name' => $customerData['name'],
                'customer_phone' => $customerData['phone'],
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'unpaid',
                'status' => $validated['payment_method'] === 'online' ? 'pending_payment' : 'pending_confirmation',
                'notes' => isset($validated['notes']) ? trim($validated['notes']) : null,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total_amount' => $total,
            ]);

            foreach ($orderItemsData as $itemData) {
                $itemData['order_id'] = $order->id;
                OrderItem::create($itemData);
            }

            session()->forget('cart_' . $request->session_token);

            DB::commit();

            if ($validated['payment_method'] === 'cashier') {
                return response()->json([
                    'success' => true,
                    'message' => 'Order berhasil dibuat, silakan tunjukkan barcode ke kasir',
                    'redirect' => route('customer.order.barcode', ['order' => $order->order_number]),
                ]);
            }

            if ($validated['payment_method'] === 'online') {
                try {
                    $this->generateMidtransSnapToken($order);

                    return response()->json([
                        'success' => true,
                        'message' => 'Order berhasil dibuat',
                        'redirect' => route('customer.payment', ['order' => $order->order_number]),
                    ]);
                } catch (\Exception $e) {
                    Log::error('Midtrans Snap Token Error: ' . $e->getMessage());

                    return response()->json([
                        'success' => true,
                        'message' => 'Order dibuat, silakan bayar di kasir (Midtrans error)',
                        'redirect' => route('customer.order.success', ['order' => $order->order_number]),
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Order berhasil dibuat',
                'redirect' => route('customer.order.success', ['order' => $order->order_number]),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses order: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function configureMidtrans(): void
    {
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('services.midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('services.midtrans.is_3ds');
    }

    private function generateMidtransSnapToken($order)
    {
        $this->configureMidtrans();

        $order->load('items.menuItem');

        $midtransOrderId = $order->order_number . '-' . time();

        $itemDetails = $order->items->map(function ($item) {
            return [
                'id' => 'item-' . $item->menu_item_id,
                'price' => (int)$item->price,
                'quantity' => (int)$item->quantity,
                'name' => substr($item->menuItem->name, 0, 50),
            ];
        })->toArray();

        if ($order->tax > 0) {
            $itemDetails[] = [
                'id' => 'tax',
                'price' => (int)$order->tax,
                'quantity' => 1,
                'name' => 'Pajak (11%)',
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id' => $midtransOrderId,
                'gross_amount' => (int)$order->total_amount,
            ],
            'customer_details' => [
                'first_name' => $order->customer_name,
                'phone' => $order->customer_phone,
            ],
            'item_details' => $itemDetails,
            'enabled_payments' => ['gopay', 'shopeepay', 'qris', 'bank_transfer'],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);
        $order->update([
            'snap_token' => $snapToken,
            'payment_reference' => $midtransOrderId,
        ]);

        return $snapToken;
    }

    public function barcode($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['table', 'items.menuItem'])
            ->firstOrFail();

        if ($order->status !== 'pending_confirmation') {
            return redirect()->route('customer.order.success', ['order' => $orderNumber]);
        }

        return view('customer.order-barcode', [
            'order' => $order,
        ]);
    }

    public function checkConfirmation($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        return response()->json([
            'confirmed' => $order->status !== 'pending_confirmation',
            'status' => $order->status,
        ]);
    }

    public function success($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['table', 'items.menuItem'])
            ->firstOrFail();

        return view('customer.order-success', [
            'order' => $order,
        ]);
    }

    public function receipt(Request $request, $orderNumber)
{
    $order = Order::where('order_number', $orderNumber)
        ->with(['table', 'items.menuItem'])
        ->firstOrFail();

    return view('customer.order-receipt', [
        'order' => $order,
    ]);
}

    public function payment($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['table'])
            ->firstOrFail();

        if ($order->payment_method !== 'online' || $order->status !== 'pending_payment') {
            return redirect()->route('order.tracking', ['order' => $order->id]);
        }

        return view('customer.payment', [
            'order' => $order,
        ]);
    }

    public function verifyPayment(Request $request, $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        if ($order->payment_method !== 'online') {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Metode pembayaran tidak dapat diverifikasi.']);
            }

            return redirect()->route('customer.order.success', ['order' => $orderNumber]);
        }

        if (in_array($order->status, ['processing', 'ready', 'completed'], true) || $order->payment_status === 'paid') {
            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'status' => $order->status]);
            }

            return redirect()->route('order.tracking', ['order' => $order->id]);
        }

        if (!$order->payment_reference) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Informasi pembayaran tidak tersedia.'], 422);
            }

            return redirect()->route('customer.payment', ['order' => $orderNumber])->with('error', 'Tidak dapat memverifikasi pembayaran.');
        }

        try {
            $this->configureMidtrans();
            $transaction = \Midtrans\Transaction::status($order->payment_reference);
            $transactionStatus = null;

            if (is_array($transaction)) {
                $transactionStatus = $transaction['transaction_status'] ?? null;
            } elseif (is_object($transaction)) {
                $transactionStatus = $transaction->transaction_status ?? null;
            }

            if (in_array($transactionStatus, ['capture', 'settlement'])) {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                    'paid_at' => now(),
                ]);
            } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'])) {
                $order->update([
                    'payment_status' => 'failed',
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Midtrans verify payment error: ' . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Verifikasi pembayaran gagal.'], 500);
            }

            return redirect()->route('customer.payment', ['order' => $orderNumber])->with('error', 'Gagal memverifikasi pembayaran.');
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'status' => $order->status]);
        }

        return redirect()->route('order.tracking', ['order' => $order->id]);
    }

    public function switchToCashier($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        if ($order->payment_status === 'unpaid' && $order->status === 'pending_payment') {
            $order->update([
                'payment_method' => 'cashier',
                'status' => 'pending_confirmation',
            ]);
        }

        return redirect()->route('customer.order.barcode', ['order' => $orderNumber]);
    }

    public function paymentCallback(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            // Midtrans order_id format: "{order_number}-{timestamp}"
            // Order numbers contain hyphens (e.g. ORD-20260611-0001), so we must strip
            // the last segment (timestamp) instead of using explode('-', ...)[0]
            $midtransOrderId = $request->order_id;
            $parts = explode('-', $midtransOrderId);
            // Remove the last segment (timestamp) to reconstruct original order number
            array_pop($parts);
            $orderNumber = implode('-', $parts);

            $order = Order::where('order_number', $orderNumber)->first();

            if ($order) {
                $payload = ['payment_reference' => $request->order_id];

                if (in_array($request->transaction_status, ['capture', 'settlement'])) {
                    $payload['payment_status'] = 'paid';
                    $payload['status'] = 'processing';
                    $payload['paid_at'] = now();
                } elseif (in_array($request->transaction_status, ['deny', 'cancel', 'expire', 'failure'])) {
                    $payload['payment_status'] = 'failed';
                }

                $order->update($payload);
            }
        }

        return response()->json(['success' => true]);
    }
}