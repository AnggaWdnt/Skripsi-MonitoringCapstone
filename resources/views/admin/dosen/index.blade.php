@extends('layouts.app')

@section('title', 'Kelola Dosen')
@section('page-title', 'Kelola Data Dosen')

@section('content')
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5 mb-6">
            <div>
                <h3 class="text-base font-bold text-slate-800">Daftar Dosen Pembimbing</h3>
                <p class="text-xs text-slate-400 mt-1">Daftar dosen akademik yang bertugas membimbing mahasiswa PKL.</p>
            </div>

            <button onclick="toggleModal('add-dosen-modal')"
                class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold transition-all shadow-md flex items-center gap-1.5 self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Dosen
            </button>
        </div>

        <!-- Table -->
        @if($dosens->isEmpty())
            <div class="text-center py-16 text-slate-400 text-sm">
                Belum ada dosen pembimbing yang terdaftar.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 text-xs font-semibold uppercase tracking-wider">
                            <th class="py-3 px-4">Nama Dosen</th>
                            <th class="py-3 px-4">NIDN</th>
                            <th class="py-3 px-4">Email</th>
                            <th class="py-3 px-4">Program Studi</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($dosens as $dosen)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3.5 px-4 font-semibold text-slate-800">{{ $dosen->name }}</td>
                                <td class="py-3.5 px-4 text-slate-500">{{ $dosen->nidn }}</td>
                                <td class="py-3.5 px-4 text-slate-500">{{ $dosen->email }}</td>
                                <td class="py-3.5 px-4 text-slate-500">{{ $dosen->prodi }}</td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <!-- Edit Button -->
                                        <button onclick="openEditModal({{ json_encode($dosen) }})"
                                            class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition-colors"
                                            title="Edit Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>

                                        <!-- Delete Form -->
                                        <form action="{{ route('admin.dosen.delete', $dosen->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus dosen ini? Hubungan bimbingan mahasiswa dengan dosen ini akan terputus.')"
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

    <!-- Modal: Add Dosen -->
    <div id="add-dosen-modal"
        class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-6 w-full max-w-md shadow-2xl relative">
            <button onclick="toggleModal('add-dosen-modal')"
                class="absolute top-4 right-4 p-1.5 text-slate-400 hover:text-slate-600 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-5">Tambah Dosen Pembimbing</h3>

            <form action="{{ route('admin.dosen.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="name"
                        class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nama Lengkap &
                        Gelar</label>
                    <input type="text" name="name" required placeholder="Contoh: Drs. Bambang, M.T."
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <label for="email"
                        class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Email</label>
                    <input type="email" name="email" required placeholder="dosen@kampus.com"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="nidn"
                            class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">NIDN</label>
                        <input type="text" name="nidn" required placeholder="Contoh: 0512088501"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label for="prodi"
                            class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Program
                            Studi</label>
                        <select name="prodi" required
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                            @foreach(\App\Models\Prodi::orderBy('name')->get() as $prodi)
                                <option value="{{ $prodi->name }}">{{ $prodi->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="password"
                        class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Password
                        Awal</label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                    <button type="button" onclick="toggleModal('add-dosen-modal')"
                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs shadow-md shadow-emerald-500/10">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Edit Dosen -->
    <div id="edit-dosen-modal"
        class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-6 w-full max-w-md shadow-2xl relative">
            <button onclick="toggleModal('edit-dosen-modal')"
                class="absolute top-4 right-4 p-1.5 text-slate-400 hover:text-slate-600 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-5">Edit Data Dosen</h3>

            <form id="edit-dosen-form" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="edit_name"
                        class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nama Lengkap &
                        Gelar</label>
                    <input type="text" name="name" id="edit_name" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <label for="edit_email"
                        class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Email</label>
                    <input type="email" name="email" id="edit_email" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="edit_nidn"
                            class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">NIDN</label>
                        <input type="text" name="nidn" id="edit_nidn" required
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label for="edit_prodi"
                            class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Program
                            Studi</label>
                        <select name="prodi" id="edit_prodi" required
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                            @foreach(\App\Models\Prodi::orderBy('name')->get() as $prodi)
                                <option value="{{ $prodi->name }}">{{ $prodi->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                    <button type="button" onclick="toggleModal('edit-dosen-modal')"
                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs shadow-md shadow-emerald-500/10">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(id) {
            const modal = document.getElementById(id);
            modal.classList.toggle('hidden');
        }

        function openEditModal(dosen) {
            // Populate fields
            document.getElementById('edit_name').value = dosen.name;
            document.getElementById('edit_email').value = dosen.email;
            document.getElementById('edit_nidn').value = dosen.nidn;
            document.getElementById('edit_prodi').value = dosen.prodi;

            // Update Action Route
            const form = document.getElementById('edit-dosen-form');
            form.action = `/admin/dosen/${dosen.id}`;

            toggleModal('edit-dosen-modal');
        }
    </script>
@endsection