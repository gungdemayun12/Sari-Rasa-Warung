@extends('layouts.customer')
@section('title', 'Reservasi Meja')

@section('content')
<div x-data="reservationApp()" class="min-h-[100dvh] bg-slate-50 pb-10">

    {{-- Header --}}
    <header class="bg-white border-b border-slate-100 shadow-sm sticky top-0 z-30 safe-area-top">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 py-3.5 flex items-center gap-3">
            <div class="w-9 h-9 bg-amber-50 rounded-2xl flex items-center justify-center shrink-0">
                <svg class="w-4.5 h-4.5 w-[18px] h-[18px] text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h1 class="font-black text-slate-800 text-sm">Reservasi Meja</h1>
                <p class="text-[10px] text-slate-400 font-medium">Sari Rasa · Booking Online</p>
            </div>
            <x-resto-logo size="xs" />
        </div>
    </header>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 py-6 space-y-4">

        {{-- Success state --}}
        <div x-show="successData" x-transition x-cloak
            class="bg-white rounded-3xl shadow-xl shadow-emerald-100/60 border border-emerald-100 overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-emerald-400 to-teal-500"></div>
            <div class="p-8 text-center">
                <div class="w-20 h-20 bg-emerald-100 rounded-[1.75rem] flex items-center justify-center mx-auto mb-5 shadow-lg shadow-emerald-200/50">
                    <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-xl font-black text-slate-800 mb-2">Reservasi Berhasil!</h3>
                <p class="text-sm text-slate-500 font-medium mb-4">Kode reservasi Anda:</p>
                <div class="inline-flex items-center px-6 py-3 bg-emerald-50 border-2 border-dashed border-emerald-200 rounded-2xl mb-5">
                    <span class="text-2xl font-black text-emerald-700 tracking-wider" x-text="successData?.reservation?.reservation_code"></span>
                </div>
                <p class="text-xs text-slate-400 font-medium leading-relaxed mb-6">
                    Kami akan mengkonfirmasi reservasi Anda segera.<br>Simpan kode ini sebagai bukti reservasi.
                </p>
                <button @click="successData = null"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-slate-900 text-white font-bold rounded-2xl hover:bg-slate-800 transition-all active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Reservasi Lagi
                </button>
            </div>
        </div>

        {{-- Form --}}
        <div x-show="!successData" class="bg-white rounded-3xl shadow-lg shadow-slate-100/60 border border-slate-100 overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-amber-400 via-amber-500 to-amber-600"></div>

            <div class="px-6 py-5 border-b border-slate-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-500 rounded-2xl flex items-center justify-center shadow-md shadow-amber-500/30">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-black text-slate-800 text-base">Buat Reservasi Baru</h2>
                        <p class="text-xs text-slate-400 font-medium">Isi semua data dengan lengkap</p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submitReservation()" novalidate class="px-6 py-4 sm:py-5 space-y-4">

                {{-- Name --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">
                        Nama Lengkap <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <input type="text" x-model="form.name" required maxlength="60"
                            autocomplete="name" autocapitalize="words"
                            @input="delete errors.name"
                            placeholder="Masukkan nama lengkap"
                            class="w-full pl-10 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 focus:bg-white transition-all"
                            :class="errors.name ? 'border-red-300 bg-red-50' : ''">
                    </div>
                    <p x-show="errors.name" x-text="errors.name" class="text-red-500 text-xs font-semibold flex items-center gap-1 ml-1">
                    </p>
                </div>

                {{-- Phone --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">
                        Nomor Telepon <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <input type="tel" x-model="form.phone" required inputmode="numeric" maxlength="15"
                            autocomplete="tel" placeholder="08xxxxxxxxxx"
                            @input="form.phone = $event.target.value.replace(/\D/g, '').slice(0, 15); delete errors.phone"
                            class="w-full pl-10 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 focus:bg-white transition-all"
                            :class="errors.phone ? 'border-red-300 bg-red-50' : ''">
                    </div>
                    <p x-show="errors.phone" x-text="errors.phone" class="text-red-500 text-xs font-semibold ml-1"></p>
                </div>

                {{-- Date & Time --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">
                            Tanggal <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 pointer-events-none z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <input type="text" id="date-picker" required placeholder="Pilih tanggal"
                                class="w-full pl-10 pr-3 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 focus:bg-white transition-all"
                                :class="errors.date ? 'border-red-300 bg-red-50' : ''">
                        </div>
                        <p x-show="errors.date" x-text="errors.date" class="text-red-500 text-xs font-semibold ml-1"></p>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">
                            Waktu <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <select x-model="form.time" required @change="delete errors.time"
                                class="w-full pl-10 pr-3 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 focus:bg-white transition-all appearance-none"
                                :class="errors.time ? 'border-red-300 bg-red-50' : ''">
                                <option value="">Pilih</option>
                                @for($h = 10; $h <= 21; $h++)
                                    <option value="{{ sprintf('%02d:00', $h) }}">{{ sprintf('%02d:00', $h) }}</option>
                                    <option value="{{ sprintf('%02d:30', $h) }}">{{ sprintf('%02d:30', $h) }}</option>
                                @endfor
                            </select>
                        </div>
                        <p x-show="errors.time" x-text="errors.time" class="text-red-500 text-xs font-semibold ml-1"></p>
                    </div>
                </div>

                {{-- Guests --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">
                        Jumlah Tamu <span class="text-red-400">*</span>
                    </label>
                    <div class="flex items-center gap-4 bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3">
                        <button type="button" @click="form.guests = Math.max(1, form.guests - 1); delete errors.guests"
                            class="w-9 h-9 bg-white border border-slate-200 rounded-xl flex items-center justify-center font-black text-slate-600 hover:bg-red-50 hover:border-red-200 hover:text-red-500 transition-all active:scale-90 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/></svg>
                        </button>
                        <div class="flex-1 text-center">
                            <span class="text-3xl font-black text-slate-800 leading-none" x-text="form.guests"></span>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">Orang</p>
                        </div>
                        <button type="button" @click="form.guests = Math.min(20, form.guests + 1); delete errors.guests"
                            class="w-9 h-9 bg-gradient-to-br from-amber-500 to-amber-600 text-white rounded-xl flex items-center justify-center font-black hover:from-amber-600 hover:to-amber-700 transition-all active:scale-90 shadow-md shadow-amber-500/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    </div>
                    <p x-show="errors.guests" x-text="errors.guests" class="text-red-500 text-xs font-semibold ml-1"></p>
                </div>

                {{-- Notes --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">
                        Catatan Khusus
                        <span class="normal-case font-medium text-slate-300 ml-1">(opsional)</span>
                    </label>
                    <textarea x-model="form.notes" rows="3" maxlength="300" @input="delete errors.notes"
                        placeholder="Cth: kursi roda, acara ulang tahun, posisi dekat jendela..."
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium text-slate-700 placeholder-slate-300 focus:outline-none focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 focus:bg-white transition-all resize-none"></textarea>
                    <p class="text-[10px] text-slate-400 text-right font-semibold" x-text="(form.notes || '').length + '/300'"></p>
                </div>

                {{-- Submit --}}
                <button type="submit" :disabled="submitting"
                    class="w-full flex items-center justify-center gap-2.5 py-4 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-black rounded-2xl hover:from-amber-600 hover:to-amber-700 transition-all shadow-xl shadow-amber-500/30 disabled:opacity-60 disabled:cursor-wait active:scale-[0.98] transform hover:-translate-y-0.5 mt-2">
                    <svg x-show="!submitting" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <svg x-show="submitting" x-cloak class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    <span x-show="!submitting">Buat Reservasi Sekarang</span>
                    <span x-show="submitting" x-cloak>Mengirim...</span>
                </button>
            </form>
        </div>

        {{-- Check status --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-50 flex items-center gap-3">
                <div class="w-9 h-9 bg-slate-100 rounded-2xl flex items-center justify-center">
                    <svg class="w-4.5 h-4.5 w-[18px] h-[18px] text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-black text-slate-800 text-sm">Cek Status Reservasi</h2>
                    <p class="text-[10px] text-slate-400 font-medium">Masukkan nomor HP yang didaftarkan</p>
                </div>
            </div>

            <div class="px-5 py-4">
                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <input type="tel" x-model="checkPhone" placeholder="08xxxxxxxxxx" inputmode="numeric"
                            maxlength="15" autocomplete="tel"
                            @input="checkPhone = $event.target.value.replace(/\D/g, '').slice(0, 15)"
                            class="w-full pl-9 pr-3 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 focus:bg-white transition-all">
                    </div>
                    <button @click="checkStatus()"
                        class="px-5 py-3 bg-slate-900 text-white rounded-2xl text-sm font-black hover:bg-slate-700 transition-all active:scale-95 shadow-md shadow-slate-900/15">
                        Cek
                    </button>
                </div>

                <div x-show="checkResults !== null" class="mt-4 space-y-2.5" x-cloak>
                    <template x-if="checkResults && checkResults.length === 0">
                        <div class="text-center py-8">
                            <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-sm text-slate-400 font-medium">Tidak ditemukan reservasi aktif</p>
                        </div>
                    </template>
                    <template x-for="r in checkResults" :key="r.id">
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-black text-sm text-slate-800 tracking-wide" x-text="r.reservation_code"></span>
                                <span class="px-2.5 py-1 text-[10px] font-black rounded-xl uppercase tracking-wide"
                                    :class="{
                                        'bg-amber-100 text-amber-700': r.status === 'pending',
                                        'bg-emerald-100 text-emerald-700': r.status === 'confirmed',
                                        'bg-red-100 text-red-700': r.status === 'rejected'
                                    }"
                                    x-text="r.status_label">
                                </span>
                            </div>
                            <div class="flex items-center gap-1.5 text-xs text-slate-500 font-medium">
                                <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span x-text="r.date + ' · ' + r.time"></span>
                                <span class="text-slate-300">·</span>
                                <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span x-text="r.guests + ' tamu'"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
function reservationApp() {
    return {
        form: { name: '', phone: '', date: '', time: '', guests: 2, notes: '' },
        errors: {}, submitting: false, successData: null,
        checkPhone: '', checkResults: null,

        normalizePhone(value) {
            let v = (value || '').replace(/\D/g, '');
            if (v.startsWith('62')) v = v.slice(2);
            else if (v.startsWith('0')) v = v.slice(1);
            return v;
        },

        validateForm() {
            const errors = {};
            const name = (this.form.name || '').replace(/\s+/g, ' ').trim();
            const phone = this.normalizePhone(this.form.phone);

            if (!name) errors.name = 'Nama wajib diisi.';
            else if (name.length < 2) errors.name = 'Nama minimal 2 karakter.';
            else if (name.length > 60) errors.name = 'Nama maksimal 60 karakter.';
            else if (!/^[\p{L}\p{M}][\p{L}\p{M}\s.'’\-]*$/u.test(name)) errors.name = 'Nama hanya boleh berisi huruf dan spasi.';

            if (!phone) errors.phone = 'Nomor telepon wajib diisi.';
            else if (!/^8\d{8,12}$/.test(phone)) errors.phone = 'Nomor harus diawali 8 dan terdiri dari 9–13 digit.';

            if (!this.form.date) errors.date = 'Pilih tanggal reservasi.';
            else {
                const today = new Date(); today.setHours(0, 0, 0, 0);
                const chosen = new Date(this.form.date + 'T00:00:00');
                if (isNaN(chosen.getTime()) || chosen < today) errors.date = 'Tanggal tidak boleh di masa lalu.';
            }

            if (!this.form.time) errors.time = 'Pilih waktu reservasi.';
            if (!this.form.guests || this.form.guests < 1 || this.form.guests > 20) errors.guests = 'Jumlah tamu 1–20 orang.';
            if ((this.form.notes || '').length > 300) errors.notes = 'Catatan maksimal 300 karakter.';

            this.errors = errors;
            return Object.keys(errors).length === 0;
        },

        async submitReservation() {
            this.errors = {};

            if (!this.validateForm()) {
                showToast('Periksa kembali data reservasi Anda.', 'error');
                return;
            }

            this.form.name = (this.form.name || '').replace(/\s+/g, ' ').trim();
            this.form.phone = this.normalizePhone(this.form.phone);
            this.form.notes = (this.form.notes || '').trim();

            this.submitting = true;
            try {
                const res = await fetch('/reservasi', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.form)
                });
                const data = await res.json();
                if (res.ok && data.success) {
                    this.successData = data;
                    this.form = { name: '', phone: '', date: '', time: '', guests: 2, notes: '' };
                    showToast('Reservasi berhasil dikirim!', 'success');
                } else if (data.errors) {
                    this.errors = {};
                    for (let k in data.errors) this.errors[k] = data.errors[k][0];
                    showToast(data.errors[Object.keys(data.errors)[0]]?.[0] || 'Periksa kembali data Anda', 'error');
                } else {
                    showToast(data.message || 'Reservasi gagal dikirim.', 'error');
                }
            } catch(e) {
                showToast('Terjadi kesalahan koneksi', 'error');
            }
            this.submitting = false;
        },

        async checkStatus() {
            const phone = this.normalizePhone(this.checkPhone);
            if (!phone) {
                showToast('Masukkan nomor HP terlebih dahulu.', 'warning');
                return;
            }
            if (!/^8\d{8,12}$/.test(phone)) {
                showToast('Format nomor HP tidak valid.', 'error');
                return;
            }

            try {
                const res = await fetch('/reservasi/check?phone=' + encodeURIComponent(phone));
                const data = await res.json();
                this.checkResults = data.reservations || [];
            } catch(e) {
                showToast('Gagal mengecek status', 'error');
            }
        },

        init() {
            const picker = flatpickr("#date-picker", {
                locale: "id",
                minDate: "today",
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "j F Y",
                disableMobile: true,
                onChange: (selectedDates, dateStr) => {
                    this.form.date = dateStr;
                    if (this.errors.date) delete this.errors.date;
                }
            });

            if (picker && picker.altInput) {
                picker.altInput.placeholder = 'Pilih tanggal';
                picker.altInput.setAttribute('autocomplete', 'off');
                picker.altInput.setAttribute('aria-label', 'Tanggal reservasi');

                // Mirror validation state onto the visible alt input.
                this.$watch('errors.date', (value) => {
                    picker.altInput.classList.toggle('border-red-300', !!value);
                    picker.altInput.classList.toggle('bg-red-50', !!value);
                });
            }
        }
    };
}
</script>
@endpush
@endsection
