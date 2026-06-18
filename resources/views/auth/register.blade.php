@extends('layouts.auth')

@section('title', 'Daftar Akun')

@section('content')
<div class="bg-slate-900/40 p-8 rounded-2xl shadow-2xl">
    <!-- Header -->
    <div class="text-center mb-6">
        <h2 class="text-xl font-extrabold text-white">Daftar Akun Baru</h2>
        <p class="text-xs text-slate-400 mt-1.5">Buat akun mahasiswa untuk bergabung</p>
    </div>

    <!-- Errors -->
    @if ($errors->any())
        <div class="bg-red-500/10 border border-red-500/20 text-red-400 text-xs rounded-xl p-4 mb-5">
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <form action="{{ route('register') }}" method="POST" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Nama lengkap Anda" 
                class="w-full px-4 py-2.5 bg-slate-900/60 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all text-sm">
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="nama@kampus.com" 
                class="w-full px-4 py-2.5 bg-slate-900/60 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all text-sm">
        </div>

        <!-- NIM -->
        <div>
            <label for="nim" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">NIM (Nomor Induk Mahasiswa)</label>
            <input type="text" name="nim" id="nim" value="{{ old('nim') }}" required placeholder="Contoh: 20220140081" 
                class="w-full px-4 py-2.5 bg-slate-900/60 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all text-sm">
        </div>

        <!-- Prodi -->
        <div>
            <label for="prodi" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Program Studi</label>
            <select name="prodi" id="prodi" required
                class="w-full px-4 py-2.5 bg-slate-900/60 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all text-sm appearance-none">
                <option value="" disabled selected class="text-slate-500 bg-slate-950">Pilih Program Studi</option>
                @foreach(\App\Models\Prodi::orderBy('name')->get() as $prodi)
                    <option value="{{ $prodi->name }}" class="bg-slate-950">{{ $prodi->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Angkatan -->
        <div>
            <label for="angkatan" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Angkatan</label>
            <input type="text" name="angkatan" id="angkatan" value="{{ old('angkatan') }}" required placeholder="Contoh: 2022" 
                class="w-full px-4 py-2.5 bg-slate-900/60 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all text-sm">
        </div>

        <!-- Password -->
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label for="password" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Password</label>
                <input type="password" name="password" id="password" required placeholder="Minimal 6 karakter" 
                    class="w-full px-4 py-2.5 bg-slate-900/60 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all text-sm">
            </div>
            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Konfirmasi</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Ulangi" 
                    class="w-full px-4 py-2.5 bg-slate-900/60 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all text-sm">
            </div>
        </div>

        <button type="submit" class="w-full py-3 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold rounded-xl transition-all text-sm shadow-md mt-2">
            Daftar Sekarang
        </button>
    </form>

    <div class="mt-6 text-center text-xs text-slate-400 pt-4">
        Sudah punya akun? <a href="{{ route('login') }}" class="font-bold text-emerald-400 hover:text-emerald-300 hover:underline transition-colors">Masuk Di Sini</a>
    </div>
</div>
@endsection
