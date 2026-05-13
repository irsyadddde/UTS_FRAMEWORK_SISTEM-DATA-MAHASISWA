<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JurusanController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('jurusans');
        
        if ($request->has('search')) {
            $query->where('nama_jurusan', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_jurusan', 'like', '%' . $request->search . '%');
        }
        
        $jurusans = $query->orderBy('id', 'desc')->paginate(10);
        
        // Get jumlah mahasiswa per jurusan
        foreach ($jurusans as $jurusan) {
            $jurusan->total_mahasiswa = DB::table('mahasiswas')
                ->where('jurusan_id', $jurusan->id)->count();
            $jurusan->total_matakuliah = DB::table('matakuliahs')
                ->where('jurusan_id', $jurusan->id)->count();
        }
        
        return view('jurusan.index', compact('jurusans'));
    }

    public function create()
    {
        return view('jurusan.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_jurusan' => 'required|unique:jurusans|max:10',
            'nama_jurusan' => 'required|max:100',
            'deskripsi' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::table('jurusans')->insert([
            'kode_jurusan' => strtoupper($request->kode_jurusan),
            'nama_jurusan' => $request->nama_jurusan,
            'deskripsi' => $request->deskripsi,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('jurusan.index')->with('success', 'Jurusan berhasil ditambahkan');
    }

    public function show($id)
    {
        $jurusan = DB::table('jurusans')->where('id', $id)->first();
        if (!$jurusan) {
            abort(404);
        }
        
        $mahasiswas = DB::table('mahasiswas')
            ->where('jurusan_id', $id)
            ->limit(10)
            ->get();
            
        $matakuliahs = DB::table('matakuliahs')
            ->where('jurusan_id', $id)
            ->get();
            
        $statistik = [
            'total_mahasiswa' => DB::table('mahasiswas')->where('jurusan_id', $id)->count(),
            'total_matakuliah' => DB::table('matakuliahs')->where('jurusan_id', $id)->count(),
            'total_dosen' => DB::table('jadwals')
                ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
                ->where('matakuliahs.jurusan_id', $id)
                ->distinct('jadwals.dosen_id')
                ->count('jadwals.dosen_id'),
        ];
        
        return view('jurusan.show', compact('jurusan', 'mahasiswas', 'matakuliahs', 'statistik'));
    }

    public function edit($id)
    {
        $jurusan = DB::table('jurusans')->where('id', $id)->first();
        if (!$jurusan) {
            abort(404);
        }
        return view('jurusan.edit', compact('jurusan'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode_jurusan' => 'required|max:10|unique:jurusans,kode_jurusan,' . $id,
            'nama_jurusan' => 'required|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::table('jurusans')->where('id', $id)->update([
            'kode_jurusan' => strtoupper($request->kode_jurusan),
            'nama_jurusan' => $request->nama_jurusan,
            'deskripsi' => $request->deskripsi,
            'updated_at' => now(),
        ]);

        return redirect()->route('jurusan.index')->with('success', 'Jurusan berhasil diupdate');
    }

    public function destroy($id)
    {
        // Check if jurusan has related data
        $mahasiswaCount = DB::table('mahasiswas')->where('jurusan_id', $id)->count();
        $matakuliahCount = DB::table('matakuliahs')->where('jurusan_id', $id)->count();
        
        if ($mahasiswaCount > 0 || $matakuliahCount > 0) {
            return redirect()->back()->with('error', 'Jurusan tidak dapat dihapus karena masih memiliki data terkait');
        }
        
        DB::table('jurusans')->where('id', $id)->delete();
        return redirect()->route('jurusan.index')->with('success', 'Jurusan berhasil dihapus');
    }
}