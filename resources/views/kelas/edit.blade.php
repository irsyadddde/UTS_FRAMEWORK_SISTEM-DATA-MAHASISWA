@extends('layouts.app')

@section('title', 'Edit Kelas')

@section('content')
<div class="bg-white rounded-lg shadow max-w-2xl mx-auto">
    <div class="p-6 border-b">
        <h1 class="text-2xl font-bold">Edit Kelas</h1>
    </div>
    
    <div class="p-6">
        <form action="{{ route('kelas.update', $kelas->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Kelas *</label>
                    <input type="text" name="kode_kelas" required maxlength="10"
                           class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                           value="{{ old('kode_kelas', $kelas->kode_kelas) }}">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kelas *</label>
                    <input type="text" name="nama_kelas" required maxlength="50"
                           class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                           value="{{ old('nama_kelas', $kelas->nama_kelas) }}">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jurusan *</label>
                    <select name="jurusan_id" required class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                        <option value="">Pilih Jurusan</option>
                        @foreach($jurusans as $jurusan)
                        <option value="{{ $jurusan->id }}" {{ old('jurusan_id', $kelas->jurusan_id) == $jurusan->id ? 'selected' : '' }}>
                            {{ $jurusan->kode_jurusan }} - {{ $jurusan->nama_jurusan }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Angkatan *</label>
                    <select name="angkatan" required class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                        <option value="">Pilih Angkatan</option>
                        @for($i = 2020; $i <= 2025; $i++)
                        <option value="{{ $i }}" {{ old('angkatan', $kelas->angkatan) == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            
            <div class="flex gap-3 mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
                <a href="{{ route('kelas.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection