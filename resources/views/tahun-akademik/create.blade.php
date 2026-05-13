@extends('layouts.app')

@section('title', 'Tambah Tahun Akademik')

@section('content')
<div class="bg-white rounded-lg shadow max-w-2xl mx-auto">
    <div class="p-6 border-b"><h1 class="text-2xl font-bold">Tambah Tahun Akademik</h1></div>
    <div class="p-6">
        <form action="{{ route('tahun-akademik.store') }}" method="POST">
            @csrf
            <div class="grid gap-4">
                <div><label class="block text-sm font-medium mb-2">Tahun *</label><input type="text" name="tahun" required placeholder="2024/2025" class="w-full border rounded-lg px-4 py-2" value="{{ old('tahun') }}"></div>
                <div><label class="block text-sm font-medium mb-2">Semester *</label><select name="semester" required class="w-full border rounded-lg px-4 py-2"><option value="">Pilih</option><option value="Ganjil">Ganjil</option><option value="Genap">Genap</option></select></div>
                <div><label class="block text-sm font-medium mb-2">Tanggal Mulai *</label><input type="date" name="tgl_mulai" required class="w-full border rounded-lg px-4 py-2"></div>
                <div><label class="block text-sm font-medium mb-2">Tanggal Selesai *</label><input type="date" name="tgl_selesai" required class="w-full border rounded-lg px-4 py-2"></div>
                <div><label><input type="checkbox" name="is_active" value="1"> Aktifkan tahun akademik ini</label></div>
            </div>
            <div class="flex gap-3 mt-4"><button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg"><i class="fas fa-save mr-2"></i>Simpan</button><a href="{{ route('tahun-akademik.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg"><i class="fas fa-arrow-left mr-2"></i>Kembali</a></div>
        </form>
    </div>
</div>
@endsection