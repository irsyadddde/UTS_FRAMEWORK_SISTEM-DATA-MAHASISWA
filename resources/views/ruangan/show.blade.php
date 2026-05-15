@extends('layouts.app')

@section('title', 'Detail Ruangan')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b flex justify-between items-center">
        <h1 class="text-2xl font-bold">Detail Ruangan</h1>
        <div class="flex gap-2">
            <a href="{{ route('ruangan.edit', $ruangan->id) }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('ruangan.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>
    
    <div class="p-6">
        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-700 mb-2">Informasi Ruangan</h3>
                <table class="w-full">
                    <tr>
                        <td class="py-2 text-gray-600 w-32">Kode Ruangan</td>
                        <td class="py-2">: {{ $ruangan->kode_ruangan }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-600">Nama Ruangan</td>
                        <td class="py-2">: {{ $ruangan->nama_ruangan }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-600">Kapasitas</td>
                        <td class="py-2">: {{ $ruangan->kapasitas }} orang</td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-600">Lokasi</td>
                        <td class="py-2">: {{ $ruangan->lokasi }}</td>
                    </tr>
                    @if($ruangan->fasilitas)
                    <tr>
                        <td class="py-2 text-gray-600">Fasilitas</td>
                        <td class="py-2">: {{ $ruangan->fasilitas }}</td>
                    </tr>
                    @endif
                </table>
            </div>
            
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-700 mb-2">Statistik Penggunaan</h3>
                <table class="w-full">
                    <tr>
                        <td class="py-2 text-gray-600 w-32">Total Jadwal</td>
                        <td class="py-2">: {{ $jadwals->count() }} jadwal</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Daftar Jadwal -->
        <div>
            <h2 class="text-lg font-semibold mb-3">Daftar Jadwal di Ruangan Ini</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full border">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left">Hari</th>
                            <th class="px-4 py-2 text-left">Jam</th>
                            <th class="px-4 py-2 text-left">Mata Kuliah</th>
                            <th class="px-4 py-2 text-left">Dosen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwals as $j)
                        <tr>
                            <td class="px-4 py-2">{{ $j->hari }}</td>
                            <td class="px-4 py-2">{{ substr($j->jam_mulai,0,5) }} - {{ substr($j->jam_selesai,0,5) }}</td>
                            <td class="px-4 py-2">{{ $j->nama_mk }}</td>
                            <td class="px-4 py-2">{{ $j->nama_dosen }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-2 text-center">Tidak ada jadwal</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection