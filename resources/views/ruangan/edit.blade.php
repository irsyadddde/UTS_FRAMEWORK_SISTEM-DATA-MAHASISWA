@extends('layouts.app')

@section('title', 'Edit Ruangan')

@section('content')
<div class="bg-white rounded-lg shadow max-w-2xl mx-auto">
    <div class="p-6 border-b">
        <h1 class="text-2xl font-bold">Edit Ruangan</h1>
    </div>
    
    <div class="p-6">
        <form action="{{ route('ruangan.update', $ruangan->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Ruangan *</label>
                    <input type="text" name="kode_ruangan" required maxlength="10"
                           class="w-full border rounded-lg px-4 py-2" value="{{ old('kode_ruangan', $ruangan->kode_ruangan) }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Ruangan *</label>
                    <input type="text" name="nama_ruangan" required maxlength="50"
                           class="w-full border rounded-lg px-4 py-2" value="{{ old('nama_ruangan', $ruangan->nama_ruangan) }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kapasitas *</label>
                    <input type="number" name="kapasitas" required min="1"
                           class="w-full border rounded-lg px-4 py-2" value="{{ old('kapasitas', $ruangan->kapasitas) }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi *</label>
                    <input type="text" name="lokasi" required maxlength="100"
                           class="w-full border rounded-lg px-4 py-2" value="{{ old('lokasi', $ruangan->lokasi) }}">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fasilitas</label>
                    <textarea name="fasilitas" rows="3"
                              class="w-full border rounded-lg px-4 py-2">{{ old('fasilitas', $ruangan->fasilitas) }}</textarea>
                </div>
            </div>
            
            <div class="flex gap-3 mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
                <a href="{{ route('ruangan.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection