@extends('layouts.app')

@section('title', 'Tambah Dosen')

@section('content')
<div class="bg-white rounded-lg shadow max-w-2xl mx-auto">
    <div class="p-6 border-b">
        <h1 class="text-2xl font-bold">Tambah Dosen</h1>
    </div>
    
    <div class="p-6">
        <form action="{{ route('dosen.store') }}" method="POST">
            @csrf
            
            <div class="grid md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">NIDN *</label>
                    <input type="text" name="nidn" required maxlength="20"
                           class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                           value="{{ old('nidn') }}">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Dosen *</label>
                    <input type="text" name="nama_dosen" required maxlength="100"
                           class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                           value="{{ old('nama_dosen') }}">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" required
                           class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                           value="{{ old('email') }}">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">No Telp</label>
                    <input type="text" name="no_telp" maxlength="15"
                           class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                           value="{{ old('no_telp') }}">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan Terakhir</label>
                    <select name="pendidikan_terakhir" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                        <option value="">Pilih</option>
                        <option value="S1">S1</option>
                        <option value="S2">S2</option>
                        <option value="S3">S3</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
                    <input type="text" name="jabatan" maxlength="50"
                           class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                           value="{{ old('jabatan') }}">
                </div>
                
                <div class="mb-4 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <textarea name="alamat" rows="3"
                              class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">{{ old('alamat') }}</textarea>
                </div>
            </div>
            
            <div class="flex gap-3 mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
                <a href="{{ route('dosen.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection