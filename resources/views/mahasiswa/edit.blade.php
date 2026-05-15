@extends('layouts.app')

@section('title', 'Edit Mahasiswa')

@section('content')
<div class="bg-white rounded-lg shadow max-w-2xl mx-auto">
    <div class="p-6 border-b">
        <h1 class="text-2xl font-bold">Edit Mahasiswa</h1>
    </div>
    
    <div class="p-6">
        <form action="{{ route('mahasiswa.update', $mahasiswa->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">NIM *</label>
                    <input type="text" name="nim" required class="w-full border rounded-lg px-4 py-2" value="{{ old('nim', $mahasiswa->nim) }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Mahasiswa *</label>
                    <input type="text" name="nama_mahasiswa" required class="w-full border rounded-lg px-4 py-2" value="{{ old('nama_mahasiswa', $mahasiswa->nama_mahasiswa) }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jurusan *</label>
                    <select name="jurusan_id" required class="w-full border rounded-lg px-4 py-2">
                        <option value="">Pilih Jurusan</option>
                        @foreach($jurusans as $j)
                        <option value="{{ $j->id }}" {{ old('jurusan_id', $mahasiswa->jurusan_id) == $j->id ? 'selected' : '' }}>
                            {{ $j->kode_jurusan }} - {{ $j->nama_jurusan }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Angkatan *</label>
                    <select name="angkatan" required class="w-full border rounded-lg px-4 py-2">
                        <option value="">Pilih Angkatan</option>
                        @for($i = 2020; $i <= 2025; $i++)
                        <option value="{{ $i }}" {{ old('angkatan', $mahasiswa->angkatan) == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" required class="w-full border rounded-lg px-4 py-2" value="{{ old('email', $mahasiswa->email) }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">No HP</label>
                    <input type="text" name="no_hp" class="w-full border rounded-lg px-4 py-2" value="{{ old('no_hp', $mahasiswa->no_hp) }}">
                </div>
            </div>
            
            <div class="flex gap-3 mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
                <a href="{{ route('mahasiswa.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection