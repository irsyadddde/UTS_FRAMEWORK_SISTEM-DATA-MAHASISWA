<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Cek apakah user sudah login dan role-nya dosen
        if (!session()->has('user_id') || session('user_role') != 'dosen') {
            return redirect('/login')->with('error', 'Akses ditolak!');
        }
        
        $userId = session('user_id');
        
        // Ambil data user
        $user = DB::table('users')->where('id', $userId)->first();
        
        // Ambil data dosen berdasarkan user
        $dosen = null;
        if ($user && $user->dosen_id) {
            $dosen = DB::table('dosens')->where('id', $user->dosen_id)->first();
        }
        
        // Jika tidak ada data dosen, redirect dengan pesan error
        if (!$dosen) {
            return redirect('/login')->with('error', 'Data dosen tidak ditemukan!');
        }
        
        // Ambil jadwal mengajar hari ini
        $hariIni = $this->getHariIni();
        $jadwalHariIni = DB::table('jadwals')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->where('jadwals.dosen_id', $dosen->id)
            ->where('jadwals.hari', $hariIni)
            ->select('jadwals.*', 'matakuliahs.nama_mk')
            ->get();
        
        // Total mahasiswa yang diajar
        $totalMahasiswa = DB::table('jadwals')
            ->join('k_r_s', 'jadwals.id', '=', 'k_r_s.jadwal_id')
            ->where('jadwals.dosen_id', $dosen->id)
            ->where('k_r_s.status', 'approved')
            ->distinct('k_r_s.mahasiswa_id')
            ->count('k_r_s.mahasiswa_id');
        
        // Total mata kuliah yang diajar
        $totalMatakuliah = DB::table('jadwals')
            ->where('dosen_id', $dosen->id)
            ->distinct('matakuliah_id')
            ->count('matakuliah_id');
        
        return view('dosen.dashboard', compact('dosen', 'jadwalHariIni', 'totalMahasiswa', 'totalMatakuliah'));
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