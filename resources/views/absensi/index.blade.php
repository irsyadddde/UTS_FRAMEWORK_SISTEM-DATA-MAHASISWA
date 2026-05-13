@extends('layouts.app')

@section('title', 'Data Absensi')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b flex justify-between items-center flex-wrap gap-2">
        <h1 class="text-2xl font-bold">Data Absensi</h1>
        <div class="flex gap-2">
            <a href="{{ route('absensi.scan') }}" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg"><i class="fas fa-qrcode mr-2"></i>Scan QR Code</a>
            <a href="{{ route('absensi.laporan') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg"><i class="fas fa-chart-bar mr-2"></i>Laporan</a>
            <a href="{{ route('absensi.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg"><i class="fas fa-plus mr-2"></i>Tambah Absensi</a>
        </div>
    </div>
    <div class="p-6">
        <form method="GET" class="mb-4 flex gap-2">
            <input type="date" name="tanggal" class="border rounded-lg px-4 py-2" value="{{ request('tanggal') }}">
            <input type="text" name="search" placeholder="Cari..." class="flex-1 border rounded-lg px-4 py-2">
            <button type="submit" class="bg-gray-500 text-white px-6 py-2 rounded-lg"><i class="fas fa-search"></i></button>
        </form>
        <div class="overflow-x-auto">
            <table class="min-w-full"><thead class="bg-gray-50"><tr><th>Tanggal</th><th>NIM</th><th>Nama</th><th>Mata Kuliah</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>@foreach($absensis as $a)<tr><td>{{ \Carbon\Carbon::parse($a->tanggal)->format('d/m/Y') }}</td><td>{{ $a->nim }}</td><td>{{ $a->nama_mahasiswa }}</td><td>{{ $a->nama_mk }}</td><td><span class="px-2 py-1 rounded text-xs @if($a->status=='hadir') bg-green-100 text-green-700 @elseif($a->status=='izin') bg-blue-100 @elseif($a->status=='sakit') bg-yellow-100 @else bg-red-100 @endif">{{ $a->status }}</span></td><td><a href="{{ route('absensi.edit', $a->id) }}" class="text-green-500"><i class="fas fa-edit"></i></a></td></tr>@endforeach</tbody>
            </table>
        </div>
        <div class="mt-4">{{ $absensis->links() }}</div>
    </div>
</div>
@endsection