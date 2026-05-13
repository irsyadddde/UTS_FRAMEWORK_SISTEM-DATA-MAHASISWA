@extends('layouts.app')

@section('title', 'Tambah Mata Kuliah')

@section('content')
<div class="bg-white rounded-lg shadow max-w-2xl mx-auto">
    <div class="p-6 border-b"><h1 class="text-2xl font-bold">Tambah Mata Kuliah</h1></div>
    <div class="p-6">
        <form action="{{ route('matakuliah.store') }}" method="POST">
            @csrf
            <div class="grid md:grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium mb-2">Kode MK *</label><input type="text" name="kode_mk" required class="w-full border rounded-lg px-4 py-2" value="{{ old('kode_mk') }}"></div>
                <div><label class="block text-sm font-medium mb-2">Nama Mata Kuliah *</label><input type="text" name="nama_mk" required class="w-full border rounded-lg px-4 py-2" value="{{ old('nama_mk') }}"></div>
                <div><label class="block text-sm font-medium mb-2">SKS *</label><input type="number" name="sks" required min="1" max="6" class="w-full border rounded-lg px-4 py-2" value="{{ old('sks') }}"></div>
                <div><label class="block text-sm font-medium mb-2">Semester *</label><input type="number" name="semester" required min="1" max="8" class="w-full border rounded-lg px-4 py-2" value="{{ old('semester') }}"></div>
                <div class="md:col-span-2"><label class="block text-sm font-medium mb-2">Jurusan *</label><select name="jurusan_id" required class="w-full border rounded-lg px-4 py-2"><option value="">Pilih Jurusan</option>@foreach($jurusans as $j)<option value="{{ $j->id }}">{{ $j->nama_jurusan }}</option>@endforeach</select></div>
                <div class="md:col-span-2"><label class="block text-sm font-medium mb-2">Deskripsi</label><textarea name="deskripsi" rows="3" class="w-full border rounded-lg px-4 py-2">{{ old('deskripsi') }}</textarea></div>
            </div>
            <div class="flex gap-3 mt-4"><button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg"><i class="fas fa-save mr-2"></i>Simpan</button><a href="{{ route('matakuliah.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg"><i class="fas fa-arrow-left mr-2"></i>Kembali</a></div>
        </form>
    </div>
</div>
@endsection