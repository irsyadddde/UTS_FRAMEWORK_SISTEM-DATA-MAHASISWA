@extends('layouts.app')

@section('title', 'Data Ruangan')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b flex justify-between items-center">
        <h1 class="text-2xl font-bold">Data Ruangan</h1>
        <a href="{{ route('ruangan.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-plus mr-2"></i>Tambah Ruangan
        </a>
    </div>
    
    <div class="p-6">
        <form method="GET" class="mb-4 flex gap-2">
            <input type="text" name="search" placeholder="Cari kode atau nama ruangan..." 
                   class="flex-1 border rounded-lg px-4 py-2" value="{{ request('search') }}">
            <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                <i class="fas fa-search mr-2"></i>Cari
            </button>
        </form>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">No</th>
                        <th class="px-6 py-3 text-left">Kode Ruangan</th>
                        <th class="px-6 py-3 text-left">Nama Ruangan</th>
                        <th class="px-6 py-3 text-left">Kapasitas</th>
                        <th class="px-6 py-3 text-left">Lokasi</th>
                        <th class="px-6 py-3 text-left">Penggunaan</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @forelse($ruangans as $index => $r)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $loop->iteration + ($ruangans->currentPage() - 1) * $ruangans->perPage() }}</td>
                        <td class="px-6 py-4 font-medium">{{ $r->kode_ruangan }}</td>
                        <td class="px-6 py-4">{{ $r->nama_ruangan }}</td>
                        <td class="px-6 py-4">{{ $r->kapasitas }}</td>
                        <td class="px-6 py-4">{{ $r->lokasi }}</td>
                        <td class="px-6 py-4">{{ $r->total_penggunaan ?? 0 }} x</td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('ruangan.show', $r->id) }}" class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('ruangan.edit', $r->id) }}" class="text-green-500 hover:text-green-700">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('ruangan.destroy', $r->id) }}" method="POST" onsubmit="return confirmDelete(event)" class="inline">
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
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $ruangans->links() }}
        </div>
    </div>
</div>
@endsection