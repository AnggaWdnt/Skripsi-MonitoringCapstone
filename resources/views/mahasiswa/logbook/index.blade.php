@extends('layouts.app')

@section('title', 'Logbook Capstone')
@section('page-title', 'Pengisian Logbook Capstone')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Logbook Form -->
    <div>
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm sticky top-6">
            <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-5 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Tambah Logbook Harian
            </h3>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 text-xs rounded-xl p-3 mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('mahasiswa.logbook.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <!-- Date (Otomatis Hari Ini) -->
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Tanggal Kegiatan</label>
                    <div class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 flex items-center gap-2 select-none">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>{{ now()->locale('id')->translatedFormat('d F Y') }} (Hari Ini)</span>
                    </div>
                    <input type="hidden" name="date" value="{{ date('Y-m-d') }}">
                </div>

                <!-- Section / Bagian -->
                <div>
                    <label for="section" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Bagian Laporan / Bab</label>
                    <select name="section" id="section" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all text-sm">
                        <option value="" disabled selected>Pilih Bagian Laporan</option>
                        <option value="Pendahuluan / Latar Belakang">Pendahuluan / Latar Belakang</option>
                        <option value="Tinjauan Pustaka / Landasan Teori">Tinjauan Pustaka / Landasan Teori</option>
                        <option value="Metodologi / Perancangan Sistem">Metodologi / Perancangan Sistem</option>
                        <option value="Analisis dan Pembahasan">Analisis dan Pembahasan</option>
                        <option value="Kesimpulan dan Saran">Kesimpulan dan Saran</option>
                        <option value="Lainnya">Lainnya / Lampiran</option>
                    </select>
                </div>

                <!-- Activity Description -->
                <div>
                    <label for="activity" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Deskripsi Kegiatan / Pekerjaan</label>
                    <textarea name="activity" id="activity" rows="4" required 
                        placeholder="Deskripsikan pekerjaan yang Anda lakukan hari ini secara lengkap..."
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all text-sm"></textarea>
                </div>

                <!-- Documentation (File) -->
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Unggah File Progress (PDF/Word/ZIP)</label>
                    <div class="relative flex flex-col items-center justify-center p-4 border-2 border-dashed border-slate-300 rounded-xl bg-slate-50/50 hover:bg-slate-50 transition-colors cursor-pointer group">
                        <input type="file" name="documentation" id="documentation" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewFile(event)">
                        
                        <div id="upload-prompt" class="text-center">
                            <svg class="w-8 h-8 text-slate-400 group-hover:text-emerald-500 mx-auto mb-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span class="text-xs font-bold text-slate-600">Klik untuk upload berkas</span>
                            <span class="block text-[10px] text-slate-400 mt-0.5">PDF, Word, Excel, PPT, ZIP/RAR (Maks. 5MB)</span>
                        </div>
                        
                        <!-- File Preview -->
                        <div id="file-preview-container" class="hidden w-full relative text-center">
                            <div class="flex items-center justify-center gap-2 p-2.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl max-w-xs mx-auto">
                                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span id="file-name" class="text-xs font-bold truncate">Nama File</span>
                            </div>
                            <button type="button" class="absolute top-1 right-1 p-1 rounded-full bg-slate-900/60 hover:bg-slate-900 text-white transition-colors" onclick="removePreview(event)">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-sm transition-all shadow-md shadow-emerald-500/10">
                    Kirim Logbook
                </button>
            </form>
        </div>
    </div>

    <!-- Logbook History -->
    <div class="lg:col-span-2">
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
            <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-5">
                Riwayat Aktivitas Logbook
            </h3>

            @if($logbooks->isEmpty())
                <div class="text-center py-16 text-slate-500 text-sm">
                    <svg class="w-16 h-16 text-slate-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 00-2 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Belum ada logbook harian yang ditambahkan.
                </div>
            @else
                <div class="space-y-6">
                    @foreach($logbooks as $log)
                        <div class="p-5 border border-slate-100 rounded-2xl bg-slate-50/30 flex flex-col gap-4 relative overflow-hidden">
                            <!-- Left indicator stripe -->
                            <div class="absolute left-0 top-0 bottom-0 w-1.5 
                                @if($log->status === 'approved') bg-emerald-500
                                @elseif($log->status === 'rejected') bg-red-500
                                @else bg-amber-500 @endif">
                            </div>
                            
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pl-2">
                                <div class="flex flex-col gap-1.5">
                                    <div class="flex items-center gap-3">
                                        <span class="font-bold text-slate-800 text-sm">
                                            {{ $log->date->format('d M Y') }}
                                        </span>
                                        <span class="text-xs text-slate-400">
                                            ({{ $log->date->locale('id')->dayName }})
                                        </span>
                                    </div>
                                    @if($log->section)
                                        <div class="text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-100 rounded-lg px-2.5 py-1 self-start inline-block">
                                            Bagian: {{ $log->section }}
                                        </div>
                                    @endif
                                </div>
                                
                                <div>
                                    @if($log->status === 'approved')
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-50 border border-emerald-200 text-emerald-700">Disetujui</span>
                                    @elseif($log->status === 'rejected')
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-red-50 border border-red-200 text-red-700">Ditolak</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-50 border border-amber-200 text-amber-700 font-medium">Pending</span>
                                    @endif
                                </div>
                            </div>

                            <div class="text-sm text-slate-700 leading-relaxed pl-2 whitespace-pre-line">
                                {{ $log->activity }}
                            </div>

                            <!-- Documentation Display -->
                            @if($log->documentation)
                                <div class="pl-2">
                                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Berkas Pendukung / File Progress</span>
                                    <a href="{{ asset($log->documentation) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200/80 hover:border-emerald-500 rounded-xl text-xs font-bold text-slate-700 hover:text-emerald-700 transition-colors shadow-sm">
                                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        Unduh / Lihat Berkas ({{ strtoupper(pathinfo($log->documentation, PATHINFO_EXTENSION)) }})
                                    </a>
                                </div>
                            @endif

                            <!-- Lecturer Comment -->
                            @if($log->dosen_note)
                                <div class="bg-slate-100 border border-slate-200/60 rounded-xl p-3.5 pl-4 text-xs text-slate-600 relative ml-2">
                                    <span class="block font-bold text-slate-700 mb-1">Catatan Pembimbing:</span>
                                    "{{ $log->dosen_note }}"
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function previewFile(event) {
        const fileInput = event.target;
        if (fileInput.files.length > 0) {
            const fileName = fileInput.files[0].name;
            document.getElementById('file-name').innerText = fileName;
            document.getElementById('upload-prompt').classList.add('hidden');
            document.getElementById('file-preview-container').classList.remove('hidden');
        }
    }

    function removePreview(event) {
        event.preventDefault();
        const fileInput = document.getElementById('documentation');
        fileInput.value = '';
        
        document.getElementById('upload-prompt').classList.remove('hidden');
        document.getElementById('file-preview-container').classList.add('hidden');
    }
</script>
@endsection
