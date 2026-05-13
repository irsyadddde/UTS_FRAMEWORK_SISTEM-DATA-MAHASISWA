@extends('layouts.app')

@section('title', 'Kartu Rencana Studi (KRS)')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b flex justify-between items-center">
        <h1 class="text-2xl font-bold">Kartu Rencana Studi (KRS)</h1>
        <a href="{{ route('krs.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg"><i class="fas fa-plus mr-2"></i>Buat KRS Baru</a>
    </div>
    <div class="p-6">
        <form method="GET" class="mb-4 flex gap-2 flex-wrap">
            <input type="text" name="search" placeholder="Cari nama atau NIM..." class="flex-1 border rounded-lg px-4 py-2" value="{{ request('search') }}">
            <select name="status" class="border rounded-lg px-4 py-2"><option value="">Semua Status</option><option value="pending">Pending</option><option value="approved">Approved</option><option value="rejected">Rejected</option></select>
            <button type="submit" class="bg-gray-500 text-white px-6 py-2 rounded-lg"><i class="fas fa-search mr-2"></i>Cari</button>
        </form>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50"><tr><th class="px-4 py-2">Kode KRS</th><th class="px-4 py-2">NIM</th><th class="px-4 py-2">Nama Mahasiswa</th><th class="px-4 py-2">Mata Kuliah</th><th class="px-4 py-2">SKS</th><th class="px-4 py-2">Jadwal</th><th class="px-4 py-2">Status</th><th class="px-4 py-2">Aksi</th></tr></thead>
                <tbody>
                    @foreach($krs as $k)
                    <tr><td class="px-4 py-2">{{ $k->kode_krs }}</td><td class="px-4 py-2">{{ $k->nim }}</td><td class="px-4 py-2">{{ $k->nama_mahasiswa }}</td><td class="px-4 py-2">{{ $k->nama_mk }}</td><td class="px-4 py-2">{{ $k->sks }}</td><td class="px-4 py-2">{{ $k->hari }} {{ \Carbon\Carbon::parse($k->jam_mulai)->format('H:i') }}</td>
                    <td class="px-4 py-2"><span class="px-2 py-1 rounded text-xs @if($k->status=='approved') bg-green-100 text-green-700 @elseif($k->status=='rejected') bg-red-100 text-red-700 @else bg-yellow-100 text-yellow-700 @endif">{{ $k->status }}</span></td>
                    <td class="px-4 py-2"><div class="flex gap-2"><a href="{{ route('krs.show', $k->id) }}" class="text-blue-500"><i class="fas fa-eye"></i></a>@if($k->status=='pending')<a href="{{ route('krs.approve', $k->id) }}" class="text-green-500" onclick="return confirm('Setujui KRS ini?')"><i class="fas fa-check"></i></a><a href="{{ route('krs.reject', $k->id) }}" class="text-red-500" onclick="return confirm('Tolak KRS ini?')"><i class="fas fa-times"></i></a>@endif<a href="{{ route('krs.cetak', $k->id) }}" target="_blank" class="text-purple-500"><i class="fas fa-print"></i></a><form action="{{ route('krs.destroy', $k->id) }}" method="POST" onsubmit="return confirmDelete(event)" class="inline">@csrf @method('DELETE')<button type="submit" class="text-red-500"><i class="fas fa-trash"></i></button></form></div></td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $krs->links() }}</div>
    </div>
</div>
@endsection