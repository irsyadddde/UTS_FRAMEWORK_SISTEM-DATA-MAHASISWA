<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('jadwals')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->join('dosens', 'jadwals.dosen_id', '=', 'dosens.id')
            ->select('jadwals.*', 'matakuliahs.nama_mk', 'matakuliahs.sks', 'dosens.nama_dosen');
        
        if ($request->has('hari') && $request->hari != '') {
            $query->where('jadwals.hari', $request->hari);
        }
        
        $jadwals = $query->orderBy('jadwals.hari')->orderBy('jadwals.jam_mulai')->paginate(10);
        $dosens = DB::table('dosens')->get();
        $matakuliahs = DB::table('matakuliahs')->get();
        
        return view('jadwal.index', compact('jadwals', 'dosens', 'matakuliahs'));
    }

    public function create()
    {
        $dosens = DB::table('dosens')->get();
        $matakuliahs = DB::table('matakuliahs')->get();
        $ruangans = DB::table('ruangans')->get();
        return view('jadwal.create', compact('dosens', 'matakuliahs', 'ruangans'));
    }

    private function checkJadwalConflict($hari, $jam_mulai, $jam_selesai, $dosen_id = null, $ruangan = null, $exclude_id = null)
    {
        $query = DB::table('jadwals')->where('hari', $hari);
        
        // Check time conflict
        $query->where(function($q) use ($jam_mulai, $jam_selesai) {
            $q->whereBetween('jam_mulai', [$jam_mulai, $jam_selesai])
              ->orWhereBetween('jam_selesai', [$jam_mulai, $jam_selesai])
              ->orWhere(function($sub) use ($jam_mulai, $jam_selesai) {
                  $sub->where('jam_mulai', '<=', $jam_mulai)
                       ->where('jam_selesai', '>=', $jam_selesai);
              });
        });
        
        if ($dosen_id) {
            $query->where('dosen_id', $dosen_id);
        }
        
        if ($ruangan) {
            $query->where('ruangan', $ruangan);
        }
        
        if ($exclude_id) {
            $query->where('id', '!=', $exclude_id);
        }
        
        return $query->exists();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'matakuliah_id' => 'required|exists:matakuliahs,id',
            'dosen_id' => 'required|exists:dosens,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'ruangan' => 'required|exists:ruangans,kode_ruangan',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Check for lecturer conflict
        if ($this->checkJadwalConflict($request->hari, $request->jam_mulai, $request->jam_selesai, $request->dosen_id, null)) {
            return redirect()->back()->with('error', 'Dosen sudah memiliki jadwal di waktu yang sama!')->withInput();
        }
        
        // Check for room conflict
        if ($this->checkJadwalConflict($request->hari, $request->jam_mulai, $request->jam_selesai, null, $request->ruangan)) {
            return redirect()->back()->with('error', 'Ruangan sudah digunakan di waktu yang sama!')->withInput();
        }

        DB::table('jadwals')->insert([
            'matakuliah_id' => $request->matakuliah_id,
            'dosen_id' => $request->dosen_id,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'ruangan' => $request->ruangan,
            'tahun_akademik_id' => $request->tahun_akademik_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function edit($id)
    {
        $jadwal = DB::table('jadwals')->where('id', $id)->first();
        if (!$jadwal) {
            abort(404);
        }
        $dosens = DB::table('dosens')->get();
        $matakuliahs = DB::table('matakuliahs')->get();
        $ruangans = DB::table('ruangans')->get();
        return view('jadwal.edit', compact('jadwal', 'dosens', 'matakuliahs', 'ruangans'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'matakuliah_id' => 'required|exists:matakuliahs,id',
            'dosen_id' => 'required|exists:dosens,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'ruangan' => 'required|exists:ruangans,kode_ruangan',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Check for lecturer conflict (excluding current)
        if ($this->checkJadwalConflict($request->hari, $request->jam_mulai, $request->jam_selesai, $request->dosen_id, null, $id)) {
            return redirect()->back()->with('error', 'Dosen sudah memiliki jadwal di waktu yang sama!')->withInput();
        }
        
        // Check for room conflict (excluding current)
        if ($this->checkJadwalConflict($request->hari, $request->jam_mulai, $request->jam_selesai, null, $request->ruangan, $id)) {
            return redirect()->back()->with('error', 'Ruangan sudah digunakan di waktu yang sama!')->withInput();
        }

        DB::table('jadwals')->where('id', $id)->update([
            'matakuliah_id' => $request->matakuliah_id,
            'dosen_id' => $request->dosen_id,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'ruangan' => $request->ruangan,
            'tahun_akademik_id' => $request->tahun_akademik_id,
            'updated_at' => now(),
        ]);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diupdate');
    }

    public function destroy($id)
    {
        // Check if jadwal has KRS
        $krsCount = DB::table('k_r_s')->where('jadwal_id', $id)->count();
        
        if ($krsCount > 0) {
            return redirect()->back()->with('error', 'Jadwal tidak dapat dihapus karena sudah ada KRS');
        }
        
        DB::table('jadwals')->where('id', $id)->delete();
        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus');
    }
    
    public function kalender()
    {
        $jadwals = DB::table('jadwals')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->join('dosens', 'jadwals.dosen_id', '=', 'dosens.id')
            ->select('jadwals.*', 'matakuliahs.nama_mk', 'dosens.nama_dosen')
            ->get();
            
        return view('jadwal.kalender', compact('jadwals'));
    }
}