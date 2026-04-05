@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <div class="flex items-center gap-4 border-b border-slate-200 pb-4">
        <a href="/staff/dashboard" class="p-2 rounded bg-white text-slate-500 hover:text-slate-800 border border-slate-200 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-blue-900">Formulir Skrining Medis</h1>
            @if(isset($queue) && $queue->exists)
                <p class="text-sm text-slate-500 font-medium">Asesment Pasien No. {{ $queue->queue_number }} - {{ $queue->patient->name }}</p>
            @else
                <p class="text-sm text-slate-500 font-medium">Pilih antrean pasien untuk dinilai prioritasnya.</p>
            @endif
        </div>
    </div>

    @if(isset($queue) && $queue->exists)
    <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-1 relative pl-4 border-l-4 border-blue-400 font-semibold text-sm w-full md:w-3/4">
            <span class="block text-xs uppercase text-slate-400 tracking-wider mb-2">Informasi Keluhan Pasien pada Pendaftaran:</span>
            <p class="text-slate-800 italic bg-blue-50 p-3 rounded">
                "{{ $queue->screening_notes ?? 'Tidak ada keluhan tertulis' }}"
            </p>
        </div>
        
        <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 flex flex-col items-center min-w-[150px] shadow-inner" x-data="{ docScore: {{ $queue->total_score }} }" @score-updated.window="docScore = $event.detail.total">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Skor Pasien</span>
            <span class="text-4xl font-black transition-colors text-blue-700" :class="docScore >= 10 ? 'text-red-600' : 'text-blue-700'" x-text="docScore">0</span>
            <span class="py-0.5 px-2 mt-2 w-full text-center text-[10px] uppercase font-bold rounded bg-white border border-blue-200 text-blue-600" :class="docScore >= 10 ? 'text-red-600 border-red-200' : 'text-blue-600 border-blue-200'" x-text="docScore >=10 ? 'Darurat' : 'Umum'">Umum</span>
        </div>
    </div>

    <!-- Active Form -->
    <div x-data="{
        selected: {{ isset($selectedIds) ? json_encode($selectedIds) : '[]' }},
        items: {{ json_encode($screenings) }},
        toggleItem(item) {
            const idx = this.selected.findIndex(id => id === item.id);
            if (idx > -1) {
                this.selected.splice(idx, 1);
            } else {
                this.selected.push(item.id);
            }
            this.updateScore();
        },
        updateScore() {
            let total = 0;
            this.selected.forEach(sId => {
                const i = this.items.find(x => x.id === sId);
                if(i) total += i.weight;
            });
            $dispatch('score-updated', { total });
        }
    }">
        <form method="POST" action="/staff/screening/{{$queue->id}}" class="bg-white rounded-lg border border-slate-200 p-5 sm:p-6 shadow-sm">
            @csrf
            <h3 class="font-bold text-base text-slate-800 mb-4 pb-2 border-b border-slate-100">
                Lakukan Penilaian Klinis
            </h3>

            <!-- We loop server side to output checkboxes properly for submit array -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($screenings as $item)
                    <label class="flex items-center p-3 rounded-md border cursor-pointer transition-colors"
                           :class="selected.includes({{ $item->id }}) ? 'border-blue-500 bg-blue-50' : 'border-slate-200 hover:bg-slate-50 hover:border-blue-300'">
                        <input type="checkbox" name="screenings[]" value="{{ $item->id }}" @change="toggleItem({ id: {{ $item->id }}, weight: {{ $item->weight }} })" 
                               :checked="selected.includes({{ $item->id }})"
                               class="w-4 h-4 text-blue-600 bg-white border-slate-300 rounded focus:ring-blue-500 mt-0.5">
                        <div class="ml-3 flex-1 flex justify-between items-center">
                            <span class="text-sm font-semibold text-slate-700">{{ $item->name }}</span>
                            <span class="px-2 py-0.5 rounded text-xs font-bold bg-slate-100 text-slate-600">+{{ $item->weight }}</span>
                        </div>
                    </label>
                @endforeach
            </div>

            <div class="mt-8 pt-5 border-t border-slate-200 flex justify-end gap-3 w-full">
                <a href="/staff/dashboard" class="px-5 py-2.5 rounded-md border border-slate-300 bg-white text-slate-700 font-semibold text-sm hover:bg-slate-50 transition">Batalkan</a>
                <button type="submit" class="px-5 py-2.5 rounded-md bg-blue-600 text-white font-semibold text-sm shadow-sm hover:bg-blue-700 transition">Update Posisi Antrean</button>
            </div>
        </form>
    </div>
    @endif

    @if(!isset($queue) || !$queue->exists)
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden mb-6">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50">
            <h3 class="text-base font-bold text-slate-800">Pilih Pasien untuk Diskrining</h3>
        </div>
        <div class="overflow-x-auto w-full">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white text-slate-500 text-xs uppercase tracking-wider font-bold">
                    <th class="p-4 border-b border-slate-200">No.</th>
                    <th class="p-4 border-b border-slate-200">Nama Pasien</th>
                    <th class="p-4 border-b border-slate-200">Prioritas Terkini</th>
                    <th class="p-4 border-b border-slate-200 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($pendingQueues ?? [] as $pq)
                <tr class="hover:bg-blue-50/50">
                    <td class="p-4 font-bold">{{ $pq->queue_number }}</td>
                    <td class="p-4">{{ $pq->patient->name }}</td>
                    <td class="p-4">
                        <span class="inline-block px-2 py-1 rounded text-xs font-bold border {{ $pq->priority === 'darurat' ? 'bg-red-100 text-red-700 border-red-200' : 'bg-blue-100 text-blue-700 border-blue-200' }}">
                            {{ ucfirst($pq->priority) }} ({{ $pq->total_score }})
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        <a href="/staff/screening/{{ $pq->id }}" class="px-3 py-1.5 rounded bg-green-50 text-green-700 border border-green-200 font-medium text-xs hover:bg-green-100 transition">
                            Mulai Skrining
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="p-6 text-center text-slate-500">Tidak ada pasien di antrian saat ini.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
    @endif
</div>
@endsection
