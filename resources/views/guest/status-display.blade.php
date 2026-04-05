@extends('layouts.guest')

@section('content')
<div class="w-full max-w-3xl mx-auto px-4 mt-8 flex flex-col items-center">
    
    @if(session('success'))
    <div class="w-full bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded-r shadow-sm">
        <p class="font-bold">PENDAFTARAN BERHASIL</p>
        <p>Gunakan Nomor Antrian <strong>{{ session('success.number') }}</strong> dan Kode Booking <strong>{{ session('success.code') }}</strong> untuk melacak antrian Anda.</p>
    </div>
    @endif

    @if(session('error'))
    <div class="w-full bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r shadow-sm">
        <p class="font-bold">Error</p>
        <p>{{ session('error') }}</p>
    </div>
    @endif

    @if(isset($queue))
    <div class="w-full bg-white border-2 border-blue-100 rounded-2xl p-6 sm:p-10 shadow-lg shadow-blue-900/5 relative">
        
        <div class="flex items-center justify-between border-b border-slate-100 pb-5 mb-6">
            <div class="space-y-3">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Tiket Virtual</p>
                    <h2 class="text-lg font-bold text-blue-900">Pasien: {{ $queue->patient->name }}</h2>
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
                <div class="inline-block {{ $statusColor }} border px-3 py-1.5 rounded-md text-xs font-bold uppercase tracking-wider shadow-sm">
                    Keterangan: {{ $statusText }}
                </div>
            </div>
            <div class="flex items-center gap-2 bg-green-50 text-green-700 px-3 py-1.5 rounded-full border border-green-200">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                <span class="text-xs font-bold uppercase tracking-wider">Live Monitoring</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Your Number -->
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-6 flex flex-col items-center text-center relative">
                <span class="text-sm font-bold text-blue-600 uppercase tracking-widest mb-2">Nomor Antrian Anda</span>
                <span class="text-6xl font-black text-blue-900 border-b-2 border-blue-200 w-full py-2">{{ $queue->queue_number }}</span>
                <div class="mt-4 inline-block {{ $queue->priority === 'darurat' ? 'bg-red-100 text-red-700 border-red-200' : 'bg-blue-100 text-blue-700 border-blue-200' }} px-3 py-1 rounded text-xs font-bold border uppercase">
                    Prioritas {{ $queue->priority }}
                </div>
            </div>

            <!-- Current Serving -->
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-6 flex flex-col items-center text-center">
                <span class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-2">Panggilan Saat Ini</span>
                <span class="text-5xl font-black text-slate-800 border-b-2 border-slate-200 w-full py-2">{{ $servingNow ? $servingNow->queue_number : '-' }}</span>
                <div class="mt-4 text-xs font-bold text-slate-500">Status Poli Aktif</div>
            </div>
        </div>

        <!-- Info List -->
        <div class="bg-white border border-slate-200 rounded-lg overflow-hidden mb-6">
            <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <span class="text-sm font-semibold text-slate-600">Estimasi Waktu Tunggu</span>
                <span class="text-sm font-bold text-blue-800">~ {{ $aheadCount * 15 }} Menit</span>
            </div>
            <div class="px-5 py-4 flex justify-between items-center">
                <span class="text-sm font-semibold text-slate-600">Jumlah Antrian di Depan Anda</span>
                <span class="text-sm font-bold text-slate-800">{{ $aheadCount }} Pasien</span>
            </div>
        </div>
    </div>

    <div class="mt-6 flex flex-col items-center">
        <a href="/status" class="text-sm font-medium text-slate-500 hover:text-blue-600 border border-transparent border-b-slate-300 pb-0.5">Pantau Nomor Lain</a>
    </div>
    @endif
</div>
@endsection
