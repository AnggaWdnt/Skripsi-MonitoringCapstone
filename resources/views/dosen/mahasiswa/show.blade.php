@extends('layouts.app')

@section('title', 'Monitoring - ' . $student->name)
@section('page-title', 'Detail Monitoring Mahasiswa')

@section('content')
<div class="space-y-6">
    <!-- Student Header Summary -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center font-bold text-emerald-800 text-lg uppercase shadow-sm">
                {{ substr($student->name, 0, 2) }}
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-800">{{ $student->name }}</h3>
                <p class="text-sm text-slate-500 mt-0.5">NIM: {{ $student->nim }} &bull; Angkatan: {{ $student->angkatan }} &bull; Prodi: {{ $student->prodi }}</p>
            </div>
        </div>
        <div>
            @if($student->group)
                <span class="px-4 py-2 rounded-xl text-xs font-semibold bg-emerald-50 border border-emerald-200 text-emerald-700">
                    {{ $student->group->group_name }}
                </span>
            @endif
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Student PKL & Grading Details -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Company Info Card -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
                <h4 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2 mb-4">Informasi Kelompok Capstone</h4>
                <div class="space-y-3.5 text-sm">
                    @if($student->group)
                        <div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Nama Kelompok</span>
                            <span class="font-bold text-slate-800 block mt-0.5">{{ $student->group->group_name }}</span>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Judul Capstone</span>
                            <span class="font-bold text-slate-800 block mt-0.5 leading-snug">{{ $student->group->title ?? 'Belum Diajukan' }}</span>
                        </div>
                        @if($student->group->description)
                            <div>
                                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Deskripsi / Abstrak</span>
                                <p class="text-xs text-slate-600 block mt-1 leading-relaxed">{{ $student->group->description }}</p>
                            </div>
                        @endif
                        <div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Berkas Laporan Akhir</span>
                            @if($student->group->laporan_file)
                                <a href="{{ asset($student->group->laporan_file) }}" target="_blank" class="mt-2 inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold transition-all shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Lihat Laporan Akhir (PDF)
                                </a>
                            @else
                                <span class="text-slate-400 text-xs mt-1 block italic">Laporan akhir belum diunggah</span>
                            @endif
                        </div>
                    @else
                        <div class="p-3 bg-red-50 border border-red-100 rounded-xl text-xs text-red-800">
                            Mahasiswa belum terdaftar dalam kelompok Capstone mana pun. Hubungi admin untuk menetapkan kelompok.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Progress Per Bagian/Bab Card -->
            @if($student->group && $student->group->status === 'approved')
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
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
                    <h4 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3 mb-5 flex items-center justify-between">
                        <span>Progress Kelompok Per Bab</span>
                        <span class="text-[10px] text-slate-400 font-normal">Sesuai Logbook</span>
                    </h4>
                    
                    <div class="space-y-3">
                        @foreach($sections as $sec)
                            @php
                                $secLogs = $groupLogbooks->filter(fn($l) => $l->section === $sec);
                                $hasApproved = $secLogs->contains('status', 'approved');
                                $hasPending = $secLogs->contains('status', 'pending');
                                $hasRejected = $secLogs->contains('status', 'rejected');
                                
                                // Find latest documentation
                                $latestDoc = $secLogs->whereNotNull('documentation')->sortByDesc('date')->first()?->documentation;
                            @endphp
                            
                            <div class="flex items-center justify-between p-3.5 border rounded-xl transition-all
                                @if($hasApproved) bg-emerald-50/10 border-emerald-100/80 hover:bg-emerald-50/20
                                @elseif($hasPending) bg-amber-50/10 border-amber-100/80 hover:bg-amber-50/20
                                @elseif($hasRejected) bg-red-50/10 border-red-100/80 hover:bg-red-50/20
                                @else bg-slate-50/30 border-slate-200/50 hover:bg-slate-50/60 @endif">
                                
                                <div class="flex items-center gap-2.5">
                                    <!-- Icon Status Left -->
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0
                                        @if($hasApproved) bg-emerald-100 text-emerald-700
                                        @elseif($hasPending) bg-amber-100 text-amber-700
                                        @elseif($hasRejected) bg-red-100 text-red-700
                                        @else bg-slate-100 text-slate-400 @endif">
                                        @if($hasApproved)
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        @elseif($hasPending)
                                            <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        @elseif($hasRejected)
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                        @else
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        @endif
                                    </div>
                                    
                                    <div class="space-y-0.5">
                                        <span class="block text-xs font-bold text-slate-800 leading-tight">{{ $sec }}</span>
                                        <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5">
                                            <span class="text-[10px] text-slate-400">Total: {{ $secLogs->count() }} Log</span>
                                            @if($latestDoc)
                                                <span class="text-[10px] text-slate-300">•</span>
                                                <a href="{{ asset($latestDoc) }}" target="_blank" class="text-[10px] font-bold text-emerald-600 hover:text-emerald-700 hover:underline inline-flex items-center gap-0.5">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                    Unduh Berkas
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="shrink-0 pl-1.5">
                                    @if($hasApproved)
                                        <span class="px-2 py-0.5 rounded-md text-[9px] font-extrabold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200/50 whitespace-nowrap">
                                            Selesai
                                        </span>
                                    @elseif($hasPending)
                                        <span class="px-2 py-0.5 rounded-md text-[9px] font-extrabold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200/50 whitespace-nowrap">
                                            Review
                                        </span>
                                    @elseif($hasRejected)
                                        <span class="px-2 py-0.5 rounded-md text-[9px] font-extrabold uppercase tracking-wider bg-red-50 text-red-700 border border-red-200/50 whitespace-nowrap">
                                            Revisi
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-md text-[9px] font-extrabold uppercase tracking-wider bg-slate-100/80 text-slate-400 border border-slate-200/40 whitespace-nowrap">
                                            Belum Mulai
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Grading / Evaluation Card -->
            @if($student->group)
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
                    <h4 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2 mb-4">Input Nilai Kelompok Capstone</h4>
                    
                    @if($student->group->nilai_capstone !== null)
                        <div class="p-4 rounded-xl border border-emerald-100 bg-emerald-50/10 mb-4 flex items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Nilai Terinput</span>
                                <span class="text-[10px] text-slate-400 block mt-0.5">(Satu kelompok menerima nilai yang sama)</span>
                            </div>
                            <span class="text-3xl font-extrabold text-emerald-600">{{ $student->group->nilai_capstone }}</span>
                        </div>
                    @endif

                    <form action="{{ route('dosen.mahasiswa.nilai', $student->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="nilai_pkl" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Nilai Akhir Kelompok (Skala 100)</label>
                            <input type="number" name="nilai_pkl" id="nilai_pkl" min="0" max="100" value="{{ old('nilai_pkl', $student->group->nilai_capstone) }}" required 
                                placeholder="Contoh: 85"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all text-sm">
                        </div>

                        <div>
                            <label for="catatan" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Catatan / Ulasan Kelompok</label>
                            <textarea name="catatan" id="catatan" rows="4" required 
                                placeholder="Masukkan catatan hasil sidang, evaluasi kelompok, atau rekomendasi..."
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all text-sm">{{ old('catatan', $student->group->catatan) }}</textarea>
                        </div>

                        <button type="submit" class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-sm transition-all shadow-md">
                            {{ $student->group->nilai_capstone !== null ? 'Perbarui Evaluasi' : 'Simpan Evaluasi Kelompok' }}
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <!-- Logbooks History List -->
        <div class="lg:col-span-2">
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
                <!-- Tabs Navigation -->
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-5">
                    <div class="flex gap-4">
                        <button onclick="switchTab('individual')" id="tab-individual-btn" class="text-sm font-bold border-b-2 border-emerald-600 text-emerald-600 pb-2.5 transition-all focus:outline-none">
                            Logbook {{ $student->name }} ({{ $logbooks->count() }})
                        </button>
                        @if($student->group)
                            <button onclick="switchTab('group')" id="tab-group-btn" class="text-sm font-medium text-slate-400 hover:text-slate-600 pb-2.5 transition-all focus:outline-none">
                                Timeline Kelompok ({{ $groupLogbooks->count() }})
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Tab: Individual Logbook -->
                <div id="tab-individual-content" class="space-y-5">
                    @if($logbooks->isEmpty())
                        <div class="text-center py-16 text-slate-400 text-sm">
                            Belum ada data logbook kegiatan dari mahasiswa ini.
                        </div>
                    @else
                        <div class="space-y-5">
                            @foreach($logbooks as $log)
                                <div class="p-4 border border-slate-100 bg-slate-50/50 rounded-2xl flex flex-col gap-4 relative overflow-hidden">
                                    <!-- Status Line -->
                                    <div class="absolute left-0 top-0 bottom-0 w-1.5 
                                        @if($log->status === 'approved') bg-emerald-500
                                        @elseif($log->status === 'rejected') bg-red-500
                                        @else bg-amber-500 @endif">
                                    </div>

                                    <div class="flex items-center justify-between gap-3 pl-2">
                                        <div class="flex flex-col gap-1">
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-slate-800 text-sm">{{ $log->date->format('d M Y') }}</span>
                                                <span class="text-xs text-slate-400">({{ $log->date->locale('id')->dayName }})</span>
                                            </div>
                                            @if($log->section)
                                                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-100 rounded-lg px-2 py-0.5 self-start">
                                                    Bagian: {{ $log->section }}
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <div>
                                            @if($log->status === 'approved')
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">Disetujui</span>
                                            @elseif($log->status === 'rejected')
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-red-50 text-red-700 border border-red-200">Ditolak</span>
                                            @else
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200">Pending</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Activity -->
                                    <div class="text-sm text-slate-700 leading-relaxed pl-2 whitespace-pre-line">
                                        {{ $log->activity }}
                                    </div>

                                    <!-- File Display -->
                                    @if($log->documentation)
                                        <div class="pl-2">
                                            <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Berkas Pendukung / File Progress</span>
                                            <a href="{{ asset($log->documentation) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-slate-200/80 hover:border-emerald-500 rounded-xl text-xs font-bold text-slate-700 hover:text-emerald-700 transition-colors shadow-sm">
                                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                Unduh / Lihat Berkas ({{ strtoupper(pathinfo($log->documentation, PATHINFO_EXTENSION)) }})
                                            </a>
                                        </div>
                                    @endif

                                    <!-- Dosen Review Form / Display Notes -->
                                    <div class="pl-2 pt-3 border-t border-slate-100 mt-1">
                                        @if($log->status === 'pending')
                                            <form action="{{ route('dosen.logbook.status', $log->id) }}" method="POST" class="space-y-3">
                                                @csrf
                                                <div class="flex gap-2">
                                                    <input type="text" name="dosen_note" placeholder="Tulis catatan revisi/komentar di sini (opsional)..." 
                                                        class="flex-1 px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                                                    
                                                    <button type="submit" name="status" value="approved" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold transition-all shadow-sm shrink-0">
                                                        Setujui
                                                    </button>
                                                    <button type="submit" name="status" value="rejected" class="px-3 py-1.5 bg-red-600 hover:bg-red-500 text-white rounded-lg text-xs font-bold transition-all shadow-sm shrink-0">
                                                        Tolak
                                                    </button>
                                                </div>
                                            </form>
                                        @else
                                            @if($log->dosen_note)
                                                <div class="bg-slate-100 rounded-xl p-3 text-xs text-slate-600 leading-relaxed">
                                                    <span class="font-bold text-slate-700 block mb-0.5">Catatan Pembimbing:</span>
                                                    "{{ $log->dosen_note }}"
                                                </div>
                                            @else
                                                <span class="text-[10px] text-slate-400 italic font-medium">
                                                    @if($log->status === 'approved')
                                                        Disetujui tanpa catatan khusus.
                                                    @else
                                                        Ditolak tanpa catatan khusus.
                                                    @endif
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Tab: Group Logbook Timeline -->
                @if($student->group)
                    <div id="tab-group-content" class="hidden space-y-5">
                        @if($groupLogbooks->isEmpty())
                            <div class="text-center py-16 text-slate-400 text-sm">
                                Belum ada data logbook kegiatan dari seluruh anggota kelompok ini.
                            </div>
                        @else
                            <div class="space-y-5">
                                @foreach($groupLogbooks as $log)
                                    <div class="p-4 border border-slate-100 bg-slate-50/50 rounded-2xl flex flex-col gap-4 relative overflow-hidden">
                                        <!-- Status Line -->
                                        <div class="absolute left-0 top-0 bottom-0 w-1.5 
                                            @if($log->status === 'approved') bg-emerald-500
                                            @elseif($log->status === 'rejected') bg-red-500
                                            @else bg-amber-500 @endif">
                                        </div>

                                        <div class="flex items-center justify-between gap-3 pl-2">
                                            <div class="flex items-center gap-3">
                                                <!-- Student Badge / Initial -->
                                                <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-extrabold" title="{{ $log->mahasiswa->name }}">
                                                    {{ substr($log->mahasiswa->name, 0, 2) }}
                                                </div>
                                                <div>
                                                    <div class="font-bold text-slate-800 text-xs flex items-center gap-1.5">
                                                        <span>{{ $log->mahasiswa->name }}</span>
                                                        <span class="text-[10px] text-slate-400 font-normal">({{ $log->mahasiswa->nim }})</span>
                                                    </div>
                                                    <div class="text-[10px] text-slate-400 mt-0.5">
                                                        {{ $log->date->format('d M Y') }} &bull; {{ $log->date->locale('id')->dayName }}
                                                    </div>
                                                    @if($log->section)
                                                        <span class="mt-1 block text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-100 rounded-lg px-2 py-0.5 self-start">
                                                            Bagian: {{ $log->section }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div>
                                                @if($log->status === 'approved')
                                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">Disetujui</span>
                                                @elseif($log->status === 'rejected')
                                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-red-50 text-red-700 border border-red-200">Ditolak</span>
                                                @else
                                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200">Pending</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Activity -->
                                        <div class="text-sm text-slate-700 leading-relaxed pl-2 whitespace-pre-line">
                                            {{ $log->activity }}
                                        </div>

                                        <!-- File Display -->
                                        @if($log->documentation)
                                            <div class="pl-2">
                                                <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Berkas Pendukung / File Progress</span>
                                                <a href="{{ asset($log->documentation) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-slate-200/80 hover:border-emerald-500 rounded-xl text-xs font-bold text-slate-700 hover:text-emerald-700 transition-colors shadow-sm">
                                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                    Unduh / Lihat Berkas ({{ strtoupper(pathinfo($log->documentation, PATHINFO_EXTENSION)) }})
                                                </a>
                                            </div>
                                        @endif

                                        <!-- Dosen Review Form / Display Notes -->
                                        <div class="pl-2 pt-3 border-t border-slate-100 mt-1">
                                            @if($log->status === 'pending')
                                                <form action="{{ route('dosen.logbook.status', $log->id) }}" method="POST" class="space-y-3">
                                                    @csrf
                                                    <div class="flex gap-2">
                                                        <input type="text" name="dosen_note" placeholder="Tulis catatan revisi/komentar di sini (opsional)..." 
                                                            class="flex-1 px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                                                        
                                                        <button type="submit" name="status" value="approved" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold transition-all shadow-sm shrink-0">
                                                            Setujui
                                                        </button>
                                                        <button type="submit" name="status" value="rejected" class="px-3 py-1.5 bg-red-600 hover:bg-red-500 text-white rounded-lg text-xs font-bold transition-all shadow-sm shrink-0">
                                                            Tolak
                                                        </button>
                                                    </div>
                                                </form>
                                            @else
                                                @if($log->dosen_note)
                                                    <div class="bg-slate-100 rounded-xl p-3 text-xs text-slate-600 leading-relaxed">
                                                        <span class="font-bold text-slate-700 block mb-0.5">Catatan Pembimbing:</span>
                                                        "{{ $log->dosen_note }}"
                                                    </div>
                                                @else
                                                    <span class="text-[10px] text-slate-400 italic font-medium">
                                                        @if($log->status === 'approved')
                                                            Disetujui tanpa catatan khusus.
                                                        @else
                                                            Ditolak tanpa catatan khusus.
                                                        @endif
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function switchTab(tabName) {
        const indBtn = document.getElementById('tab-individual-btn');
        const grpBtn = document.getElementById('tab-group-btn');
        const indContent = document.getElementById('tab-individual-content');
        const grpContent = document.getElementById('tab-group-content');

        if (tabName === 'group') {
            indContent.classList.add('hidden');
            grpContent.classList.remove('hidden');

            grpBtn.className = "text-sm font-bold border-b-2 border-emerald-600 text-emerald-600 pb-2.5 transition-all focus:outline-none";
            indBtn.className = "text-sm font-medium text-slate-400 hover:text-slate-600 pb-2.5 transition-all focus:outline-none";
        } else {
            grpContent.classList.add('hidden');
            indContent.classList.remove('hidden');

            indBtn.className = "text-sm font-bold border-b-2 border-emerald-600 text-emerald-600 pb-2.5 transition-all focus:outline-none";
            grpBtn.className = "text-sm font-medium text-slate-400 hover:text-slate-600 pb-2.5 transition-all focus:outline-none";
        }
    }
</script>
@endsection
