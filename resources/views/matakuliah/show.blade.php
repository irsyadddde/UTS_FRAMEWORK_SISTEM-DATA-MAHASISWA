@extends('layouts.app')

@section('title', 'Detail Mata Kuliah')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b flex justify-between items-center">
        <h1 class="text-2xl font-bold">Detail Mata Kuliah</h1>
        <div class="flex gap-2">
            <a href="{{ route('matakuliah.edit', $matakuliah->id) }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('matakuliah.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>
    
    <div class="p-6">
        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-700 mb-2">Informasi Mata Kuliah</h3>
                <table class="w-full">
                    <tr>
                        <td class="py-2 text-gray-600 w-32">Kode MK</td>
                        <td class="py-2">: {{ $matakuliah->kode_mk }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-600">Nama Mata Kuliah</td>
                        <td class="py-2">: {{ $matakuliah->nama_mk }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-600">SKS</td>
                        <td class="py-2">: {{ $matakuliah->sks }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-600">Semester</td>
                        <td class="py-2">: {{ $matakuliah->semester }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-600">Jurusan</td>
                        <td class="py-2">: {{ $matakuliah->nama_jurusan }}</td>
                    </tr>
                    @if($matakuliah->deskripsi)
                    <tr>
                        <td class="py-2 text-gray-600">Deskripsi</td>
                        <td class="py-2">: {{ $matakuliah->deskripsi }}</td>
                    </tr>
                    @endif
                </table>
            </div>
            
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-700 mb-2">Statistik</h3>
                <table class="w-full">
                    <tr>
                        <td class="py-2 text-gray-600 w-32">Total Jadwal</td>
                        <td class="py-2">: {{ $statistik['total_jadwal'] ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-600">Total Dosen</td>
                        <td class="py-2">: {{ $statistik['total_dosen'] ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-600">Total Mahasiswa</td>
                        <td class="py-2">: {{ $statistik['total_mahasiswa'] ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-600">Rata-rata Nilai</td>
                        <td class="py-2">: {{ round($statistik['rata_rata_nilai'] ?? 0, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Daftar Jadwal -->
        <div>
            <h2 class="text-lg font-semibold mb-3">Daftar Jadwal</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full border">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">Hari</th>
                            <th class="px-4 py-2">Jam</th>
                            <th class="px-4 py-2">Dosen</th>
                            <th class="px-4 py-2">Ruangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwals as $j)
                        <tr>
                            <td class="px-4 py-2">{{ $j->hari }}</td>
                            <td class="px-4 py-2">{{ substr($j->jam_mulai,0,5) }} - {{ substr($j->jam_selesai,0,5) }}</td>
                            <td class="px-4 py-2">{{ $j->nama_dosen }}</td>
                            <td class="px-4 py-2">{{ $j->ruangan }}</td>
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