@extends('layouts.guest')

@section('content')
<div class="w-full max-w-xl mx-auto px-4 mt-8 flex flex-col items-center">
    
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-blue-900 mb-2">Pendaftaran Antrian</h1>
        <p class="text-slate-600">Dapatkan nomor antrian dan Nomor Rekam Medis (RM) Anda tanpa perlu mengantre fisik.</p>
    </div>

    @if($errors->any())
        <div class="w-full mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
            <p class="font-bold text-sm">Terdapat Kesalahan</p>
            <ul class="list-disc list-inside text-sm mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="w-full bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden" x-data="{ tab: 'lama' }">
        
        <!-- Tabs -->
        <div class="flex border-b border-slate-200">
            <button type="button" @click="tab = 'lama'" :class="tab === 'lama' ? 'border-b-2 border-blue-600 text-blue-700 bg-blue-50/50' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50'" class="flex-1 py-4 text-center font-bold text-sm transition">Pasien Lama / Kontrol</button>
            <button type="button" @click="tab = 'baru'" :class="tab === 'baru' ? 'border-b-2 border-blue-600 text-blue-700 bg-blue-50/50' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50'" class="flex-1 py-4 text-center font-bold text-sm transition">Pasien Baru</button>
        </div>

        <form method="POST" action="/register" class="p-6 sm:p-8 space-y-5">
            @csrf
            
            <input type="hidden" name="patient_type" x-model="tab">

            <!-- Tab Pasien Lama -->
            <div x-show="tab === 'lama'" class="space-y-4">
                <div class="bg-blue-50 p-4 rounded border border-blue-100 mb-4">
                    <p class="text-sm text-blue-800">Jika Anda sudah pernah berobat ke klinik kami, Anda tidak perlu mengisi ulang seluruh biodata. Cukup masukkan No. RM Anda.</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">No. Rekam Medis (RM) <span class="text-red-500">*</span></label>
                    <input type="text" name="medical_record_number" placeholder="Contoh: RM-0001" :required="tab === 'lama'" class="w-full px-4 py-3 rounded-md border border-slate-300 bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-colors text-slate-800 font-bold tracking-wider text-xl uppercase">
                </div>
            </div>

            <!-- Tab Pasien Baru -->
            <div x-show="tab === 'baru'" class="space-y-5" style="display: none;">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap Sesuai KTP <span class="text-red-500">*</span></label>
                    <input type="text" name="name" :required="tab === 'baru'" class="w-full px-4 py-2.5 rounded-md border border-slate-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition text-slate-800">
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">No. WhatsApp/HP Aktif</label>
                        <input type="tel" name="phone" placeholder="08xxxxxxxxxx" class="w-full px-4 py-2.5 rounded-md border border-slate-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition text-slate-800">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Jenis Kelamin</label>
                        <select name="gender" class="w-full px-4 py-2.5 rounded-md border border-slate-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition text-slate-800 bg-white">
                            <option value="">-- Pilih --</option>
                            <option value="L">Laki-laki (L)</option>
                            <option value="P">Perempuan (P)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100">
                <label class="block text-sm font-semibold text-slate-700 mb-2 mt-4">Keluhan Utama Hari Ini <span class="text-red-500">*</span></label>
                <textarea required name="complaint" rows="3" class="w-full px-4 py-2.5 rounded-md border border-slate-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition text-slate-800 resize-none" placeholder="Misal: Demam menggigil sejak 3 hari yang lalu dll..."></textarea>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-3 rounded-md bg-blue-600 hover:bg-blue-700 text-white font-bold text-lg transition shadow-sm">
                    Daftar & Ambil Antrian
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
