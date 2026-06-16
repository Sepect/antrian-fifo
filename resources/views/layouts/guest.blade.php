<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full antialiased bg-blue-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sistem Pendaftaran Antrian dan Rekam Medis Elektronik Poliklinik BPKP">
    <title>{{ config('app.name', 'Antrian Klinik EMR') }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-full bg-slate-50 text-slate-800 flex flex-col relative overflow-x-hidden">
    
    <!-- Clean Minimalist Background pattern -->
    <div class="fixed inset-0 z-[-1] bg-slate-50">
        <div class="absolute inset-0 bg-[radial-gradient(#e2e8f0_1px,transparent_1px)] [background-size:20px_20px] opacity-50"></div>
        <!-- Simple Top Blue Gradient -->
        <div class="absolute top-0 left-0 right-0 h-96 bg-gradient-to-b from-blue-100 to-transparent"></div>
    </div>

    <!-- Clean Header Navigation -->
    <header class="bg-white shadow-sm sticky top-0 z-50" x-data="{ mobileNav: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-14 sm:h-16">
                <!-- Logo -->
                <div class="flex items-center gap-2 sm:gap-3">
                    <div class="w-7 h-7 sm:w-8 sm:h-8 rounded bg-blue-600 flex items-center justify-center text-white font-bold text-xs sm:text-sm shadow">
                        B
                    </div>
                    <a href="/" class="text-lg sm:text-xl font-bold tracking-tight text-blue-900">
                        Poliklinik <span class="text-blue-600">BPKP</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex flex-1 justify-end items-center gap-8 mr-8">
                    <a href="/" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors">Pendaftaran Mandiri</a>
                    <a href="/status" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors">Cek Antrian</a>
                </nav>

                <!-- Actions -->
                <div class="flex items-center gap-2 sm:gap-4">
                    <a href="/login" class="inline-flex items-center justify-center px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-bold text-blue-600 bg-blue-50 border border-blue-100 rounded-md hover:bg-blue-100 transition-colors shadow-sm">
                        Login Staff
                    </a>
                    <!-- Mobile Menu Toggle -->
                    <button @click="mobileNav = !mobileNav" class="md:hidden p-1.5 rounded text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path x-show="!mobileNav" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="mobileNav" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Dropdown -->
        <div x-show="mobileNav" x-transition.opacity class="md:hidden border-t border-slate-100 bg-white shadow-lg">
            <nav class="px-4 py-3 space-y-1">
                <a href="/" class="block px-3 py-2.5 rounded-md text-sm font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    Pendaftaran Mandiri
                </a>
                <a href="/status" class="block px-3 py-2.5 rounded-md text-sm font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    Cek Antrian
                </a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow pb-8 sm:pb-12 flex flex-col items-center">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto py-6 sm:py-8 bg-white border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-slate-500 text-xs sm:text-sm font-medium">
                &copy; {{ date('Y') }} Sistem Informasi Poliklinik BPKP.
            </p>
        </div>
    </footer>

</body>
</html>
