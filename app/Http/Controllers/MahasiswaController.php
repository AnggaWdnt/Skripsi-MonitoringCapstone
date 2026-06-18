<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller
{
    /**
     * Student Dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $group = $user->group; // Loaded from Eloquent relation
        
        // Count logbook stats
        $totalLogs = Logbook::where('mahasiswa_id', $user->id)->count();
        $approvedLogs = Logbook::where('mahasiswa_id', $user->id)->where('status', 'approved')->count();
        $pendingLogs = Logbook::where('mahasiswa_id', $user->id)->where('status', 'pending')->count();
        
        // Get advisor info
        $dosen = $group ? $group->pembimbing : null;
        
        // Load evaluation score (using group final score or individual)
        $evaluasi = $group;

        return view('mahasiswa.dashboard', compact('user', 'group', 'totalLogs', 'approvedLogs', 'pendingLogs', 'dosen', 'evaluasi'));
    }

    /**
     * View Capstone submission form.
     */
    public function pengajuan()
    {
        $user = Auth::user();
        $group = $user->group;

        if (!$group) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Anda belum terdaftar dalam kelompok Capstone. Silakan hubungi Program Studi.');
        }

        return view('mahasiswa.pkl.pengajuan', compact('user', 'group'));
    }

    /**
     * Submit/Update Capstone title & survey details.
     */
    public function storePengajuan(Request $request)
    {
        $user = Auth::user();
        $group = $user->group;

        if (!$group) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Kelompok Anda tidak ditemukan.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'survey_file' => ($group->survey_file ? 'nullable' : 'required') . '|mimes:pdf|max:2048',
        ], [
            'title.required' => 'Judul Capstone wajib diisi.',
            'description.required' => 'Deskripsi proyek Capstone wajib diisi.',
            'survey_file.required' => 'Silakan unggah bukti dokumen survei lapangan (PDF).',
            'survey_file.mimes' => 'Bukti survei harus berupa file PDF.',
            'survey_file.max' => 'Ukuran file bukti survei maksimal 2MB.',
        ]);

        $surveyPath = $group->survey_file;
        if ($request->hasFile('survey_file')) {
            // Delete old file if exists
            if ($group->survey_file && file_exists(public_path($group->survey_file))) {
                @unlink(public_path($group->survey_file));
            }
            
            $file = $request->file('survey_file');
            $filename = 'survey_group_' . $group->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/survey'), $filename);
            $surveyPath = 'uploads/survey/' . $filename;
        }
        
        $group->update([
            'title' => $request->title,
            'description' => $request->description,
            'survey_file' => $surveyPath,
            'status' => 'pending',
        ]);

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Judul Capstone dan bukti survei berhasil diajukan. Menunggu verifikasi Program Studi.');
    }

    /**
     * View daily logbook entries.
     */
    public function logbook()
    {
        $user = Auth::user();
        $group = $user->group;
        
        if (!$group || in_array($group->status, ['belum_mengajukan', 'rejected'])) {
            return redirect()->route('mahasiswa.pengajuan')->with('error', 'Silakan ajukan judul Capstone dan dokumen survei terlebih dahulu sebelum mengisi logbook.');
        }

        if ($group->status === 'pending') {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Pengajuan judul Capstone Anda sedang ditinjau. Anda dapat mengisi logbook setelah disetujui.');
        }

        $logbooks = Logbook::where('mahasiswa_id', $user->id)
            ->orderBy('date', 'desc')
            ->get();

        return view('mahasiswa.logbook.index', compact('user', 'logbooks'));
    }

    /**
     * Store new logbook entry.
     */
    public function storeLogbook(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'activity' => 'required|string',
            'documentation' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'documentation.max' => 'Ukuran gambar dokumentasi maksimal adalah 2MB.',
        ]);

        $user = Auth::user();
        
        $docPath = null;
        if ($request->hasFile('documentation')) {
            $file = $request->file('documentation');
            $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/documentation'), $filename);
            $docPath = 'uploads/documentation/' . $filename;
        }

        Logbook::create([
            'mahasiswa_id' => $user->id,
            'date' => $request->date,
            'activity' => $request->activity,
            'documentation' => $docPath,
            'status' => 'pending',
        ]);

        return redirect()->route('mahasiswa.logbook')->with('success', 'Logbook harian berhasil ditambahkan dan menunggu verifikasi Dosen Pembimbing.');
    }

    /**
     * View final report upload panel.
     */
    public function laporan()
    {
        $user = Auth::user();
        $group = $user->group;
        
        if (!$group || $group->status === 'belum_mengajukan') {
            return redirect()->route('mahasiswa.pengajuan')->with('error', 'Silakan isi data pengajuan Capstone Anda terlebih dahulu.');
        }

        return view('mahasiswa.laporan.index', compact('user', 'group'));
    }

    /**
     * Upload final report PDF.
     */
    public function uploadLaporan(Request $request)
    {
        $request->validate([
            'laporan_file' => 'required|mimes:pdf|max:5120',
        ], [
            'laporan_file.required' => 'Silakan pilih file PDF laporan akhir.',
            'laporan_file.mimes' => 'File laporan harus bertipe PDF.',
            'laporan_file.max' => 'Ukuran file laporan maksimal adalah 5MB.',
        ]);

        $user = Auth::user();
        $group = $user->group;
        
        if ($group && $request->hasFile('laporan_file')) {
            // Delete old file if exists
            if ($group->laporan_file && file_exists(public_path($group->laporan_file))) {
                @unlink(public_path($group->laporan_file));
            }
            
            $file = $request->file('laporan_file');
            $filename = 'laporan_group_' . $group->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/laporan'), $filename);
            
            $group->update([
                'laporan_file' => 'uploads/laporan/' . $filename,
            ]);
        }

        return redirect()->route('mahasiswa.laporan')->with('success', 'Laporan akhir Capstone berhasil diunggah.');
    }
}
