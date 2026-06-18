<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Capstone Project</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col relative overflow-x-hidden">
    <!-- Subtle glow background -->
    <div
        class="absolute top-0 left-1/2 -translate-x-1/2 w-[500px] h-[350px] rounded-full bg-emerald-500/5 blur-[120px] pointer-events-none z-0">
    </div>

    <!-- Header Navigation -->
    <header class="container mx-auto px-6 h-20 flex items-center justify-between relative z-10">
        <div class="flex items-center gap-3">
            <div
                class="w-9 h-9 rounded-lg bg-emerald-500 flex items-center justify-center text-slate-950 font-extrabold text-lg">
                M
            </div>
            <span class="font-extrabold text-base tracking-wider text-white">Monitoring Project Mu</span>
        </div>

        <div>
            @auth
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : (auth()->user()->role === 'dosen' ? route('dosen.dashboard') : route('mahasiswa.dashboard')) }}"
                    class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold rounded-lg text-sm transition-all shadow-md shadow-emerald-500/10">
                    Dashboard Saya
                </a>
            @else
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}"
                        class="font-semibold text-slate-300 hover:text-white transition-colors text-sm">Masuk</a>
                    <a href="{{ route('register') }}"
                        class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-lg transition-all text-sm">Daftar</a>
                </div>
            @endauth
        </div>
    </header>

    <!-- Hero Section -->
    <main class="flex-1 flex flex-col justify-center relative z-10">
        <div class="container mx-auto px-6 py-20 text-center max-w-3xl">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white leading-tight mb-8">
                Monitoring Kegiatan <br>
                <span class="text-emerald-400">Project Mu</span>
            </h1>

            <div class="flex items-center justify-center gap-4">
                @auth
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : (auth()->user()->role === 'dosen' ? route('dosen.dashboard') : route('mahasiswa.dashboard')) }}"
                        class="px-6 py-3 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold rounded-lg shadow-lg shadow-emerald-500/10 transition-all text-sm">
                        Buka Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="px-6 py-3 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold rounded-lg shadow-lg shadow-emerald-500/10 transition-all text-sm">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                        class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-lg transition-all text-sm">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>

        <!-- Role Overview Section -->
        <section class="bg-slate-950/60 py-16">
            <div class="container mx-auto px-6 max-w-4xl">
                <div class="grid md:grid-cols-3 gap-6">
                    <!-- Mahasiswa -->
                    <div class="bg-slate-900/40 p-6 rounded-xl">
                        <div
                            class="w-10 h-10 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-400 mb-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h4 class="text-sm font-bold text-white mb-1.5">Mahasiswa</h4>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Mengajukan kelompok Capstone, mengunggah bukti survei lapangan, mengisi logbook harian, dan
                            menyerahkan berkas laporan akhir.
                        </p>
                    </div>

                    <!-- Dosen Pembimbing -->
                    <div class="bg-slate-900/40 p-6 rounded-xl">
                        <div
                            class="w-10 h-10 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-400 mb-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 00-2 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <h4 class="text-sm font-bold text-white mb-1.5">Dosen Pembimbing</h4>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Memantau progress bimbingan, menyetujui logbook harian, memberikan umpan balik revisi, dan
                            memberikan nilai evaluasi kelompok Capstone.
                        </p>
                    </div>

                    <!-- Admin Program Studi -->
                    <div class="bg-slate-900/40 p-6 rounded-xl">
                        <div
                            class="w-10 h-10 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-400 mb-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h4 class="text-sm font-bold text-white mb-1.5">Admin Program Studi</h4>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Mengelola data mahasiswa & dosen, menetapkan dosen pembimbing kelompok, serta memantau dan
                            mencetak rekapitulasi data Capstone.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="h-16 flex items-center justify-center bg-slate-950 text-xs text-slate-500 relative z-10">
        &copy; 2026 Universitas - Program Studi Teknologi Informasi. All Rights Reserved.
    </footer>
</body>

</html>