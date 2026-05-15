<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
    public function index()
    {
        if (!session()->has('user_id') || session('user_role') != 'mahasiswa') {
            return redirect('/login')->with('error', 'Akses ditolak!');
        }
        
        $userId = session('user_id');
        $user = DB::table('users')->where('id', $userId)->first();
        $mahasiswa = DB::table('mahasiswas')->where('id', $user->mahasiswa_id)->first();
        
        $jadwals = DB::table('k_r_s')
            ->join('jadwals', 'k_r_s.jadwal_id', '=', 'jadwals.id')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->join('dosens', 'jadwals.dosen_id', '=', 'dosens.id')
            ->where('k_r_s.mahasiswa_id', $mahasiswa->id)
            ->where('k_r_s.status', 'approved')
            ->select('jadwals.*', 'matakuliahs.nama_mk', 'dosens.nama_dosen')
            ->orderByRaw("FIELD(jadwals.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
            ->orderBy('jadwals.jam_mulai')
            ->get();
        
        return view('mahasiswa.jadwal', compact('jadwals'));
    }
}