<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Cek apakah user sudah login dan role-nya admin
        if (!session()->has('user_id') || session('user_role') != 'admin') {
            return redirect('/login')->with('error', 'Akses ditolak!');
        }
        
        $totalMahasiswa = DB::table('mahasiswas')->count();
        $totalDosen = DB::table('dosens')->count();
        $totalMatakuliah = DB::table('matakuliahs')->count();
        $totalJadwal = DB::table('jadwals')->count();
        $totalKRS = DB::table('k_r_s')->count();
        $totalAbsensi = DB::table('absensis')->count();
        
        $mahasiswaPerJurusan = DB::table('jurusans')
            ->leftJoin('mahasiswas', 'jurusans.id', '=', 'mahasiswas.jurusan_id')
            ->select('jurusans.nama_jurusan', DB::raw('count(mahasiswas.id) as total'))
            ->groupBy('jurusans.id', 'jurusans.nama_jurusan')
            ->get();
        
        $aktivitasTerbaru = DB::table('absensis')
            ->join('mahasiswas', 'absensis.mahasiswa_id', '=', 'mahasiswas.id')
            ->join('jadwals', 'absensis.jadwal_id', '=', 'jadwals.id')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->select('absensis.*', 'mahasiswas.nama_mahasiswa', 'matakuliahs.nama_mk')
            ->orderBy('absensis.created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalMahasiswa', 'totalDosen', 'totalMatakuliah', 'totalJadwal',
            'totalKRS', 'totalAbsensi', 'mahasiswaPerJurusan', 'aktivitasTerbaru'
        ));
    }
}