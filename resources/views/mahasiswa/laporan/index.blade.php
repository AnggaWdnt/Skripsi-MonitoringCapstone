@extends('layouts.app')

@section('title', 'Laporan Akhir Capstone')
@section('page-title', 'Laporan & Nilai Akhir')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- PDF Report Upload Card -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
        <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-5">
            Unggah Laporan Akhir Capstone Kelompok
        </h3>

        <!-- Group Alert banner -->
        <div class="bg-slate-50 border border-slate-200/60 rounded-xl p-4 mb-6">
            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kelompok Anda</span>
            <span class="text-sm font-bold text-slate-800 mt-1 block">{{ $group->group_name }}</span>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 text-sm rounded-xl p-4 mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($group->laporan_file)
            <div class="p-5 border border-emerald-100 bg-emerald-50/20 rounded-2xl mb-6">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-lg bg-emerald-100 border border-emerald-200 flex items-center justify-center text-emerald-800 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div class="overflow-hidden">
                        <span class="block text-sm font-bold text-slate-800">Laporan Akhir Berhasil Diunggah</span>
                        <span class="block text-xs text-slate-400 mt-1 truncate">{{ basename($group->laporan_file) }}</span>
                        
                        <div class="flex items-center gap-3 mt-3">
                            <a href="{{ asset($group->laporan_file) }}" target="_blank" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-semibold inline-flex items-center gap-1 shadow-sm transition-all">
                                Lihat PDF
                            </a>
                            <button onclick="document.getElementById('upload-form-container').classList.toggle('hidden')" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs font-semibold transition-all">
                                Perbarui File
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="p-4 border border-slate-100 bg-slate-50/50 rounded-2xl mb-6 text-center text-sm text-slate-500">
                Kelompok Anda belum mengunggah laporan akhir Capstone. Harap unggah laporan dalam format PDF yang disetujui dosen pembimbing.
            </div>
        @endif

        <div id="upload-form-container" class="{{ $group->laporan_file ? 'hidden' : '' }} space-y-4">
            <form action="{{ route('mahasiswa.laporan.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="space-y-4">
                    <div>
                        <label for="laporan_file" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Pilih File Laporan Akhir (PDF)</label>
                        <div class="relative flex flex-col items-center justify-center p-6 border-2 border-dashed border-slate-300 rounded-2xl bg-slate-50 hover:bg-slate-100/50 cursor-pointer group transition-colors">
                            <input type="file" name="laporan_file" id="laporan_file" accept="application/pdf" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="showPdfName(event)">
                            
                            <svg class="w-10 h-10 text-slate-400 group-hover:text-emerald-500 mb-3 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            
                            <span id="pdf-file-label" class="text-xs font-bold text-slate-600 text-center">Tarik file di sini atau klik untuk memilih file</span>
                            <span class="block text-[10px] text-slate-400 mt-1">Hanya format PDF (Maks. 5MB)</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-sm transition-all shadow-md shadow-emerald-500/10">
                        Unggah Laporan Akhir
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Final Grade Card -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
        <div>
            <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-5">
                Nilai Evaluasi & Catatan Akhir Kelompok
            </h3>

            @if($group->nilai_capstone === null)
                <div class="text-center py-16 text-slate-400 text-sm">
                    <svg class="w-16 h-16 text-slate-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Dosen pembimbing belum menginputkan nilai evaluasi akhir kelompok. Nilai akan tampil setelah penilaian sidang/demo produk selesai.
                </div>
            @else
                <div class="space-y-6">
                    <div class="p-6 rounded-2xl border border-emerald-100 bg-emerald-50/10 flex items-center justify-between">
                        <div>
                            <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Nilai Capstone Kelompok</span>
                            <span class="block text-sm font-semibold text-slate-500 mt-1">Dosen Pembimbing Utama</span>
                        </div>
                        <div class="text-center">
                            <span class="text-5xl font-extrabold text-emerald-600">{{ $group->nilai_capstone }}</span>
                            <span class="block text-[10px] text-slate-400 font-semibold uppercase mt-1">Skala 100</span>
                        </div>
                    </div>

                    @if($group->catatan)
                        <div class="space-y-2">
                            <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Catatan & Ulasan Evaluasi</span>
                            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200/60 text-sm text-slate-700 leading-relaxed italic">
                                "{{ $group->catatan }}"
                            </div>
                        </div>
                    @endif

                    <div class="text-xs text-slate-400 flex items-center justify-between pt-4 border-t border-slate-100">
                        <span>Penilai: {{ $group->pembimbing ? $group->pembimbing->name : 'Dosen Pembimbing' }}</span>
                        <span>Tanggal Update: {{ $group->updated_at->format('d M Y') }}</span>
                    </div>
                </div>
            @endif
        </div>
        
        <div class="mt-8 border-t border-slate-100 pt-4 flex items-center justify-between text-xs text-slate-400">
            <span>Status Kelompok: {{ ucfirst($group->status) }}</span>
            <span>Program Studi: {{ $user->prodi }}</span>
        </div>
    </div>
</div>

<script>
    function showPdfName(event) {
        const fileInput = event.target;
        const label = document.getElementById('pdf-file-label');
        if (fileInput.files.length > 0) {
            label.textContent = "File Terpilih: " + fileInput.files[0].name;
            label.classList.add('text-emerald-600');
        } else {
            label.textContent = "Tarik file di sini atau klik untuk memilih file";
            label.classList.remove('text-emerald-600');
        }
    }
</script>
@endsection
