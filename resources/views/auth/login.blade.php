@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')
    <div class="bg-slate-900/40 p-8 rounded-2xl shadow-2xl">
        <!-- Header -->
        <div class="text-center mb-8">
            <div
                class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-emerald-500 text-slate-950 font-extrabold text-xl mb-4">
                M
            </div>
            <h2 class="text-xl font-extrabold text-white">Monitoring Capstone</h2>
            <p class="text-xs text-slate-400 mt-1.5"></p>
        </div>

        <!-- Errors -->
        @if ($errors->any())
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 text-xs rounded-xl p-4 mb-6">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Email
                    Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    placeholder="email@gmail.com"
                    class="w-full px-4 py-2.5 bg-slate-900/60 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all text-sm">
            </div>

            <div>
                <label for="password"
                    class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Password</label>
                <input type="password" name="password" id="password" required placeholder="••••••••"
                    class="w-full px-4 py-2.5 bg-slate-900/60 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all text-sm">
            </div>

            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center text-slate-400 cursor-pointer">
                    <input type="checkbox" name="remember"
                        class="w-4 h-4 rounded bg-slate-900 border-slate-850 text-emerald-500 focus:ring-emerald-500 mr-2">
                    Ingat Saya
                </label>
            </div>

            <button type="submit"
                class="w-full py-3 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold rounded-xl transition-all text-sm shadow-md">
                Masuk
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-slate-400 pt-5">
            Belum punya akun? <a href="{{ route('register') }}"
                class="font-bold text-emerald-400 hover:text-emerald-300 hover:underline transition-colors">Daftar di
                sini</a>
        </div>
    </div>
@endsection