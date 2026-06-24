@extends('layouts.app')

@section('title', 'Kelola Tema')
@section('page-title', 'Kelola Data Tema Proyek')

@section('content')
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5 mb-6">
            <div>
                <h3 class="text-base font-bold text-slate-800">Daftar Tema Proyek</h3>
                <p class="text-xs text-slate-400 mt-1">Kelola data Tema Proyek Capstone yang aktif dan dapat dipilih oleh kelompok.</p>
            </div>

            <button onclick="toggleModal('add-tema-modal')"
                class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold transition-all shadow-md flex items-center gap-1.5 self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Tema Proyek
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
        @if($themes->isEmpty())
            <div class="text-center py-16 text-slate-400 text-sm">
                Belum ada Tema Proyek yang terdaftar.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 text-xs font-semibold uppercase tracking-wider">
                            <th class="py-3 px-4">Nama Tema Proyek</th>
                            <th class="py-3 px-4">Deskripsi</th>
                            <th class="py-3 px-4">Tanggal Ditambahkan</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($themes as $theme)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3.5 px-4 font-semibold text-slate-800 leading-relaxed">{{ $theme->name }}</td>
                                <td class="py-3.5 px-4 text-slate-500 max-w-xs truncate" title="{{ $theme->description }}">{{ $theme->description ?? '-' }}</td>
                                <td class="py-3.5 px-4 text-slate-500 whitespace-nowrap">{{ $theme->created_at->format('d M Y') }}</td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <!-- Edit Button -->
                                        <button onclick="openEditModal({{ json_encode($theme) }})"
                                            class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition-colors"
                                            title="Edit Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>

                                        <!-- Delete Button -->
                                        <button onclick="openDeleteModal('{{ route('admin.tema.delete', $theme->id) }}', 'Apakah Anda yakin ingin menghapus Tema Proyek ini? Kelompok yang menggunakan tema ini tetap ada, namun data pilihan temanya akan tersimpan sebagai teks biasa.')"
                                            class="p-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors"
                                            title="Hapus Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Modal: Tambah Tema -->
    <div id="add-tema-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-6 w-full max-w-md shadow-2xl relative">
            <button onclick="toggleModal('add-tema-modal')" class="absolute top-4 right-4 p-1.5 text-slate-400 hover:text-slate-600 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            
            <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-5">Tambah Tema Proyek Baru</h3>
            
            <form action="{{ route('admin.tema.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nama Tema Proyek</label>
                    <input type="text" name="name" required placeholder="Contoh: Peningkatan Produktivitas UMKM"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <label for="description" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Deskripsi Tema (Opsional)</label>
                    <textarea name="description" placeholder="Penjelasan singkat mengenai tema proyek..." rows="3"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                    <button type="button" onclick="toggleModal('add-tema-modal')"
                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs shadow-md shadow-emerald-500/10">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Edit Tema -->
    <div id="edit-tema-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-6 w-full max-w-md shadow-2xl relative">
            <button onclick="toggleModal('edit-tema-modal')" class="absolute top-4 right-4 p-1.5 text-slate-400 hover:text-slate-600 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            
            <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-5">Edit Tema Proyek</h3>
            
            <form id="edit-tema-form" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label for="edit_name" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nama Tema Proyek</label>
                    <input type="text" name="name" id="edit_name" required placeholder="Contoh: Peningkatan Produktivitas UMKM"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <label for="edit_description" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Deskripsi Tema (Opsional)</label>
                    <textarea name="description" id="edit_description" placeholder="Penjelasan singkat mengenai tema proyek..." rows="3"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                    <button type="button" onclick="toggleModal('edit-tema-modal')"
                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs shadow-md shadow-emerald-500/10">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Konfirmasi Hapus -->
    <div id="delete-confirm-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-6 w-full max-w-md shadow-2xl relative">
            <button onclick="toggleModal('delete-confirm-modal')" class="absolute top-4 right-4 p-1.5 text-slate-400 hover:text-slate-600 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            
            <div class="flex items-center gap-3 border-b border-slate-100 pb-3 mb-5">
                <div class="p-2 bg-red-50 text-red-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Konfirmasi Hapus</h3>
            </div>
            
            <p id="delete-modal-message" class="text-sm text-slate-600 mb-6">Apakah Anda yakin ingin menghapus data ini?</p>
            
            <form id="delete-confirm-form" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                    <button type="button" onclick="toggleModal('delete-confirm-modal')"
                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white font-bold rounded-xl text-xs shadow-md shadow-red-500/10">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openDeleteModal(actionUrl, message) {
            const form = document.getElementById('delete-confirm-form');
            form.action = actionUrl;
            document.getElementById('delete-modal-message').textContent = message;
            toggleModal('delete-confirm-modal');
        }

        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.toggle('hidden');
        }

        function openEditModal(theme) {
            const form = document.getElementById('edit-tema-form');
            form.action = `/admin/tema/${theme.id}`;
            document.getElementById('edit_name').value = theme.name;
            document.getElementById('edit_description').value = theme.description || '';
            toggleModal('edit-tema-modal');
        }
    </script>
@endsection
