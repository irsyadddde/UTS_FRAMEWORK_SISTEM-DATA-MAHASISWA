@extends('layouts.app')

@section('title', 'Tambah Absensi')

@section('content')
<div class="bg-white rounded-lg shadow max-w-2xl mx-auto">
    <div class="p-6 border-b">
        <h1 class="text-2xl font-bold">Tambah Absensi</h1>
    </div>
    
    <div class="p-6">
        <form action="{{ route('absensi.store') }}" method="POST">
            @csrf
            
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mahasiswa *</label>
                    <select name="mahasiswa_id" required class="w-full border rounded-lg px-4 py-2">
                        <option value="">Pilih Mahasiswa</option>
                        @foreach($mahasiswas as $m)
                        <option value="{{ $m->id }}">{{ $m->nim }} - {{ $m->nama_mahasiswa }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jadwal *</label>
                    <select name="jadwal_id" required class="w-full border rounded-lg px-4 py-2">
                        <option value="">Pilih Jadwal</option>
                        @foreach($jadwals as $j)
                        <option value="{{ $j->id }}">{{ $j->nama_mk }} - {{ $j->hari }} {{ substr($j->jam_mulai,0,5) }} ({{ $j->nama_dosen }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal *</label>
                    <input type="date" name="tanggal" required class="w-full border rounded-lg px-4 py-2" value="{{ date('Y-m-d') }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" required class="w-full border rounded-lg px-4 py-2">
                        <option value="">Pilih Status</option>
                        <option value="hadir">Hadir</option>
                        <option value="izin">Izin</option>
                        <option value="sakit">Sakit</option>
                        <option value="alpha">Alpha</option>
                    </select>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                    <textarea name="keterangan" rows="3" class="w-full border rounded-lg px-4 py-2">{{ old('keterangan') }}</textarea>
                </div>
            </div>
            
            <div class="flex gap-3 mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
                <a href="{{ route('absensi.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection