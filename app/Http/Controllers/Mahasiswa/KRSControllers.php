<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KRSController extends Controller
{
    public function index()
    {
        if (!session()->has('user_id') || session('user_role') != 'mahasiswa') {
            return redirect('/login')->with('error', 'Akses ditolak!');
        }
        
        $userId = session('user_id');
        $user = DB::table('users')->where('id', $userId)->first();
        $mahasiswa = DB::table('mahasiswas')->where('id', $user->mahasiswa_id)->first();
        
        // Ambil KRS yang sudah diambil
        $krsList = DB::table('k_r_s')
            ->join('jadwals', 'k_r_s.jadwal_id', '=', 'jadwals.id')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->join('dosens', 'jadwals.dosen_id', '=', 'dosens.id')
            ->where('k_r_s.mahasiswa_id', $mahasiswa->id)
            ->select('k_r_s.*', 'matakuliahs.nama_mk', 'matakuliahs.sks', 'dosens.nama_dosen', 
                     'jadwals.hari', 'jadwals.jam_mulai', 'jadwals.jam_selesai', 'jadwals.ruangan')
            ->get();
        
        // Ambil jadwal yang tersedia untuk KRS
        $availableJadwals = DB::table('jadwals')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->join('dosens', 'jadwals.dosen_id', '=', 'dosens.id')
            ->select('jadwals.*', 'matakuliahs.nama_mk', 'matakuliahs.sks', 'dosens.nama_dosen')
            ->get();
        
        // Hitung total SKS yang sudah diambil (approved)
        $totalSKS = DB::table('k_r_s')
            ->join('jadwals', 'k_r_s.jadwal_id', '=', 'jadwals.id')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->where('k_r_s.mahasiswa_id', $mahasiswa->id)
            ->where('k_r_s.status', 'approved')
            ->sum('matakuliahs.sks');
        
        return view('mahasiswa.krs', compact('krsList', 'availableJadwals', 'totalSKS', 'mahasiswa'));
    }
    
    public function store(Request $request)
    {
        if (!session()->has('user_id') || session('user_role') != 'mahasiswa') {
            return redirect('/login')->with('error', 'Akses ditolak!');
        }
        
        $validator = Validator::make($request->all(), [
            'jadwal_id' => 'required|exists:jadwals,id',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Data tidak valid!');
        }
        
        $userId = session('user_id');
        $user = DB::table('users')->where('id', $userId)->first();
        $mahasiswa = DB::table('mahasiswas')->where('id', $user->mahasiswa_id)->first();
        
        // Cek apakah sudah mengambil mata kuliah ini
        $exists = DB::table('k_r_s')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->where('jadwal_id', $request->jadwal_id)
            ->exists();
        
        if ($exists) {
            return redirect()->back()->with('error', 'Anda sudah mengambil mata kuliah ini!');
        }
        
        // Cek bentrok jadwal
        $jadwal = DB::table('jadwals')->where('id', $request->jadwal_id)->first();
        $conflict = DB::table('k_r_s')
            ->join('jadwals', 'k_r_s.jadwal_id', '=', 'jadwals.id')
            ->where('k_r_s.mahasiswa_id', $mahasiswa->id)
            ->where('jadwals.hari', $jadwal->hari)
            ->where(function($q) use ($jadwal) {
                $q->whereBetween('jadwals.jam_mulai', [$jadwal->jam_mulai, $jadwal->jam_selesai])
                  ->orWhereBetween('jadwals.jam_selesai', [$jadwal->jam_mulai, $jadwal->jam_selesai]);
            })
            ->exists();
        
        if ($conflict) {
            return redirect()->back()->with('error', 'Jadwal bentrok dengan mata kuliah lain!');
        }
        
        // Generate kode KRS
        $kode_krs = 'KRS' . date('Ymd') . rand(100, 999);
        
        DB::table('k_r_s')->insert([
            'kode_krs' => $kode_krs,
            'mahasiswa_id' => $mahasiswa->id,
            'jadwal_id' => $request->jadwal_id,
            'tahun_akademik_id' => 3,
            'status' => 'pending',
            'tgl_krs' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return redirect()->back()->with('success', 'KRS berhasil ditambahkan! Menunggu persetujuan admin.');
    }
    
    public function destroy($id)
    {
        if (!session()->has('user_id') || session('user_role') != 'mahasiswa') {
            return redirect('/login')->with('error', 'Akses ditolak!');
        }
        
        $krs = DB::table('k_r_s')->where('id', $id)->first();
        
        if ($krs && $krs->status == 'pending') {
            DB::table('k_r_s')->where('id', $id)->delete();
            return redirect()->back()->with('success', 'KRS berhasil dibatalkan.');
        }
        
        return redirect()->back()->with('error', 'KRS tidak dapat dibatalkan!');
    }
}