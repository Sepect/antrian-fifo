@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-blue-900">Dashboard Antrian</h1>
                <p class="text-slate-500 text-sm">Status antrian real-time hari ini berdasarkan metode prioritas medis.</p>
            </div>

            @if(auth()->user()->role === 'perawat')
                <div class="flex items-center gap-3">
                    <a href="/staff/register-patient"
                        class="px-4 py-2 rounded-md bg-blue-600 text-white font-semibold text-sm hover:bg-blue-700 transition flex items-center gap-2 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Daftar Pasien Walk-in
                    </a>
                </div>
            @endif
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white border text-center border-slate-200 rounded-lg p-5 shadow-sm border-l-4 border-l-blue-500">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">Total Hari Ini</span>
                <span class="text-3xl font-black text-blue-900 leading-none">{{ $stats['total'] }}</span>
            </div>

            <div
                class="bg-white border text-center border-slate-200 rounded-lg p-5 shadow-sm border-l-4 border-l-amber-400">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">Menunggu</span>
                <span class="text-3xl font-black text-amber-600 leading-none">{{ $stats['menunggu'] }}</span>
            </div>

            <div class="bg-white border text-center border-slate-200 rounded-lg p-5 shadow-sm border-l-4 border-l-red-500">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">Darurat</span>
                <span class="text-3xl font-black text-red-600 leading-none">{{ $stats['darurat'] }}</span>
            </div>

            <div
                class="bg-white border text-center border-slate-200 rounded-lg p-5 shadow-sm border-l-4 border-l-green-500">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">Selesai</span>
                <span class="text-3xl font-black text-green-600 leading-none">{{ $stats['selesai'] }}</span>
            </div>
        </div>

        @if(session('message'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded" role="alert">
                <p class="font-bold">Info</p>
                <p>{{ session('message') }}</p>
            </div>
        @endif

        @if(auth()->user()->role === 'perawat')
            <!-- Queue Table -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden mt-6">
                <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                    <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                        Daftar Antrean Berjalan ({{ count($queues) }} Active)
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white text-slate-500 text-xs uppercase tracking-wider font-bold">
                                <th class="p-4 border-b border-slate-200">No. Antrian</th>
                                <th class="p-4 border-b border-slate-200">Nama Pasien</th>
                                <th class="p-4 border-b border-slate-200">Status Prioritas</th>
                                <th class="p-4 border-b border-slate-200">Progres</th>
                                <th class="p-4 border-b border-slate-200 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($queues as $q)
                                <tr
                                    class="hover:bg-blue-50/50 transition-colors {{ $q->priority == 'darurat' ? 'bg-red-50/20' : '' }}">
                                    <td class="p-4">
                                        <span
                                            class="inline-flex w-8 h-8 items-center justify-center {{ $q->priority == 'darurat' ? 'bg-red-100 text-red-800' : 'bg-slate-100 text-slate-800' }} font-bold rounded border border-slate-200">
                                            {{ $q->queue_number }}
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <p class="font-bold text-slate-800">{{ $q->patient->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $q->created_at->format('H:i A') }}</p>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex flex-col items-start gap-1">
                                            <span
                                                class="{{ $q->priority == 'darurat' ? 'bg-red-100 text-red-700 border-red-200' : 'bg-blue-100 text-blue-700 border-blue-200' }} py-0.5 px-2 rounded text-[10px] font-bold uppercase tracking-wider border">
                                                {{ $q->priority }}
                                            </span>
                                            <span class="text-xs font-semibold text-slate-500">Skor Medis:
                                                {{ $q->total_score }}</span>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        @php
                                            $statusLabel = ucfirst($q->status);
                                            $colorStr = 'text-amber-600 bg-amber-500';
                                            if ($q->status == 'dipanggil') {
                                                $statusLabel = 'Diproses';
                                                $colorStr = 'text-blue-600 bg-blue-500';
                                            } elseif ($q->status == 'selesai') {
                                                $colorStr = 'text-green-600 bg-green-500';
                                            }
                                            $textColors = explode(' ', $colorStr)[0];
                                            $bgColors = explode(' ', $colorStr)[1];
                                        @endphp
                                        <div class="flex items-center gap-2 {{ $textColors }} font-semibold text-xs">
                                            <span class="w-2 h-2 rounded-full {{ $bgColors }}"></span> {{ $statusLabel }}
                                        </div>
                                    </td>
                                    <td class="p-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            @if($q->status == 'menunggu')
                                                <form method="POST" action="/staff/queue/{{$q->id}}/call">
                                                    @csrf
                                                    <button
                                                        class="px-3 py-1.5 rounded bg-blue-600 text-white font-medium text-xs hover:bg-blue-700 transition">
                                                        Panggil
                                                    </button>
                                                </form>
                                            @endif
                                            @if($q->status == 'dipanggil')
                                                <a href="/staff/emr/{{$q->id}}"
                                                    class="px-3 py-1.5 rounded bg-white border border-slate-300 text-slate-600 font-medium text-xs hover:bg-slate-50 transition">
                                                    Input EMR
                                                </a>
                                            @endif
                                            <a href="/staff/screening/{{$q->id}}"
                                                class="px-3 py-1.5 rounded bg-green-50 text-green-700 border border-green-200 font-medium text-xs hover:bg-green-100 transition">
                                                Skrining
                                            </a>
                                            <form method="POST" action="/staff/queue/{{$q->id}}/cancel">
                                                @csrf
                                                <button
                                                    class="px-2 py-1.5 rounded bg-red-50 text-red-600 hover:bg-red-100">Batal</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-6 text-center text-slate-500">Belum ada pasien yang antre hari ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection