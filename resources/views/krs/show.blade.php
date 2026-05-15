@extends('layouts.app')

@section('title', 'Detail KRS')

@section('content')
<div class="bg-white rounded-lg shadow max-w-2xl mx-auto">
    <div class="p-6 border-b flex justify-between items-center">
        <h1 class="text-2xl font-bold">Detail KRS</h1>
        <div class="flex gap-2">
            @if($krs->status == 'pending')
                <a href="{{ route('krs.approve', $krs->id) }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg"
                   onclick="return confirm('Setujui KRS ini?')">
                    <i class="fas fa-check mr-2"></i>Setujui
                </a>
                <a href="{{ route('krs.reject', $krs->id) }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg"
                   onclick="return confirm('Tolak KRS ini?')">
                    <i class="fas fa-times mr-2"></i>Tolak
                </a>
            @endif
            <a href="{{ route('krs.cetak', $krs->id) }}" target="_blank" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-print mr-2"></i>Cetak
            </a>
            <a href="{{ route('krs.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>
    
    <div class="p-6">
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h3 class="font-semibold text-gray-700 mb-3">Informasi KRS</h3>
            <table class="w-full">
                <tr><td class="py-2 text-gray-600 w-32">Kode KRS</td><td class="py-2">: {{ $krs->kode_krs }}</td></tr>
                <tr><td class="py-2 text-gray-600">Tanggal KRS</td><td class="py-2">: {{ \Carbon\Carbon::parse($krs->tgl_krs)->format('d/m/Y') }}</td></tr>
                <tr><td class="py-2 text-gray-600">Status</td>
                    <td class="py-2">: 
                        <span class="px-2 py-1 rounded text-xs 
                            @if($krs->status == 'approved') bg-green-100 text-green-700
                            @elseif($krs->status == 'rejected') bg-red-100 text-red-700
                            @else bg-yellow-100 text-yellow-700 @endif">
                            {{ $krs->status }}
                        </span>
                    </td>
                </tr>
                <tr><td class="py-2 text-gray-600">Tahun Akademik</td><td class="py-2">: {{ $krs->tahun }} - {{ $krs->semester }}</td></tr>
            </table>
        </div>
        
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h3 class="font-semibold text-gray-700 mb-3">Informasi Mahasiswa</h3>
            <table class="w-full">
                <tr><td class="py-2 text-gray-600 w-32">NIM</td><td class="py-2">: {{ $krs->nim }}</td></tr>
                <tr><td class="py-2 text-gray-600">Nama Mahasiswa</td><td class="py-2">: {{ $krs->nama_mahasiswa }}</td></tr>
            </table>
        </div>
        
        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="font-semibold text-gray-700 mb-3">Informasi Mata Kuliah</h3>
            <table class="w-full">
                <tr><td class="py-2 text-gray-600 w-32">Mata Kuliah</td><td class="py-2">: {{ $krs->nama_mk }}</td></tr>
                <tr><td class="py-2 text-gray-600">SKS</td><td class="py-2">: {{ $krs->sks }}</td></tr>
                <tr><td class="py-2 text-gray-600">Dosen</td><td class="py-2">: {{ $krs->nama_dosen }}</td></tr>
                <tr><td class="py-2 text-gray-600">Jadwal</td><td class="py-2">: {{ $krs->hari }}, {{ substr($krs->jam_mulai,0,5) }} - {{ substr($krs->jam_selesai,0,5) }}</td></tr>
                <tr><td class="py-2 text-gray-600">Ruangan</td><td class="py-2">: {{ $krs->ruangan }}</td></tr>
            </table>
        </div>
    </div>
</div>
@endsection