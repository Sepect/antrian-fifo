<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Staff - KlinikKu EMR</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="h-full flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden">
        <div class="bg-blue-700 px-6 py-8 text-center">
            <div
                class="w-12 h-12 rounded bg-white flex items-center justify-center text-blue-700 font-bold text-xl mx-auto mb-3 shadow">
                K
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Poliklinik BPKP</h1>
            <p class="text-blue-200 text-sm mt-1">Sistem Manajemen & Antrean Prioritas</p>
        </div>

        <div class="p-8">
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Alamat Email</label>
                    <input type="email" name="email" required placeholder="contoh@klinik.com"
                        class="w-full px-4 py-2.5 rounded-md border border-slate-300 bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-slate-800 transition">
                </div>
                <div>
                    <!-- <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-semibold text-slate-700">Password</label>
                        <a href="#" class="text-xs text-blue-600 font-semibold hover:underline">Lupa Password?</a>
                    </div> -->
                    <input type="password" name="password" required placeholder="••••••••"
                        class="w-full px-4 py-2.5 rounded-md border border-slate-300 bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-slate-800 transition">
                </div>
                <!-- <div class="flex items-center">
                    <input id="remember-me" name="remember" type="checkbox"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                    <label for="remember-me" class="ml-2 block text-sm text-slate-700">
                        Ingat sesi saya
                    </label>
                </div> -->
                <div class="pt-2">
                    <button type="submit"
                        class="w-full py-2.5 rounded-md bg-blue-600 hover:bg-blue-700 text-white font-bold transition">
                        Masuk Dashboard
                    </button>
                </div>
            </form>

            <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                <a href="/" class="text-sm font-medium text-slate-500 hover:text-blue-600">&larr; Kembali ke Portal
                    Pasien</a>
            </div>
        </div>
    </div>
</body>

</html>