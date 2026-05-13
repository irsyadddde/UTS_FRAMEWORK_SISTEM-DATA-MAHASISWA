<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TahunAkademikController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('tahun_akademiks');
        
        if ($request->has('search')) {
            $query->where('tahun', 'like', '%' . $request->search . '%');
        }
        
        $tahunAkademiks = $query->orderBy('id', 'desc')->paginate(10);
        
        // Get active year
        $active = DB::table('tahun_akademiks')->where('is_active', true)->first();
        
        return view('tahun-akademik.index', compact('tahunAkademiks', 'active'));
    }

    public function create()
    {
        return view('tahun-akademik.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required|unique:tahun_akademiks|max:9',
            'semester' => 'required|in:Ganjil,Genap',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after:tgl_mulai',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // If this is set as active, deactivate others
        $is_active = $request->has('is_active') ? true : false;
        
        if ($is_active) {
            DB::table('tahun_akademiks')->update(['is_active' => false]);
        }

        DB::table('tahun_akademiks')->insert([
            'tahun' => $request->tahun,
            'semester' => $request->semester,
            'is_active' => $is_active,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('tahun-akademik.index')->with('success', 'Tahun Akademik berhasil ditambahkan');
    }

    public function edit($id)
    {
        $tahunAkademik = DB::table('tahun_akademiks')->where('id', $id)->first();
        if (!$tahunAkademik) {
            abort(404);
        }
        return view('tahun-akademik.edit', compact('tahunAkademik'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required|max:9|unique:tahun_akademiks,tahun,' . $id,
            'semester' => 'required|in:Ganjil,Genap',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after:tgl_mulai',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $is_active = $request->has('is_active') ? true : false;
        
        if ($is_active) {
            DB::table('tahun_akademiks')->where('id', '!=', $id)->update(['is_active' => false]);
        }

        DB::table('tahun_akademiks')->where('id', $id)->update([
            'tahun' => $request->tahun,
            'semester' => $request->semester,
            'is_active' => $is_active,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'updated_at' => now(),
        ]);

        return redirect()->route('tahun-akademik.index')->with('success', 'Tahun Akademik berhasil diupdate');
    }

    public function destroy($id)
    {
        $tahunAkademik = DB::table('tahun_akademiks')->where('id', $id)->first();
        
        // Check if has KRS
        $krsCount = DB::table('k_r_s')->where('tahun_akademik_id', $id)->count();
        
        if ($krsCount > 0) {
            return redirect()->back()->with('error', 'Tahun Akademik tidak dapat dihapus karena sudah ada KRS');
        }
        
        DB::table('tahun_akademiks')->where('id', $id)->delete();
        return redirect()->route('tahun-akademik.index')->with('success', 'Tahun Akademik berhasil dihapus');
    }
    
    public function setActive($id)
    {
        DB::table('tahun_akademiks')->update(['is_active' => false]);
        DB::table('tahun_akademiks')->where('id', $id)->update(['is_active' => true]);
        
        return redirect()->back()->with('success', 'Tahun Akademik aktif berhasil diubah');
    }
}