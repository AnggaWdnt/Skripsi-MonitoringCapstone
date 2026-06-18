@extends('layouts.app')

@section('title', 'Pengajuan Capstone')
@section('page-title', 'Form Pengajuan Judul Capstone')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3 mb-6">
            Ajukan Judul Capstone & Bukti Survei Kelompok
        </h3>

        <!-- Group Info Summary -->
        <div class="bg-slate-50 border border-slate-200/60 rounded-xl p-4 mb-6">
            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Informasi Kelompok Anda</span>
            <div class="mt-2 flex items-center justify-between">
                <span class="text-sm font-extrabold text-slate-850">{{ $group->group_name }}</span>
                <span class="text-xs text-slate-500">Dosen Pembimbing: <strong class="text-slate-800">{{ $group->pembimbing ? $group->pembimbing->name : 'Belum Ditentukan' }}</strong></span>
            </div>
        </div>

        <!-- Errors -->
        @if ($errors->any())
            <div class="bg-red-50/80 border border-red-200 text-red-800 text-sm rounded-xl p-4 mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('mahasiswa.pengajuan') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Capstone Title -->
            <div>
                <label for="title" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Judul Capstone Kelompok</label>
                <input type="text" name="title" id="title" value="{{ old('title', $group->title) }}" required 
                    placeholder="Masukkan judul proyek Capstone yang diusulkan..."
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all text-sm">
                <p class="text-[11px] text-slate-400 mt-1">Gunakan judul yang jelas, ringkas, dan merefleksikan solusi/produk yang dibangun.</p>
            </div>

            <!-- Description / Abstract -->
            <div>
                <label for="description" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Deskripsi Singkat / Abstrak</label>
                <textarea name="description" id="description" rows="5" required 
                    placeholder="Tuliskan latar belakang masalah, tujuan, dan metode/teknologi yang akan digunakan..."
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all text-sm">{{ old('description', $group->description) }}</textarea>
                <p class="text-[11px] text-slate-400 mt-1">Uraikan secara singkat permasalahan yang diselesaikan dan solusi produk yang diusulkan.</p>
            </div>



            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('mahasiswa.dashboard') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-bold rounded-xl transition-all">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold rounded-xl shadow-md transition-all">
                    Kirim Pengajuan Judul
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
