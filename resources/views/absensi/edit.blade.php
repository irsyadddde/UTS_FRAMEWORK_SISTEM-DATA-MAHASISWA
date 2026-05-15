@extends('layouts.app')

@section('title', 'Edit Absensi')

@section('content')
<div class="bg-white rounded-lg shadow max-w-2xl mx-auto">
    <div class="p-6 border-b">
        <h1 class="text-2xl font-bold">Edit Absensi</h1>
    </div>
    
    <div class="p-6">
        <form action="{{ route('absensi.update', $absensi->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mahasiswa</label>
                    <input type="text" class="w-full border rounded-lg px-4 py-2 bg-gray-100" 
                           value="{{ $absensi->nim ?? '' }} - {{ $absensi->nama_mahasiswa ?? '' }}" readonly disabled>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mata Kuliah</label>
                    <input type="text" class="w-full border rounded-lg px-4 py-2 bg-gray-100" 
                           value="{{ $absensi->nama_mk ?? '' }}" readonly disabled>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                    <input type="date" name="tanggal" class="w-full border rounded-lg px-4 py-2 bg-gray-100" 
                           value="{{ $absensi->tanggal }}" readonly disabled>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" required class="w-full border rounded-lg px-4 py-2">
                        <option value="hadir" {{ $absensi->status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="izin" {{ $absensi->status == 'izin' ? 'selected' : '' }}>Izin</option>
                        <option value="sakit" {{ $absensi->status == 'sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="alpha" {{ $absensi->status == 'alpha' ? 'selected' : '' }}>Alpha</option>
                    </select>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                    <textarea name="keterangan" rows="3" class="w-full border rounded-lg px-4 py-2">{{ old('keterangan', $absensi->keterangan) }}</textarea>
                </div>
            </div>
            
            <div class="flex gap-3 mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
                <a href="{{ route('absensi.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection