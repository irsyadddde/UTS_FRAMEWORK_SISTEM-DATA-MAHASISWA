@extends('layouts.app')

@section('title', 'Tahun Akademik')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b flex justify-between items-center">
        <h1 class="text-2xl font-bold">Tahun Akademik</h1>
        <a href="{{ route('tahun-akademik.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg"><i class="fas fa-plus mr-2"></i>Tambah</a>
    </div>
    <div class="p-6">
        @if($active)
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
            <i class="fas fa-check-circle mr-2"></i>Tahun Akademik Aktif: <strong>{{ $active->tahun }} - {{ $active->semester }}</strong>
        </div>
        @endif
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50"><tr><th class="px-6 py-3">Tahun</th><th class="px-6 py-3">Semester</th><th class="px-6 py-3">Status</th><th class="px-6 py-3">Tanggal Mulai</th><th class="px-6 py-3">Tanggal Selesai</th><th class="px-6 py-3">Aksi</th></tr></thead>
                <tbody>
                    @foreach($tahunAkademiks as $ta)
                    <tr><td class="px-6 py-4">{{ $ta->tahun }}</td><td class="px-6 py-4">{{ $ta->semester }}</td><td class="px-6 py-4">@if($ta->is_active) <span class="bg-green-100 text-green-700 px-2 py-1 rounded">Aktif</span> @else <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded">Tidak Aktif</span> @endif</td><td class="px-6 py-4">{{ \Carbon\Carbon::parse($ta->tgl_mulai)->format('d/m/Y') }}</td><td class="px-6 py-4">{{ \Carbon\Carbon::parse($ta->tgl_selesai)->format('d/m/Y') }}</td>
                    <td class="px-6 py-4"><div class="flex gap-2">@if(!$ta->is_active)<a href="{{ route('tahun-akademik.set-active', $ta->id) }}" class="text-green-500" onclick="return confirm('Aktifkan tahun akademik ini?')"><i class="fas fa-check-circle"></i></a>@endif<a href="{{ route('tahun-akademik.edit', $ta->id) }}" class="text-green-500"><i class="fas fa-edit"></i></a><form action="{{ route('tahun-akademik.destroy', $ta->id) }}" method="POST" onsubmit="return confirmDelete(event)" class="inline">@csrf @method('DELETE')<button class="text-red-500"><i class="fas fa-trash"></i></button></form></div></td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $tahunAkademiks->links() }}</div>
    </div>
</div>
@endsection