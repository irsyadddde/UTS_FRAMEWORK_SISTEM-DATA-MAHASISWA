@extends('layouts.app')

@section('title', 'Data Jurusan')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b flex justify-between items-center">
        <h1 class="text-2xl font-bold">Data Jurusan</h1>
        <a href="{{ route('jurusan.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-plus mr-2"></i>Tambah Jurusan
        </a>
    </div>
    
    <div class="p-6">
        <form method="GET" class="mb-4 flex gap-2">
            <input type="text" name="search" placeholder="Cari kode atau nama jurusan..." 
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Jurusan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Jurusan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jml Mahasiswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jml Matakuliah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @forelse($jurusans as $index => $jurusan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $loop->iteration + ($jurusans->currentPage() - 1) * $jurusans->perPage() }}</td>
                        <td class="px-6 py-4 font-medium">{{ $jurusan->kode_jurusan }}</td>
                        <td class="px-6 py-4">{{ $jurusan->nama_jurusan }}</td>
                        <td class="px-6 py-4">{{ $jurusan->total_mahasiswa ?? 0 }}</td>
                        <td class="px-6 py-4">{{ $jurusan->total_matakuliah ?? 0 }}</td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('jurusan.show', $jurusan->id) }}" class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('jurusan.edit', $jurusan->id) }}" class="text-green-500 hover:text-green-700">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('jurusan.destroy', $jurusan->id) }}" method="POST" onsubmit="return confirmDelete(event)" class="inline">
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
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada数据</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $jurusans->links() }}
        </div>
    </div>
</div>
@endsection