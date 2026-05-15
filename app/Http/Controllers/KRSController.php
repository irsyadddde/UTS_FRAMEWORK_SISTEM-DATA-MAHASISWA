<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KRSController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('k_r_s')
            ->join('mahasiswas', 'k_r_s.mahasiswa_id', '=', 'mahasiswas.id')
            ->join('jadwals', 'k_r_s.jadwal_id', '=', 'jadwals.id')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->join('tahun_akademiks', 'k_r_s.tahun_akademik_id', '=', 'tahun_akademiks.id')
            ->select(
                'k_r_s.*',
                'mahasiswas.nim',
                'mahasiswas.nama_mahasiswa',
                'matakuliahs.nama_mk',
                'matakuliahs.sks',
                'jadwals.hari',
                'jadwals.jam_mulai',
                'jadwals.jam_selesai',
                'tahun_akademiks.tahun',
                'tahun_akademiks.semester'
            );
        
        if ($request->has('search') && $request->search != '') {
            $query->where('mahasiswas.nama_mahasiswa', 'like', '%' . $request->search . '%')
                  ->orWhere('mahasiswas.nim', 'like', '%' . $request->search . '%');
        }
        
        if ($request->has('status') && $request->status != '') {
            $query->where('k_r_s.status', $request->status);
        }
        
        $krs = $query->orderBy('k_r_s.created_at', 'desc')->paginate(10);
        
        return view('krs.index', compact('krs'));
    }

    public function create()
    {
        $mahasiswas = DB::table('mahasiswas')->get();
        $jadwals = DB::table('jadwals')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->join('dosens', 'jadwals.dosen_id', '=', 'dosens.id')
            ->select('jadwals.*', 'matakuliahs.nama_mk', 'matakuliahs.sks', 'dosens.nama_dosen')
            ->get();
        $activeYear = DB::table('tahun_akademiks')->where('is_active', true)->first();
        
        return view('krs.create', compact('mahasiswas', 'jadwals', 'activeYear'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'jadwal_id' => 'required|exists:jadwals,id',
            'tahun_akademik_id' => 'required|exists:tahun_akademiks,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        // Cek duplikasi
        $exists = DB::table('k_r_s')
            ->where('mahasiswa_id', $request->mahasiswa_id)
            ->where('jadwal_id', $request->jadwal_id)
            ->where('tahun_akademik_id', $request->tahun_akademik_id)
            ->exists();
        
        if ($exists) {
            return redirect()->back()->with('error', 'Mahasiswa sudah mengambil mata kuliah ini!');
        }
        
        // Generate kode KRS
        $kode_krs = 'KRS' . date('Ymd') . rand(100, 999);
        
        DB::table('k_r_s')->insert([
            'kode_krs' => $kode_krs,
            'mahasiswa_id' => $request->mahasiswa_id,
            'jadwal_id' => $request->jadwal_id,
            'tahun_akademik_id' => $request->tahun_akademik_id,
            'status' => 'pending',
            'tgl_krs' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return redirect()->route('krs.index')->with('success', 'KRS berhasil ditambahkan');
    }

    public function show($id)
    {
        $krs = DB::table('k_r_s')
            ->join('mahasiswas', 'k_r_s.mahasiswa_id', '=', 'mahasiswas.id')
            ->join('jadwals', 'k_r_s.jadwal_id', '=', 'jadwals.id')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->join('dosens', 'jadwals.dosen_id', '=', 'dosens.id')
            ->join('tahun_akademiks', 'k_r_s.tahun_akademik_id', '=', 'tahun_akademiks.id')
            ->where('k_r_s.id', $id)
            ->select(
                'k_r_s.*',
                'mahasiswas.nim',
                'mahasiswas.nama_mahasiswa',
                'matakuliahs.nama_mk',
                'matakuliahs.sks',
                'dosens.nama_dosen',
                'jadwals.hari',
                'jadwals.jam_mulai',
                'jadwals.jam_selesai',
                'jadwals.ruangan',
                'tahun_akademiks.tahun',
                'tahun_akademiks.semester'
            )
            ->first();
            
        if (!$krs) {
            abort(404);
        }
        
        return view('krs.show', compact('krs'));
    }

    // ... method lainnya (edit, update, destroy, approve, reject, cetak)
}