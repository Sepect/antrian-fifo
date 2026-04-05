@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ 
    addModal: false, 
    editModal: false, 
    editData: { id: '', name: '', category: '', weight: '' },
    openEdit(item) {
        this.editData = { ...item };
        this.editModal = true;
    }
}">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-blue-900">Kelola Master Skrining & Gejala</h1>
        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded font-bold text-sm border-l-4 border-green-500 shadow-sm">{{ session('success') }}</div>
        @endif
    </div>
    
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
            <h3 class="text-base font-bold text-slate-800">Daftar Kriteria Gejala Utama</h3>
            <button @click="addModal = true" class="px-4 py-2 rounded-md bg-blue-600 text-white font-semibold text-sm hover:bg-blue-700 transition shadow-sm">
                + Tambah Gejala
            </button>
        </div>
        
        <div class="overflow-x-auto w-full">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white text-slate-500 text-xs uppercase tracking-wider font-bold">
                    <th class="p-4 border-b border-slate-200 w-16">ID</th>
                    <th class="p-4 border-b border-slate-200">Nama Gejala / Kriteria Klinis</th>
                    <th class="p-4 border-b border-slate-200">Kategori Medis</th>
                    <th class="p-4 border-b border-slate-200">Bobot Nilai</th>
                    <th class="p-4 border-b border-slate-200 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @foreach($screenings as $s)
                <tr class="hover:bg-slate-50">
                    <td class="p-4 text-slate-500 font-medium">{{ $s->id }}</td>
                    <td class="p-4 font-bold text-slate-800">{{ $s->name }}</td>
                    <td class="p-4 text-slate-600">{{ $s->category ?? '-' }}</td>
                    <td class="p-4">
                        <span class="{{ $s->weight >= 10 ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }} px-2.5 py-0.5 rounded font-bold text-xs border border-white shadow-sm">
                            {{ $s->weight }} Poin
                        </span>
                    </td>
                    <td class="p-4 text-right flex justify-end gap-2 items-center">
                        <button type="button" @click="openEdit({{ json_encode($s) }})" class="px-3 py-1 bg-white border border-slate-300 text-blue-600 hover:bg-slate-50 rounded text-xs font-bold">Edit</button>
                        <form method="POST" action="/staff/master/screening/{{$s->id}}/delete" onsubmit="return confirm('Yakin hapus data ini?');">
                            @csrf
                            <button type="submit" class="px-3 py-1 bg-red-50 border border-red-200 text-red-600 hover:bg-red-100 rounded text-xs font-bold">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div x-show="addModal" class="fixed inset-0 z-50 flex items-center justify-center pointer-events-auto" style="display: none;">
        <div class="fixed inset-0 bg-slate-900/50" @click="addModal = false"></div>
        <div class="bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-md z-10 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="font-bold text-slate-800">Tambah Parameter Gejala</h3>
                <button @click="addModal = false" class="text-slate-400 hover:text-slate-600 font-bold">&times;</button>
            </div>
            <form method="POST" action="/staff/master/screening" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Gejala</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border border-slate-300 rounded focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori</label>
                    <input type="text" name="category" class="w-full px-3 py-2 border border-slate-300 rounded focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Bobot Nilai (Poin)</label>
                    <input type="number" name="weight" required min="1" class="w-full px-3 py-2 border border-slate-300 rounded focus:border-blue-500 outline-none">
                    <p class="text-xs text-slate-500 mt-1">Saran: >10 untuk Darurat, <5 untuk Ringan.</p>
                </div>
                <div class="pt-4 flex justify-end gap-2">
                    <button type="button" @click="addModal = false" class="px-4 py-2 border border-slate-300 rounded text-slate-600 text-sm font-bold">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 rounded text-white text-sm font-bold hover:bg-blue-700">Simpan Gejala</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div x-show="editModal" class="fixed inset-0 z-50 flex items-center justify-center pointer-events-auto" style="display: none;">
        <div class="fixed inset-0 bg-slate-900/50" @click="editModal = false"></div>
        <div class="bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-md z-10 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="font-bold text-slate-800">Edit Parameter Gejala</h3>
                <button @click="editModal = false" class="text-slate-400 hover:text-slate-600 font-bold">&times;</button>
            </div>
            <form method="POST" :action="'/staff/master/screening/' + editData.id + '/update'" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Gejala</label>
                    <input type="text" name="name" x-model="editData.name" required class="w-full px-3 py-2 border border-slate-300 rounded focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori</label>
                    <input type="text" name="category" x-model="editData.category" class="w-full px-3 py-2 border border-slate-300 rounded focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Bobot Nilai (Poin)</label>
                    <input type="number" name="weight" x-model="editData.weight" required min="1" class="w-full px-3 py-2 border border-slate-300 rounded focus:border-blue-500 outline-none">
                </div>
                <div class="pt-4 flex justify-end gap-2">
                    <button type="button" @click="editModal = false" class="px-4 py-2 border border-slate-300 rounded text-slate-600 text-sm font-bold">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 rounded text-white text-sm font-bold hover:bg-blue-700">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
