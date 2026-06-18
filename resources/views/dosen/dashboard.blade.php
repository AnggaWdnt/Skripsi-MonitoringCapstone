@extends('layouts.app')

@section('title', 'Dashboard Dosen Pembimbing')
@section('page-title', 'Dashboard Pembimbing Capstone')

@section('content')
<div class="space-y-6">
    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm flex items-center gap-5">
            <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
                <span class="block text-2xl font-extrabold text-slate-800">{{ $totalBimbingan }}</span>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kelompok Bimbingan</span>
            </div>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm flex items-center gap-5">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <span class="block text-2xl font-extrabold text-slate-800">{{ $aktifCount }}</span>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kelompok Aktif</span>
            </div>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm flex items-center gap-5">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 00-2 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <span class="block text-2xl font-extrabold text-slate-800">{{ $selesaiCount }}</span>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Selesai Dinilai</span>
            </div>
        </div>
    </div>

    <!-- Quick Review Pending Logbooks -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
        <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-5 flex items-center gap-2">
            <span class="flex h-2.5 w-2.5 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
            </span>
            Logbook Menunggu Persetujuan Anda ({{ $pendingLogbooks->count() }})
        </h3>
        
        @if($pendingLogbooks->isEmpty())
            <div class="text-center py-8 text-slate-400 text-sm">
                Tidak ada logbook bimbingan yang membutuhkan persetujuan saat ini.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[350px] overflow-y-auto pr-2">
                @foreach($pendingLogbooks as $log)
                    <div class="p-4 border border-slate-100 bg-slate-50/50 rounded-xl flex flex-col justify-between gap-3 relative">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <span class="text-xs font-bold text-slate-800 block">{{ $log->mahasiswa->name }}</span>
                                <span class="text-[10px] text-slate-400 font-semibold">{{ $log->date->format('d M Y') }}</span>
                            </div>
                            <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
                                {{ $log->activity }}
                            </p>
                        </div>
                        <div class="flex items-center justify-between border-t border-slate-100 pt-3 mt-1">
                            @if($log->documentation)
                                <a href="{{ asset($log->documentation) }}" target="_blank" class="text-[10px] font-bold text-emerald-600 hover:underline inline-flex items-center gap-0.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Lihat Lampiran
                                </a>
                            @else
                                <span class="text-[10px] text-slate-400">Tanpa Lampiran</span>
                            @endif
                            <a href="{{ route('dosen.mahasiswa.show', $log->mahasiswa->id) }}" class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-[10px] font-bold shadow-sm transition-all">
                                Review Logbook
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Monitored Groups Table -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
        <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-5">
            Daftar Kelompok Bimbingan Capstone Anda
        </h3>

        @if($groups->isEmpty())
            <div class="text-center py-12 text-slate-400 text-sm">
                Belum ada kelompok bimbingan Capstone yang ditugaskan kepada Anda.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 text-xs font-semibold uppercase tracking-wider">
                            <th class="py-3 px-4">Nama Kelompok</th>
                            <th class="py-3 px-4">Anggota Kelompok</th>
                            <th class="py-3 px-4">Judul Capstone</th>
                            <th class="py-3 px-4">Laporan Akhir</th>
                            <th class="py-3 px-4">Nilai Kelompok</th>
                            <th class="py-3 px-4">Status Proyek</th>
                            <th class="py-3 px-4 text-right">Aksi Persetujuan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($groups as $group)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-4 font-bold text-slate-800">{{ $group->group_name }}</td>
                                <td class="py-4 px-4 text-slate-600">
                                    <div class="space-y-2">
                                        @foreach($group->members as $member)
                                            <div class="flex items-center justify-between gap-4 py-0.5 border-b border-slate-50 last:border-0">
                                                <span class="text-xs font-medium">{{ $member->name }} <span class="text-slate-400">({{ $member->nim }})</span></span>
                                                <a href="{{ route('dosen.mahasiswa.show', $member->id) }}" class="px-2 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded text-[10px] font-bold border border-emerald-100 transition-all">
                                                    Logbook
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-slate-700 max-w-xs">
                                    @if($group->title)
                                        <div class="font-bold text-xs leading-normal truncate" title="{{ $group->title }}">{{ $group->title }}</div>
                                        <div class="text-[10px] text-slate-400 mt-1">
                                            <a href="{{ asset($group->survey_file) }}" target="_blank" class="text-emerald-600 font-bold hover:underline">
                                                Unduh Proposal Survei
                                            </a>
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic text-xs">Belum mengajukan judul</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    @if($group->laporan_file)
                                        <a href="{{ asset($group->laporan_file) }}" target="_blank" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold inline-flex items-center gap-1 shadow-sm transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                            Unduh PDF
                                        </a>
                                    @else
                                        <span class="text-slate-400 italic text-xs">Belum diunggah</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    @if($group->nilai_capstone !== null)
                                        <span class="text-lg font-extrabold text-emerald-600">{{ $group->nilai_capstone }}</span>
                                    @else
                                        <span class="text-slate-400 italic text-xs">Belum dinilai</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    @if($group->status === 'belum_mengajukan')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">Belum Pengajuan</span>
                                    @elseif($group->status === 'pending')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Menunggu Review</span>
                                    @elseif($group->status === 'approved')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Disetujui</span>
                                    @elseif($group->status === 'rejected')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-700 border border-red-200">Ditolak</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-right">
                                    @if($group->status === 'pending')
                                        <div class="inline-flex gap-2 justify-end">
                                            <form action="{{ route('dosen.kelompok.approve', $group->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-[10px] font-bold shadow-sm transition-all">
                                                    Setujui
                                                </button>
                                            </form>
                                            <form action="{{ route('dosen.kelompok.reject', $group->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1.5 bg-red-650 hover:bg-red-500 text-white rounded-lg text-[10px] font-bold shadow-sm transition-all">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 italic font-semibold">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
