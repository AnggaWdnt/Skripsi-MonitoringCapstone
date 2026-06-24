<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Mahasiswa Routes
Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('dashboard');
    Route::get('/pengajuan', [MahasiswaController::class, 'pengajuan'])->name('pengajuan');
    Route::post('/pengajuan', [MahasiswaController::class, 'storePengajuan']);
    Route::get('/logbook', [MahasiswaController::class, 'logbook'])->name('logbook');
    Route::post('/logbook', [MahasiswaController::class, 'storeLogbook'])->name('logbook.store');
    Route::get('/laporan', [MahasiswaController::class, 'laporan'])->name('laporan');
    Route::post('/laporan', [MahasiswaController::class, 'uploadLaporan'])->name('laporan.upload');
});

// Dosen Routes
Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/dashboard', [DosenController::class, 'dashboard'])->name('dashboard');
    Route::get('/mahasiswa/{id}', [DosenController::class, 'showMahasiswa'])->name('mahasiswa.show');
    Route::post('/logbook/{id}/status', [DosenController::class, 'updateLogbookStatus'])->name('logbook.status');
    Route::post('/mahasiswa/{id}/nilai', [DosenController::class, 'storeNilai'])->name('mahasiswa.nilai');
    Route::post('/kelompok/{id}/approve', [DosenController::class, 'approveGroup'])->name('kelompok.approve');
    Route::post('/kelompok/{id}/reject', [DosenController::class, 'rejectGroup'])->name('kelompok.reject');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Manage Mahasiswa
    Route::get('/mahasiswa', [AdminController::class, 'mahasiswa'])->name('mahasiswa');
    Route::post('/mahasiswa', [AdminController::class, 'storeMahasiswa'])->name('mahasiswa.store');
    Route::post('/mahasiswa/{id}', [AdminController::class, 'updateMahasiswa'])->name('mahasiswa.update');
    Route::delete('/mahasiswa/{id}', [AdminController::class, 'deleteMahasiswa'])->name('mahasiswa.delete');

    // Manage Dosen
    Route::get('/dosen', [AdminController::class, 'dosen'])->name('dosen');
    Route::post('/dosen', [AdminController::class, 'storeDosen'])->name('dosen.store');
    Route::post('/dosen/{id}', [AdminController::class, 'updateDosen'])->name('dosen.update');
    Route::delete('/dosen/{id}', [AdminController::class, 'deleteDosen'])->name('dosen.delete');

    // Manage Kelompok
    Route::get('/kelompok', [AdminController::class, 'kelompok'])->name('kelompok');
    Route::post('/kelompok', [AdminController::class, 'storeGroup'])->name('kelompok.store');
    Route::post('/kelompok/{id}', [AdminController::class, 'updateGroup'])->name('kelompok.update');
    Route::delete('/kelompok/{id}', [AdminController::class, 'deleteGroup'])->name('kelompok.delete');


    // Manage Prodi
    Route::get('/prodi', [AdminController::class, 'prodiIndex'])->name('prodi');
    Route::post('/prodi', [AdminController::class, 'prodiStore'])->name('prodi.store');
    Route::post('/prodi/{id}', [AdminController::class, 'prodiUpdate'])->name('prodi.update');
    Route::delete('/prodi/{id}', [AdminController::class, 'prodiDestroy'])->name('prodi.delete');

    // Rekap & Laporan
    Route::get('/laporan', [AdminController::class, 'laporan'])->name('laporan');
});
