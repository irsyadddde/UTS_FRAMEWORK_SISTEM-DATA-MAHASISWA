<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('kelas')
            ->join('jurusans', 'kelas.jurusan_id', '=', 'jurusans.id')
            ->select('kelas.*', 'jurusans.nama_jurusan');
        
        if ($request->has('search')) {
            $query->where('kelas.nama_kelas', 'like', '%' . $request->search . '%')
                  ->orWhere('kelas.kode_kelas', 'like', '%' . $request->search . '%');
        }
        
        $kelas = $query->orderBy('kelas.id', 'desc')->paginate(10);
        return view('kelas.index', compact('kelas'));
    }

    public function create()
    {
        $jurusans = DB::table('jurusans')->get();
        return view('kelas.create', compact('jurusans'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_kelas' => 'required|unique:kelas|max:10',
            'nama_kelas' => 'required|max:50',
            'jurusan_id' => 'required|exists:jurusans,id',
            'angkatan' => 'required|digits:4',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::table('kelas')->insert([
            'kode_kelas' => strtoupper($request->kode_kelas),
            'nama_kelas' => $request->nama_kelas,
            'jurusan_id' => $request->jurusan_id,
            'angkatan' => $request->angkatan,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan');
    }

    public function show($id)
    {
        $kelas = DB::table('kelas')
            ->join('jurusans', 'kelas.jurusan_id', '=', 'jurusans.id')
            ->where('kelas.id', $id)
            ->select('kelas.*', 'jurusans.nama_jurusan')
            ->first();
            
        if (!$kelas) {
            abort(404);
        }
        
        $mahasiswas = DB::table('mahasiswas')
            ->where('jurusan_id', $kelas->jurusan_id)
            ->where('angkatan', $kelas->angkatan)
            ->get();
            
        return view('kelas.show', compact('kelas', 'mahasiswas'));
    }

    public function edit($id)
    {
        $kelas = DB::table('kelas')->where('id', $id)->first();
        if (!$kelas) {
            abort(404);
        }
        $jurusans = DB::table('jurusans')->get();
        return view('kelas.edit', compact('kelas', 'jurusans'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode_kelas' => 'required|max:10|unique:kelas,kode_kelas,' . $id,
            'nama_kelas' => 'required|max:50',
            'jurusan_id' => 'required|exists:jurusans,id',
            'angkatan' => 'required|digits:4',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::table('kelas')->where('id', $id)->update([
            'kode_kelas' => strtoupper($request->kode_kelas),
            'nama_kelas' => $request->nama_kelas,
            'jurusan_id' => $request->jurusan_id,
            'angkatan' => $request->angkatan,
            'updated_at' => now(),
        ]);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diupdate');
    }

    public function destroy($id)
    {
        DB::table('kelas')->where('id', $id)->delete();
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus');
    }
}