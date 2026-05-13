<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DosenController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('dosens');
        
        if ($request->has('search')) {
            $query->where('nama_dosen', 'like', '%' . $request->search . '%')
                  ->orWhere('nidn', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }
        
        $dosens = $query->orderBy('id', 'desc')->paginate(10);
        
        // Get statistics for each dosen
        foreach ($dosens as $dosen) {
            $dosen->total_jadwal = DB::table('jadwals')
                ->where('dosen_id', $dosen->id)->count();
            $dosen->total_mahasiswa = DB::table('jadwals')
                ->join('k_r_s', 'jadwals.id', '=', 'k_r_s.jadwal_id')
                ->where('jadwals.dosen_id', $dosen->id)
                ->distinct('k_r_s.mahasiswa_id')
                ->count('k_r_s.mahasiswa_id');
        }
        
        return view('dosen.index', compact('dosens'));
    }

    public function create()
    {
        return view('dosen.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nidn' => 'required|unique:dosens|max:20',
            'nama_dosen' => 'required|max:100',
            'email' => 'required|email|unique:dosens',
            'no_telp' => 'nullable|max:15',
            'alamat' => 'nullable',
            'pendidikan_terakhir' => 'nullable|max:50',
            'jabatan' => 'nullable|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::table('dosens')->insert([
            'nidn' => $request->nidn,
            'nama_dosen' => $request->nama_dosen,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'pendidikan_terakhir' => $request->pendidikan_terakhir,
            'jabatan' => $request->jabatan,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Also create user account for this lecturer
        DB::table('users')->insert([
            'name' => $request->nama_dosen,
            'email' => $request->email,
            'password' => Hash::make('dosen123'),
            'role' => 'dosen',
            'dosen_id' => DB::getPdo()->lastInsertId(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('dosen.index')->with('success', 'Dosen berhasil ditambahkan');
    }

    public function show($id)
    {
        $dosen = DB::table('dosens')->where('id', $id)->first();
        if (!$dosen) {
            abort(404);
        }
        
        $jadwals = DB::table('jadwals')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->where('jadwals.dosen_id', $id)
            ->select('jadwals.*', 'matakuliahs.nama_mk', 'matakuliahs.sks')
            ->get();
            
        $statistik = [
            'total_jadwal' => $jadwals->count(),
            'total_matakuliah' => $jadwals->unique('matakuliah_id')->count(),
            'total_mahasiswa' => DB::table('jadwals')
                ->join('k_r_s', 'jadwals.id', '=', 'k_r_s.jadwal_id')
                ->where('jadwals.dosen_id', $id)
                ->distinct('k_r_s.mahasiswa_id')
                ->count('k_r_s.mahasiswa_id'),
            'rata_rata_kehadiran' => DB::table('absensis')
                ->join('jadwals', 'absensis.jadwal_id', '=', 'jadwals.id')
                ->where('jadwals.dosen_id', $id)
                ->where('absensis.status', 'hadir')
                ->count() / max(DB::table('absensis')
                ->join('jadwals', 'absensis.jadwal_id', '=', 'jadwals.id')
                ->where('jadwals.dosen_id', $id)->count(), 1) * 100,
        ];
        
        return view('dosen.show', compact('dosen', 'jadwals', 'statistik'));
    }

    public function edit($id)
    {
        $dosen = DB::table('dosens')->where('id', $id)->first();
        if (!$dosen) {
            abort(404);
        }
        return view('dosen.edit', compact('dosen'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nidn' => 'required|max:20|unique:dosens,nidn,' . $id,
            'nama_dosen' => 'required|max:100',
            'email' => 'required|email|unique:dosens,email,' . $id,
            'no_telp' => 'nullable|max:15',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::table('dosens')->where('id', $id)->update([
            'nidn' => $request->nidn,
            'nama_dosen' => $request->nama_dosen,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'pendidikan_terakhir' => $request->pendidikan_terakhir,
            'jabatan' => $request->jabatan,
            'updated_at' => now(),
        ]);

        // Update user account
        DB::table('users')->where('dosen_id', $id)->where('role', 'dosen')->update([
            'name' => $request->nama_dosen,
            'email' => $request->email,
            'updated_at' => now(),
        ]);

        return redirect()->route('dosen.index')->with('success', 'Dosen berhasil diupdate');
    }

    public function destroy($id)
    {
        // Check if dosen has jadwal
        $jadwalCount = DB::table('jadwals')->where('dosen_id', $id)->count();
        
        if ($jadwalCount > 0) {
            return redirect()->back()->with('error', 'Dosen tidak dapat dihapus karena masih memiliki jadwal mengajar');
        }
        
        // Delete user account
        DB::table('users')->where('dosen_id', $id)->where('role', 'dosen')->delete();
        
        DB::table('dosens')->where('id', $id)->delete();
        return redirect()->route('dosen.index')->with('success', 'Dosen berhasil dihapus');
    }
}