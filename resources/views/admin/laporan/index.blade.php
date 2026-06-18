@extends('layouts.app')

@section('title', 'Rekap & Laporan Capstone')
@section('page-title', 'Rekapitulasi Pelaksanaan Capstone')

@section('content')
<div class="space-y-6">
    <!-- Metrics Row -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
            <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Kelompok</span>
            <span class="block text-2xl font-extrabold text-slate-800 mt-2">{{ $total }}</span>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
            <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Kelompok Selesai</span>
            <span class="block text-2xl font-extrabold text-blue-600 mt-2">{{ $completed }}</span>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
            <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Tingkat Penyelesaian</span>
            <span class="block text-2xl font-extrabold text-emerald-600 mt-2">{{ $rate }}%</span>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
            <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Rata-Rata Nilai</span>
            <span class="block text-2xl font-extrabold text-slate-800 mt-2">{{ $avgGrade }}</span>
        </div>
    </div>

    <!-- Details Table -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm printable-area">
        <div class="flex items-center justify-between border-b border-slate-100 pb-5 mb-6">
            <div>
                <h3 class="text-base font-bold text-slate-800">Laporan Rekapitulasi Capstone</h3>
                <p class="text-xs text-slate-400 mt-1">Rekap seluruh data pelaksanaan Capstone Project per kelompok beserta dosen pembimbing dan nilai.</p>
            </div>
            
            <button onclick="window.print()" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-all shadow-md flex items-center gap-1.5 non-printable">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak Rekapitulasi
            </button>
        </div>

        @if($groups->isEmpty())
            <div class="text-center py-16 text-slate-400 text-sm">
                Belum ada data kelompok Capstone.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 text-xs font-semibold uppercase tracking-wider">
                            <th class="py-3 px-4">Nama Kelompok</th>
                            <th class="py-3 px-4">Anggota Tim</th>
                            <th class="py-3 px-4">Judul Capstone Proyek</th>
                            <th class="py-3 px-4">Dosen Pembimbing</th>
                            <th class="py-3 px-4">Nilai Akhir</th>
                            <th class="py-3 px-4">Berkas Dokumen</th>
                            <th class="py-3 px-4">Status Proyek</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($groups as $group)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3.5 px-4 font-bold text-slate-800">{{ $group->group_name }}</td>
                                <td class="py-3.5 px-4 text-xs text-slate-600">
                                    <ul class="list-disc list-inside space-y-0.5">
                                        @foreach($group->members as $member)
                                            <li>{{ $member->name }} <span class="text-slate-400">({{ $member->nim }})</span></li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="py-3.5 px-4 text-slate-700 max-w-xs">
                                    <span class="font-semibold text-xs leading-normal block">{{ $group->title ?? '-' }}</span>
                                </td>
                                <td class="py-3.5 px-4 text-slate-650">{{ $group->pembimbing ? $group->pembimbing->name : 'Belum Di-plot' }}</td>
                                <td class="py-3.5 px-4 font-bold text-slate-800">
                                    {{ $group->nilai_capstone !== null ? $group->nilai_capstone : '-' }}
                                </td>
                                <td class="py-3.5 px-4 text-xs space-y-1">
                                    <div>
                                        <span class="text-[9px] text-slate-400 uppercase tracking-wider block font-semibold">Bukti Survei:</span>
                                        @if($group->survey_file)
                                            <a href="{{ asset($group->survey_file) }}" target="_blank" class="font-bold text-slate-800 hover:text-emerald-600 transition-colors inline-flex items-center gap-0.5">
                                                Lihat Proposal (PDF)
                                            </a>
                                        @else
                                            <span class="text-slate-400 italic">Belum Ada</span>
                                        @endif
                                    </div>
                                    <div class="pt-1 border-t border-slate-100 mt-1">
                                        <span class="text-[9px] text-slate-400 uppercase tracking-wider block font-semibold">Laporan Akhir:</span>
                                        @if($group->laporan_file)
                                            <a href="{{ asset($group->laporan_file) }}" target="_blank" class="font-bold text-emerald-600 hover:text-emerald-500 transition-colors inline-flex items-center gap-0.5">
                                                Lihat Laporan (PDF)
                                            </a>
                                        @else
                                            <span class="text-slate-400 italic">Belum Ada</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($group->status === 'belum_mengajukan')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">Belum Pengajuan</span>
                                    @elseif($group->status === 'pending')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Review</span>
                                    @elseif($group->status === 'approved')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Disetujui</span>
                                    @elseif($group->status === 'rejected')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-700 border border-red-200">Ditolak</span>
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

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .printable-area, .printable-area * {
            visibility: visible;
        }
        .printable-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .non-printable {
            display: none !important;
        }
    }
</style>
@endsection
