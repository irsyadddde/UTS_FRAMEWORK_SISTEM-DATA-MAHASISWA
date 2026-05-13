<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('absensis')
            ->join('mahasiswas', 'absensis.mahasiswa_id', '=', 'mahasiswas.id')
            ->join('jadwals', 'absensis.jadwal_id', '=', 'jadwals.id')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->select(
                'absensis.*',
                'mahasiswas.nim',
                'mahasiswas.nama_mahasiswa',
                'matakuliahs.nama_mk',
                'jadwals.hari',
                'jadwals.jam_mulai',
                'jadwals.jam_selesai'
            );
        
        if ($request->has('search')) {
            $query->where('mahasiswas.nama_mahasiswa', 'like', '%' . $request->search . '%')
                  ->orWhere('mahasiswas.nim', 'like', '%' . $request->search . '%');
        }
        
        if ($request->has('tanggal') && $request->tanggal != '') {
            $query->where('absensis.tanggal', $request->tanggal);
        }
        
        $absensis = $query->orderBy('absensis.tanggal', 'desc')
                         ->orderBy('absensis.created_at', 'desc')
                         ->paginate(10);
        
        return view('absensi.index', compact('absensis'));
    }

    public function create()
    {
        $jadwals = DB::table('jadwals')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->join('dosens', 'jadwals.dosen_id', '=', 'dosens.id')
            ->select('jadwals.*', 'matakuliahs.nama_mk', 'dosens.nama_dosen')
            ->get();
            
        return view('absensi.create', compact('jadwals'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'jadwal_id' => 'required|exists:jadwals,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,izin,sakit,alpha',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        // Check if already absen today
        $existing = DB::table('absensis')
            ->where('mahasiswa_id', $request->mahasiswa_id)
            ->where('jadwal_id', $request->jadwal_id)
            ->where('tanggal', $request->tanggal)
            ->exists();
            
        if ($existing) {
            return redirect()->back()->with('error', 'Mahasiswa sudah melakukan absensi untuk jadwal ini hari ini!');
        }

        DB::table('absensis')->insert([
            'mahasiswa_id' => $request->mahasiswa_id,
            'jadwal_id' => $request->jadwal_id,
            'tanggal' => $request->tanggal,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil ditambahkan');
    }
    
    public function qrScan()
    {
        return view('absensi.scan');
    }
    
    public function generateQR($jadwal_id)
    {
        $jadwal = DB::table('jadwals')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->where('jadwals.id', $jadwal_id)
            ->select('jadwals.*', 'matakuliahs.nama_mk')
            ->first();
            
        if (!$jadwal) {
            abort(404);
        }
        
        // Generate unique token for this attendance session
        $token = base64_encode(json_encode([
            'jadwal_id' => $jadwal_id,
            'tanggal' => now()->format('Y-m-d'),
            'expires' => now()->addMinutes(15)->timestamp
        ]));
        
        $qrCode = QrCode::size(300)->generate(url("/absensi/verify/{$token}"));
        
        return view('absensi.qr-code', compact('qrCode', 'jadwal'));
    }
    
    public function verifyQR($token)
    {
        try {
            $data = json_decode(base64_decode($token), true);
            
            // Check expiration
            if ($data['expires'] < now()->timestamp) {
                return view('absensi.verify-result', ['success' => false, 'message' => 'QR Code sudah kadaluarsa!']);
            }
            
            $jadwal = DB::table('jadwals')
                ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
                ->where('jadwals.id', $data['jadwal_id'])
                ->select('jadwals.*', 'matakuliahs.nama_mk')
                ->first();
                
            return view('absensi.verify-form', compact('token', 'jadwal'));
            
        } catch (\Exception $e) {
            return view('absensi.verify-result', ['success' => false, 'message' => 'QR Code tidak valid!']);
        }
    }
    
    public function processQR(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nim' => 'required|exists:mahasiswas,nim',
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'NIM tidak ditemukan!']);
        }
        
        $data = json_decode(base64_decode($request->token), true);
        
        // Check expiration again
        if ($data['expires'] < now()->timestamp) {
            return response()->json(['success' => false, 'message' => 'QR Code sudah kadaluarsa!']);
        }
        
        $mahasiswa = DB::table('mahasiswas')->where('nim', $request->nim)->first();
        
        // Check if already absen today
        $existing = DB::table('absensis')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->where('jadwal_id', $data['jadwal_id'])
            ->where('tanggal', now()->format('Y-m-d'))
            ->exists();
            
        if ($existing) {
            return response()->json(['success' => false, 'message' => 'Anda sudah melakukan absensi hari ini!']);
        }
        
        // Save attendance
        DB::table('absensis')->insert([
            'mahasiswa_id' => $mahasiswa->id,
            'jadwal_id' => $data['jadwal_id'],
            'tanggal' => now()->format('Y-m-d'),
            'status' => 'hadir',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return response()->json(['success' => true, 'message' => 'Absensi berhasil!']);
    }
    
    public function laporan(Request $request)
    {
        $query = DB::table('absensis')
            ->join('mahasiswas', 'absensis.mahasiswa_id', '=', 'mahasiswas.id')
            ->join('jadwals', 'absensis.jadwal_id', '=', 'jadwals.id')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->select(
                'absensis.*',
                'mahasiswas.nim',
                'mahasiswas.nama_mahasiswa',
                'matakuliahs.nama_mk'
            );
        
        if ($request->has('start_date') && $request->start_date != '') {
            $query->where('absensis.tanggal', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date != '') {
            $query->where('absensis.tanggal', '<=', $request->end_date);
        }
        
        if ($request->has('matakuliah_id') && $request->matakuliah_id != '') {
            $query->where('jadwals.matakuliah_id', $request->matakuliah_id);
        }
        
        $absensis = $query->orderBy('absensis.tanggal', 'desc')->get();
        
        // Calculate statistics
        $statistik = [
            'total' => $absensis->count(),
            'hadir' => $absensis->where('status', 'hadir')->count(),
            'izin' => $absensis->where('status', 'izin')->count(),
            'sakit' => $absensis->where('status', 'sakit')->count(),
            'alpha' => $absensis->where('status', 'alpha')->count(),
        ];
        
        $matakuliahs = DB::table('matakuliahs')->get();
        
        return view('absensi.laporan', compact('absensis', 'statistik', 'matakuliahs'));
    }
    
    public function rekapitulasi($jadwal_id)
    {
        $jadwal = DB::table('jadwals')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->join('dosens', 'jadwals.dosen_id', '=', 'dosens.id')
            ->where('jadwals.id', $jadwal_id)
            ->select('jadwals.*', 'matakuliahs.nama_mk', 'dosens.nama_dosen')
            ->first();
            
        // Get all students enrolled in this jadwal
        $mahasiswas = DB::table('k_r_s')
            ->join('mahasiswas', 'k_r_s.mahasiswa_id', '=', 'mahasiswas.id')
            ->where('k_r_s.jadwal_id', $jadwal_id)
            ->where('k_r_s.status', 'approved')
            ->select('mahasiswas.*')
            ->get();
            
        // Get attendance data
        foreach ($mahasiswas as $mhs) {
            $mhs->hadir = DB::table('absensis')
                ->where('mahasiswa_id', $mhs->id)
                ->where('jadwal_id', $jadwal_id)
                ->where('status', 'hadir')
                ->count();
                
            $mhs->izin = DB::table('absensis')
                ->where('mahasiswa_id', $mhs->id)
                ->where('jadwal_id', $jadwal_id)
                ->where('status', 'izin')
                ->count();
                
            $mhs->sakit = DB::table('absensis')
                ->where('mahasiswa_id', $mhs->id)
                ->where('jadwal_id', $jadwal_id)
                ->where('status', 'sakit')
                ->count();
                
            $mhs->alpha = DB::table('absensis')
                ->where('mahasiswa_id', $mhs->id)
                ->where('jadwal_id', $jadwal_id)
                ->where('status', 'alpha')
                ->count();
                
            $mhs->total_pertemuan = $mhs->hadir + $mhs->izin + $mhs->sakit + $mhs->alpha;
            $mhs->persentase = $mhs->total_pertemuan > 0 ? round(($mhs->hadir / $mhs->total_pertemuan) * 100, 2) : 0;
        }
        
        return view('absensi.rekapitulasi', compact('jadwal', 'mahasiswas'));
    }

    public function edit($id)
    {
        $absensi = DB::table('absensis')->where('id', $id)->first();
        if (!$absensi) {
            abort(404);
        }
        $jadwals = DB::table('jadwals')
            ->join('matakuliahs', 'jadwals.matakuliah_id', '=', 'matakuliahs.id')
            ->select('jadwals.*', 'matakuliahs.nama_mk')
            ->get();
            
        return view('absensi.edit', compact('absensi', 'jadwals'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:hadir,izin,sakit,alpha',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::table('absensis')->where('id', $id)->update([
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'updated_at' => now(),
        ]);

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil diupdate');
    }

    public function destroy($id)
    {
        DB::table('absensis')->where('id', $id)->delete();
        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil dihapus');
    }
}