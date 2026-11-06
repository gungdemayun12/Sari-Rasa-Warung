<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReservationController extends Controller
{
    public function index()
    {
        return view('customer.reservation.index');
    }

    private function normalizePhone(?string $value): string
    {
        $digits = preg_replace('/\D+/', '', (string) $value);

        if (str_starts_with($digits, '62')) {
            $digits = substr($digits, 2);
        } elseif (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        return $digits;
    }

    private function timeSlots(): array
    {
        $slots = [];
        for ($hour = 10; $hour <= 21; $hour++) {
            $slots[] = sprintf('%02d:00', $hour);
            $slots[] = sprintf('%02d:30', $hour);
        }
        return $slots;
    }

    public function store(Request $request)
    {
        $request->merge([
            'name' => preg_replace('/\s+/u', ' ', trim((string) $request->name)),
            'phone' => $this->normalizePhone($request->phone),
            'notes' => $request->notes !== null ? trim((string) $request->notes) : null,
        ]);

        $validated = $request->validate([
            'name' => [
                'required', 'string', 'min:2', 'max:60',
                'regex:/^[\p{L}\p{M}][\p{L}\p{M}\s.\'’\-]*$/u',
            ],
            'phone' => ['required', 'string', 'regex:/^8\d{8,12}$/'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time' => ['required', 'string', Rule::in($this->timeSlots())],
            'guests' => ['required', 'integer', 'min:1', 'max:20'],
            'notes' => ['nullable', 'string', 'max:300'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.min' => 'Nama minimal 2 karakter.',
            'name.max' => 'Nama maksimal 60 karakter.',
            'name.regex' => 'Nama hanya boleh berisi huruf, spasi, titik, apostrof, dan tanda hubung.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.regex' => 'Nomor telepon harus diawali 8 dan terdiri dari 9–13 digit.',
            'date.required' => 'Tanggal reservasi wajib diisi.',
            'date.after_or_equal' => 'Tanggal tidak boleh di masa lalu.',
            'time.required' => 'Waktu reservasi wajib diisi.',
            'time.in' => 'Waktu reservasi di luar jam operasional (10:00–21:30).',
            'guests.required' => 'Jumlah tamu wajib diisi.',
            'guests.min' => 'Minimal 1 tamu.',
            'guests.max' => 'Maksimal 20 tamu.',
            'notes.max' => 'Catatan maksimal 300 karakter.',
        ]);

        // Simpan dalam format lokal yang konsisten: 08xxxxxxxxxx
        $validated['phone'] = '0' . $validated['phone'];

        $validated['reservation_code'] = Reservation::generateCode();
        $validated['status'] = 'pending';

        $reservation = Reservation::create($validated);

        return response()->json([
            'success' => true,
            'reservation' => $reservation,
            'message' => 'Reservasi berhasil dibuat! Kode reservasi Anda: ' . $reservation->reservation_code,
        ]);
    }

    public function checkStatus(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'regex:/^8\d{8,12}$/'],
        ], [
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.regex' => 'Nomor telepon tidak valid.',
        ]);

        $digits = $this->normalizePhone($request->phone);

        $reservations = Reservation::whereIn('phone', [
                $digits,
                '0' . $digits,
                '62' . $digits,
                '+62' . $digits,
            ])
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'reservations' => $reservations->map(function ($r) {
                return [
                    'id' => $r->id,
                    'reservation_code' => $r->reservation_code,
                    'name' => $r->name,
                    'date' => $r->formatted_date,
                    'time' => $r->formatted_time,
                    'guests' => $r->guests,
                    'status' => $r->status,
                    'status_label' => $r->status_label,
                    'status_color' => $r->status_color,
                ];
            }),
        ]);
    }
}
