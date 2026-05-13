@extends('layouts.app')

@section('title', 'Tambah Jadwal')

@section('content')
<div class="bg-white rounded-lg shadow max-w-2xl mx-auto">
    <div class="p-6 border-b"><h1 class="text-2xl font-bold">Tambah Jadwal</h1></div>
    <div class="p-6">
        <form action="{{ route('jadwal.store') }}" method="POST">
            @csrf
            <div class="grid md:grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium mb-2">Mata Kuliah *</label><select name="matakuliah_id" required class="w-full border rounded-lg px-4 py-2"><option value="">Pilih</option>@foreach($matakuliahs as $mk)<option value="{{ $mk->id }}">{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</option>@endforeach</select></div>
                <div><label class="block text-sm font-medium mb-2">Dosen *</label><select name="dosen_id" required class="w-full border rounded-lg px-4 py-2"><option value="">Pilih</option>@foreach($dosens as $d)<option value="{{ $d->id }}">{{ $d->nama_dosen }}</option>@endforeach</select></div>
                <div><label class="block text-sm font-medium mb-2">Hari *</label><select name="hari" required class="w-full border rounded-lg px-4 py-2"><option value="">Pilih</option><option value="Senin">Senin</option><option value="Selasa">Selasa</option><option value="Rabu">Rabu</option><option value="Kamis">Kamis</option><option value="Jumat">Jumat</option><option value="Sabtu">Sabtu</option></select></div>
                <div><label class="block text-sm font-medium mb-2">Jam Mulai *</label><input type="time" name="jam_mulai" required class="w-full border rounded-lg px-4 py-2"></div>
                <div><label class="block text-sm font-medium mb-2">Jam Selesai *</label><input type="time" name="jam_selesai" required class="w-full border rounded-lg px-4 py-2"></div>
                <div><label class="block text-sm font-medium mb-2">Ruangan *</label><select name="ruangan" required class="w-full border rounded-lg px-4 py-2"><option value="">Pilih</option>@foreach($ruangans as $r)<option value="{{ $r->kode_ruangan }}">{{ $r->kode_ruangan }} - {{ $r->nama_ruangan }} (Kapasitas: {{ $r->kapasitas }})</option>@endforeach</select></div>
            </div>
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 my-4"><p class="text-sm text-yellow-700"><i class="fas fa-info-circle mr-2"></i>Pastikan tidak ada bentrok jadwal dengan dosen atau ruangan yang sama!</p></div>
            <div class="flex gap-3 mt-4"><button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg"><i class="fas fa-save mr-2"></i>Simpan</button><a href="{{ route('jadwal.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg"><i class="fas fa-arrow-left mr-2"></i>Kembali</a></div>
        </form>
    </div>
</div>
@endsection