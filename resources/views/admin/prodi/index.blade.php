@extends('layouts.app')

@section('title', 'Kelola Prodi')
@section('page-title', 'Kelola Data Program Studi')

@section('content')
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5 mb-6">
            <div>
                <h3 class="text-base font-bold text-slate-800">Daftar Program Studi</h3>
                <p class="text-xs text-slate-400 mt-1">Kelola data Program Studi yang aktif di program Capstone Project.</p>
            </div>

            <button onclick="toggleModal('add-prodi-modal')"
                class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold transition-all shadow-md flex items-center gap-1.5 self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Program Studi
            </button>
        </div>

        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-xl p-3.5 mb-5">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 text-xs rounded-xl p-3.5 mb-5">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Table -->
        @if($prodis->isEmpty())
            <div class="text-center py-16 text-slate-400 text-sm">
                Belum ada Program Studi yang terdaftar.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 text-xs font-semibold uppercase tracking-wider">
                            <th class="py-3 px-4">Nama Program Studi</th>
                            <th class="py-3 px-4">Tanggal Ditambahkan</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($prodis as $prodi)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3.5 px-4 font-semibold text-slate-800">{{ $prodi->name }}</td>
                                <td class="py-3.5 px-4 text-slate-500">{{ $prodi->created_at->format('d M Y') }}</td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <!-- Edit Button -->
                                        <button onclick="openEditModal({{ json_encode($prodi) }})"
                                            class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition-colors"
                                            title="Edit Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>

                                        <!-- Delete Form -->
                                        <form action="{{ route('admin.prodi.delete', $prodi->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus Program Studi ini? Data mahasiswa/dosen yang menggunakan prodi ini tetap ada tetapi Anda harus menyesuaikannya.')"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors"
                                                title="Hapus Data">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Modal: Tambah Prodi -->
    <div id="add-prodi-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-6 w-full max-w-md shadow-2xl relative">
            <button onclick="toggleModal('add-prodi-modal')" class="absolute top-4 right-4 p-1.5 text-slate-400 hover:text-slate-600 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            
            <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-5">Tambah Program Studi Baru</h3>
            
            <form action="{{ route('admin.prodi.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nama Program Studi</label>
                    <input type="text" name="name" required placeholder="Contoh: Teknologi Informasi"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                    <button type="button" onclick="toggleModal('add-prodi-modal')"
                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs shadow-md shadow-emerald-500/10">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Edit Prodi -->
    <div id="edit-prodi-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-6 w-full max-w-md shadow-2xl relative">
            <button onclick="toggleModal('edit-prodi-modal')" class="absolute top-4 right-4 p-1.5 text-slate-400 hover:text-slate-600 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            
            <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-5">Edit Program Studi</h3>
            
            <form id="edit-prodi-form" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label for="edit_name" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nama Program Studi</label>
                    <input type="text" name="name" id="edit_name" required placeholder="Contoh: Teknologi Informasi"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                    <button type="button" onclick="toggleModal('edit-prodi-modal')"
                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs shadow-md shadow-emerald-500/10">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.toggle('hidden');
        }

        function openEditModal(prodi) {
            const form = document.getElementById('edit-prodi-form');
            form.action = `/admin/prodi/${prodi.id}`;
            document.getElementById('edit_name').value = prodi.name;
            toggleModal('edit-prodi-modal');
        }
    </script>
@endsection
