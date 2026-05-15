@extends('layouts.app')

@section('title', 'Buat KRS')

@section('content')
<div class="bg-white rounded-lg shadow max-w-2xl mx-auto">
    <div class="p-6 border-b">
        <h1 class="text-2xl font-bold">Buat KRS Baru</h1>
    </div>
    
    <div class="p-6">
        @if($activeYear)
        <form action="{{ route('krs.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Akademik</label>
                <input type="text" class="w-full border rounded-lg px-4 py-2 bg-gray-100" 
                       value="{{ $activeYear->tahun }} - {{ $activeYear->semester }}" readonly disabled>
                <input type="hidden" name="tahun_akademik_id" value="{{ $activeYear->id }}">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Mahasiswa *</label>
                <select name="mahasiswa_id" required class="w-full border rounded-lg px-4 py-2">
                    <option value="">Pilih Mahasiswa</option>
                    @foreach($mahasiswas as $m)
                    <option value="{{ $m->id }}">{{ $m->nim }} - {{ $m->nama_mahasiswa }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Mata Kuliah *</label>
                <select name="jadwal_id" required class="w-full border rounded-lg px-4 py-2">
                    <option value="">Pilih Mata Kuliah</option>
                    @foreach($jadwals as $j)
                    <option value="{{ $j->id }}">{{ $j->nama_mk }} ({{ $j->sks }} SKS) - {{ $j->hari }} {{ substr($j->jam_mulai,0,5) }} - {{ $j->nama_dosen }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                <p class="text-sm text-yellow-700"><i class="fas fa-info-circle mr-2"></i>KRS akan diproses oleh admin.</p>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-save mr-2"></i>Simpan KRS
                </button>
                <a href="{{ route('krs.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </form>
        @else
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
            <p>Belum ada tahun akademik yang aktif. Silakan aktifkan tahun akademik terlebih dahulu.</p>
            <a href="{{ route('tahun-akademik.index') }}" class="text-red-700 underline mt-2 inline-block">Kelola Tahun Akademik</a>
        </div>
        @endif
    </div>
</div>
@endsection