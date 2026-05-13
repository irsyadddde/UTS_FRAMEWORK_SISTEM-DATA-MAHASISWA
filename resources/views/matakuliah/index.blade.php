@extends('layouts.app')

@section('title', 'Data Mata Kuliah')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b flex justify-between items-center">
        <h1 class="text-2xl font-bold">Data Mata Kuliah</h1>
        <a href="{{ route('matakuliah.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-plus mr-2"></i>Tambah Mata Kuliah
        </a>
    </div>
    
    <div class="p-6">
        <form method="GET" class="mb-4 flex gap-2 flex-wrap">
            <input type="text" name="search" placeholder="Cari kode atau nama MK..." 
                   class="flex-1 border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                   value="{{ request('search') }}">
            <select name="jurusan_id" class="border rounded-lg px-4 py-2">
                <option value="">Semua Jurusan</option>
                @foreach($jurusans as $j)
                <option value="{{ $j->id }}" {{ request('jurusan_id') == $j->id ? 'selected' : '' }}>{{ $j->nama_jurusan }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                <i class="fas fa-search mr-2"></i>Cari
            </button>
        </form>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th><th class="px-6 py-3 text-left">Kode MK</th><th class="px-6 py-3 text-left">Nama Mata Kuliah</th><th class="px-6 py-3 text-left">SKS</th><th class="px-6 py-3 text-left">Semester</th><th class="px-6 py-3 text-left">Jurusan</th><th class="px-6 py-3 text-left">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($matakuliahs as $index => $mk)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $loop->iteration + ($matakuliahs->currentPage() - 1) * $matakuliahs->perPage() }}</td>
                        <td class="px-6 py-4 font-medium">{{ $mk->kode_mk }}</td>
                        <td class="px-6 py-4">{{ $mk->nama_mk }}</td>
                        <td class="px-6 py-4">{{ $mk->sks }}</td>
                        <td class="px-6 py-4">{{ $mk->semester }}</td>
                        <td class="px-6 py-4">{{ $mk->nama_jurusan }}</td>
                        <td class="px-6 py-4"><div class="flex gap-2"><a href="{{ route('matakuliah.show', $mk->id) }}" class="text-blue-500"><i class="fas fa-eye"></i></a><a href="{{ route('matakuliah.edit', $mk->id) }}" class="text-green-500"><i class="fas fa-edit"></i></a><form action="{{ route('matakuliah.destroy', $mk->id) }}" method="POST" onsubmit="return confirmDelete(event)" class="inline">@csrf @method('DELETE')<button type="submit" class="text-red-500"><i class="fas fa-trash"></i></button></form></div></td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $matakuliahs->links() }}</div>
    </div>
</div>
@endsection