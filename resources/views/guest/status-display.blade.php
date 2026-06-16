@extends('layouts.guest')

@section('content')
<div class="w-full max-w-3xl mx-auto px-4 mt-6 sm:mt-8 flex flex-col items-center" x-data="{ showModal: {{ session('registration_success') ? 'true' : 'false' }} }">
    
    @if(session('error'))
    <div class="w-full bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r shadow-sm text-sm sm:text-base">
        <p class="font-bold">Error</p>
        <p>{{ session('error') }}</p>
    </div>
    @endif

    @if(isset($queue))
    <div class="w-full bg-white border-2 border-blue-100 rounded-xl sm:rounded-2xl p-5 sm:p-8 md:p-10 shadow-lg shadow-blue-900/5 relative">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-5 mb-6 gap-4 sm:gap-0">
            <div class="space-y-2 sm:space-y-3">
                <div>
                    <p class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Tiket Virtual</p>
                    <h2 class="text-base sm:text-lg font-bold text-blue-900 leading-tight">Pasien: {{ $queue->patient->name }}</h2>
                </div>
                
                @php
                    $statusColor = 'bg-slate-100 text-slate-700';
                    $statusText = 'Menunggu';
                    if($queue->status == 'dipanggil') {
                        $statusColor = 'bg-blue-100 text-blue-700 border-blue-200';
                        $statusText = 'Diproses (Sedang Diperiksa)';
                    } elseif($queue->status == 'selesai') {
                        $statusColor = 'bg-green-100 text-green-700 border-green-200';
                        $statusText = 'Pemeriksaan Selesai';
                    } elseif($queue->status == 'menunggu') {
                        $statusColor = 'bg-amber-100 text-amber-700 border-amber-200';
                        $statusText = 'Menunggu Antrian';
                    } else {
                        $statusText = 'Antrian Dibatalkan';
                        $statusColor = 'bg-red-100 text-red-700 border-red-200';
                    }
                @endphp
                <div class="inline-block {{ $statusColor }} border px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-md text-[10px] sm:text-xs font-bold uppercase tracking-wider shadow-sm">
                    Keterangan: {{ $statusText }}
                </div>
            </div>
            
            <div class="self-start sm:self-center flex items-center gap-2 bg-green-50 text-green-700 px-3 py-1.5 rounded-full border border-green-200">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider">Live Monitoring</span>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-2 gap-3 sm:gap-6 mb-6 sm:mb-8">
            <!-- Your Number -->
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 sm:p-6 flex flex-col items-center text-center relative col-span-2 sm:col-span-1">
                <span class="text-xs sm:text-sm font-bold text-blue-600 uppercase tracking-widest mb-1 sm:mb-2">Nomor Anda</span>
                <span class="text-5xl sm:text-6xl font-black text-blue-900 border-b-2 border-blue-200 w-full py-1 sm:py-2">{{ $queue->queue_number }}</span>
                <div class="mt-3 sm:mt-4 inline-block {{ $queue->priority === 'darurat' ? 'bg-red-100 text-red-700 border-red-200' : 'bg-blue-100 text-blue-700 border-blue-200' }} px-2 sm:px-3 py-1 rounded text-[10px] sm:text-xs font-bold border uppercase tracking-wider">
                    {{ $queue->priority }}
                </div>
            </div>

            <!-- Current Serving -->
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 sm:p-6 flex flex-col items-center text-center col-span-2 sm:col-span-1">
                <span class="text-xs sm:text-sm font-bold text-slate-500 uppercase tracking-widest mb-1 sm:mb-2">Panggilan Aktif</span>
                <span class="text-4xl sm:text-5xl font-black text-slate-800 border-b-2 border-slate-200 w-full py-1 sm:py-2">{{ $servingNow ? $servingNow->queue_number : '-' }}</span>
                <div class="mt-3 sm:mt-4 text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider">Status Poli Aktif</div>
            </div>
        </div>

        <!-- Info List -->
        <div class="bg-white border border-slate-200 rounded-lg overflow-hidden mb-2">
            <div class="px-4 sm:px-5 py-3 sm:py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <span class="text-xs sm:text-sm font-semibold text-slate-600">Estimasi Waktu Tunggu</span>
                <span class="text-xs sm:text-sm font-bold text-blue-800">~ {{ $aheadCount * 15 }} Menit</span>
            </div>
            <div class="px-4 sm:px-5 py-3 sm:py-4 flex justify-between items-center">
                <span class="text-xs sm:text-sm font-semibold text-slate-600">Banyaknya Antrean di Depan Anda</span>
                <span class="text-xs sm:text-sm font-bold text-slate-800">{{ $aheadCount }} Pasien</span>
            </div>
        </div>
    </div>

    <!-- Additional Detail Toggle for Mobile -->
    <div class="w-full mt-4 bg-white border border-slate-200 rounded-lg p-4 shadow-sm" x-data="{ expanded: false }">
        <button @click="expanded = !expanded" class="w-full flex justify-between items-center text-sm font-bold text-slate-700">
            <span>Detail Registrasi</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
        <div x-show="expanded" x-collapse.duration.300ms class="mt-3 pt-3 border-t border-slate-100 space-y-2 text-sm" style="display: none;">
            <div class="flex justify-between">
                <span class="text-slate-500">Pasien</span>
                <span class="font-semibold text-slate-800">{{ $queue->patient->name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Nomor RM</span>
                <span class="font-semibold text-slate-800 font-mono">{{ $queue->patient->medical_record_number }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Kode Booking</span>
                <span class="font-semibold text-slate-800 font-mono">{{ $queue->booking_code }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Waktu Daftar</span>
                <span class="font-semibold text-slate-800">{{ $queue->created_at->format('H:i') }} WIB</span>
            </div>
        </div>
    </div>

    <div class="mt-6 mb-8 flex flex-col items-center">
        <a href="/status" class="text-sm font-medium text-slate-500 hover:text-blue-600 border border-transparent border-b-slate-300 pb-0.5 transition">Kembali / Pantau Nomor Lain</a>
    </div>
    @endif

    <!-- Registration Success Modal -->
    @if(session('registration_success'))
    <div x-show="showModal" x-transition.opacity class="fixed inset-0 z-[100] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4" style="display: none;">
        <div x-show="showModal" x-transition.scale.origin.bottom class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden" @click.away="showModal = false">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-green-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-1">Berhasil Daftar!</h3>
                <p class="text-sm text-slate-500 mb-5">Berikut adalah detail antrian Anda.</p>
                
                <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 space-y-3 mb-6">
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-0.5">Nomor Antrian</p>
                        <p class="text-3xl font-black text-blue-700">{{ session('registration_success')['number'] }}</p>
                    </div>
                    <div class="pt-3 border-t border-slate-200">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Simpan Nomor Rekam Medis (RM) Anda</p>
                        <p class="text-lg font-bold font-mono text-slate-800 tracking-widest bg-white border border-slate-300 py-1 rounded">{{ session('registration_success')['rm'] }}</p>
                        <p class="text-[10px] text-slate-500 mt-1.5 leading-tight">Gunakan No. RM ini untuk berobat kembali atau mengecek antrian di hari lain.</p>
                    </div>
                </div>

                <button @click="showModal = false" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition-colors shadow-sm active:scale-[0.98]">
                    Tutup & Pantau Antrian
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
