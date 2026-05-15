<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
    public function index()
    {
        if (!session()->has('user_id') || session('user_role') != 'mahasiswa') {
            return redirect('/login')->with('error', 'Akses ditolak!');
        }
        
        $userId = session('user_id');
        $user = DB::table('users')->where('id', $userId)->first();
        $mahasiswa = DB::table('mahasiswas')->where('id', $user->mahasiswa_id)->first();
        
        $nilai = DB::table('nilais')
            ->join('k_r_s', 'nilais.krs_id', '=', 'k_r_s.id')
            ->join('jadwals', 'k_r_s.jadwal_id', '=', 'jadwals.id')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->where('k_r_s.mahasiswa_id', $mahasiswa->id)
            ->where('k_r_s.status', 'approved')
            ->select('nilais.*', 'matakuliahs.nama_mk', 'matakuliahs.sks')
            ->get();
        
        return view('mahasiswa.nilai', compact('nilai'));
    }
    
    public function transkrip()
    {
        if (!session()->has('user_id') || session('user_role') != 'mahasiswa') {
            return redirect('/login')->with('error', 'Akses ditolak!');
        }
        
        $userId = session('user_id');
        $user = DB::table('users')->where('id', $userId)->first();
        $mahasiswa = DB::table('mahasiswas')
            ->join('jurusans', 'mahasiswas.jurusan_id', '=', 'jurusans.id')
            ->where('mahasiswas.id', $user->mahasiswa_id)
            ->select('mahasiswas.*', 'jurusans.nama_jurusan')
            ->first();
        
        $nilai = DB::table('nilais')
            ->join('k_r_s', 'nilais.krs_id', '=', 'k_r_s.id')
            ->join('jadwals', 'k_r_s.jadwal_id', '=', 'jadwals.id')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->where('k_r_s.mahasiswa_id', $mahasiswa->id)
            ->whereNotNull('nilais.nilai_akhir')
            ->select('nilais.*', 'matakuliahs.nama_mk', 'matakuliahs.sks')
            ->get();
        
        $totalSKS = $nilai->sum('sks');
        $totalBobot = 0;
        foreach ($nilai as $n) {
            $bobot = $this->getBobotGrade($n->grade);
            $totalBobot += $bobot * $n->sks;
        }
        $ipk = $totalSKS > 0 ? round($totalBobot / $totalSKS, 2) : 0;
        
        return view('mahasiswa.transkrip', compact('mahasiswa', 'nilai', 'totalSKS', 'ipk'));
    }
    
    private function getBobotGrade($grade)
    {
        $bobot = ['A' => 4.0, 'A-' => 3.7, 'B+' => 3.3, 'B' => 3.0, 
                  'B-' => 2.7, 'C+' => 2.3, 'C' => 2.0, 'D' => 1.0, 'E' => 0.0];
        return $bobot[$grade] ?? 0;
    }
}