@extends('layouts.app')

@section('title', 'Kelola Mahasiswa')
@section('page-title', 'Kelola Data Mahasiswa')

@section('content')
<div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-5 mb-6">
        <div>
            <h3 class="text-base font-bold text-slate-800">Daftar Mahasiswa Capstone</h3>
            <p class="text-xs text-slate-400 mt-1">Kelola data mahasiswa dan penempatan kelompok Capstone mereka.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
            <!-- Search bar -->
            <div class="relative">
                <input type="text" id="search-student-input" onkeyup="filterStudents()" placeholder="Cari Nama, NIM, Prodi, Kelompok..." 
                    class="w-full sm:w-64 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                    style="padding-left: 2.5rem;">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
            
            <button onclick="toggleModal('add-student-modal')" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold transition-all shadow-md flex items-center gap-1.5 shrink-0 self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Mahasiswa
            </button>
        </div>
    </div>

    <!-- Table -->
    @if($students->isEmpty())
        <div class="text-center py-16 text-slate-400 text-sm">
            Belum ada mahasiswa yang terdaftar di sistem.
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-100 text-slate-400 text-xs font-semibold uppercase tracking-wider">
                        <th class="py-3 px-4">Nama</th>
                        <th class="py-3 px-4">NIM</th>
                        <th class="py-3 px-4">Program Studi</th>
                        <th class="py-3 px-4">Angkatan</th>
                        <th class="py-3 px-4">Kelompok Capstone</th>
                        <th class="py-3 px-4">Dosen Pembimbing</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($students as $student)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3.5 px-4 font-semibold text-slate-800">
                                <div>{{ $student->name }}</div>
                                @if($student->group && $student->group->title)
                                    <div class="text-[11px] text-slate-400 font-normal mt-0.5 truncate max-w-xs">
                                        Judul: "{{ $student->group->title }}"
                                    </div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-500">{{ $student->nim }}</td>
                            <td class="py-3.5 px-4 text-slate-500">{{ $student->prodi }}</td>
                            <td class="py-3.5 px-4 text-slate-500">{{ $student->angkatan }}</td>
                            <td class="py-3.5 px-4 font-medium text-slate-700">
                                @if($student->group)
                                    <span class="px-2.5 py-1 bg-slate-100 border border-slate-200 text-slate-700 rounded-lg text-xs font-bold">
                                        {{ $student->group->group_name }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-xs italic">Belum Masuk Kelompok</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-600">
                                @if($student->group && $student->group->pembimbing)
                                    {{ $student->group->pembimbing->name }}
                                @else
                                    <span class="text-slate-400 text-xs italic">Belum Ditugaskan</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <!-- Edit Button -->
                                    <button onclick="openEditModal({{ json_encode($student) }})" class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition-colors" title="Edit Data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </button>
                                    
                                    <!-- Delete Button -->
                                    <button onclick="openDeleteModal('{{ route('admin.mahasiswa.delete', $student->id) }}', 'Apakah Anda yakin ingin menghapus mahasiswa ini? Seluruh data logbook dan progres PKL/Capstone mahasiswa yang bersangkutan akan ikut terhapus secara permanen.')"
                                        class="p-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors" title="Hapus Data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
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

<!-- Modal: Add Student -->
<div id="add-student-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-900/60 backdrop-blur-sm p-4">
    <div class="bg-white border border-slate-200 rounded-2xl p-6 w-full max-w-md shadow-2xl relative">
        <button onclick="toggleModal('add-student-modal')" class="absolute top-4 right-4 p-1.5 text-slate-400 hover:text-slate-600 rounded-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        
        <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-5">Tambah Mahasiswa Baru</h3>
        
        <form action="{{ route('admin.mahasiswa.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" required placeholder="Nama lengkap mahasiswa"
                    class="w-full px-4.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>

            <div>
                <label for="email" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Email</label>
                <input type="email" name="email" required placeholder="email@gmail.com"
                    class="w-full px-4.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="nim" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">NIM</label>
                    <input type="text" name="nim" required placeholder="NIM"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label for="angkatan" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Angkatan</label>
                    <input type="text" name="angkatan" required placeholder="Contoh: 2022"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <div>
                <label for="prodi" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Program Studi</label>
                <select name="prodi" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                    @foreach(\App\Models\Prodi::orderBy('name')->get() as $prodi)
                        <option value="{{ $prodi->name }}">{{ $prodi->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="group_id" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Kelompok Capstone</label>
                <select name="group_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                    <option value="">Belum Masuk Kelompok</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}">{{ $group->group_name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Password Awal</label>
                <input type="password" name="password" required placeholder="Minimal 6 karakter"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                <button type="button" onclick="toggleModal('add-student-modal')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs">Batal</button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs shadow-md shadow-emerald-500/10">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Edit Student -->
<div id="edit-student-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-900/60 backdrop-blur-sm p-4">
    <div class="bg-white border border-slate-200 rounded-2xl p-6 w-full max-w-md shadow-2xl relative">
        <button onclick="toggleModal('edit-student-modal')" class="absolute top-4 right-4 p-1.5 text-slate-400 hover:text-slate-600 rounded-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        
        <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-5">Edit Data Mahasiswa</h3>
        
        <form id="edit-student-form" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label for="edit_name" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" id="edit_name" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>

            <div>
                <label for="edit_email" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Email</label>
                <input type="email" name="email" id="edit_email" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="edit_nim" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">NIM</label>
                    <input type="text" name="nim" id="edit_nim" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label for="edit_angkatan" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Angkatan</label>
                    <input type="text" name="angkatan" id="edit_angkatan" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <div>
                <label for="edit_prodi" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Prodi</label>
                <select name="prodi" id="edit_prodi" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                    @foreach(\App\Models\Prodi::orderBy('name')->get() as $prodi)
                        <option value="{{ $prodi->name }}">{{ $prodi->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="edit_group" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Kelompok Capstone</label>
                <select name="group_id" id="edit_group" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                    <option value="">Belum Masuk Kelompok</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}">{{ $group->group_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                <button type="button" onclick="toggleModal('edit-student-modal')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs">Batal</button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs shadow-md shadow-emerald-500/10">Perbarui</button>
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
    function toggleModal(id) {
        const modal = document.getElementById(id);
        modal.classList.toggle('hidden');
    }

    function openDeleteModal(actionUrl, message) {
        const form = document.getElementById('delete-confirm-form');
        form.action = actionUrl;
        document.getElementById('delete-modal-message').textContent = message;
        toggleModal('delete-confirm-modal');
    }

    function openEditModal(student) {
        // Populate fields
        document.getElementById('edit_name').value = student.name;
        document.getElementById('edit_email').value = student.email;
        document.getElementById('edit_nim').value = student.nim;
        document.getElementById('edit_angkatan').value = student.angkatan;
        document.getElementById('edit_prodi').value = student.prodi;
        document.getElementById('edit_group').value = student.group_id || '';
        
        // Action action
        const form = document.getElementById('edit-student-form');
        form.action = `/admin/mahasiswa/${student.id}`;
        
        toggleModal('edit-student-modal');
    }

    function filterStudents() {
        const input = document.getElementById('search-student-input');
        const filter = input.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const name = row.cells[0].textContent.toLowerCase();
            const nim = row.cells[1].textContent.toLowerCase();
            const prodi = row.cells[2].textContent.toLowerCase();
            const group = row.cells[4].textContent.toLowerCase();

            if (name.includes(filter) || nim.includes(filter) || prodi.includes(filter) || group.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endsection
