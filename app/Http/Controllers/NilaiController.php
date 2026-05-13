<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NilaiController extends Controller
{
    private function calculateGrade($nilai_akhir)
    {
        if ($nilai_akhir >= 85) return 'A';
        if ($nilai_akhir >= 80) return 'A-';
        if ($nilai_akhir >= 75) return 'B+';
        if ($nilai_akhir >= 70) return 'B';
        if ($nilai_akhir >= 65) return 'B-';
        if ($nilai_akhir >= 60) return 'C+';
        if ($nilai_akhir >= 55) return 'C';
        if ($nilai_akhir >= 50) return 'D';
        return 'E';
    }
    
    private function calculateNilaiAkhir($nilai_tugas, $nilai_uts, $nilai_uas)
    {
        // Weight: Tugas 30%, UTS 30%, UAS 40%
        return ($nilai_tugas * 0.3) + ($nilai_uts * 0.3) + ($nilai_uas * 0.4);
    }

    public function index(Request $request)
    {
        $query = DB::table('nilais')
            ->join('k_r_s', 'nilais.krs_id', '=', 'k_r_s.id')
            ->join('mahasiswas', 'k_r_s.mahasiswa_id', '=', 'mahasiswas.id')
            ->join('jadwals', 'k_r_s.jadwal_id', '=', 'jadwals.id')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->join('tahun_akademiks', 'k_r_s.tahun_akademik_id', '=', 'tahun_akademiks.id')
            ->select(
                'nilais.*',
                'mahasiswas.nim',
                'mahasiswas.nama_mahasiswa',
                'matakuliahs.nama_mk',
                'matakuliahs.sks',
                'tahun_akademiks.tahun',
                'tahun_akademiks.semester',
                'k_r_s.status'
            );
        
        if ($request->has('search')) {
            $query->where('mahasiswas.nama_mahasiswa', 'like', '%' . $request->search . '%')
                  ->orWhere('mahasiswas.nim', 'like', '%' . $request->search . '%');
        }
        
        if ($request->has('matakuliah_id') && $request->matakuliah_id != '') {
            $query->where('jadwals.matakuliah_id', $request->matakuliah_id);
        }
        
        if ($request->has('tahun_akademik_id') && $request->tahun_akademik_id != '') {
            $query->where('k_r_s.tahun_akademik_id', $request->tahun_akademik_id);
        }
        
        $nilais = $query->where('k_r_s.status', 'approved')
                       ->orderBy('nilais.id', 'desc')
                       ->paginate(10);
                       
        $matakuliahs = DB::table('matakuliahs')->get();
        $tahunAkademiks = DB::table('tahun_akademiks')->get();
        
        return view('nilai.index', compact('nilais', 'matakuliahs', 'tahunAkademiks'));
    }

    public function create()
    {
        // Get KRS that have been approved but don't have nilai yet
        $krsList = DB::table('k_r_s')
            ->join('mahasiswas', 'k_r_s.mahasiswa_id', '=', 'mahasiswas.id')
            ->join('jadwals', 'k_r_s.jadwal_id', '=', 'jadwals.id')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->leftJoin('nilais', 'k_r_s.id', '=', 'nilais.krs_id')
            ->where('k_r_s.status', 'approved')
            ->whereNull('nilais.nilai_tugas')
            ->select(
                'k_r_s.id as krs_id',
                'mahasiswas.nim',
                'mahasiswas.nama_mahasiswa',
                'matakuliahs.nama_mk'
            )
            ->get();
            
        return view('nilai.create', compact('krsList'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'krs_id' => 'required|exists:k_r_s,id',
            'nilai_tugas' => 'nullable|numeric|min:0|max:100',
            'nilai_uts' => 'nullable|numeric|min:0|max:100',
            'nilai_uas' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $nilai_tugas = $request->nilai_tugas ?? 0;
        $nilai_uts = $request->nilai_uts ?? 0;
        $nilai_uas = $request->nilai_uas ?? 0;
        
        $nilai_akhir = $this->calculateNilaiAkhir($nilai_tugas, $nilai_uts, $nilai_uas);
        $grade = $this->calculateGrade($nilai_akhir);
        
        // Check if nilai already exists
        $existing = DB::table('nilais')->where('krs_id', $request->krs_id)->first();
        
        if ($existing) {
            DB::table('nilais')->where('krs_id', $request->krs_id)->update([
                'nilai_tugas' => $nilai_tugas,
                'nilai_uts' => $nilai_uts,
                'nilai_uas' => $nilai_uas,
                'nilai_akhir' => $nilai_akhir,
                'grade' => $grade,
                'updated_at' => now(),
            ]);
        } else {
            DB::table('nilais')->insert([
                'krs_id' => $request->krs_id,
                'nilai_tugas' => $nilai_tugas,
                'nilai_uts' => $nilai_uts,
                'nilai_uas' => $nilai_uas,
                'nilai_akhir' => $nilai_akhir,
                'grade' => $grade,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('nilai.index')->with('success', 'Nilai berhasil disimpan');
    }

    public function edit($id)
    {
        $nilai = DB::table('nilais')
            ->join('k_r_s', 'nilais.krs_id', '=', 'k_r_s.id')
            ->join('mahasiswas', 'k_r_s.mahasiswa_id', '=', 'mahasiswas.id')
            ->join('jadwals', 'k_r_s.jadwal_id', '=', 'jadwals.id')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->where('nilais.id', $id)
            ->select('nilais.*', 'mahasiswas.nim', 'mahasiswas.nama_mahasiswa', 'matakuliahs.nama_mk')
            ->first();
            
        if (!$nilai) {
            abort(404);
        }
        
        return view('nilai.edit', compact('nilai'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nilai_tugas' => 'nullable|numeric|min:0|max:100',
            'nilai_uts' => 'nullable|numeric|min:0|max:100',
            'nilai_uas' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $nilai_tugas = $request->nilai_tugas ?? 0;
        $nilai_uts = $request->nilai_uts ?? 0;
        $nilai_uas = $request->nilai_uas ?? 0;
        
        $nilai_akhir = $this->calculateNilaiAkhir($nilai_tugas, $nilai_uts, $nilai_uas);
        $grade = $this->calculateGrade($nilai_akhir);
        
        DB::table('nilais')->where('id', $id)->update([
            'nilai_tugas' => $nilai_tugas,
            'nilai_uts' => $nilai_uts,
            'nilai_uas' => $nilai_uas,
            'nilai_akhir' => $nilai_akhir,
            'grade' => $grade,
            'updated_at' => now(),
        ]);

        return redirect()->route('nilai.index')->with('success', 'Nilai berhasil diupdate');
    }
    
    public function show($id)
    {
        $nilai = DB::table('nilais')
            ->join('k_r_s', 'nilais.krs_id', '=', 'k_r_s.id')
            ->join('mahasiswas', 'k_r_s.mahasiswa_id', '=', 'mahasiswas.id')
            ->join('jadwals', 'k_r_s.jadwal_id', '=', 'jadwals.id')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->join('dosens', 'jadwals.dosen_id', '=', 'dosens.id')
            ->join('tahun_akademiks', 'k_r_s.tahun_akademik_id', '=', 'tahun_akademiks.id')
            ->where('nilais.id', $id)
            ->select(
                'nilais.*',
                'mahasiswas.nim',
                'mahasiswas.nama_mahasiswa',
                'matakuliahs.nama_mk',
                'matakuliahs.sks',
                'dosens.nama_dosen',
                'tahun_akademiks.tahun',
                'tahun_akademiks.semester'
            )
            ->first();
            
        if (!$nilai) {
            abort(404);
        }
        
        return view('nilai.show', compact('nilai'));
    }
    
    public function transkrip($mahasiswa_id)
    {
        $mahasiswa = DB::table('mahasiswas')
            ->join('jurusans', 'mahasiswas.jurusan_id', '=', 'jurusans.id')
            ->where('mahasiswas.id', $mahasiswa_id)
            ->select('mahasiswas.*', 'jurusans.nama_jurusan')
            ->first();
            
        if (!$mahasiswa) {
            abort(404);
        }
        
        $nilais = DB::table('nilais')
            ->join('k_r_s', 'nilais.krs_id', '=', 'k_r_s.id')
            ->join('jadwals', 'k_r_s.jadwal_id', '=', 'jadwals.id')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->join('tahun_akademiks', 'k_r_s.tahun_akademik_id', '=', 'tahun_akademiks.id')
            ->where('k_r_s.mahasiswa_id', $mahasiswa_id)
            ->whereNotNull('nilais.nilai_akhir')
            ->select(
                'nilais.*',
                'matakuliahs.nama_mk',
                'matakuliahs.sks',
                'tahun_akademiks.tahun',
                'tahun_akademiks.semester'
            )
            ->orderBy('tahun_akademiks.id')
            ->get();
            
        $totalSKS = $nilais->sum('sks');
        $totalNilai = 0;
        foreach ($nilais as $n) {
            $bobot = $this->getBobotGrade($n->grade);
            $totalNilai += $bobot * $n->sks;
        }
        $ipk = $totalSKS > 0 ? round($totalNilai / $totalSKS, 2) : 0;
        
        return view('nilai.transkrip', compact('mahasiswa', 'nilais', 'totalSKS', 'ipk'));
    }
    
    private function getBobotGrade($grade)
    {
        $bobot = [
            'A' => 4.0,
            'A-' => 3.7,
            'B+' => 3.3,
            'B' => 3.0,
            'B-' => 2.7,
            'C+' => 2.3,
            'C' => 2.0,
            'D' => 1.0,
            'E' => 0.0,
        ];
        return $bobot[$grade] ?? 0;
    }
}