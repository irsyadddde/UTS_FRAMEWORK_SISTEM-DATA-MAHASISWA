@extends('layouts.app')

@section('title', 'Detail Kelas')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b flex justify-between items-center">
        <h1 class="text-2xl font-bold">Detail Kelas</h1>
        <div class="flex gap-2">
            <a href="{{ route('kelas.edit', $kelas->id) }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('kelas.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>
    
    <div class="p-6">
        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-700 mb-3">Informasi Kelas</h3>
                <table class="w-full">
                    <tr><td class="py-2 text-gray-600 w-32">Kode Kelas</td><td>: {{ $kelas->kode_kelas }}</td></tr>
                    <tr><td class="py-2 text-gray-600">Nama Kelas</td><td>: {{ $kelas->nama_kelas }}</td></tr>
                    <tr><td class="py-2 text-gray-600">Jurusan</td><td>: {{ $kelas->nama_jurusan }}</td></tr>
                    <tr><td class="py-2 text-gray-600">Angkatan</td><td>: {{ $kelas->angkatan }}</td></tr>
                </table>
            </div>
            
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-700 mb-3">Statistik</h3>
                <table class="w-full">
                    <tr><td class="py-2 text-gray-600 w-32">Total Mahasiswa</td><td>: {{ $mahasiswas->count() }}</td></tr>
                </table>
            </div>
        </div>
        
        <div>
            <h2 class="text-lg font-semibold mb-3">Daftar Mahasiswa</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full border">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left">NIM</th>
                            <th class="px-4 py-2 text-left">Nama Mahasiswa</th>
                            <th class="px-4 py-2 text-left">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mahasiswas as $mhs)
                        <tr>
                            <td class="px-4 py-2">{{ $mhs->nim }}</td>
                            <td class="px-4 py-2">{{ $mhs->nama_mahasiswa }}</td>
                            <td class="px-4 py-2">{{ $mhs->email }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-4 py-2 text-center">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection