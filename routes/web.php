<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MatakuliahController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\KRSController;
use App\Http\Controllers\TahunAkademikController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\UserManagementController;

// Authentication routes
Route::get('/login', [UserManagementController::class, 'loginForm'])->name('login');
Route::post('/login', [UserManagementController::class, 'login']);
Route::post('/logout', [UserManagementController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth.session')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Jurusan
    Route::resource('jurusan', JurusanController::class);
    
    // Mahasiswa
    Route::resource('mahasiswa', MahasiswaController::class);
    
    // Dosen
    Route::resource('dosen', DosenController::class);
    
    // Mata Kuliah
    Route::resource('matakuliah', MatakuliahController::class);
    
    // Jadwal
    Route::resource('jadwal', JadwalController::class);
    Route::get('/jadwal-kalender', [JadwalController::class, 'kalender'])->name('jadwal.kalender');
    
    // Kelas
    Route::resource('kelas', KelasController::class);
    
    // Ruangan
    Route::resource('ruangan', RuanganController::class);
    
    // Tahun Akademik
    Route::resource('tahun-akademik', TahunAkademikController::class);
    Route::put('/tahun-akademik/{id}/set-active', [TahunAkademikController::class, 'setActive'])->name('tahun-akademik.set-active');
    
    // KRS
    Route::resource('krs', KRSController::class);
    Route::put('/krs/{id}/approve', [KRSController::class, 'approve'])->name('krs.approve');
    Route::put('/krs/{id}/reject', [KRSController::class, 'reject'])->name('krs.reject');
    Route::get('/krs/{id}/cetak', [KRSController::class, 'cetak'])->name('krs.cetak');
    
    // Nilai
    Route::resource('nilai', NilaiController::class);
    Route::get('/transkrip/{mahasiswa_id}', [NilaiController::class, 'transkrip'])->name('nilai.transkrip');
    
    // Absensi
    Route::resource('absensi', AbsensiController::class);
    Route::get('/absensi-scan', [AbsensiController::class, 'qrScan'])->name('absensi.scan');
    Route::get('/absensi-generate-qr/{jadwal_id}', [AbsensiController::class, 'generateQR'])->name('absensi.generate-qr');
    Route::get('/absensi-verify/{token}', [AbsensiController::class, 'verifyQR'])->name('absensi.verify');
    Route::post('/absensi-process', [AbsensiController::class, 'processQR'])->name('absensi.process');
    Route::get('/absensi-laporan', [AbsensiController::class, 'laporan'])->name('absensi.laporan');
    Route::get('/absensi-rekapitulasi/{jadwal_id}', [AbsensiController::class, 'rekapitulasi'])->name('absensi.rekapitulasi');
    
    // User Management
    Route::resource('user-management', UserManagementController::class);
    Route::put('/user-management/{id}/reset-password', [UserManagementController::class, 'resetPassword'])->name('user-management.reset-password');
    Route::get('/profile', [UserManagementController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserManagementController::class, 'updateProfile'])->name('profile.update');
});