@extends('layouts.app')

@section('title', 'Kartu Rencana Studi (KRS)')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b">
        <h1 class="text-2xl font-bold">Kartu Rencana Studi (KRS)</h1>
        <p class="text-gray-600">Mahasiswa: {{ $mahasiswa->nama_mahasiswa }} ({{ $mahasiswa->nim }})</p>
        <p class="text-gray-600">Total SKS: {{ $totalSKS }} / 24</p>
    </div>
    
    <div class="p-6">
        <!-- Daftar KRS yang sudah diambil -->
        <h2 class="text-lg font-semibold mb-3">KRS Saat Ini</h2>
        <div class="overflow-x-auto mb-6">
            <table class="min-w-full border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2">Mata Kuliah</th>
                        <th class="px-4 py-2">SKS</th>
                        <th class="px-4 py-2">Dosen</th>
                        <th class="px-4 py-2">Jadwal</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($krsList as $krs)
                    <tr>
                        <td class="px-4 py-2">{{ $krs->nama_mk }}</td>
                        <td class="px-4 py-2">{{ $krs->sks }}</td>
                        <td class="px-4 py-2">{{ $krs->nama_dosen }}</td>
                        <td class="px-4 py-2">{{ $krs->hari }} {{ substr($krs->jam_mulai,0,5) }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-xs 
                                @if($krs->status == 'approved') bg-green-100 text-green-700
                                @elseif($krs->status == 'rejected') bg-red-100 text-red-700
                                @else bg-yellow-100 text-yellow-700 @endif">
                                {{ $krs->status }}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            @if($krs->status == 'pending')
                            <form action="/mahasiswa/krs/{{ $krs->id }}" method="POST" onsubmit="return confirm('Batalkan KRS ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-2 text-center">Belum ada KRS</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Form Tambah KRS -->
        @if($totalSKS < 24)
        <h2 class="text-lg font-semibold mb-3">Tambah Mata Kuliah</h2>
        <form action="/mahasiswa/krs/store" method="POST" class="mb-4">
            @csrf
            <div class="flex gap-2">
                <select name="jadwal_id" required class="flex-1 border rounded-lg px-4 py-2">
                    <option value="">Pilih Mata Kuliah</option>
                    @foreach($availableJadwals as $jadwal)
                    <option value="{{ $jadwal->id }}">{{ $jadwal->nama_mk }} ({{ $jadwal->sks }} SKS) - {{ $jadwal->hari }} {{ substr($jadwal->jam_mulai,0,5) }} - {{ $jadwal->nama_dosen }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Tambah</button>
            </div>
        </form>
        @else
        <div class="bg-yellow-100 p-3 rounded">Maksimal SKS 24 sudah tercapai!</div>
        @endif
    </div>
</div>
@endsection