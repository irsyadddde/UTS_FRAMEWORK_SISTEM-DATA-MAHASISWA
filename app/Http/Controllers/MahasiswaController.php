<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('mahasiswas')
            ->join('jurusans', 'mahasiswas.jurusan_id', '=', 'jurusans.id')
            ->select('mahasiswas.*', 'jurusans.nama_jurusan');
        
        if ($request->has('search')) {
            $query->where('mahasiswas.nama_mahasiswa', 'like', '%' . $request->search . '%')
                  ->orWhere('mahasiswas.nim', 'like', '%' . $request->search . '%');
        }
        
        $mahasiswas = $query->paginate(10);
        return view('mahasiswa.index', compact('mahasiswas'));
    }

    public function create()
    {
        $jurusans = DB::table('jurusans')->get();
        return view('mahasiswa.create', compact('jurusans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|unique:mahasiswas',
            'nama_mahasiswa' => 'required',
            'jurusan_id' => 'required',
            'angkatan' => 'required',
            'email' => 'required|email|unique:mahasiswas',
        ]);

        DB::table('mahasiswas')->insert([
            'nim' => $request->nim,
            'nama_mahasiswa' => $request->nama_mahasiswa,
            'jurusan_id' => $request->jurusan_id,
            'angkatan' => $request->angkatan,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa berhasil ditambahkan');
    }

    public function edit($id)
    {
        $mahasiswa = DB::table('mahasiswas')->where('id', $id)->first();
        $jurusans = DB::table('jurusans')->get();
        return view('mahasiswa.edit', compact('mahasiswa', 'jurusans'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nim' => 'required|unique:mahasiswas,nim,' . $id,
            'nama_mahasiswa' => 'required',
            'jurusan_id' => 'required',
            'angkatan' => 'required',
            'email' => 'required|email|unique:mahasiswas,email,' . $id,
        ]);

        DB::table('mahasiswas')->where('id', $id)->update([
            'nim' => $request->nim,
            'nama_mahasiswa' => $request->nama_mahasiswa,
            'jurusan_id' => $request->jurusan_id,
            'angkatan' => $request->angkatan,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'updated_at' => now(),
        ]);

        return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa berhasil diupdate');
    }

    public function destroy($id)
    {
        DB::table('mahasiswas')->where('id', $id)->delete();
        return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa berhasil dihapus');
    }

    public function show($id)
    {
        $mahasiswa = DB::table('mahasiswas')
            ->join('jurusans', 'mahasiswas.jurusan_id', '=', 'jurusans.id')
            ->where('mahasiswas.id', $id)
            ->select('mahasiswas.*', 'jurusans.nama_jurusan')
            ->first();
        
        $krs = DB::table('k_r_s')
            ->join('jadwals', 'k_r_s.jadwal_id', '=', 'jadwals.id')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->where('k_r_s.mahasiswa_id', $id)
            ->get();
            
        return view('mahasiswa.show', compact('mahasiswa', 'krs'));
    }
}