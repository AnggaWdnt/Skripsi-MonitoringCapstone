@extends('layouts.app')

@section('title', 'Dashboard Admin Prodi')
@section('page-title', 'Dashboard Program Studi')

@section('content')
<div class="space-y-6">
    <!-- Top Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm flex items-center gap-5">
            <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <div>
                <span class="block text-2xl font-extrabold text-slate-800">{{ $totalMahasiswa }}</span>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Mahasiswa</span>
            </div>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm flex items-center gap-5">
            <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div>
                <span class="block text-2xl font-extrabold text-slate-800">{{ $totalDosen }}</span>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Dosen</span>
            </div>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm flex items-center gap-5">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
                <span class="block text-2xl font-extrabold text-slate-800">{{ $totalKelompok }}</span>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kelompok Capstone</span>
            </div>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm flex items-center gap-5">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <span class="block text-2xl font-extrabold text-slate-800">{{ $statusApproved }}</span>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Judul Disetujui</span>
            </div>
        </div>
    </div>

    <!-- Status Breakdown Visualization -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
        <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-5">
            Status Pengajuan Judul Kelompok Capstone
        </h3>
        
        <div class="space-y-4">
            <!-- Belum Mengajukan -->
            <div>
                <div class="flex items-center justify-between text-xs font-semibold text-slate-600 mb-1.5">
                    <span>Belum Mengajukan Judul</span>
                    <span>{{ $statusBelum }} / {{ $totalKelompok }} Kelompok</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-3.5 overflow-hidden">
                    <div class="bg-slate-400 h-full rounded-full transition-all duration-500" style="width: {{ $totalKelompok > 0 ? ($statusBelum / $totalKelompok) * 100 : 0 }}%"></div>
                </div>
            </div>

            <!-- Pending -->
            <div>
                <div class="flex items-center justify-between text-xs font-semibold text-slate-600 mb-1.5">
                    <span>Menunggu Review Judul & Bukti Survei</span>
                    <span>{{ $statusPending }} / {{ $totalKelompok }} Kelompok</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-3.5 overflow-hidden">
                    <div class="bg-amber-500 h-full rounded-full transition-all duration-500" style="width: {{ $totalKelompok > 0 ? ($statusPending / $totalKelompok) * 100 : 0 }}%"></div>
                </div>
            </div>

            <!-- Approved -->
            <div>
                <div class="flex items-center justify-between text-xs font-semibold text-slate-600 mb-1.5">
                    <span>Judul & Proyek Disetujui (Aktif)</span>
                    <span>{{ $statusApproved }} / {{ $totalKelompok }} Kelompok</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-3.5 overflow-hidden">
                    <div class="bg-emerald-500 h-full rounded-full transition-all duration-500" style="width: {{ $totalKelompok > 0 ? ($statusApproved / $totalKelompok) * 100 : 0 }}%"></div>
                </div>
            </div>

            <!-- Rejected -->
            <div>
                <div class="flex items-center justify-between text-xs font-semibold text-slate-600 mb-1.5">
                    <span>Pengajuan Judul Ditolak / Perlu Revisi</span>
                    <span>{{ $statusRejected }} / {{ $totalKelompok }} Kelompok</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-3.5 overflow-hidden">
                    <div class="bg-red-500 h-full rounded-full transition-all duration-500" style="width: {{ $totalKelompok > 0 ? ($statusRejected / $totalKelompok) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid: Lecturer Workload & Recent Logs -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Advisor Workload Card -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
            <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-5">
                Beban Bimbingan Kelompok Dosen Pembimbing
            </h3>

            @if($dosens->isEmpty())
                <div class="text-center py-8 text-slate-400 text-sm">
                    Belum ada dosen yang terdaftar.
                </div>
            @else
                <div class="divide-y divide-slate-100 max-h-[350px] overflow-y-auto pr-2">
                    @foreach($dosens as $dosen)
                        <div class="py-3 flex items-center justify-between gap-4">
                            <div>
                                <h4 class="text-sm font-bold text-slate-800">{{ $dosen->name }}</h4>
                                <span class="text-xs text-slate-400">NIDN: {{ $dosen->nidn }}</span>
                            </div>
                            <div class="text-right">
                                <span class="inline-block px-3 py-1 bg-slate-100 border border-slate-200/60 rounded-full text-xs font-bold text-slate-700">
                                    {{ $dosen->groups_count }} Kelompok
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Recent Activities Card -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
            <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-5">
                Aktivitas Logbook Terbaru
            </h3>

            @if($recentLogbooks->isEmpty())
                <div class="text-center py-8 text-slate-400 text-sm">
                    Belum ada aktivitas pengisian logbook harian.
                </div>
            @else
                <div class="space-y-4 max-h-[350px] overflow-y-auto pr-2">
                    @foreach($recentLogbooks as $log)
                        <div class="p-3.5 border border-slate-100 rounded-xl bg-slate-50/45 flex flex-col gap-1.5">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-slate-800">{{ $log->mahasiswa->name }}</span>
                                <span class="text-slate-400">{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                                {{ $log->activity }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
