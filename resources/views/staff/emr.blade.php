@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4 border-b border-slate-200 pb-4">
        <a href="/staff/dashboard" class="p-2 rounded bg-white text-slate-500 hover:text-slate-800 border border-slate-200 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-blue-900">Rekam Medik Elektronik (EMR)</h1>
    </div>
    
    @if(isset($queue) && $queue->exists)
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Patient Context -->
        <div class="lg:w-1/3 space-y-6">
            <div class="bg-white border text-center border-slate-200 rounded-lg p-6 shadow-sm">
                <div class="w-20 h-20 bg-blue-100 text-blue-700 rounded-full flex items-center justify-center font-bold text-3xl mx-auto mb-4 border-2 border-white shadow">
                    {{ strtoupper(substr($queue->patient->name, 0, 2)) }}
                </div>
                <h2 class="text-xl font-bold text-slate-800">{{ $queue->patient->name }}</h2>
                <p class="text-sm text-slate-500 mt-1 font-bold">{{ $queue->patient->medical_record_number ?? 'Belum ada RM' }}</p>
                <div class="mt-4 flex flex-col gap-2">
                    <div class="text-sm border-b border-slate-100 p-2 flex justify-between"><span class="text-slate-500">Antrian Hari ini</span><span class="font-bold text-blue-600">No. {{ $queue->queue_number }} ({{ ucfirst($queue->priority) }})</span></div>
                    <div class="text-sm border-b border-slate-100 p-2 flex justify-between"><span class="text-slate-500">Gender / No HP</span><span class="font-bold">{{ $queue->patient->gender ?? '-' }} / {{ $queue->patient->phone ?? '-' }}</span></div>
                </div>
            </div>
            
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-5">
                <h3 class="text-xs font-bold text-amber-700 uppercase mb-2">Riwayat Skrining Hari Ini</h3>
                <ul class="text-sm text-amber-900 space-y-1 font-medium list-disc list-inside">
                    @forelse($queue->screeningItems as $item)
                        <li>{{ $item->screening->name }} (+{{ $item->screening->weight }})</li>
                    @empty
                        <li>Tidak ada data skrining darurat.</li>
                    @endforelse
                </ul>
                <div class="mt-3 border-t border-amber-200 pt-2 text-xs font-bold">
                    Total Skor: {{ $queue->total_score }}
                </div>
            </div>

            <!-- Riwayat Historis -->
            @if(count($history) > 0)
            <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm max-h-96 overflow-y-auto">
                <h3 class="text-sm font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Histori Kunjungan Medis
                </h3>
                <div class="space-y-4">
                    @foreach($history as $rec)
                    <div class="text-sm border border-slate-100 p-3 rounded bg-slate-50">
                        <div class="font-bold text-blue-700 flex justify-between">
                            <span>{{ $rec->created_at->format('d M Y') }}</span>
                            <span class="text-xs text-slate-500 bg-white border px-1 rounded">{{ $rec->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="mt-2 text-slate-700 space-y-1">
                            <p><span class="font-semibold text-slate-900 text-xs uppercase">ICD:</span> {{ $rec->diagnosis }}</p>
                            @if($rec->prescription)
                            <p><span class="font-semibold text-slate-900 text-xs uppercase">R/ Obat:</span> {{ $rec->prescription }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm text-center">
                <p class="text-sm text-slate-500 font-medium">Ini adalah kunjungan pertama pasien.</p>
            </div>
            @endif
        </div>

        <!-- EMR Form -->
        <div class="lg:w-2/3 bg-white border border-slate-200 rounded-lg shadow-sm p-6 h-fit">
            <h3 class="font-bold text-lg text-slate-800 border-b border-slate-100 pb-4 mb-6">Input Pemeriksaan Dokter (SOAP)</h3>
            <form method="POST" action="/staff/emr/{{ $queue->id }}" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Subjective (Keluhan/Anamnesis)</label>
                    <!-- Pre-fill with screening notes for subjective -->
                    <textarea readonly rows="3" class="w-full px-4 py-2.5 rounded-md border border-slate-200 bg-slate-50 text-slate-600 outline-none resize-none">{{ $queue->screening_notes }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Assessment (Diagnosis / ICD) <span class="text-red-500">*</span></label>
                    <input type="text" name="diagnosis" required class="w-full px-4 py-2.5 rounded-md border border-slate-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none" placeholder="Masukkan ICD 10 / Diagnosa Utama">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Action / Objective</label>
                    <textarea name="action_taken" rows="3" class="w-full px-4 py-2.5 rounded-md border border-slate-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none resize-none" placeholder="Isi hasil objektif dari vital sign, observasi, dll..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Plan (Tindakan/Resep Terapi)</label>
                    <textarea name="prescription" rows="3" class="w-full px-4 py-2.5 rounded-md border border-slate-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none resize-none" placeholder="Obat yang diberikan, instruksi medis selanjutnya..."></textarea>
                </div>
                
                <div class="pt-6 border-t border-slate-200 flex justify-end gap-3">
                    <button type="submit" class="px-6 py-2.5 rounded-md bg-green-600 hover:bg-green-700 text-white font-bold transition shadow-sm">Simpan Rekam Medis & Tutup Antrian</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    @if(!isset($queue) || !$queue->exists)
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden mb-6">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50">
            <h3 class="text-base font-bold text-slate-800">Pilih Pasien untuk Input Rekam Medik</h3>
        </div>
        <div class="overflow-x-auto w-full">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white text-slate-500 text-xs uppercase tracking-wider font-bold">
                    <th class="p-4 border-b border-slate-200">No. Antrian</th>
                    <th class="p-4 border-b border-slate-200">No. RM</th>
                    <th class="p-4 border-b border-slate-200">Nama Pasien</th>
                    <th class="p-4 border-b border-slate-200">Status</th>
                    <th class="p-4 border-b border-slate-200 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($pendingQueues ?? [] as $pq)
                <tr class="hover:bg-blue-50/50">
                    <td class="p-4 font-bold">{{ $pq->queue_number }}</td>
                    <td class="p-4 text-slate-600 font-semibold">{{ $pq->patient->medical_record_number ?? 'Belum ada' }}</td>
                    <td class="p-4 font-bold text-slate-800">{{ $pq->patient->name }}</td>
                    <td class="p-4">
                        <span class="inline-block px-2 py-1 rounded text-xs font-bold {{ $pq->status === 'dipanggil' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ ucfirst($pq->status) }}
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        <a href="/staff/emr/{{ $pq->id }}" class="px-4 py-2 rounded-md bg-blue-600 text-white font-semibold text-xs hover:bg-blue-700 transition">
                            Input EMR
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="p-6 text-center text-slate-500">Tidak ada pasien yang menunggu atau dipanggil.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
    @endif
</div>
@endsection
