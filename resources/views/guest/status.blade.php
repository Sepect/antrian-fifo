@extends('layouts.guest')

@section('content')
<div class="w-full max-w-md mx-auto px-4 mt-16 flex flex-col items-center">
    
    <div class="mb-8 text-center">
        <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-blue-900 mb-2">Cek Status Antrian</h1>
        <p class="text-sm text-slate-600">Masukkan No Rekam Medis (RM) Anda untuk melacak posisi pelayanan tersisa secara live.</p>
    </div>

    @if(session('error'))
    <div class="w-full bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r shadow-sm">
        <p class="font-bold text-sm">Pencarian Gagal</p>
        <p class="text-sm">{{ session('error') }}</p>
    </div>
    @endif

    <div class="w-full bg-white border border-slate-200 rounded-xl shadow-sm p-6 sm:p-8">
        <form method="GET" action="/status-display" class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2 text-center">Nomor Rekam Medis (RM)</label>
                <input type="text" name="medical_record_number" required placeholder="Contoh: RM-0001" class="w-full px-4 py-4 rounded-md border-2 border-slate-300 bg-white focus:ring-0 focus:border-blue-600 outline-none transition-colors text-slate-800 font-black tracking-widest text-center uppercase text-xl placeholder-slate-300">
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-4 rounded-md bg-blue-900 hover:bg-black text-white font-bold transition-colors uppercase tracking-widest">
                    Lacak Sekarang
                </button>
            </div>
        </form>
    </div>
    
    <div class="mt-6 font-medium">
        <a href="/" class="text-sm text-slate-500 hover:text-blue-600 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Beranda
        </a>
    </div>

</div>
@endsection
