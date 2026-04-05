@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-4 border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-bold text-blue-900">Pengaturan Profil Anda</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded border-l-4 border-green-500 font-bold mb-4 shadow-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded border-l-4 border-red-500 font-bold mb-4 shadow-sm">
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded border-l-4 border-red-500 font-bold mb-4 shadow-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-6">
        <form method="POST" action="/staff/profile" class="space-y-5">
            @csrf
            
            <div class="flex items-center gap-6 mb-6 pb-6 border-b border-slate-100">
                <div class="w-20 h-20 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-3xl shadow-sm border border-blue-200">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800">{{ auth()->user()->name }}</h2>
                    <p class="text-sm font-medium text-slate-500 capitalize bg-slate-100 px-2 py-0.5 rounded inline-block mt-1">Akses: {{ auth()->user()->role }}</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ auth()->user()->name }}" required class="w-full px-4 py-2.5 border border-slate-300 rounded-md focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition text-slate-800 font-medium">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Alamat Email (Digunakan untuk Login)</label>
                <input type="email" name="email" value="{{ auth()->user()->email }}" required class="w-full px-4 py-2.5 border border-slate-300 rounded-md focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition text-slate-800 font-medium">
            </div>

            <div class="pt-4 border-t border-slate-100">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Ubah Keamanan Sandi</label>
                <p class="text-xs text-slate-500 mb-3">Isi bidang di bawah ini hanya jika Anda ingin mengubah kata sandi Anda. Jika tidak, biarkan kosong.</p>
                <input type="password" name="password" placeholder="••••••••" class="w-full px-4 py-2.5 border border-slate-300 rounded-md focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition text-slate-800">
            </div>

            <div class="pt-6 flex gap-3">
                <button type="submit" class="px-6 py-2.5 rounded-md bg-blue-600 text-white font-bold shadow-sm hover:bg-blue-700 transition">Update Profil</button>
            </div>
        </form>
    </div>
</div>
@endsection
