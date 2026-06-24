<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Monitoring Capstone</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex">

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-30 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-slate-900 text-slate-100 flex flex-col z-40 transform -translate-x-full lg:translate-x-0 lg:static transition-transform duration-300 ease-in-out border-r border-slate-800">
        <!-- Logo -->
        <div class="h-16 flex items-center px-6 border-b border-slate-800 gap-3">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-slate-950 font-bold">
                C
            </div>
            <span class="font-bold text-sm tracking-wide bg-gradient-to-r from-white to-slate-300 bg-clip-text text-transparent">MONITORING CAPSTONE</span>
        </div>

        <!-- Role Badge -->
        <div class="px-6 py-4 border-b border-slate-800 bg-slate-950/40">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center font-bold text-emerald-400 border border-slate-700 uppercase">
                    {{ substr(auth()->user()->name, 0, 2) }}
                </div>
                <div class="overflow-hidden">
                    <h4 class="font-semibold text-sm truncate text-white">{{ auth()->user()->name }}</h4>
                    <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold mt-0.5">
                        @if(auth()->user()->role === 'admin')
                            Admin Prodi
                        @elseif(auth()->user()->role === 'dosen')
                            Dosen
                        @else
                            Mahasiswa
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all hover:bg-slate-800/60 {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' : 'text-slate-400 hover:text-slate-200' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.kelompok') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all hover:bg-slate-800/60 {{ request()->routeIs('admin.kelompok*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' : 'text-slate-400 hover:text-slate-200' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Kelola Kelompok
                </a>
                <a href="{{ route('admin.mahasiswa') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all hover:bg-slate-800/60 {{ request()->routeIs('admin.mahasiswa*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' : 'text-slate-400 hover:text-slate-200' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Kelola Mahasiswa
                </a>
                <a href="{{ route('admin.dosen') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all hover:bg-slate-800/60 {{ request()->routeIs('admin.dosen*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' : 'text-slate-400 hover:text-slate-200' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Kelola Dosen
                </a>
                <a href="{{ route('admin.prodi') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all hover:bg-slate-800/60 {{ request()->routeIs('admin.prodi*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' : 'text-slate-400 hover:text-slate-200' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Kelola Prodi
                </a>
                <a href="{{ route('admin.tema') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all hover:bg-slate-800/60 {{ request()->routeIs('admin.tema*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' : 'text-slate-400 hover:text-slate-200' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                    Kelola Tema
                </a>
                <a href="{{ route('admin.laporan') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all hover:bg-slate-800/60 {{ request()->routeIs('admin.laporan*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' : 'text-slate-400 hover:text-slate-200' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 2v-6m-9-3h9m0 0a2 2 0 012 2v3m2 4H10m0 0V8m0 12h-3"/></svg>
                    Rekap & Laporan
                </a>
            @elseif(auth()->user()->role === 'dosen')
                <a href="{{ route('dosen.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all hover:bg-slate-800/60 {{ request()->routeIs('dosen.dashboard') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' : 'text-slate-400 hover:text-slate-200' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/></svg>
                    Dashboard Dosen
                </a>
            @elseif(auth()->user()->role === 'mahasiswa')
                <a href="{{ route('mahasiswa.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all hover:bg-slate-800/60 {{ request()->routeIs('mahasiswa.dashboard') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' : 'text-slate-400 hover:text-slate-200' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Status Capstone
                </a>
                <a href="{{ route('mahasiswa.pengajuan') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all hover:bg-slate-800/60 {{ request()->routeIs('mahasiswa.pengajuan') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' : 'text-slate-400 hover:text-slate-200' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Pengajuan Judul
                </a>
                <a href="{{ route('mahasiswa.logbook') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all hover:bg-slate-800/60 {{ request()->routeIs('mahasiswa.logbook*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' : 'text-slate-400 hover:text-slate-200' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Logbook Capstone
                </a>
                <a href="{{ route('mahasiswa.laporan') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all hover:bg-slate-800/60 {{ request()->routeIs('mahasiswa.laporan*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' : 'text-slate-400 hover:text-slate-200' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Laporan & Nilai
                </a>
            @endif
        </nav>

        <!-- Logout Button -->
        <div class="p-4 border-t border-slate-800">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-red-950/40 text-slate-300 hover:text-red-400 text-sm font-semibold transition-all border border-slate-700 hover:border-red-900/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Keluar Aplikasi
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-h-screen overflow-hidden">
        
        <!-- Header / Navbar -->
        <header class="h-16 bg-white border-b border-slate-200/80 flex items-center justify-between px-6 z-20 shrink-0">
            <div class="flex items-center gap-3">
                <button class="p-2 -ml-2 rounded-lg hover:bg-slate-100 lg:hidden text-slate-600" onclick="toggleSidebar()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h2 class="font-bold text-slate-800 text-lg hidden sm:block">@yield('page-title')</h2>
            </div>
            
            <div class="flex items-center gap-4">
                <span class="text-sm font-semibold text-slate-500 bg-slate-100 px-3 py-1 rounded-full border border-slate-200/55">
                    T.A. 2026/2027
                </span>
                
                <div class="w-px h-6 bg-slate-200"></div>
                
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-slate-600">{{ auth()->user()->name }}</span>
                    <div class="w-8 h-8 rounded-full bg-emerald-100 border border-emerald-200 flex items-center justify-center font-bold text-xs text-emerald-800 uppercase">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-1 overflow-y-auto p-6 bg-slate-50/50">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium shadow-sm">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm font-medium shadow-sm">
                    <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm font-medium shadow-sm space-y-1">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span class="font-bold">Terjadi kesalahan input:</span>
                    </div>
                    <ul class="list-disc list-inside pl-8 text-xs text-red-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Sidebar controller script -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
