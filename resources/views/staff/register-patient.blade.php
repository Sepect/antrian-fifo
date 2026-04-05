@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4 border-b border-slate-200 pb-4">
        <a href="/staff/dashboard" class="p-2 rounded bg-white text-slate-500 hover:text-slate-800 border border-slate-200 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-blue-900">Pendaftaran Walk-In Karyawan</h1>
    </div>

    @if($errors->any())
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded border-l-4 border-red-500 font-bold shadow-sm">
            Terdapat data yang tidak valid. Mohon periksa kembali form di bawah.
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden" x-data="{ tab: 'lama' }">
        <div class="flex border-b border-slate-200">
            <button type="button" @click="tab = 'lama'" :class="tab === 'lama' ? 'border-b-2 border-blue-600 text-blue-700 bg-blue-50/50' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50'" class="flex-1 py-3 text-center font-bold text-sm transition">Pasien Lama / Kontrol</button>
            <button type="button" @click="tab = 'baru'" :class="tab === 'baru' ? 'border-b-2 border-blue-600 text-blue-700 bg-blue-50/50' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50'" class="flex-1 py-3 text-center font-bold text-sm transition">Pasien Baru</button>
        </div>

        <form method="POST" action="/staff/register-patient" class="p-6 space-y-5">
            @csrf
            <input type="hidden" name="patient_type" x-model="tab">

            <!-- Tab Pasien Lama -->
            <div x-show="tab === 'lama'" class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">No. Rekam Medis (RM) <span class="text-red-500">*</span></label>
                    <input type="text" name="medical_record_number" placeholder="RM-XXXX" :required="tab === 'lama'" class="w-full px-4 py-2 border border-slate-300 rounded focus:border-blue-500 outline-none text-lg font-bold uppercase transition">
                </div>
            </div>

            <!-- Tab Pasien Baru -->
            <div x-show="tab === 'baru'" class="space-y-5" style="display: none;">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap Sesuai KTP <span class="text-red-500">*</span></label>
                    <input type="text" name="name" :required="tab === 'baru'" class="w-full px-4 py-2.5 rounded-md border border-slate-300 focus:border-blue-500 outline-none transition text-slate-800">
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">No. WhatsApp/HP</label>
                        <input type="tel" name="phone" class="w-full px-4 py-2.5 rounded-md border border-slate-300 focus:border-blue-500 outline-none transition text-slate-800">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Jenis Kelamin</label>
                        <select name="gender" class="w-full px-4 py-2.5 rounded-md border border-slate-300 focus:border-blue-500 outline-none bg-white">
                            <option value="">-- Pilih --</option>
                            <option value="L">Laki-laki (L)</option>
                            <option value="P">Perempuan (P)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Keluhan di Pendaftaran Khusus <span class="text-red-500">*</span></label>
                <textarea required name="complaint" rows="3" class="w-full px-4 py-2.5 rounded-md border border-slate-300 focus:border-blue-500 outline-none transition text-slate-800 resize-none"></textarea>
            </div>

            <div class="pt-4">
                <button type="submit" class="px-6 py-2.5 rounded-md bg-blue-600 hover:bg-blue-700 text-white font-bold transition shadow-sm">
                    Simpan & Daftarkan Antrian
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
