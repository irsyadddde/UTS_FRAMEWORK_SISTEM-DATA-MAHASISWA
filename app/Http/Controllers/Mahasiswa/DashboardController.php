<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Cek apakah user sudah login dan role-nya mahasiswa
        if (!session()->has('user_id') || session('user_role') != 'mahasiswa') {
            return redirect('/login')->with('error', 'Akses ditolak!');
        }
        
        $userId = session('user_id');
        
        // Ambil data user
        $user = DB::table('users')->where('id', $userId)->first();
        
        // Ambil data mahasiswa berdasarkan user
        $mahasiswa = null;
        if ($user && $user->mahasiswa_id) {
            $mahasiswa = DB::table('mahasiswas')->where('id', $user->mahasiswa_id)->first();
        }
        
        // Jika tidak ada data mahasiswa, redirect dengan pesan error
        if (!$mahasiswa) {
            return redirect('/login')->with('error', 'Data mahasiswa tidak ditemukan!');
        }
        
        // Ambil KRS aktif
        $krsAktif = DB::table('k_r_s')
            ->join('jadwals', 'k_r_s.jadwal_id', '=', 'jadwals.id')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->join('dosens', 'jadwals.dosen_id', '=', 'dosens.id')
            ->where('k_r_s.mahasiswa_id', $mahasiswa->id)
            ->where('k_r_s.status', 'approved')
            ->select('k_r_s.*', 'matakuliahs.nama_mk', 'matakuliahs.sks', 'dosens.nama_dosen', 'jadwals.hari', 'jadwals.jam_mulai', 'jadwals.jam_selesai', 'jadwals.ruangan')
            ->get();
        
        // Ambil jadwal hari ini
        $hariIni = $this->getHariIni();
        $jadwalHariIni = DB::table('k_r_s')
            ->join('jadwals', 'k_r_s.jadwal_id', '=', 'jadwals.id')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->join('dosens', 'jadwals.dosen_id', '=', 'dosens.id')
            ->where('k_r_s.mahasiswa_id', $mahasiswa->id)
            ->where('k_r_s.status', 'approved')
            ->where('jadwals.hari', $hariIni)
            ->select('jadwals.*', 'matakuliahs.nama_mk', 'dosens.nama_dosen')
            ->get();
        
        // Hitung total SKS
        $totalSKS = $krsAktif->sum('sks');
        
        // Hitung kehadiran
        $totalHadir = DB::table('absensis')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'hadir')
            ->count();
        
        $totalAbsensi = DB::table('absensis')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->count();
        
        $persentaseKehadiran = $totalAbsensi > 0 ? round(($totalHadir / $totalAbsensi) * 100, 2) : 0;
        
        return view('mahasiswa.dashboard', compact(
            'mahasiswa', 
            'krsAktif', 
            'jadwalHariIni', 
            'totalSKS', 
            'persentaseKehadiran'
        ));
    }
    
    private function getHariIni()
    {
        $hari = date('l');
        $map = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];
        return $map[$hari] ?? 'Senin';
    }
}