@extends('layouts.app')

@section('title', 'Data Jadwal')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b flex justify-between items-center flex-wrap gap-2">
        <h1 class="text-2xl font-bold">Data Jadwal</h1>
        <div class="flex gap-2">
            <a href="{{ route('jadwal.kalender') }}" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg"><i class="fas fa-calendar-alt mr-2"></i>Kalender</a>
            <a href="{{ route('jadwal.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg"><i class="fas fa-plus mr-2"></i>Tambah Jadwal</a>
        </div>
    </div>
    <div class="p-6">
        <form method="GET" class="mb-4 flex gap-2">
            <select name="hari" class="border rounded-lg px-4 py-2"><option value="">Semua Hari</option><option value="Senin">Senin</option><option value="Selasa">Selasa</option><option value="Rabu">Rabu</option><option value="Kamis">Kamis</option><option value="Jumat">Jumat</option><option value="Sabtu">Sabtu</option></select>
            <button type="submit" class="bg-gray-500 text-white px-6 py-2 rounded-lg"><i class="fas fa-filter mr-2"></i>Filter</button>
        </form>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50"><tr><th class="px-4 py-2">Hari</th><th class="px-4 py-2">Jam</th><th class="px-4 py-2">Mata Kuliah</th><th class="px-4 py-2">Dosen</th><th class="px-4 py-2">SKS</th><th class="px-4 py-2">Ruangan</th><th class="px-4 py-2">Aksi</th></tr></thead>
                <tbody>
                    @forelse($jadwals as $j)
                    <tr class="hover:bg-gray-50"><td class="px-4 py-2">{{ $j->hari }}</td><td class="px-4 py-2">{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</td><td class="px-4 py-2">{{ $j->nama_mk }}</td><td class="px-4 py-2">{{ $j->nama_dosen }}</td><td class="px-4 py-2">{{ $j->sks }}</td><td class="px-4 py-2">{{ $j->ruangan }}</td><td class="px-4 py-2"><div class="flex gap-2"><a href="{{ route('jadwal.edit', $j->id) }}" class="text-green-500"><i class="fas fa-edit"></i></a><form action="{{ route('jadwal.destroy', $j->id) }}" method="POST" onsubmit="return confirmDelete(event)" class="inline">@csrf @method('DELETE')<button type="submit" class="text-red-500"><i class="fas fa-trash"></i></button></form></div></td></tr>
                    @empty
                    <tr><td colspan="7" class="px-4 py-4 text-center">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $jadwals->links() }}</div>
    </div>
</div>
@endsection