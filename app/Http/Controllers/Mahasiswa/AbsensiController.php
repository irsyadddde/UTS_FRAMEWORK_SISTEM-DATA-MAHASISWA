<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    public function index()
    {
        if (!session()->has('user_id') || session('user_role') != 'mahasiswa') {
            return redirect('/login')->with('error', 'Akses ditolak!');
        }
        
        $userId = session('user_id');
        $user = DB::table('users')->where('id', $userId)->first();
        $mahasiswa = DB::table('mahasiswas')->where('id', $user->mahasiswa_id)->first();
        
        $absensi = DB::table('absensis')
            ->join('jadwals', 'absensis.jadwal_id', '=', 'jadwals.id')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->where('absensis.mahasiswa_id', $mahasiswa->id)
            ->select('absensis.*', 'matakuliahs.nama_mk', 'jadwals.hari', 'jadwals.jam_mulai')
            ->orderBy('absensis.tanggal', 'desc')
            ->get();
        
        return view('mahasiswa.absensi', compact('absensi'));
    }
}