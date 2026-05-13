@extends('layouts.app')

@section('title', 'Data Kelas')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b flex justify-between items-center">
        <h1 class="text-2xl font-bold">Data Kelas</h1>
        <a href="{{ route('kelas.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-plus mr-2"></i>Tambah Kelas
        </a>
    </div>
    
    <div class="p-6">
        <form method="GET" class="mb-4 flex gap-2">
            <input type="text" name="search" placeholder="Cari kode atau nama kelas..." 
                   class="flex-1 border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                   value="{{ request('search') }}">
            <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-search mr-2"></i>Cari
            </button>
        </form>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jurusan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Angkatan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @forelse($kelas as $index => $k)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $loop->iteration + ($kelas->currentPage() - 1) * $kelas->perPage() }}</td>
                        <td class="px-6 py-4 font-medium">{{ $k->kode_kelas }}</td>
                        <td class="px-6 py-4">{{ $k->nama_kelas }}</td>
                        <td class="px-6 py-4">{{ $k->nama_jurusan }}</td>
                        <td class="px-6 py-4">{{ $k->angkatan }}</td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('kelas.show', $k->id) }}" class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('kelas.edit', $k->id) }}" class="text-green-500 hover:text-green-700">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('kelas.destroy', $k->id) }}" method="POST" onsubmit="return confirmDelete(event)" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $kelas->links() }}
        </div>
    </div>
</div>
@endsection