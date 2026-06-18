<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DosenController extends Controller
{
    /**
     * Dosen Dashboard.
     */
    public function dashboard()
    {
        $dosen = Auth::user();
        
        // Fetch groups supervised by this Dosen
        $groups = Group::where('dosen_id', $dosen->id)
            ->with('members')
            ->get();
            
        $totalBimbingan = $groups->count();
        $aktifCount = $groups->where('status', 'approved')->count();
        $selesaiCount = $groups->whereNotNull('nilai_capstone')->count();
        
        // Fetch student IDs inside these groups
        $studentIds = User::where('role', 'mahasiswa')
            ->whereIn('group_id', $groups->pluck('id'))
            ->pluck('id')
            ->toArray();
            
        $pendingLogbooks = Logbook::whereIn('mahasiswa_id', $studentIds)
            ->where('status', 'pending')
            ->with('mahasiswa')
            ->orderBy('date', 'asc')
            ->get();

        return view('dosen.dashboard', compact('groups', 'totalBimbingan', 'aktifCount', 'selesaiCount', 'pendingLogbooks'));
    }

    /**
     * Show detailed student info & logbooks.
     */
    public function showMahasiswa($id)
    {
        $dosen = Auth::user();
        
        // Verify student belongs to a group supervised by this Dosen
        $student = User::where('id', $id)
            ->where('role', 'mahasiswa')
            ->whereHas('group', function ($query) use ($dosen) {
                $query->where('dosen_id', $dosen->id);
            })
            ->firstOrFail();

        $logbooks = Logbook::where('mahasiswa_id', $student->id)
            ->orderBy('date', 'desc')
            ->get();

        $groupLogbooks = collect();
        if ($student->group) {
            $memberIds = $student->group->members->pluck('id');
            $groupLogbooks = Logbook::whereIn('mahasiswa_id', $memberIds)
                ->with('mahasiswa')
                ->orderBy('date', 'desc')
                ->get();
        }

        // Evaluasi is taken from group details
        $evaluasi = $student->group;

        return view('dosen.mahasiswa.show', compact('student', 'logbooks', 'groupLogbooks', 'evaluasi'));
    }

    /**
     * Approve or reject a logbook entry.
     */
    public function updateLogbookStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'dosen_note' => 'nullable|string',
        ]);

        $dosen = Auth::user();
        $logbook = Logbook::findOrFail($id);
        
        // Verify this logbook belongs to a student in a group supervised by this Dosen
        $student = User::findOrFail($logbook->mahasiswa_id);
        if (!$student->group || $student->group->dosen_id !== $dosen->id) {
            abort(403, 'Aksi tidak diizinkan. Mahasiswa bukan bimbingan Anda.');
        }

        $logbook->update([
            'status' => $request->status,
            'dosen_note' => $request->dosen_note,
        ]);

        return redirect()->back()->with('success', 'Status logbook mahasiswa berhasil diperbarui.');
    }

    /**
     * Submit evaluation and grade.
     */
    public function storeNilai(Request $request, $id)
    {
        $request->validate([
            'nilai_pkl' => 'required|integer|min:0|max:100',
            'catatan' => 'required|string',
        ], [
            'nilai_pkl.required' => 'Nilai Capstone wajib diisi.',
            'catatan.required' => 'Catatan evaluasi wajib diisi.',
        ]);

        $dosen = Auth::user();
        $student = User::where('id', $id)
            ->whereHas('group', function ($query) use ($dosen) {
                $query->where('dosen_id', $dosen->id);
            })
            ->firstOrFail();
            
        $group = $student->group;
        if ($group) {
            $group->update([
                'nilai_capstone' => $request->nilai_pkl,
                'catatan' => $request->catatan,
            ]);
        }

        return redirect()->back()->with('success', 'Nilai dan evaluasi Capstone kelompok berhasil disimpan.');
    }

    /**
     * Approve Group Title.
     */
    public function approveGroup($id)
    {
        $dosen = Auth::user();
        $group = Group::where('id', $id)->where('dosen_id', $dosen->id)->firstOrFail();
        $group->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Judul Capstone kelompok berhasil disetujui.');
    }

    /**
     * Reject Group Title.
     */
    public function rejectGroup($id)
    {
        $dosen = Auth::user();
        $group = Group::where('id', $id)->where('dosen_id', $dosen->id)->firstOrFail();
        $group->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Judul Capstone kelompok ditolak.');
    }
}
