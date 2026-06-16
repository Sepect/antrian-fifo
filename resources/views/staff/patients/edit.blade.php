@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4 border-b border-slate-200 pb-4">
        <a href="/staff/patients/{{ $patient->id }}" class="p-2 rounded bg-white text-slate-500 hover:text-slate-800 border border-slate-200 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-blue-900">Edit Data Pasien</h1>
            <p class="text-sm text-slate-500">Lengkapi atau perbarui data demografis pasien — {{ $patient->medical_record_number }}</p>
        </div>
    </div>

    @if(session('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded" role="alert">
            <p class="font-bold">{{ session('message') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded border-l-4 border-red-500 shadow-sm">
            <p class="font-bold text-sm">Terdapat Kesalahan</p>
            <ul class="list-disc list-inside text-sm mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/staff/patients/{{ $patient->id }}/update" class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
        @csrf

        <div class="px-6 py-5 border-b border-slate-200 bg-slate-50">
            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Data Identitas Pasien
            </h3>
        </div>

        <div class="p-6 space-y-5">
            <!-- NIK -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">NIK (Nomor Induk Kependudukan)</label>
                <input type="text" name="nik" value="{{ old('nik', $patient->nik) }}" maxlength="16" placeholder="16 digit angka NIK" class="w-full px-4 py-2.5 rounded-md border border-slate-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition text-slate-800 tracking-wide" inputmode="numeric">
            </div>

            <!-- Nama -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $patient->name) }}" required class="w-full px-4 py-2.5 rounded-md border border-slate-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition text-slate-800">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Jenis Kelamin -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Jenis Kelamin</label>
                    <select name="gender" class="w-full px-4 py-2.5 rounded-md border border-slate-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition text-slate-800 bg-white">
                        <option value="">-- Pilih --</option>
                        <option value="L" {{ old('gender', $patient->gender) === 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                        <option value="P" {{ old('gender', $patient->gender) === 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                    </select>
                </div>

                <!-- Tanggal Lahir -->
                <div x-data="{ 
                    birthDate: '{{ old('birth_date', $patient->birth_date ? $patient->birth_date->format('Y-m-d') : '') }}',
                    get age() {
                        if (!this.birthDate) return null;
                        const today = new Date();
                        const birth = new Date(this.birthDate);
                        let age = today.getFullYear() - birth.getFullYear();
                        const m = today.getMonth() - birth.getMonth();
                        if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) age--;
                        return age;
                    }
                }">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Lahir</label>
                    <input type="date" name="birth_date" x-model="birthDate" max="{{ date('Y-m-d') }}" class="w-full px-4 py-2.5 rounded-md border border-slate-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition text-slate-800">
                    <p class="text-xs mt-1.5" :class="age !== null ? 'text-blue-600 font-semibold' : 'text-slate-400'">
                        <template x-if="age !== null">
                            <span>Umur: <span x-text="age"></span> tahun</span>
                        </template>
                        <template x-if="age === null">
                            <span>Umur akan terhitung otomatis setelah diisi.</span>
                        </template>
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Agama -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Agama</label>
                    <select name="religion" class="w-full px-4 py-2.5 rounded-md border border-slate-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition text-slate-800 bg-white">
                        <option value="">-- Pilih Agama --</option>
                        @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu', 'Lainnya'] as $religion)
                            <option value="{{ $religion }}" {{ old('religion', $patient->religion) === $religion ? 'selected' : '' }}>{{ $religion }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Pekerjaan -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Pekerjaan</label>
                    <input type="text" name="occupation" value="{{ old('occupation', $patient->occupation) }}" placeholder="Contoh: PNS, Wiraswasta, Pelajar..." class="w-full px-4 py-2.5 rounded-md border border-slate-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition text-slate-800">
                </div>
            </div>

            <!-- No HP -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">No. WhatsApp / HP</label>
                <input type="tel" name="phone" value="{{ old('phone', $patient->phone) }}" placeholder="08xxxxxxxxxx" class="w-full px-4 py-2.5 rounded-md border border-slate-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition text-slate-800">
            </div>

            <!-- Alamat -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Alamat Lengkap</label>
                <textarea name="address" rows="3" placeholder="Masukkan alamat lengkap pasien..." class="w-full px-4 py-2.5 rounded-md border border-slate-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition text-slate-800 resize-none">{{ old('address', $patient->address) }}</textarea>
            </div>
        </div>

        <!-- Actions -->
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row justify-end gap-3">
            <a href="/staff/patients/{{ $patient->id }}" class="px-5 py-2.5 rounded-md border border-slate-300 bg-white text-slate-700 font-semibold text-sm hover:bg-slate-50 transition text-center">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-md bg-blue-600 text-white font-bold text-sm shadow-sm hover:bg-blue-700 transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
