@extends('layouts.app')

@section('title', 'Kartu Rencana Studi (KRS)')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b flex justify-between items-center">
        <h1 class="text-2xl font-bold">Kartu Rencana Studi (KRS)</h1>
        <a href="{{ route('krs.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-plus mr-2"></i>Buat KRS
        </a>
    </div>
    
    <div class="p-6">
        <form method="GET" class="mb-4 flex gap-2">
            <input type="text" name="search" placeholder="Cari nama atau NIM..." 
                   class="flex-1 border rounded-lg px-4 py-2" value="{{ request('search') }}">
            <select name="status" class="border rounded-lg px-4 py-2">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                <i class="fas fa-search mr-2"></i>Cari
            </button>
        </form>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">No</th>
                        <th class="px-6 py-3 text-left">Kode KRS</th>
                        <th class="px-6 py-3 text-left">NIM</th>
                        <th class="px-6 py-3 text-left">Nama Mahasiswa</th>
                        <th class="px-6 py-3 text-left">Mata Kuliah</th>
                        <th class="px-6 py-3 text-left">SKS</th>
                        <th class="px-6 py-3 text-left">Jadwal</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @forelse($krs as $index => $k)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $loop->iteration + ($krs->currentPage() - 1) * $krs->perPage() }}</td>
                        <td class="px-6 py-4">{{ $k->kode_krs }}</td>
                        <td class="px-6 py-4">{{ $k->nim }}</td>
                        <td class="px-6 py-4">{{ $k->nama_mahasiswa }}</td>
                        <td class="px-6 py-4">{{ $k->nama_mk }}</td>
                        <td class="px-6 py-4">{{ $k->sks }}</td>
                        <td class="px-6 py-4">{{ $k->hari }} {{ substr($k->jam_mulai,0,5) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs 
                                @if($k->status == 'approved') bg-green-100 text-green-700
                                @elseif($k->status == 'rejected') bg-red-100 text-red-700
                                @else bg-yellow-100 text-yellow-700 @endif">
                                {{ $k->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('krs.show', $k->id) }}" class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($k->status == 'pending')
                                <a href="{{ route('krs.approve', $k->id) }}" class="text-green-500 hover:text-green-700" 
                                   onclick="return confirm('Setujui KRS ini?')">
                                    <i class="fas fa-check"></i>
                                </a>
                                <a href="{{ route('krs.reject', $k->id) }}" class="text-red-500 hover:text-red-700" 
                                   onclick="return confirm('Tolak KRS ini?')">
                                    <i class="fas fa-times"></i>
                                </a>
                                @endif
                                <a href="{{ route('krs.cetak', $k->id) }}" target="_blank" class="text-purple-500 hover:text-purple-700">
                                    <i class="fas fa-print"></i>
                                </a>
                                <form action="{{ route('krs.destroy', $k->id) }}" method="POST" onsubmit="return confirmDelete(event)" class="inline">
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
                        <td colspan="9" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $krs->links() }}
        </div>
    </div>
</div>
@endsection