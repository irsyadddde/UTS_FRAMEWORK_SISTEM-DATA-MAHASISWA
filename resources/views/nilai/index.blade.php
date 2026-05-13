@extends('layouts.app')

@section('title', 'Data Nilai')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b flex justify-between items-center">
        <h1 class="text-2xl font-bold">Data Nilai</h1>
        <a href="{{ route('nilai.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg"><i class="fas fa-plus mr-2"></i>Input Nilai</a>
    </div>
    <div class="p-6">
        <form method="GET" class="mb-4 flex gap-2 flex-wrap">
            <input type="text" name="search" placeholder="Cari..." class="flex-1 border rounded-lg px-4 py-2" value="{{ request('search') }}">
            <select name="matakuliah_id" class="border rounded-lg px-4 py-2"><option value="">Semua MK</option>@foreach($matakuliahs as $mk)<option value="{{ $mk->id }}">{{ $mk->nama_mk }}</option>@endforeach</select>
            <button type="submit" class="bg-gray-500 text-white px-6 py-2 rounded-lg"><i class="fas fa-search"></i></button>
        </form>
        <div class="overflow-x-auto">
            <table class="min-w-full"><thead class="bg-gray-50"><tr><th>NIM</th><th>Nama</th><th>Mata Kuliah</th><th>Tugas</th><th>UTS</th><th>UAS</th><th>Nilai Akhir</th><th>Grade</th><th>Aksi</th></tr></thead>
                <tbody>@foreach($nilais as $n)<tr><td class="px-4 py-2">{{ $n->nim }}</td><td>{{ $n->nama_mahasiswa }}</td><td>{{ $n->nama_mk }}</td><td>{{ $n->nilai_tugas ?? '-' }}</td><td>{{ $n->nilai_uts ?? '-' }}</td><td>{{ $n->nilai_uas ?? '-' }}</td><td class="font-bold">{{ $n->nilai_akhir ?? '-' }}</td><td><span class="px-2 py-1 rounded text-xs bg-{{ $n->grade == 'A' ? 'green' : ($n->grade == 'E' ? 'red' : 'yellow') }}-100">{{ $n->grade ?? '-' }}</span></td><td><a href="{{ route('nilai.edit', $n->id) }}" class="text-green-500"><i class="fas fa-edit"></i></a></td></tr>@endforeach</tbody>
            </table>
        </div>
        <div class="mt-4">{{ $nilais->links() }}</div>
    </div>
</div>
@endsection