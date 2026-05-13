@extends('layouts.app')

@section('title', 'Edit Nilai')

@section('content')
<div class="bg-white rounded-lg shadow max-w-2xl mx-auto">
    <div class="p-6 border-b"><h1 class="text-2xl font-bold">Edit Nilai</h1></div>
    <div class="p-6">
        <form action="{{ route('nilai.update', $nilai->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <div class="bg-gray-50 p-3 rounded mb-3">
                    <p><strong>Mahasiswa:</strong> {{ $nilai->nama_mahasiswa }} ({{ $nilai->nim }})</p>
                    <p><strong>Mata Kuliah:</strong> {{ $nilai->nama_mk }}</p>
                </div>
            </div>
            <div class="grid md:grid-cols-3 gap-4">
                <div><label class="block text-sm font-medium mb-2">Nilai Tugas</label><input type="number" name="nilai_tugas" step="0.01" min="0" max="100" class="w-full border rounded-lg px-4 py-2" value="{{ $nilai->nilai_tugas }}"></div>
                <div><label class="block text-sm font-medium mb-2">Nilai UTS</label><input type="number" name="nilai_uts" step="0.01" min="0" max="100" class="w-full border rounded-lg px-4 py-2" value="{{ $nilai->nilai_uts }}"></div>
                <div><label class="block text-sm font-medium mb-2">Nilai UAS</label><input type="number" name="nilai_uas" step="0.01" min="0" max="100" class="w-full border rounded-lg px-4 py-2" value="{{ $nilai->nilai_uas }}"></div>
            </div>
            <div class="flex gap-3 mt-4"><button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg"><i class="fas fa-save mr-2"></i>Update</button><a href="{{ route('nilai.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg"><i class="fas fa-arrow-left mr-2"></i>Kembali</a></div>
        </form>
    </div>
</div>
@endsection