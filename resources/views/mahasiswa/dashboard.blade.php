@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')
@section('page-title', 'Dashboard Capstone Project')

@section('content')
<div class="space-y-6">
    @if(!$group)
        <!-- No Group Banner -->
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-8 text-center shadow-sm">
            <svg class="w-16 h-16 text-amber-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <h3 class="text-lg font-bold text-amber-800">Anda Belum Terdaftar Kelompok</h3>
            <p class="text-sm text-amber-600 mt-2 max-w-md mx-auto">Program Studi belum mendaftarkan akun Anda ke kelompok Capstone manapun. Harap hubungi Admin atau Koordinator Capstone Program Studi Anda.</p>
        </div>
    @else
        <!-- Capstone Progress Banner -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Status Capstone Kelompok</h3>
                    <p class="text-sm text-slate-500 mt-1">Berikut tahapan pengerjaan proyek Capstone Anda.</p>
                </div>
                <div>
                    @if($group->status === 'belum_mengajukan')
                        <span class="px-4 py-2 rounded-xl text-xs font-semibold uppercase tracking-wider bg-slate-100 border border-slate-200 text-slate-600 inline-block">Belum Mengajukan Judul</span>
                    @elseif($group->status === 'pending')
                        <span class="px-4 py-2 rounded-xl text-xs font-semibold uppercase tracking-wider bg-amber-50 border border-amber-200 text-amber-700 inline-block">Menunggu Persetujuan Judul</span>
                    @elseif($group->status === 'approved')
                        <span class="px-4 py-2 rounded-xl text-xs font-semibold uppercase tracking-wider bg-emerald-50 border border-emerald-200 text-emerald-700 inline-block">Judul Disetujui (Aktif)</span>
                    @elseif($group->status === 'rejected')
                        <span class="px-4 py-2 rounded-xl text-xs font-semibold uppercase tracking-wider bg-red-50 border border-red-200 text-red-700 inline-block">Pengajuan Judul Ditolak</span>
                    @endif
                </div>
            </div>

            <!-- Progress Timeline (Visual) -->
            <div class="mt-8 relative">
                <div class="absolute inset-0 top-1/2 -translate-y-1/2 h-0.5 bg-slate-200 z-0 hidden md:block"></div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 relative z-10">
                    <!-- Step 1 -->
                    <div class="flex items-center md:flex-col gap-4 text-left md:text-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm border-2 bg-emerald-500 border-emerald-500 text-white shadow-lg shadow-emerald-500/20">
                            1
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-slate-800">Penempatan Kelompok</h4>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $group->group_name }}</p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="flex items-center md:flex-col gap-4 text-left md:text-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm border-2 {{ in_array($group->status, ['approved', 'pending']) || $group->laporan_file ? 'bg-emerald-500 border-emerald-500 text-white shadow-lg shadow-emerald-500/20' : 'bg-white border-slate-300 text-slate-500' }}">
                            2
                        </div>
                        <div>
                            <h4 class="font-bold text-sm {{ in_array($group->status, ['approved', 'pending']) || $group->laporan_file ? 'text-slate-800' : 'text-slate-500' }}">Pengajuan Judul</h4>
                            <p class="text-xs text-slate-400 mt-0.5">Pengusulan judul kelompok</p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="flex items-center md:flex-col gap-4 text-left md:text-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm border-2 {{ $group->status === 'approved' ? 'bg-emerald-500 border-emerald-500 text-white shadow-lg shadow-emerald-500/20' : 'bg-white border-slate-300 text-slate-500' }}">
                            3
                        </div>
                        <div>
                            <h4 class="font-bold text-sm {{ $group->status === 'approved' ? 'text-slate-800' : 'text-slate-500' }}">Logbook Mingguan</h4>
                            <p class="text-xs text-slate-400 mt-0.5">Catatan pengerjaan produk</p>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="flex items-center md:flex-col gap-4 text-left md:text-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm border-2 {{ $group->laporan_file ? 'bg-emerald-500 border-emerald-500 text-white shadow-lg shadow-emerald-500/20' : 'bg-white border-slate-300 text-slate-500' }}">
                            4
                        </div>
                        <div>
                            <h4 class="font-bold text-sm {{ $group->laporan_file ? 'text-slate-800' : 'text-slate-500' }}">Laporan Akhir</h4>
                            <p class="text-xs text-slate-400 mt-0.5">Berkas laporan Capstone</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm flex items-center gap-5">
                <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <span class="block text-2xl font-extrabold text-slate-800">{{ $totalLogs }}</span>
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Logbook Saya</span>
                </div>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm flex items-center gap-5">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <span class="block text-2xl font-extrabold text-slate-800">{{ $approvedLogs }}</span>
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Logbook Disetujui</span>
                </div>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm flex items-center gap-5">
                <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <span class="block text-2xl font-extrabold text-slate-800">{{ $pendingLogs }}</span>
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Menunggu Review</span>
                </div>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Capstone Details & Section Progress -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Capstone Details Card -->
                <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-6 pb-3 border-b border-slate-100 flex items-center justify-between">
                        <span>Detail Proyek Capstone Kelompok</span>
                        @if($group->status !== 'approved')
                            <a href="{{ route('mahasiswa.pengajuan') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-500 transition-colors">
                                {{ $group->status === 'belum_mengajukan' ? 'Ajukan Judul' : 'Edit Pengajuan' }}
                            </a>
                        @endif
                    </h3>
                    
                    @if($group->status === 'belum_mengajukan')
                        <div class="text-center py-8">
                            <p class="text-slate-500 text-sm">Kelompok Anda belum mengajukan Judul Capstone.</p>
                            <a href="{{ route('mahasiswa.pengajuan') }}" class="mt-4 inline-block px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold rounded-xl shadow-md transition-all">
                                Ajukan Judul
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Nama Kelompok</span>
                                    <span class="text-sm font-bold text-slate-800 mt-1 block">{{ $group->group_name }}</span>
                                </div>
                                <div>
                                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Judul Capstone</span>
                                    <span class="text-sm font-bold text-slate-800 mt-1 block">{{ $group->title }}</span>
                                </div>
                                <div>
                                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Deskripsi Singkat / Abstrak</span>
                                    <p class="text-sm text-slate-600 mt-1 block leading-relaxed">{{ $group->description }}</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Dosen Pembimbing</span>
                                    <span class="text-sm font-bold text-slate-800 mt-1 block">
                                        {{ $dosen ? $dosen->name : 'Belum Ditentukan' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Anggota Kelompok</span>
                                    <div class="mt-2 space-y-2">
                                        @foreach($group->members as $member)
                                            <div class="flex items-center justify-between p-2.5 bg-slate-50 border border-slate-200/60 rounded-xl">
                                                <div>
                                                    <span class="text-xs font-bold text-slate-800 block">{{ $member->name }}</span>
                                                    <span class="text-[10px] text-slate-500 block">NIM: {{ $member->nim }}</span>
                                                </div>
                                                @if($member->id === $user->id)
                                                    <span class="text-[9px] font-bold bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">Saya</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div>
                                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Laporan Akhir Capstone</span>
                                    @if($group->laporan_file)
                                        <a href="{{ asset($group->laporan_file) }}" target="_blank" class="text-sm font-semibold text-emerald-600 hover:text-emerald-500 transition-colors inline-flex items-center gap-1.5 mt-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            Lihat File Laporan
                                        </a>
                                    @else
                                        <span class="text-sm text-slate-400 mt-1 block">Belum Diunggah</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Progress Per Bagian/Bab Card -->
                @if($group->status === 'approved')
                    @php
                        $sections = [
                            'Pendahuluan / Latar Belakang',
                            'Tinjauan Pustaka / Landasan Teori',
                            'Metodologi / Perancangan Sistem',
                            'Analisis dan Pembahasan',
                            'Kesimpulan dan Saran',
                            'Lainnya'
                        ];
                    @endphp
                    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm p-6">
                        <h3 class="text-base font-bold text-slate-800 mb-6 pb-3 border-b border-slate-100 flex items-center justify-between">
                            <span>Progress Capstone Per Bagian / Bab</span>
                            <span class="text-xs text-slate-400 font-normal">Dipantau real-time dari Logbook</span>
                        </h3>
                        
                        <div class="grid grid-cols-1 gap-3">
                            @foreach($sections as $sec)
                                @php
                                    $secLogs = $groupLogbooks->filter(fn($l) => $l->section === $sec);
                                    $hasApproved = $secLogs->contains('status', 'approved');
                                    $hasPending = $secLogs->contains('status', 'pending');
                                    $hasRejected = $secLogs->contains('status', 'rejected');
                                    
                                    // Find latest documentation
                                    $latestDoc = $secLogs->whereNotNull('documentation')->sortByDesc('date')->first()?->documentation;
                                @endphp
                                
                                <div class="flex items-center justify-between p-4 border rounded-xl transition-all
                                    @if($hasApproved) bg-emerald-50/10 border-emerald-100/80 hover:bg-emerald-50/20
                                    @elseif($hasPending) bg-amber-50/10 border-amber-100/80 hover:bg-amber-50/20
                                    @elseif($hasRejected) bg-red-50/10 border-red-100/80 hover:bg-red-50/20
                                    @else bg-slate-50/30 border-slate-200/50 hover:bg-slate-50/60 @endif">
                                    
                                    <div class="flex items-center gap-3">
                                        <!-- Icon Status Left -->
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0
                                            @if($hasApproved) bg-emerald-100 text-emerald-700
                                            @elseif($hasPending) bg-amber-100 text-amber-700
                                            @elseif($hasRejected) bg-red-100 text-red-700
                                            @else bg-slate-100 text-slate-400 @endif">
                                            @if($hasApproved)
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            @elseif($hasPending)
                                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            @elseif($hasRejected)
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            @endif
                                        </div>
                                        
                                        <div class="space-y-0.5">
                                            <span class="block text-xs font-bold text-slate-800 leading-tight">{{ $sec }}</span>
                                            <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5">
                                                <span class="text-[10px] text-slate-400">Total: {{ $secLogs->count() }} Logbook</span>
                                                @if($latestDoc)
                                                    <span class="text-[10px] text-slate-300">•</span>
                                                    <a href="{{ asset($latestDoc) }}" target="_blank" class="text-[10px] font-bold text-emerald-600 hover:text-emerald-700 hover:underline inline-flex items-center gap-0.5">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                        Unduh Berkas Terbaru
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="shrink-0 pl-2">
                                        @if($hasApproved)
                                            <span class="px-2.5 py-1 rounded-md text-[9px] font-extrabold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200/50 whitespace-nowrap">
                                                Selesai
                                            </span>
                                        @elseif($hasPending)
                                            <span class="px-2.5 py-1 rounded-md text-[9px] font-extrabold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200/50 whitespace-nowrap">
                                                Review
                                            </span>
                                        @elseif($hasRejected)
                                            <span class="px-2.5 py-1 rounded-md text-[9px] font-extrabold uppercase tracking-wider bg-red-50 text-red-700 border border-red-200/50 whitespace-nowrap">
                                                Revisi
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-md text-[9px] font-extrabold uppercase tracking-wider bg-slate-100/80 text-slate-400 border border-slate-200/40 whitespace-nowrap">
                                                Belum Mulai
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Advisor & Evaluation Card -->
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm p-6 flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 mb-6 pb-3 border-b border-slate-100">Evaluasi Pembimbing</h3>
                    
                    @if($group->nilai_capstone === null)
                        <div class="text-center py-8 text-slate-500 text-sm">
                            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                            Dosen pembimbing belum memberikan penilaian akhir Capstone.
                        </div>
                    @else
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Nilai Capstone Kelompok</span>
                                <span class="text-3xl font-extrabold text-emerald-600 bg-emerald-50 px-3.5 py-1 rounded-2xl border border-emerald-100">
                                    {{ $group->nilai_capstone }}
                                </span>
                            </div>
                            
                            @if($group->catatan)
                                <div>
                                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Catatan Evaluasi</span>
                                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-200/60 mt-2 text-sm text-slate-600 leading-relaxed italic">
                                        "{{ $group->catatan }}"
                                    </div>
                                </div>
                            @endif

                            <div>
                                <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Status Proyek</span>
                                <span class="text-xs font-bold text-blue-700 mt-1 block uppercase bg-blue-50 border border-blue-100 px-3 py-1.5 rounded-xl text-center">Capstone Selesai & Dinilai</span>
                            </div>
                        </div>
                    @endif
                </div>

                @if($group->status === 'approved' && !$group->laporan_file)
                    <div class="mt-6 border-t border-slate-100 pt-4">
                        <a href="{{ route('mahasiswa.laporan') }}" class="w-full text-center block py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-sm transition-all shadow-md">
                            Unggah Laporan Akhir
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
