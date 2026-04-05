@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ 
    addModal: false, 
    editModal: false, 
    editData: { id: '', name: '', email: '', role: '' },
    openEdit(user) {
        this.editData = { ...user };
        this.editModal = true;
    }
}">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-blue-900">Kelola Data Pengguna Staff</h1>
        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded font-bold text-sm border-l-4 border-green-500 shadow-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded font-bold text-sm border-l-4 border-red-500 shadow-sm">{{ session('error') }}</div>
        @endif
    </div>
    
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
            <h3 class="text-base font-bold text-slate-800">Daftar Akun Karyawan</h3>
            <button @click="addModal = true" class="px-4 py-2 rounded-md bg-blue-600 text-white font-semibold text-sm hover:bg-blue-700 transition shadow-sm">
                + Tambah Akun Baru
            </button>
        </div>
        
        <div class="overflow-x-auto w-full">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white text-slate-500 text-xs uppercase tracking-wider font-bold">
                    <th class="p-4 border-b border-slate-200 w-16">ID</th>
                    <th class="p-4 border-b border-slate-200">Nama Staff</th>
                    <th class="p-4 border-b border-slate-200">Email Login</th>
                    <th class="p-4 border-b border-slate-200">Peran (Role)</th>
                    <th class="p-4 border-b border-slate-200 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @foreach($users as $u)
                <tr class="hover:bg-slate-50">
                    <td class="p-4 text-slate-500 font-medium">{{ $u->id }}</td>
                    <td class="p-4 font-bold text-slate-800 flex items-center gap-3">
                        <div class="w-8 h-8 rounded bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs">{{ strtoupper(substr($u->name, 0, 2)) }}</div>
                        {{ $u->name }}
                    </td>
                    <td class="p-4 text-slate-600">{{ $u->email }}</td>
                    <td class="p-4"><span class="{{ $u->role === 'admin' ? 'bg-slate-800 text-white' : 'bg-blue-100 text-blue-700 border border-blue-200' }} px-2.5 py-0.5 rounded font-bold text-xs capitalize">{{ $u->role }}</span></td>
                    <td class="p-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button type="button" @click="openEdit({{ json_encode($u) }})" class="text-blue-600 hover:text-blue-800 font-bold text-xs px-2 py-1 rounded hover:bg-blue-50 border border-transparent">Edit</button>
                            <form method="POST" action="/staff/master/users/{{$u->id}}/delete" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $u->name }}?');">
                                @csrf
                                <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-xs px-2 py-1 rounded hover:bg-red-50 border border-transparent">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>

    <!-- Modal Tambah Users -->
    <div x-show="addModal" class="fixed inset-0 z-50 flex items-center justify-center pointer-events-auto" style="display: none;">
        <div class="fixed inset-0 bg-slate-900/50" @click="addModal = false"></div>
        <div class="bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-md z-10 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="font-bold text-slate-800">Tambah Akun Karyawan</h3>
                <button @click="addModal = false" class="text-slate-400 hover:text-slate-600 font-bold">&times;</button>
            </div>
            <form method="POST" action="/staff/master/users" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border border-slate-300 rounded focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Alamat Email (Login)</label>
                    <input type="email" name="email" required class="w-full px-3 py-2 border border-slate-300 rounded focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Password Baru</label>
                    <input type="password" name="password" required class="w-full px-3 py-2 border border-slate-300 rounded focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Role Jabatan</label>
                    <select name="role" required class="w-full px-3 py-2 border border-slate-300 rounded focus:border-blue-500 outline-none">
                        <option value="perawat">Perawat (Antrian & EMR)</option>
                        <option value="admin">Admin (Master & Dashboard)</option>
                    </select>
                </div>
                <div class="pt-4 flex justify-end gap-2">
                    <button type="button" @click="addModal = false" class="px-4 py-2 border border-slate-300 rounded text-slate-600 text-sm font-bold">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 rounded text-white text-sm font-bold hover:bg-blue-700">Buat Akun</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Users -->
    <div x-show="editModal" class="fixed inset-0 z-50 flex items-center justify-center pointer-events-auto" style="display: none;">
        <div class="fixed inset-0 bg-slate-900/50" @click="editModal = false"></div>
        <div class="bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-md z-10 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="font-bold text-slate-800">Edit Akses Karyawan</h3>
                <button @click="editModal = false" class="text-slate-400 hover:text-slate-600 font-bold">&times;</button>
            </div>
            <form method="POST" :action="'/staff/master/users/' + editData.id + '/update'" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" x-model="editData.name" required class="w-full px-3 py-2 border border-slate-300 rounded focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Alamat Email</label>
                    <input type="email" name="email" x-model="editData.email" required class="w-full px-3 py-2 border border-slate-300 rounded focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Ubah Password (Kosongkan bila sama)</label>
                    <input type="password" name="password" class="w-full px-3 py-2 border border-slate-300 rounded focus:border-blue-500 outline-none" placeholder="••••••••">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Role Jabatan</label>
                    <select name="role" x-model="editData.role" required class="w-full px-3 py-2 border border-slate-300 rounded focus:border-blue-500 outline-none">
                        <option value="perawat">Perawat (Antrian & EMR)</option>
                        <option value="admin">Admin (Master & Dashboard)</option>
                    </select>
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
