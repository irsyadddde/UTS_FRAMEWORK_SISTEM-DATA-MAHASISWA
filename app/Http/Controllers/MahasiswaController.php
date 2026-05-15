<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('mahasiswas')
            ->join('jurusans', 'mahasiswas.jurusan_id', '=', 'jurusans.id')
            ->select('mahasiswas.*', 'jurusans.nama_jurusan');
        
        if ($request->has('search') && $request->search != '') {
            $query->where('mahasiswas.nama_mahasiswa', 'like', '%' . $request->search . '%')
                  ->orWhere('mahasiswas.nim', 'like', '%' . $request->search . '%');
        }
        
        $mahasiswas = $query->orderBy('mahasiswas.id', 'desc')->paginate(10);
        return view('mahasiswa.index', compact('mahasiswas'));
    }

    public function create()
    {
        $jurusans = DB::table('jurusans')->get();
        return view('mahasiswa.create', compact('jurusans'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nim' => 'required|unique:mahasiswas|max:20',
            'nama_mahasiswa' => 'required|max:100',
            'jurusan_id' => 'required|exists:jurusans,id',
            'angkatan' => 'required|digits:4',
            'email' => 'required|email|unique:mahasiswas',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Simpan data
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

        // Redirect ke halaman index dengan pesan sukses
        return redirect('/mahasiswa')->with('success', 'Mahasiswa berhasil ditambahkan');
    }

    public function show($id)
    {
        $mahasiswa = DB::table('mahasiswas')
            ->join('jurusans', 'mahasiswas.jurusan_id', '=', 'jurusans.id')
            ->where('mahasiswas.id', $id)
            ->select('mahasiswas.*', 'jurusans.nama_jurusan')
            ->first();
        
        if (!$mahasiswa) {
            abort(404);
        }
        
        $krs = DB::table('k_r_s')
            ->join('jadwals', 'k_r_s.jadwal_id', '=', 'jadwals.id')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->where('k_r_s.mahasiswa_id', $id)
            ->select('matakuliahs.nama_mk', 'matakuliahs.sks', 'k_r_s.status')
            ->get();
            
        return view('mahasiswa.show', compact('mahasiswa', 'krs'));
    }

    public function edit($id)
    {
        $mahasiswa = DB::table('mahasiswas')->where('id', $id)->first();
        if (!$mahasiswa) {
            abort(404);
        }
        $jurusans = DB::table('jurusans')->get();
        return view('mahasiswa.edit', compact('mahasiswa', 'jurusans'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nim' => 'required|max:20|unique:mahasiswas,nim,' . $id,
            'nama_mahasiswa' => 'required|max:100',
            'jurusan_id' => 'required|exists:jurusans,id',
            'angkatan' => 'required|digits:4',
            'email' => 'required|email|unique:mahasiswas,email,' . $id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::table('mahasiswas')->where('id', $id)->update([
            'nim' => $request->nim,
            'nama_mahasiswa' => $request->nama_mahasiswa,
            'jurusan_id' => $request->jurusan_id,
            'angkatan' => $request->angkatan,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'updated_at' => now(),
        ]);

        return redirect('/mahasiswa')->with('success', 'Mahasiswa berhasil diupdate');
    }

    public function destroy($id)
    {
        DB::table('mahasiswas')->where('id', $id)->delete();
        return redirect('/mahasiswa')->with('success', 'Mahasiswa berhasil dihapus');
    }
}