<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMahasiswa = DB::table('mahasiswas')->count();
        $totalDosen = DB::table('dosens')->count();
        $totalMatakuliah = DB::table('matakuliahs')->count();
        $totalJadwal = DB::table('jadwals')->count();
        
        $mahasiswaPerJurusan = DB::table('jurusans')
            ->leftJoin('mahasiswas', 'jurusans.id', '=', 'mahasiswas.jurusan_id')
            ->select('jurusans.nama_jurusan', DB::raw('count(mahasiswas.id) as total'))
            ->groupBy('jurusans.id', 'jurusans.nama_jurusan')
            ->get();
        
        $aktivitasPerMinggu = DB::table('absensis')
            ->select(DB::raw('WEEK(tanggal) as minggu'), DB::raw('count(*) as total'))
            ->whereYear('tanggal', date('Y'))
            ->groupBy(DB::raw('WEEK(tanggal)'))
            ->limit(6)
            ->get();
        
        return view('dashboard', compact(
            'totalMahasiswa', 'totalDosen', 'totalMatakuliah', 'totalJadwal',
            'mahasiswaPerJurusan', 'aktivitasPerMinggu'
        ));
    }
}