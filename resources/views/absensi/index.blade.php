@extends('layouts.app')

@section('title', 'Data Absensi')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b flex justify-between items-center">
        <h1 class="text-2xl font-bold">Data Absensi</h1>
        <div class="flex gap-2">
            <a href="{{ route('absensi.scan') }}" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-qrcode mr-2"></i>Scan QR Code
            </a>
            <a href="{{ route('absensi.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-plus mr-2"></i>Tambah Absensi
            </a>
        </div>
    </div>
    
    <div class="p-6">
        <form method="GET" class="mb-4 flex gap-2 flex-wrap">
            <input type="date" name="tanggal" class="border rounded-lg px-4 py-2" value="{{ request('tanggal') }}">
            <input type="text" name="search" placeholder="Cari nama atau NIM..." class="flex-1 border rounded-lg px-4 py-2" value="{{ request('search') }}">
            <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                <i class="fas fa-search mr-2"></i>Cari
            </button>
        </form>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">No</th>
                        <th class="px-6 py-3 text-left">Tanggal</th>
                        <th class="px-6 py-3 text-left">NIM</th>
                        <th class="px-6 py-3 text-left">Nama Mahasiswa</th>
                        <th class="px-6 py-3 text-left">Mata Kuliah</th>
                        <th class="px-6 py-3 text-left">Jadwal</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @forelse($absensis as $index => $a)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $loop->iteration + ($absensis->currentPage() - 1) * $absensis->perPage() }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($a->tanggal)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">{{ $a->nim }}</td>
                        <td class="px-6 py-4">{{ $a->nama_mahasiswa }}</td>
                        <td class="px-6 py-4">{{ $a->nama_mk }}</td>
                        <td class="px-6 py-4">{{ $a->hari }} {{ substr($a->jam_mulai,0,5) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs 
                                @if($a->status == 'hadir') bg-green-100 text-green-700
                                @elseif($a->status == 'izin') bg-blue-100 text-blue-700
                                @elseif($a->status == 'sakit') bg-yellow-100 text-yellow-700
                                @else bg-red-100 text-red-700 @endif">
                                {{ ucfirst($a->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('absensi.edit', $a->id) }}" class="text-green-500 hover:text-green-700">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('absensi.destroy', $a->id) }}" method="POST" onsubmit="return confirmDelete(event)" class="inline">
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
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">Tidak ada数据</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $absensis->links() }}
        </div>
    </div>
</div>
@endsection