<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full antialiased bg-blue-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded bg-blue-600 flex items-center justify-center text-white font-bold text-sm shadow">
                        B
                    </div>
                    <a href="/" class="text-xl font-bold tracking-tight text-blue-900">
                        Poliklinik <span class="text-blue-600">BPKP</span>
                    </a>
                </div>

                <!-- Web Navigation -->
                <nav class="hidden md:flex flex-1 justify-end items-center gap-8 mr-8">
                    <a href="/" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors">Pendaftaran Mandiri</a>
                    <a href="/status" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors">Cek Antrian</a>
                </nav>

                <!-- Actions -->
                <div class="flex items-center gap-4">
                    <a href="/login" class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-blue-600 bg-blue-50 border border-blue-100 rounded-md hover:bg-blue-100 transition-colors shadow-sm">
                        Login Staff
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow pb-12 flex flex-col items-center">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto py-8 bg-white border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-slate-500 text-sm font-medium">
                &copy; {{ date('Y') }} Sistem Informasi Poliklinik BPKP.
            </p>
        </div>
    </footer>

</body>
</html>
