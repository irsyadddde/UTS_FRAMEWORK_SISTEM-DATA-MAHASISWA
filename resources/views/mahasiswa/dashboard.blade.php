@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold mb-4">Dashboard Mahasiswa</h1>
    <p class="text-gray-600 mb-6">Selamat datang, <strong>{{ $mahasiswa->nama_mahasiswa ?? 'Mahasiswa' }}</strong>!</p>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-blue-50 rounded-lg p-4">
            <p class="text-blue-600 text-sm">Total SKS</p>
            <p class="text-2xl font-bold text-blue-700">{{ $totalSKS ?? 0 }}</p>
        </div>
        <div class="bg-green-50 rounded-lg p-4">
            <p class="text-green-600 text-sm">Kehadiran</p>
            <p class="text-2xl font-bold text-green-700">{{ $persentaseKehadiran ?? 0 }}%</p>
        </div>
        <div class="bg-purple-50 rounded-lg p-4">
            <p class="text-purple-600 text-sm">Mata Kuliah Aktif</p>
            <p class="text-2xl font-bold text-purple-700">{{ $krsAktif->count() ?? 0 }}</p>
        </div>
    </div>
    
    <!-- Jadwal Hari Ini -->
    @if(isset($jadwalHariIni) && $jadwalHariIni->count() > 0)
    <div class="mb-6">
        <h2 class="text-lg font-semibold mb-3">Jadwal Hari Ini</h2>
        @foreach($jadwalHariIni as $j)
        <div class="bg-gray-50 p-3 rounded mb-2">
            <p class="font-medium">{{ $j->nama_mk }}</p>
            <p class="text-sm text-gray-600">{{ $j->jam_mulai }} - {{ $j->jam_selesai }} | {{ $j->ruangan }}</p>
        </div>
        @endforeach
    </div>
    @endif
    
    <!-- KRS Aktif -->
    <div>
        <h2 class="text-lg font-semibold mb-3">Kartu Rencana Studi (KRS) Aktif</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2">Mata Kuliah</th>
                        <th class="px-4 py-2">SKS</th>
                        <th class="px-4 py-2">Dosen</th>
                        <th class="px-4 py-2">Jadwal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($krsAktif as $krs)
                    <tr>
                        <td class="px-4 py-2">{{ $krs->nama_mk }}</td>
                        <td class="px-4 py-2">{{ $krs->sks }}</td>
                        <td class="px-4 py-2">{{ $krs->nama_dosen }}</td>
                        <td class="px-4 py-2">{{ $krs->hari }} {{ substr($krs->jam_mulai,0,5) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-2 text-center text-gray-500">Belum ada KRS aktif</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection