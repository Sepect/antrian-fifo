@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-bold text-blue-900">Riwayat Pendaftaran Antrean</h1>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center flex-wrap gap-4">
            <h3 class="text-base font-bold text-slate-800">Semua Data Antrean Pasien</h3>
            
            <form method="GET" action="/staff/queue-history" class="flex gap-2 w-full md:w-auto">
                <input type="date" name="date" value="{{ request('date') }}" class="px-3 py-1.5 border border-slate-300 rounded text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                
                <select name="status" class="px-3 py-1.5 border border-slate-300 rounded text-sm focus:border-blue-500 outline-none">
                    <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="dipanggil" {{ request('status') == 'dipanggil' ? 'selected' : '' }}>Diproses (Dipanggil)</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal</option>
                </select>
                
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama/RM/Booking..." class="w-full md:w-48 px-3 py-1.5 border border-slate-300 rounded text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                
                <button type="submit" class="px-4 py-1.5 bg-blue-600 text-white font-semibold rounded text-sm hover:bg-blue-700 transition">
                    Filter
                </button>
            </form>
        </div>
        
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white text-slate-500 text-xs uppercase tracking-wider font-bold">
                        <th class="p-4 border-b border-slate-200">Tanggal & Waktu</th>
                        <th class="p-4 border-b border-slate-200">Kode Booking</th>
                        <th class="p-4 border-b border-slate-200">Pasien (RM)</th>
                        <th class="p-4 border-b border-slate-200">Prioritas</th>
                        <th class="p-4 border-b border-slate-200">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($queues as $q)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-4 font-semibold text-slate-700">
                            {{ \Carbon\Carbon::parse($q->queue_date)->format('d M Y') }}
                            <div class="text-xs text-slate-400 font-normal">{{ $q->created_at->format('H:i') }}</div>
                        </td>
                        <td class="p-4 font-mono text-xs font-bold bg-slate-50 text-slate-600 rounded">
                            {{ $q->booking_code }} (No. {{ $q->queue_number }})
                        </td>
                        <td class="p-4">
                            <p class="font-bold text-slate-800">{{ $q->patient->name }}</p>
                            <p class="text-xs text-blue-600 font-bold uppercase">{{ $q->patient->medical_record_number }}</p>
                        </td>
                        <td class="p-4 capitalize font-semibold {{ $q->priority == 'darurat' ? 'text-red-600' : 'text-slate-600' }}">
                            {{ $q->priority }}
                        </td>
                        <td class="p-4">
                            @php
                                $statusLabel = ucfirst($q->status);
                                $colorStr = 'text-slate-600 bg-slate-100 border-slate-200';
                                
                                if($q->status == 'menunggu') {
                                    $colorStr = 'text-amber-700 bg-amber-100 border-amber-200';
                                } elseif($q->status == 'dipanggil') {
                                    $statusLabel = 'Diproses';
                                    $colorStr = 'text-blue-700 bg-blue-100 border-blue-200';
                                } elseif($q->status == 'selesai') {
                                    $colorStr = 'text-green-700 bg-green-100 border-green-200';
                                } elseif($q->status == 'batal') {
                                    $colorStr = 'text-red-700 bg-red-100 border-red-200';
                                }
                            @endphp
                            <span class="inline-block border px-2 py-0.5 rounded text-[10px] uppercase font-bold tracking-wider {{ $colorStr }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-slate-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="font-medium">Tidak ada riwayat pendaftaran ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-5 py-3 border-t border-slate-200 bg-slate-50">
            {{ $queues->links('pagination::tailwind') }}
        </div>
    </div>
</div>
@endsection
