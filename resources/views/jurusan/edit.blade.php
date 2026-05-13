@extends('layouts.app')

@section('title', 'Edit Jurusan')

@section('content')
<div class="bg-white rounded-lg shadow max-w-2xl mx-auto">
    <div class="p-6 border-b">
        <h1 class="text-2xl font-bold">Edit Jurusan</h1>
    </div>
    
    <div class="p-6">
        <form action="{{ route('jurusan.update', $jurusan->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Kode Jurusan *</label>
                <input type="text" name="kode_jurusan" required maxlength="10" 
                       class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                       value="{{ old('kode_jurusan', $jurusan->kode_jurusan) }}">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Jurusan *</label>
                <input type="text" name="nama_jurusan" required maxlength="100"
                       class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                       value="{{ old('nama_jurusan', $jurusan->nama_jurusan) }}">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="deskripsi" rows="4"
                          class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">{{ old('deskripsi', $jurusan->deskripsi ?? '') }}</textarea>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
                <a href="{{ route('jurusan.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection