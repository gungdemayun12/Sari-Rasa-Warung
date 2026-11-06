<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\RestaurantTable;
use App\Models\TableSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QrScanController extends Controller
{

    public function scan($qrToken)
    {
        try {

            $table = RestaurantTable::where('qr_token', $qrToken)->first();

            if (!$table) {
                return view('customer.error', [
                    'title' => 'QR Code Tidak Valid',
                    'message' => 'QR code yang Anda scan tidak terdaftar dalam sistem.',
                ]);
            }

            if ($table->status === 'reserved') {
                return view('customer.error', [
                    'title' => 'Meja Telah Direservasi',
                    'message' => 'Maaf, meja ini sudah dipesan melalui reservasi. Silakan hubungi staf kami.',
                ]);
            }

            $activeSession = TableSession::getActiveSessionForTable($table->id);

            if ($activeSession) {

                return redirect()->route('customer.form', [
                    'session' => $activeSession->session_token
                ]);
            }

            if ($table->status === 'occupied' && !$activeSession) {

                $table->update(['status' => 'available']);
            }

            if ($table->status !== 'available') {
                return view('customer.error', [
                    'title' => 'Meja Tidak Tersedia',
                    'message' => 'Meja ini sedang digunakan atau sedang dalam pembersihan.',
                ]);
            }

            $session = TableSession::createForTable($table->id, 3);

            $table->update(['status' => 'occupied']);

            Log::info('New table session created', [
                'table_id' => $table->id,
                'table_number' => $table->number,
                'session_token' => $session->session_token,
            ]);

            return redirect()->route('customer.form', [
                'session' => $session->session_token
            ]);

        } catch (\Exception $e) {
            Log::error('QR Scan Error: ' . $e->getMessage());

            return view('customer.error', [
                'title' => 'Terjadi Kesalahan',
                'message' => 'Maaf, terjadi kesalahan saat memproses QR code. Silakan coba lagi.',
            ]);
        }
    }

    public function showForm(Request $request)
    {
        $sessionToken = $request->query('session');

        $session = TableSession::where('session_token', $sessionToken)
            ->with('table')
            ->first();

        if (!$session) {
            return view('customer.error', [
                'title' => 'Session Tidak Valid',
                'message' => 'Session Anda tidak ditemukan. Silakan scan QR code lagi.',
            ]);
        }

        if (!$session->isActive()) {
            return view('customer.session-ended');
        }

        $customerData = session()->get('customer_data_' . $sessionToken);
        if ($customerData) {

            return redirect()->route('customer.menu', ['session' => $sessionToken]);
        }

        return view('customer.form', [
            'session' => $session,
            'tableNumber' => $session->table->number,
        ]);
    }

    public function storeCustomerData(Request $request)
    {
        $request->validate([
            'session_token' => ['required', 'string', 'max:100'],
            'customer_name' => [
                'required', 'string', 'min:2', 'max:50',
                'regex:/^[\p{L}\p{M}][\p{L}\p{M}\s.\'’\-]*$/u',
            ],
            'customer_phone' => ['required', 'string', 'max:20'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ], [
            'customer_name.required' => 'Nama lengkap wajib diisi.',
            'customer_name.min' => 'Nama minimal 2 karakter.',
            'customer_name.max' => 'Nama maksimal 50 karakter.',
            'customer_name.regex' => 'Nama hanya boleh berisi huruf, spasi, titik, apostrof, dan tanda hubung.',
            'customer_phone.required' => 'Nomor WhatsApp wajib diisi.',
            'customer_phone.max' => 'Nomor WhatsApp terlalu panjang.',
            'latitude.numeric' => 'Koordinat lokasi tidak valid.',
            'longitude.numeric' => 'Koordinat lokasi tidak valid.',
        ]);

        $sessionToken = $request->session_token;

        $session = TableSession::where('session_token', $sessionToken)->first();
        if (!$session || !$session->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi meja tidak valid atau sudah berakhir. Silakan scan ulang QR code.',
                'errors' => [
                    'session_token' => ['Sesi meja tidak valid atau sudah berakhir. Silakan scan ulang QR code.'],
                ],
            ], 403);
        }

        // Normalize name: collapse extra whitespace.
        $name = preg_replace('/\s+/u', ' ', trim((string) $request->customer_name));

        // Normalize phone: keep digits, drop Indonesian country/leading zero.
        $phone = preg_replace('/\D+/', '', (string) $request->customer_phone);
        if (str_starts_with($phone, '62')) {
            $phone = substr($phone, 2);
        } elseif (str_starts_with($phone, '0')) {
            $phone = substr($phone, 1);
        }

        if (!preg_match('/^8\d{8,12}$/', $phone)) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor WhatsApp tidak valid.',
                'errors' => [
                    'customer_phone' => ['Nomor WhatsApp harus diawali angka 8 dan terdiri dari 9–13 digit (contoh: 81234567890).'],
                ],
            ], 422);
        }

        session()->put('customer_data_' . $sessionToken, [
            'name' => $name,
            'phone' => '62' . $phone,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan',
            'redirect' => route('customer.menu', ['session' => $sessionToken]),
        ]);
    }

    public function validateSession(Request $request)
    {
        $sessionToken = $request->input('session_token');

        $session = TableSession::where('session_token', $sessionToken)
            ->with('table')
            ->first();

        if (!$session) {
            return response()->json([
                'valid' => false,
                'message' => 'Session tidak ditemukan',
            ], 404);
        }

        if (!$session->isActive()) {
            return response()->json([
                'valid' => false,
                'message' => 'Session sudah tidak aktif atau expired',
                'expired' => true,
            ], 403);
        }

        return response()->json([
            'valid' => true,
            'session' => [
                'token' => $session->session_token,
                'table_number' => $session->table->number,
                'expires_at' => $session->expires_at->format('Y-m-d H:i:s'),
                'remaining_minutes' => now()->diffInMinutes($session->expires_at),
            ],
        ]);
    }
}