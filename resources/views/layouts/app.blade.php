<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full antialiased bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Antrian Klinik EMR') }} - Staff</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="h-full text-slate-800 bg-slate-50 relative overflow-hidden">
    
    <div class="h-full flex" x-data="{ sidebarOpen: false }">
        
        <!-- Mobile Sidebar Backdrop -->
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-slate-800/50 lg:hidden" @click="sidebarOpen = false"></div>

        <!-- Sidebar -->
        <nav :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}" class="fixed inset-y-0 left-0 z-50 w-64 bg-blue-700 text-white transition-transform duration-300 lg:translate-x-0 lg:static lg:flex-shrink-0 flex flex-col shadow-xl">
            <div class="p-5 flex items-center justify-between border-b border-blue-600">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white text-blue-700 flex items-center justify-center font-bold text-xl shadow-sm">
                        B
                    </div>
                    <div>
                        <h1 class="text-xl font-bold tracking-tight">Poliklinik BPKP</h1>
                        <p class="text-xs text-blue-200 font-medium tracking-wide">Sistem Informasi</p>
                    </div>
                </div>
                <!-- Close Mobile Sidebar -->
                <button @click="sidebarOpen = false" class="lg:hidden text-blue-200 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="p-4 flex-1 overflow-y-auto space-y-2">
                <div class="text-xs font-semibold text-blue-300 uppercase tracking-wider mb-2 mt-4 px-3">Antrian & Pelayanan</div>
                
                <a href="/staff/dashboard" class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-blue-800/40 text-white font-medium hover:bg-blue-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    Dashboard Antrian
                </a>

                @if(auth()->check() && auth()->user()->role === 'perawat')
                <a href="/staff/register-patient" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-blue-100 font-medium hover:bg-blue-600/50 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    Pendaftaran Pasien
                </a>
                
                <a href="/staff/screening" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-blue-100 font-medium hover:bg-blue-600/50 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Skrining Fisik
                </a>

                <a href="/staff/emr" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-blue-100 font-medium hover:bg-blue-600/50 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Rekam Medik (EMR)
                </a>
                
                <a href="/staff/queue-history" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-blue-100 font-medium hover:bg-blue-600/50 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Riwayat Pendaftaran
                </a>
                
                <a href="/staff/patients" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-blue-100 font-medium hover:bg-blue-600/50 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Database Pasien
                </a>
                @endif

                @if(auth()->check() && auth()->user()->role === 'admin')
                <div class="text-xs font-semibold text-blue-300 uppercase tracking-wider mb-2 mt-6 px-3">Data Master (Admin)</div>

                <a href="/staff/master/screening" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-blue-100 font-medium hover:bg-blue-600/50 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Master Skrining
                </a>
                
                <a href="/staff/patients" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-blue-100 font-medium hover:bg-blue-600/50 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Database Direktori Pasien
                </a>
                
                <a href="/staff/master/users" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-blue-100 font-medium hover:bg-blue-600/50 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Pengguna Staff
                </a>
                @endif
            </div>

            <!-- User Info -->
            <div class="p-4 bg-blue-800">
                <a href="/staff/profile" class="flex items-center gap-3 cursor-pointer p-2 rounded-lg hover:bg-blue-700 transition">
                    <div class="w-10 h-10 rounded-full bg-blue-600 border-2 border-blue-400 flex items-center justify-center font-bold text-white text-sm">
                        {{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 2)) : 'U' }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-white group-hover:underline">{{ auth()->check() ? auth()->user()->name : 'Guest' }}</p>
                        <p class="text-xs text-blue-300 capitalize">{{ auth()->check() ? auth()->user()->role : '-' }}</p>
                    </div>
                </a>
                <div class="mt-4 pt-4 border-t border-blue-700">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left text-xs text-blue-300 hover:text-white flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white shadow-sm border-b border-slate-200 flex-shrink-0">
                <div class="flex items-center justify-between px-4 sm:px-6 py-4">
                    <button @click="sidebarOpen = true" class="text-slate-500 hover:text-slate-700 lg:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div class="hidden lg:block text-slate-800 font-semibold uppercase tracking-wider text-sm">
                        <!-- Breadcrumbs or page title could go here -->
                        Sistem Informasi Poliklinik BPKP
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <button class="text-slate-400 hover:text-blue-600 relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="absolute top-0 right-0 w-2 h-2 rounded-full bg-red-500 border border-white block"></span>
                        </button>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto bg-slate-50 p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>
