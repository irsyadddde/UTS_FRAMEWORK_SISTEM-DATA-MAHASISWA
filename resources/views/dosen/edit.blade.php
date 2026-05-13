@extends('layouts.app')

@section('title', 'Edit Dosen')

@section('content')
<div class="bg-white rounded-lg shadow max-w-2xl mx-auto">
    <div class="p-6 border-b">
        <h1 class="text-2xl font-bold">Edit Dosen</h1>
    </div>
    
    <div class="p-6">
        <form action="{{ route('dosen.update', $dosen->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">NIDN *</label>
                    <input type="text" name="nidn" required maxlength="20"
                           class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                           value="{{ old('nidn', $dosen->nidn) }}">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Dosen *</label>
                    <input type="text" name="nama_dosen" required maxlength="100"
                           class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                           value="{{ old('nama_dosen', $dosen->nama_dosen) }}">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" required
                           class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                           value="{{ old('email', $dosen->email) }}">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">No Telp</label>
                    <input type="text" name="no_telp" maxlength="15"
                           class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                           value="{{ old('no_telp', $dosen->no_telp) }}">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan Terakhir</label>
                    <select name="pendidikan_terakhir" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                        <option value="">Pilih</option>
                        <option value="S1" {{ old('pendidikan_terakhir', $dosen->pendidikan_terakhir) == 'S1' ? 'selected' : '' }}>S1</option>
                        <option value="S2" {{ old('pendidikan_terakhir', $dosen->pendidikan_terakhir) == 'S2' ? 'selected' : '' }}>S2</option>
                        <option value="S3" {{ old('pendidikan_terakhir', $dosen->pendidikan_terakhir) == 'S3' ? 'selected' : '' }}>S3</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
                    <input type="text" name="jabatan" maxlength="50"
                           class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                           value="{{ old('jabatan', $dosen->jabatan) }}">
                </div>
                
                <div class="mb-4 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <textarea name="alamat" rows="3"
                              class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">{{ old('alamat', $dosen->alamat) }}</textarea>
                </div>
            </div>
            
            <div class="flex gap-3 mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
                <a href="{{ route('dosen.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection