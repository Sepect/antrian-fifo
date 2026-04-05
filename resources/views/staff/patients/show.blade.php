@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4 border-b border-slate-200 pb-4">
        <a href="/staff/patients" class="p-2 rounded bg-white text-slate-500 hover:text-slate-800 border border-slate-200 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-blue-900">Rekam Jejak Medis Komprehensif (RM)</h1>
    </div>

    <!-- Patient Header Card -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden flex flex-col md:flex-row gap-6 p-6">
        <div class="md:w-1/4 flex flex-col items-center justify-center border-r border-slate-100 pr-0 md:pr-6">
            <div class="w-24 h-24 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-4xl shadow-sm border border-blue-200 mb-4">
                {{ strtoupper(substr($patient->name, 0, 2)) }}
            </div>
            <h2 class="text-xl font-bold text-slate-800 text-center">{{ $patient->name }}</h2>
            <div class="mt-2 bg-blue-50 text-blue-800 font-bold px-3 py-1 rounded w-full text-center border border-blue-100 uppercase tracking-widest">
                {{ $patient->medical_record_number ?? 'Belum ada RM' }}
            </div>
        </div>
        
        <div class="md:w-3/4 grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
            <div>
                <p class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-1">Jenis Kelamin</p>
                <p class="text-sm font-semibold text-slate-800">{{ $patient->gender === 'L' ? 'Laki-laki (Male)' : ($patient->gender === 'P' ? 'Perempuan (Female)' : 'Belum dikonfirmasi') }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-1">No. HP / Kontak Utama</p>
                <p class="text-sm font-semibold text-slate-800">{{ $patient->phone ?? 'Tidak ada data kontak' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-1">Total Kunjungan</p>
                <p class="text-sm font-semibold text-slate-800">{{ $patient->medicalRecords->count() }} Kunjungan Tercatat</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-1">Pendaftaran Pertama</p>
                <p class="text-sm font-semibold text-slate-800">{{ $patient->created_at->translatedFormat('d F Y, H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Medical History Timeline -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 bg-slate-50">
            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                Riwayat Kunjungan dan Diagnosa (EMR)
            </h3>
        </div>
        
        <div class="p-6">
            @if($patient->medicalRecords->count() > 0)
                <div class="space-y-8 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 before:to-transparent">
                    @foreach($patient->medicalRecords as $record)
                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-blue-100 text-blue-600 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        
                        <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white p-5 rounded-lg border border-slate-200 shadow-sm group-hover:border-blue-300 transition-colors">
                            <div class="flex items-center justify-between mb-3 border-b border-slate-100 pb-2">
                                <span class="font-bold text-blue-800 text-sm">{{ $record->created_at->translatedFormat('d M Y') }}</span>
                                <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded">{{ $record->created_at->diffForHumans() }}</span>
                            </div>
                            
                            <!-- EMR Core Data -->
                            <div class="space-y-3">
                                @if(isset($record->queue) && $record->queue->screening_notes)
                                <div>
                                    <p class="text-xs uppercase tracking-wider font-bold text-slate-400 mb-1">Keluhan Awal (Subjective):</p>
                                    <p class="text-sm font-medium text-slate-700 bg-amber-50 p-2 rounded">{{ $record->queue->screening_notes }}</p>
                                </div>
                                @endif
                                
                                <div class="bg-blue-50 border border-blue-100 p-3 rounded">
                                    <p class="text-xs uppercase tracking-wider font-bold text-blue-500 mb-1">ICD / Diagnosa Utama (Assessment):</p>
                                    <p class="text-sm font-bold text-slate-800">{{ $record->diagnosis }}</p>
                                </div>
                                
                                @if($record->action_taken)
                                <div>
                                    <p class="text-xs uppercase tracking-wider font-bold text-slate-400 mb-1">Observasi / Evaluasi (Objective):</p>
                                    <p class="text-sm text-slate-700">{{ $record->action_taken }}</p>
                                </div>
                                @endif

                                @if($record->prescription)
                                <div>
                                    <p class="text-xs uppercase tracking-wider font-bold text-slate-400 mb-1">Resep / Tindakan (Plan):</p>
                                    <div class="flex items-start gap-2 bg-green-50 p-2 rounded text-green-800 text-sm font-medium border border-green-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                        </svg>
                                        <p>{{ $record->prescription }}</p>
                                    </div>
                                </div>
                                @endif

                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10">
                    <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700 mb-1">Data EMR Kosong</h3>
                    <p class="text-slate-500">Pasien ini belum pernah menyelesaikan pemeriksaan medis.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
