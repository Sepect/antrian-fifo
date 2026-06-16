@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Master Data Poli</h1>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-6 rounded shadow-md mb-8">
        <h2 class="text-lg font-bold mb-4">Tambah Poli Baru</h2>
        <form action="{{ route('admin.polyclinics.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Poli</label>
                    <input type="text" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required placeholder="Contoh: GIGI">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Keterangan / Deskripsi</label>
                    <input type="text" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Opsional">
                </div>
            </div>
            <button class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Tambah</button>
        </form>
    </div>

    <div class="bg-white p-6 rounded shadow-md">
        <h2 class="text-lg font-bold mb-4">Daftar Poli</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-700">ID</th>
                        <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-700">Nama Poli</th>
                        <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-700">Keterangan</th>
                        <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($polyclinics as $poli)
                    <tr>
                        <td class="py-2 px-4 border-b text-sm">{{ $poli->id }}</td>
                        <td class="py-2 px-4 border-b text-sm font-bold">{{ $poli->name }}</td>
                        <td class="py-2 px-4 border-b text-sm">{{ $poli->description ?? '-' }}</td>
                        <td class="py-2 px-4 border-b text-sm">
                            <form action="{{ route('admin.polyclinics.destroy', $poli->id) }}" method="POST" onsubmit="return confirm('Hapus poli ini?');" class="inline-block">
                                @csrf
                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-semibold">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-4 text-center text-gray-500">Belum ada data poli.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
