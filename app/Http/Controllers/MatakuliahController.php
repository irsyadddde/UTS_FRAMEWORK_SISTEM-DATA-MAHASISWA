<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MatakuliahController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('matakuliahs')
            ->join('jurusans', 'matakuliahs.jurusan_id', '=', 'jurusans.id')
            ->select('matakuliahs.*', 'jurusans.nama_jurusan');
        
        if ($request->has('search')) {
            $query->where('matakuliahs.nama_mk', 'like', '%' . $request->search . '%')
                  ->orWhere('matakuliahs.kode_mk', 'like', '%' . $request->search . '%');
        }
        
        if ($request->has('jurusan_id') && $request->jurusan_id != '') {
            $query->where('matakuliahs.jurusan_id', $request->jurusan_id);
        }
        
        $matakuliahs = $query->orderBy('matakuliahs.id', 'desc')->paginate(10);
        $jurusans = DB::table('jurusans')->get();
        
        return view('matakuliah.index', compact('matakuliahs', 'jurusans'));
    }

    public function create()
    {
        $jurusans = DB::table('jurusans')->get();
        return view('matakuliah.create', compact('jurusans'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_mk' => 'required|unique:matakuliahs|max:10',
            'nama_mk' => 'required|max:100',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8',
            'jurusan_id' => 'required|exists:jurusans,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::table('matakuliahs')->insert([
            'kode_mk' => strtoupper($request->kode_mk),
            'nama_mk' => $request->nama_mk,
            'sks' => $request->sks,
            'semester' => $request->semester,
            'jurusan_id' => $request->jurusan_id,
            'deskripsi' => $request->deskripsi,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('matakuliah.index')->with('success', 'Mata Kuliah berhasil ditambahkan');
    }

    public function show($id)
    {
        $matakuliah = DB::table('matakuliahs')
            ->join('jurusans', 'matakuliahs.jurusan_id', '=', 'jurusans.id')
            ->where('matakuliahs.id', $id)
            ->select('matakuliahs.*', 'jurusans.nama_jurusan')
            ->first();
            
        if (!$matakuliah) {
            abort(404);
        }
        
        $jadwals = DB::table('jadwals')
            ->join('dosens', 'jadwals.dosen_id', '=', 'dosens.id')
            ->where('jadwals.matakuliah_id', $id)
            ->select('jadwals.*', 'dosens.nama_dosen')
            ->get();
            
        $statistik = [
            'total_jadwal' => $jadwals->count(),
            'total_dosen' => $jadwals->unique('dosen_id')->count(),
            'total_mahasiswa' => DB::table('jadwals')
                ->join('k_r_s', 'jadwals.id', '=', 'k_r_s.jadwal_id')
                ->where('jadwals.matakuliah_id', $id)
                ->distinct('k_r_s.mahasiswa_id')
                ->count('k_r_s.mahasiswa_id'),
            'rata_rata_nilai' => DB::table('nilais')
                ->join('k_r_s', 'nilais.krs_id', '=', 'k_r_s.id')
                ->join('jadwals', 'k_r_s.jadwal_id', '=', 'jadwals.id')
                ->where('jadwals.matakuliah_id', $id)
                ->avg('nilai_akhir'),
        ];
        
        return view('matakuliah.show', compact('matakuliah', 'jadwals', 'statistik'));
    }

    public function edit($id)
    {
        $matakuliah = DB::table('matakuliahs')->where('id', $id)->first();
        if (!$matakuliah) {
            abort(404);
        }
        $jurusans = DB::table('jurusans')->get();
        return view('matakuliah.edit', compact('matakuliah', 'jurusans'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode_mk' => 'required|max:10|unique:matakuliahs,kode_mk,' . $id,
            'nama_mk' => 'required|max:100',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8',
            'jurusan_id' => 'required|exists:jurusans,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::table('matakuliahs')->where('id', $id)->update([
            'kode_mk' => strtoupper($request->kode_mk),
            'nama_mk' => $request->nama_mk,
            'sks' => $request->sks,
            'semester' => $request->semester,
            'jurusan_id' => $request->jurusan_id,
            'deskripsi' => $request->deskripsi,
            'updated_at' => now(),
        ]);

        return redirect()->route('matakuliah.index')->with('success', 'Mata Kuliah berhasil diupdate');
    }

    public function destroy($id)
    {
        // Check if matakuliah has jadwal
        $jadwalCount = DB::table('jadwals')->where('matakuliah_id', $id)->count();
        
        if ($jadwalCount > 0) {
            return redirect()->back()->with('error', 'Mata Kuliah tidak dapat dihapus karena masih memiliki jadwal');
        }
        
        DB::table('matakuliahs')->where('id', $id)->delete();
        return redirect()->route('matakuliah.index')->with('success', 'Mata Kuliah berhasil dihapus');
    }
}