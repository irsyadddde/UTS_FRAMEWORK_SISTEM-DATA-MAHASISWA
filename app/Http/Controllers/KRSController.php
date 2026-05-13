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
        
        if ($request->has('search')) {
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
        $activeYear = DB::table('tahun_akademiks')->where('is_active', true)->first();
        
        if (!$activeYear) {
            return redirect()->back()->with('error', 'Silahkan aktifkan tahun akademik terlebih dahulu');
        }
        
        return view('krs.create', compact('mahasiswas', 'activeYear'));
    }
    
    // Check schedule conflict for student
    private function checkScheduleConflict($mahasiswa_id, $jadwal_id, $exclude_krs_id = null)
    {
        $jadwal = DB::table('jadwals')->where('id', $jadwal_id)->first();
        if (!$jadwal) return false;
        
        $query = DB::table('k_r_s')
            ->join('jadwals', 'k_r_s.jadwal_id', '=', 'jadwals.id')
            ->where('k_r_s.mahasiswa_id', $mahasiswa_id)
            ->where('jadwals.hari', $jadwal->hari)
            ->where(function($q) use ($jadwal) {
                $q->whereBetween('jadwals.jam_mulai', [$jadwal->jam_mulai, $jadwal->jam_selesai])
                  ->orWhereBetween('jadwals.jam_selesai', [$jadwal->jam_mulai, $jadwal->jam_selesai])
                  ->orWhere(function($sub) use ($jadwal) {
                      $sub->where('jadwals.jam_mulai', '<=', $jadwal->jam_mulai)
                           ->where('jadwals.jam_selesai', '>=', $jadwal->jam_selesai);
                  });
            });
        
        if ($exclude_krs_id) {
            $query->where('k_r_s.id', '!=', $exclude_krs_id);
        }
        
        return $query->exists();
    }
    
    // Check if student already took this subject
    private function checkDuplicateSubject($mahasiswa_id, $jadwal_id, $exclude_krs_id = null)
    {
        $jadwal = DB::table('jadwals')->where('id', $jadwal_id)->first();
        
        $query = DB::table('k_r_s')
            ->join('jadwals', 'k_r_s.jadwal_id', '=', 'jadwals.id')
            ->where('k_r_s.mahasiswa_id', $mahasiswa_id)
            ->where('jadwals.matakuliah_id', $jadwal->matakuliah_id);
        
        if ($exclude_krs_id) {
            $query->where('k_r_s.id', '!=', $exclude_krs_id);
        }
        
        return $query->exists();
    }
    
    // Calculate total SKS for student
    private function getTotalSKS($mahasiswa_id, $status = 'approved')
    {
        return DB::table('k_r_s')
            ->join('jadwals', 'k_r_s.jadwal_id', '=', 'jadwals.id')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->where('k_r_s.mahasiswa_id', $mahasiswa_id)
            ->where('k_r_s.status', $status)
            ->sum('matakuliahs.sks');
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
        
        // Check schedule conflict
        if ($this->checkScheduleConflict($request->mahasiswa_id, $request->jadwal_id)) {
            return redirect()->back()->with('error', 'Jadwal bentrok dengan mata kuliah lain!')->withInput();
        }
        
        // Check duplicate subject
        if ($this->checkDuplicateSubject($request->mahasiswa_id, $request->jadwal_id)) {
            return redirect()->back()->with('error', 'Mahasiswa sudah mengambil mata kuliah ini!')->withInput();
        }
        
        // Check max SKS (max 24 SKS per semester)
        $currentSKS = $this->getTotalSKS($request->mahasiswa_id);
        $jadwal = DB::table('jadwals')->where('id', $request->jadwal_id)->first();
        $matakuliah = DB::table('matakuliahs')->where('id', $jadwal->matakuliah_id)->first();
        
        if (($currentSKS + $matakuliah->sks) > 24) {
            return redirect()->back()->with('error', 'Total SKS melebihi batas maksimal 24 SKS!')->withInput();
        }

        // Generate KRS code
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
        
        // Create Nilai record
        $krs_id = DB::getPdo()->lastInsertId();
        DB::table('nilais')->insert([
            'krs_id' => $krs_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('krs.index')->with('success', 'KRS berhasil ditambahkan, menunggu persetujuan');
    }
    
    public function approve($id)
    {
        DB::table('k_r_s')->where('id', $id)->update([
            'status' => 'approved',
            'updated_at' => now(),
        ]);
        
        return redirect()->back()->with('success', 'KRS berhasil disetujui');
    }
    
    public function reject($id)
    {
        DB::table('k_r_s')->where('id', $id)->update([
            'status' => 'rejected',
            'updated_at' => now(),
        ]);
        
        return redirect()->back()->with('success', 'KRS ditolak');
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

    public function destroy($id)
    {
        // Delete nilai first
        DB::table('nilais')->where('krs_id', $id)->delete();
        DB::table('k_r_s')->where('id', $id)->delete();
        
        return redirect()->route('krs.index')->with('success', 'KRS berhasil dihapus');
    }
    
    public function cetak($id)
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
            
        // Get all KRS for this student in same semester
        $allKRS = DB::table('k_r_s')
            ->join('jadwals', 'k_r_s.jadwal_id', '=', 'jadwals.id')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->where('k_r_s.mahasiswa_id', $krs->mahasiswa_id)
            ->where('k_r_s.tahun_akademik_id', $krs->tahun_akademik_id)
            ->where('k_r_s.status', 'approved')
            ->select('matakuliahs.nama_mk', 'matakuliahs.sks', 'jadwals.*')
            ->get();
            
        $totalSKS = $allKRS->sum('sks');
        
        return view('krs.cetak', compact('krs', 'allKRS', 'totalSKS'));
    }
}