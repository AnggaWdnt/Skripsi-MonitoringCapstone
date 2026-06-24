@extends('layouts.app')

@section('title', 'Kelola Kelompok Capstone')
@section('page-title', 'Kelola Kelompok Capstone')

@section('content')
<div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5 mb-6">
        <div>
            <h3 class="text-base font-bold text-slate-800">Daftar Kelompok Capstone</h3>
            <p class="text-xs text-slate-400 mt-1">Buat kelompok, plot dosen pembimbing, dan tinjau judul proyek mahasiswa.</p>
        </div>
        
        <button onclick="toggleModal('add-group-modal')" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold transition-all shadow-md flex items-center gap-1.5 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Kelompok Baru
        </button>
    </div>

    <!-- Errors -->
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 text-xs rounded-xl p-4 mb-6">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Table -->
    @if($groups->isEmpty())
        <div class="text-center py-16 text-slate-400 text-sm">
            Belum ada kelompok Capstone yang terdaftar.
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                     <tr class="border-b border-slate-100 text-slate-400 text-xs font-semibold uppercase tracking-wider">
                        <th class="py-3 px-4">Nama Kelompok</th>
                        <th class="py-3 px-4">Tema Proyek</th>
                        <th class="py-3 px-4">Dosen Pembimbing</th>
                        <th class="py-3 px-4">Anggota Tim</th>
                        <th class="py-3 px-4">Judul Capstone</th>
                        <th class="py-3 px-4">Status Pengajuan</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($groups as $group)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-4 font-bold text-slate-800">
                                {{ $group->group_name }}
                            </td>
                            <td class="py-4 px-4 text-slate-700 font-semibold">
                                <span class="px-2.5 py-1 rounded-lg text-xs bg-slate-100 text-slate-700 border border-slate-200/60 inline-block">
                                    {{ $group->theme ?? 'Belum Ditentukan' }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-slate-700 font-medium">
                                {{ $group->pembimbing ? $group->pembimbing->name : 'Belum Di-plot' }}
                            </td>
                            <td class="py-4 px-4 text-slate-600">
                                <ul class="list-disc list-inside space-y-0.5 text-xs">
                                    @forelse($group->members as $member)
                                        <li>{{ $member->name }} <span class="text-slate-400">({{ $member->nim }})</span></li>
                                    @empty
                                        <span class="text-slate-400 italic">Tidak ada anggota</span>
                                    @endforelse
                                </ul>
                            </td>
                            <td class="py-4 px-4 text-slate-600 max-w-xs">
                                @if($group->title)
                                    <div class="font-bold text-slate-800 text-xs leading-normal whitespace-normal" title="{{ $group->title }}">
                                        {{ $group->title }}
                                    </div>
                                @else
                                    <span class="text-slate-400 italic text-xs">Belum diajukan</span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                @if($group->status === 'belum_mengajukan')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">Belum Mengajukan</span>
                                @elseif($group->status === 'pending')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Menunggu Review</span>
                                @elseif($group->status === 'approved')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Disetujui</span>
                                @elseif($group->status === 'rejected')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-700 border border-red-200">Ditolak</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <!-- Edit Button -->
                                    <button onclick="openEditModal({{ json_encode($group) }}, {{ json_encode($group->members->pluck('id')) }})" class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition-colors" title="Edit Data Kelompok">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </button>
                                    
                                    <!-- Delete Button -->
                                    <button onclick="openDeleteModal('{{ route('admin.kelompok.delete', $group->id) }}', 'Apakah Anda yakin ingin menghapus kelompok ini? Data bimbingan kelompok ini akan dihapus dan semua mahasiswa anggota kelompok ini akan dilepaskan kelompoknya.')"
                                        class="p-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors" title="Hapus Kelompok">
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

<!-- Modal: Add Group -->
<div id="add-group-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-900/60 backdrop-blur-sm p-4">
    <div class="bg-white border border-slate-200 rounded-2xl p-6 w-full max-w-md shadow-2xl relative">
        <button onclick="toggleModal('add-group-modal')" class="absolute top-4 right-4 p-1.5 text-slate-400 hover:text-slate-600 rounded-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        
        <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-5">Buat Kelompok Capstone Baru</h3>
        
        <form action="{{ route('admin.kelompok.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="group_name" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nama Kelompok</label>
                <input type="text" name="group_name" required placeholder="Contoh: Kelompok TI-01"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>

            <div>
                <label for="theme" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Tema Proyek</label>
                <select name="theme" id="theme" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="" disabled selected>Pilih Tema Proyek</option>
                    @foreach($themes as $theme)
                        <option value="{{ $theme->name }}">{{ $theme->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="dosen_id" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Dosen Pembimbing</label>
                <select name="dosen_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none">
                    <option value="">Belum Ditentukan</option>
                    @foreach($dosens as $dosen)
                        <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Pilih Anggota Mahasiswa</label>
                <div class="max-h-40 overflow-y-auto border border-slate-200 rounded-xl p-3 space-y-2 bg-slate-50">
                    @forelse($unassignedStudents as $student)
                        <label class="flex items-center gap-2.5 text-xs text-slate-700 cursor-pointer">
                            <input type="checkbox" name="member_ids[]" value="{{ $student->id }}" class="rounded text-emerald-600 focus:ring-emerald-500">
                            <span>{{ $student->name }} <strong class="text-[10px] text-slate-400">({{ $student->nim }})</strong></span>
                        </label>
                    @empty
                        <span class="text-xs text-slate-400 italic">Semua mahasiswa sudah memiliki kelompok.</span>
                    @endforelse
                </div>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                <button type="button" onclick="toggleModal('add-group-modal')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs">Batal</button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs shadow-md shadow-emerald-500/10">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Edit Group -->
<div id="edit-group-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-900/60 backdrop-blur-sm p-4">
    <div class="bg-white border border-slate-200 rounded-2xl p-6 w-full max-w-md shadow-2xl relative">
        <button onclick="toggleModal('edit-group-modal')" class="absolute top-4 right-4 p-1.5 text-slate-400 hover:text-slate-600 rounded-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        
        <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-5">Edit Kelompok Capstone</h3>
        
        <form id="edit-group-form" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="edit_group_name" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nama Kelompok</label>
                <input type="text" name="group_name" id="edit_group_name" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>

            <div>
                <label for="edit_theme" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Tema Proyek</label>
                <select name="theme" id="edit_theme" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="" disabled selected>Pilih Tema Proyek</option>
                    @foreach($themes as $theme)
                        <option value="{{ $theme->name }}">{{ $theme->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="edit_dosen_id" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Dosen Pembimbing</label>
                <select name="dosen_id" id="edit_dosen_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                    <option value="">Belum Ditentukan</option>
                    @foreach($dosens as $dosen)
                        <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Pilih Anggota Kelompok</label>
                <div class="max-h-48 overflow-y-auto border border-slate-200 rounded-xl p-3 space-y-2 bg-slate-50">
                    <!-- In edit mode we list all students. If they belong to this group they are checked. If they belong to other groups, we style them disabled or filter out. Let's make a container for JS to inject. -->
                    <div id="edit-members-container" class="space-y-2 text-xs">
                        <!-- Filled dynamically by JavaScript -->
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                <button type="button" onclick="toggleModal('edit-group-modal')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs">Batal</button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs shadow-md shadow-emerald-500/10">Simpan Perubahan</button>
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

    // List of ALL mahasiswa in database to be dynamically rendered in edit modal
    const allStudentsList = {!! json_encode(\App\Models\User::where('role', 'mahasiswa')->get(['id', 'name', 'nim', 'group_id'])) !!};

    function toggleModal(id) {
        const modal = document.getElementById(id);
        modal.classList.toggle('hidden');
    }

    function openEditModal(group, currentMemberIds) {
        document.getElementById('edit_group_name').value = group.group_name;
        
        const themeSelect = document.getElementById('edit_theme');
        themeSelect.value = group.theme || '';
        if (group.theme && !themeSelect.value) {
            // Remove dynamically added custom options from previous opens first (if any)
            const existingCustom = themeSelect.querySelector('option[data-custom="true"]');
            if (existingCustom) {
                existingCustom.remove();
            }
            const opt = document.createElement('option');
            opt.value = group.theme;
            opt.textContent = group.theme;
            opt.setAttribute('data-custom', 'true');
            themeSelect.appendChild(opt);
            themeSelect.value = group.theme;
        }
        
        document.getElementById('edit_dosen_id').value = group.dosen_id || '';
        
        // Build list of checkboxes dynamically
        const container = document.getElementById('edit-members-container');
        container.innerHTML = ''; // clear first
        
        allStudentsList.forEach(student => {
            // Show if: not in any group, OR currently in THIS group
            if (!student.group_id || student.group_id === group.id) {
                const isChecked = currentMemberIds.includes(student.id) ? 'checked' : '';
                
                const label = document.createElement('label');
                label.className = "flex items-center gap-2.5 cursor-pointer py-0.5";
                label.innerHTML = `
                    <input type="checkbox" name="member_ids[]" value="${student.id}" ${isChecked} class="rounded text-emerald-600 focus:ring-emerald-500">
                    <span>${student.name} <strong class="text-[10px] text-slate-400">(${student.nim})</strong></span>
                `;
                container.appendChild(label);
            }
        });

        if (container.innerHTML === '') {
            container.innerHTML = '<span class="text-slate-400 italic">Tidak ada mahasiswa yang tersedia untuk dimasukkan.</span>';
        }

        const form = document.getElementById('edit-group-form');
        form.action = `/admin/kelompok/${group.id}`;
        
        toggleModal('edit-group-modal');
    }
</script>
@endsection
