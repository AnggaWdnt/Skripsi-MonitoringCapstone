<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use App\Models\User;
use App\Models\Group;
use App\Models\Prodi;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Admin Dashboard.
     */
    public function dashboard()
    {
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();
        $totalDosen = User::where('role', 'dosen')->count();
        $totalKelompok = Group::count();
        
        // Group Status breakdown
        $statusBelum = Group::where('status', 'belum_mengajukan')->count();
        $statusPending = Group::where('status', 'pending')->count();
        $statusApproved = Group::where('status', 'approved')->count();
        $statusRejected = Group::where('status', 'rejected')->count();

        // Lecturers with group count
        $dosens = User::where('role', 'dosen')
            ->withCount(['mahasiswas']) // legacy count or keep it
            ->get();
            
        // Calculate groups count for each lecturer
        foreach ($dosens as $d) {
            $d->groups_count = Group::where('dosen_id', $d->id)->count();
        }

        // Recent logbooks
        $recentLogbooks = Logbook::with('mahasiswa')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalMahasiswa', 'totalDosen', 'totalKelompok',
            'statusBelum', 'statusPending', 'statusApproved', 'statusRejected',
            'dosens', 'recentLogbooks'
        ));
    }

    /**
     * List all Mahasiswa.
     */
    public function mahasiswa()
    {
        $students = User::where('role', 'mahasiswa')
            ->with(['dosen', 'group'])
            ->get();
            
        $dosens = User::where('role', 'dosen')->get();
        $groups = Group::all();
        return view('admin.mahasiswa.index', compact('students', 'dosens', 'groups'));
    }

    /**
     * Add new Mahasiswa.
     */
    public function storeMahasiswa(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'nim' => 'required|numeric|unique:users',
            'prodi' => 'required|string',
            'angkatan' => 'required|numeric',
            'group_id' => 'nullable|exists:groups,id',
        ], [
            'name.required' => 'Nama mahasiswa wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
            'nim.required' => 'NIM wajib diisi.',
            'nim.numeric' => 'NIM harus berupa angka.',
            'nim.unique' => 'NIM ini sudah terdaftar.',
            'prodi.required' => 'Program Studi wajib diisi.',
            'angkatan.required' => 'Angkatan wajib diisi.',
            'angkatan.numeric' => 'Angkatan harus berupa angka.',
        ]);

        // Find lecturer supervisor from group if group is assigned
        $dosen_id = null;
        if ($request->group_id) {
            $grp = Group::find($request->group_id);
            if ($grp) {
                $dosen_id = $grp->dosen_id;
            }
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'mahasiswa',
            'nim' => $request->nim,
            'prodi' => $request->prodi,
            'angkatan' => $request->angkatan,
            'dosen_id' => $dosen_id,
            'group_id' => $request->group_id,
            'status_pkl' => 'belum',
        ]);

        return redirect()->back()->with('success', 'Mahasiswa baru berhasil ditambahkan.');
    }

    /**
     * Update Mahasiswa.
     */
    public function updateMahasiswa(Request $request, $id)
    {
        $student = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $student->id,
            'nim' => 'required|numeric|unique:users,nim,' . $student->id,
            'prodi' => 'required|string',
            'angkatan' => 'required|numeric',
            'group_id' => 'nullable|exists:groups,id',
        ], [
            'name.required' => 'Nama mahasiswa wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'nim.required' => 'NIM wajib diisi.',
            'nim.numeric' => 'NIM harus berupa angka.',
            'nim.unique' => 'NIM ini sudah terdaftar.',
            'prodi.required' => 'Program Studi wajib diisi.',
            'angkatan.required' => 'Angkatan wajib diisi.',
            'angkatan.numeric' => 'Angkatan harus berupa angka.',
        ]);

        // Find lecturer supervisor from group if group is assigned
        $dosen_id = null;
        if ($request->group_id) {
            $grp = Group::find($request->group_id);
            if ($grp) {
                $dosen_id = $grp->dosen_id;
            }
        }

        $student->update([
            'name' => $request->name,
            'email' => $request->email,
            'nim' => $request->nim,
            'prodi' => $request->prodi,
            'angkatan' => $request->angkatan,
            'dosen_id' => $dosen_id,
            'group_id' => $request->group_id,
        ]);

        return redirect()->back()->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    /**
     * Delete Mahasiswa.
     */
    public function deleteMahasiswa($id)
    {
        $student = User::findOrFail($id);
        
        // Delete related logs
        $student->logbooks()->delete();
        $student->delete();

        return redirect()->back()->with('success', 'Data mahasiswa berhasil dihapus.');
    }

    /**
     * List all Dosen.
     */
    public function dosen()
    {
        $dosens = User::where('role', 'dosen')->get();
        return view('admin.dosen.index', compact('dosens'));
    }

    /**
     * Add new Dosen.
     */
    public function storeDosen(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'nidn' => 'required|numeric|unique:users',
            'prodi' => 'required|string',
        ], [
            'name.required' => 'Nama dosen wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
            'nidn.required' => 'NIDN wajib diisi.',
            'nidn.numeric' => 'NIDN harus berupa angka.',
            'nidn.unique' => 'NIDN ini sudah terdaftar.',
            'prodi.required' => 'Program Studi wajib diisi.',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'dosen',
            'nidn' => $request->nidn,
            'prodi' => $request->prodi,
        ]);

        return redirect()->back()->with('success', 'Dosen baru berhasil ditambahkan.');
    }

    /**
     * Update Dosen.
     */
    public function updateDosen(Request $request, $id)
    {
        $dosen = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $dosen->id,
            'nidn' => 'required|numeric|unique:users,nidn,' . $dosen->id,
            'prodi' => 'required|string',
        ], [
            'name.required' => 'Nama dosen wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'nidn.required' => 'NIDN wajib diisi.',
            'nidn.numeric' => 'NIDN harus berupa angka.',
            'nidn.unique' => 'NIDN ini sudah terdaftar.',
            'prodi.required' => 'Program Studi wajib diisi.',
        ]);

        $dosen->update([
            'name' => $request->name,
            'email' => $request->email,
            'nidn' => $request->nidn,
            'prodi' => $request->prodi,
        ]);

        return redirect()->back()->with('success', 'Data dosen berhasil diperbarui.');
    }

    /**
     * Delete Dosen.
     */
    public function deleteDosen($id)
    {
        $dosen = User::findOrFail($id);
        
        // Set student's dosen_id relation to null if this lecturer is deleted
        User::where('dosen_id', $dosen->id)->update(['dosen_id' => null]);
        Group::where('dosen_id', $dosen->id)->update(['dosen_id' => null]);
        
        $dosen->delete();

        return redirect()->back()->with('success', 'Data dosen berhasil dihapus.');
    }

    /**
     * List Capstone Groups (Kelola Kelompok).
     */
    public function kelompok()
    {
        $groups = Group::with(['members', 'pembimbing'])->get();
        $dosens = User::where('role', 'dosen')->get();
        $unassignedStudents = User::where('role', 'mahasiswa')->whereNull('group_id')->get();
        $themes = Theme::orderBy('name')->get();
        
        return view('admin.kelompok.index', compact('groups', 'dosens', 'unassignedStudents', 'themes'));
    }

    /**
     * Create Capstone Group.
     */
    public function storeGroup(Request $request)
    {
        $request->validate([
            'group_name' => 'required|string|max:255|unique:groups,group_name',
            'theme' => 'required|string|exists:themes,name',
            'dosen_id' => 'nullable|exists:users,id',
            'member_ids' => 'nullable|array',
            'member_ids.*' => 'exists:users,id',
        ], [
            'group_name.required' => 'Nama kelompok wajib diisi.',
            'group_name.unique' => 'Nama kelompok sudah digunakan.',
            'theme.required' => 'Tema proyek wajib dipilih.',
            'theme.exists' => 'Tema proyek yang dipilih tidak terdaftar.',
        ]);

        $group = Group::create([
            'group_name' => $request->group_name,
            'theme' => $request->theme,
            'dosen_id' => $request->dosen_id,
            'status' => 'belum_mengajukan',
        ]);

        if ($request->has('member_ids')) {
            User::whereIn('id', $request->member_ids)->update([
                'group_id' => $group->id,
                'dosen_id' => $request->dosen_id,
            ]);
        }

        return redirect()->back()->with('success', 'Kelompok Capstone baru berhasil dibuat.');
    }

    /**
     * Update Capstone Group.
     */
    public function updateGroup(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        $request->validate([
            'group_name' => 'required|string|max:255|unique:groups,group_name,' . $group->id,
            'theme' => 'required|string|exists:themes,name',
            'dosen_id' => 'nullable|exists:users,id',
            'member_ids' => 'nullable|array',
            'member_ids.*' => 'exists:users,id',
        ], [
            'group_name.required' => 'Nama kelompok wajib diisi.',
            'group_name.unique' => 'Nama kelompok sudah digunakan.',
            'theme.required' => 'Tema proyek wajib dipilih.',
            'theme.exists' => 'Tema proyek yang dipilih tidak terdaftar.',
        ]);

        $group->update([
            'group_name' => $request->group_name,
            'theme' => $request->theme,
            'dosen_id' => $request->dosen_id,
        ]);

        // Remove old members' group_id association
        User::where('group_id', $group->id)->update([
            'group_id' => null,
            'dosen_id' => null,
        ]);

        // Add new members
        if ($request->has('member_ids')) {
            User::whereIn('id', $request->member_ids)->update([
                'group_id' => $group->id,
                'dosen_id' => $request->dosen_id,
            ]);
        }

        return redirect()->back()->with('success', 'Kelompok Capstone berhasil diperbarui.');
    }

    /**
     * Delete Capstone Group.
     */
    public function deleteGroup($id)
    {
        $group = Group::findOrFail($id);

        // Disassociate members
        User::where('group_id', $group->id)->update([
            'group_id' => null,
            'dosen_id' => null,
        ]);

        $group->delete();

        return redirect()->back()->with('success', 'Kelompok Capstone berhasil dihapus.');
    }

    /**
     * Generate report of completion and statistics.
     */
    public function laporan()
    {
        $groups = Group::with(['members', 'pembimbing'])->get();
            
        // Metrics
        $total = $groups->count();
        $completed = $groups->whereNotNull('nilai_capstone')->count();
        $active = $groups->where('status', 'approved')->count();
        $rate = $total > 0 ? round(($completed / $total) * 100, 1) : 0;
        
        $avgGrade = $groups->whereNotNull('nilai_capstone')->count() > 0 
            ? round($groups->whereNotNull('nilai_capstone')->avg('nilai_capstone'), 1) 
            : 0;

        return view('admin.laporan.index', compact('groups', 'total', 'completed', 'active', 'rate', 'avgGrade'));
    }

    /**
     * List all Program Studi (Prodi).
     */
    public function prodiIndex()
    {
        $prodis = Prodi::orderBy('name')->get();
        return view('admin.prodi.index', compact('prodis'));
    }

    /**
     * Store new Program Studi.
     */
    public function prodiStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:prodis,name',
        ], [
            'name.unique' => 'Nama Program Studi sudah ada.',
        ]);

        Prodi::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Program Studi baru berhasil ditambahkan.');
    }

    /**
     * Update Program Studi.
     */
    public function prodiUpdate(Request $request, $id)
    {
        $prodi = Prodi::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:prodis,name,' . $prodi->id,
        ], [
            'name.unique' => 'Nama Program Studi sudah digunakan.',
        ]);

        $prodi->update([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Program Studi berhasil diperbarui.');
    }

    /**
     * Delete Program Studi.
     */
    public function prodiDestroy($id)
    {
        $prodi = Prodi::findOrFail($id);
        $prodi->delete();

        return redirect()->back()->with('success', 'Program Studi berhasil dihapus.');
    }

    /**
     * List all Themes.
     */
    public function temaIndex()
    {
        $themes = Theme::orderBy('name')->get();
        return view('admin.tema.index', compact('themes'));
    }

    /**
     * Store new Theme.
     */
    public function temaStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:themes,name',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Nama Tema wajib diisi.',
            'name.unique' => 'Nama Tema sudah ada.',
        ]);

        Theme::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Tema baru berhasil ditambahkan.');
    }

    /**
     * Update Theme.
     */
    public function temaUpdate(Request $request, $id)
    {
        $theme = Theme::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:themes,name,' . $theme->id,
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Nama Tema wajib diisi.',
            'name.unique' => 'Nama Tema sudah digunakan.',
        ]);

        $theme->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Tema berhasil diperbarui.');
    }

    /**
     * Delete Theme.
     */
    public function temaDestroy($id)
    {
        $theme = Theme::findOrFail($id);
        $theme->delete();

        return redirect()->back()->with('success', 'Tema berhasil dihapus.');
    }
}
