@extends('layouts.app')

@section('title', 'Detail Dosen')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b flex justify-between items-center">
        <h1 class="text-2xl font-bold">Detail Dosen</h1>
        <div class="flex gap-2">
            <a href="{{ route('dosen.edit', $dosen->id) }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('dosen.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>
    
    <div class="p-6">
        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-700 mb-3">Informasi Pribadi</h3>
                <table class="w-full">
                    <tr><td class="py-2 text-gray-600 w-32">NIDN</td><td>: {{ $dosen->nidn }}</td></tr>
                    <tr><td class="py-2 text-gray-600">Nama Dosen</td><td>: {{ $dosen->nama_dosen }}</td></tr>
                    <tr><td class="py-2 text-gray-600">Email</td><td>: {{ $dosen->email }}</td></tr>
                    <tr><td class="py-2 text-gray-600">No Telp</td><td>: {{ $dosen->no_telp ?? '-' }}</td></tr>
                    <tr><td class="py-2 text-gray-600">Pendidikan</td><td>: {{ $dosen->pendidikan_terakhir ?? '-' }}</td></tr>
                    <tr><td class="py-2 text-gray-600">Jabatan</td><td>: {{ $dosen->jabatan ?? '-' }}</td></tr>
                    <tr><td class="py-2 text-gray-600">Alamat</td><td>: {{ $dosen->alamat ?? '-' }}</td></tr>
                </table>
            </div>
            
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-700 mb-3">Statistik Mengajar</h3>
                <table class="w-full">
                    <tr><td class="py-2 text-gray-600 w-32">Total Jadwal</td><td>: {{ $statistik['total_jadwal'] ?? 0 }}</td></tr>
                    <tr><td class="py-2 text-gray-600">Total Mata Kuliah</td><td>: {{ $statistik['total_matakuliah'] ?? 0 }}</td></tr>
                    <tr><td class="py-2 text-gray-600">Total Mahasiswa</td><td>: {{ $statistik['total_mahasiswa'] ?? 0 }}</td></tr>
                    <tr><td class="py-2 text-gray-600">Rata-rata Kehadiran</td><td>: {{ round($statistik['rata_rata_kehadiran'] ?? 0) }}%</td></tr>
                </table>
            </div>
        </div>
        
        <div>
            <h2 class="text-lg font-semibold mb-3">Jadwal Mengajar</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full border">
                    <thead class="bg-gray-50">
                        <tr><th class="px-4 py-2">Hari</th><th class="px-4 py-2">Jam</th><th class="px-4 py-2">Mata Kuliah</th><th class="px-4 py-2">SKS</th><th class="px-4 py-2">Ruangan</th></tr>
                    </thead>
                    <tbody>
                        @forelse($jadwals as $j)
                        <tr>
                            <td class="px-4 py-2">{{ $j->hari }}</td>
                            <td class="px-4 py-2">{{ $j->jam_mulai }} - {{ $j->jam_selesai }}</td>
                            <td class="px-4 py-2">{{ $j->nama_mk }}</td>
                            <td class="px-4 py-2">{{ $j->sks }}</td>
                            <td class="px-4 py-2">{{ $j->ruangan }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-4 py-2 text-center">Tidak ada jadwal</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection