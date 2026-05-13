<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RuanganController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('ruangans');
        
        if ($request->has('search')) {
            $query->where('nama_ruangan', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_ruangan', 'like', '%' . $request->search . '%');
        }
        
        $ruangans = $query->orderBy('id', 'desc')->paginate(10);
        
        // Get usage statistics
        foreach ($ruangans as $ruangan) {
            $ruangan->total_penggunaan = DB::table('jadwals')
                ->where('ruangan', $ruangan->kode_ruangan)
                ->count();
        }
        
        return view('ruangan.index', compact('ruangans'));
    }

    public function create()
    {
        return view('ruangan.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_ruangan' => 'required|unique:ruangans|max:10',
            'nama_ruangan' => 'required|max:50',
            'kapasitas' => 'required|integer|min:1',
            'lokasi' => 'required|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::table('ruangans')->insert([
            'kode_ruangan' => strtoupper($request->kode_ruangan),
            'nama_ruangan' => $request->nama_ruangan,
            'kapasitas' => $request->kapasitas,
            'lokasi' => $request->lokasi,
            'fasilitas' => $request->fasilitas,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil ditambahkan');
    }

    public function show($id)
    {
        $ruangan = DB::table('ruangans')->where('id', $id)->first();
        if (!$ruangan) {
            abort(404);
        }
        
        $jadwals = DB::table('jadwals')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->join('dosens', 'jadwals.dosen_id', '=', 'dosens.id')
            ->where('jadwals.ruangan', $ruangan->kode_ruangan)
            ->select('jadwals.*', 'matakuliahs.nama_mk', 'dosens.nama_dosen')
            ->orderBy('jadwals.hari')
            ->orderBy('jadwals.jam_mulai')
            ->get();
            
        return view('ruangan.show', compact('ruangan', 'jadwals'));
    }

    public function edit($id)
    {
        $ruangan = DB::table('ruangans')->where('id', $id)->first();
        if (!$ruangan) {
            abort(404);
        }
        return view('ruangan.edit', compact('ruangan'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode_ruangan' => 'required|max:10|unique:ruangans,kode_ruangan,' . $id,
            'nama_ruangan' => 'required|max:50',
            'kapasitas' => 'required|integer|min:1',
            'lokasi' => 'required|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::table('ruangans')->where('id', $id)->update([
            'kode_ruangan' => strtoupper($request->kode_ruangan),
            'nama_ruangan' => $request->nama_ruangan,
            'kapasitas' => $request->kapasitas,
            'lokasi' => $request->lokasi,
            'fasilitas' => $request->fasilitas,
            'updated_at' => now(),
        ]);

        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil diupdate');
    }

    public function destroy($id)
    {
        $ruangan = DB::table('ruangans')->where('id', $id)->first();
        
        // Check if room is used in jadwal
        $jadwalCount = DB::table('jadwals')->where('ruangan', $ruangan->kode_ruangan)->count();
        
        if ($jadwalCount > 0) {
            return redirect()->back()->with('error', 'Ruangan tidak dapat dihapus karena masih digunakan dalam jadwal');
        }
        
        DB::table('ruangans')->where('id', $id)->delete();
        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil dihapus');
    }
}