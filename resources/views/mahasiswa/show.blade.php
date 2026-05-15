@extends('layouts.app')

@section('title', 'Detail Mahasiswa')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b flex justify-between items-center">
        <h1 class="text-2xl font-bold">Detail Mahasiswa</h1>
        <div class="flex gap-2">
            <a href="{{ route('mahasiswa.edit', $mahasiswa->id) }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('mahasiswa.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>
    
    <div class="p-6">
        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-700 mb-3">Informasi Pribadi</h3>
                <table class="w-full">
                    <tr>
                        <td class="py-2 text-gray-600 w-32">NIM</td>
                        <td class="py-2">: {{ $mahasiswa->nim }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-600">Nama Lengkap</td>
                        <td class="py-2">: {{ $mahasiswa->nama_mahasiswa }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-600">Jurusan</td>
                        <td class="py-2">: {{ $mahasiswa->nama_jurusan }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-600">Angkatan</td>
                        <td class="py-2">: {{ $mahasiswa->angkatan }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-600">Email</td>
                        <td class="py-2">: {{ $mahasiswa->email }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-600">No HP</td>
                        <td class="py-2">: {{ $mahasiswa->no_hp ?? '-' }}</td>
                    </tr>
                </table>
            </div>
            
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-700 mb-3">Statistik Akademik</h3>
                <table class="w-full">
                    <tr>
                        <td class="py-2 text-gray-600 w-32">Total SKS</td>
                        <td class="py-2">: {{ $krs->sum('sks') ?? 0 }} SKS</td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-600">Jumlah Mata Kuliah</td>
                        <td class="py-2">: {{ $krs->count() }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="mt-6">
            <h2 class="text-lg font-semibold mb-3">Kartu Rencana Studi (KRS)</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full border">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left">Mata Kuliah</th>
                            <th class="px-4 py-2 text-left">SKS</th>
                            <th class="px-4 py-2 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($krs as $item)
                        <tr>
                            <td class="px-4 py-2">{{ $item->nama_mk }}</td>
                            <td class="px-4 py-2">{{ $item->sks }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded text-xs 
                                    @if($item->status == 'approved') bg-green-100 text-green-700
                                    @elseif($item->status == 'rejected') bg-red-100 text-red-700
                                    @else bg-yellow-100 text-yellow-700 @endif">
                                    {{ $item->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-2 text-center">Belum ada KRS</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="mt-4">
            <a href="{{ route('nilai.transkrip', $mahasiswa->id) }}" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg inline-block">
                <i class="fas fa-print mr-2"></i>Lihat Transkrip Nilai
            </a>
        </div>
    </div>
</div>
@endsection