@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-bold text-blue-900">Database Pasien & Rekam Medis</h1>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
            <h3 class="text-base font-bold text-slate-800">Direktori Pasien Terdaftar</h3>
            
            <form method="GET" action="/staff/patients" class="relative w-64">
                <input type="text" name="search" placeholder="Cari No RM / Nama..." class="w-full pl-3 pr-10 py-1.5 border border-slate-300 rounded text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                <button type="submit" class="absolute right-2 top-1.5 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </button>
            </form>
        </div>
        
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white text-slate-500 text-xs uppercase tracking-wider font-bold">
                    <th class="p-4 border-b border-slate-200">No. RM</th>
                    <th class="p-4 border-b border-slate-200">Nama Lengkap</th>
                    <th class="p-4 border-b border-slate-200">Jenis Kelamin</th>
                    <th class="p-4 border-b border-slate-200">No. HP</th>
                    <th class="p-4 border-b border-slate-200">Kunjungan Medis</th>
                    <th class="p-4 border-b border-slate-200 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($patients as $patient)
                <tr class="hover:bg-slate-50 transition">
                    <td class="p-4 text-blue-700 font-bold uppercase">{{ $patient->medical_record_number ?? '-' }}</td>
                    <td class="p-4 font-bold text-slate-800">{{ $patient->name }}</td>
                    <td class="p-4 text-slate-600 font-medium whitespace-nowrap">{{ $patient->gender === 'L' ? 'Laki-laki' : ($patient->gender === 'P' ? 'Perempuan' : '-') }}</td>
                    <td class="p-4 text-slate-600">{{ $patient->phone ?? '-' }}</td>
                    <td class="p-4">
                        <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full font-bold text-xs bg-indigo-100 text-indigo-700">
                            {{ $patient->medical_records_count }} Riwayat EMR
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        <a href="/staff/patients/{{ $patient->id }}" class="px-3 py-1.5 rounded bg-blue-50 border border-blue-200 text-blue-700 font-semibold text-xs hover:bg-blue-100 transition whitespace-nowrap">
                            Detail Medis
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <p class="font-medium">Belum ada pasien yang terdaftar di database.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
        
        <div class="px-5 py-3 border-t border-slate-200 bg-slate-50">
            {{ $patients->links('pagination::tailwind') }}
        </div>
    </div>
</div>
@endsection
