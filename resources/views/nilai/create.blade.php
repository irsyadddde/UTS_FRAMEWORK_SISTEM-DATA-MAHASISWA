@extends('layouts.app')

@section('title', 'Input Nilai')

@section('content')
<div class="bg-white rounded-lg shadow max-w-2xl mx-auto">
    <div class="p-6 border-b"><h1 class="text-2xl font-bold">Input Nilai</h1></div>
    <div class="p-6">
        <form action="{{ route('nilai.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Mahasiswa & Mata Kuliah</label>
                <select name="krs_id" required class="w-full border rounded-lg px-4 py-2">
                    <option value="">Pilih</option>
                    @foreach($krsList as $k)
                    <option value="{{ $k->krs_id }}">{{ $k->nim }} - {{ $k->nama_mahasiswa }} ({{ $k->nama_mk }})</option>
                    @endforeach
                </select>
            </div>
            <div class="grid md:grid-cols-3 gap-4">
                <div><label class="block text-sm font-medium mb-2">Nilai Tugas</label><input type="number" name="nilai_tugas" step="0.01" min="0" max="100" class="w-full border rounded-lg px-4 py-2"></div>
                <div><label class="block text-sm font-medium mb-2">Nilai UTS</label><input type="number" name="nilai_uts" step="0.01" min="0" max="100" class="w-full border rounded-lg px-4 py-2"></div>
                <div><label class="block text-sm font-medium mb-2">Nilai UAS</label><input type="number" name="nilai_uas" step="0.01" min="0" max="100" class="w-full border rounded-lg px-4 py-2"></div>
            </div>
            <div class="mt-4 bg-blue-50 p-3 rounded"><p class="text-sm text-blue-600"><i class="fas fa-info-circle mr-2"></i>Nilai Akhir = (Tugas x 30%) + (UTS x 30%) + (UAS x 40%)</p></div>
            <div class="flex gap-3 mt-4"><button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg"><i class="fas fa-save mr-2"></i>Simpan</button><a href="{{ route('nilai.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg"><i class="fas fa-arrow-left mr-2"></i>Kembali</a></div>
        </form>
    </div>
</div>
@endsection