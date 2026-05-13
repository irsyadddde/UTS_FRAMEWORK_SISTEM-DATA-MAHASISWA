@extends('layouts.app')

@section('title', 'Transkrip Nilai')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b flex justify-between items-center">
        <h1 class="text-2xl font-bold">Transkrip Nilai</h1>
        <button onclick="printPage()" class="bg-purple-500 text-white px-4 py-2 rounded-lg"><i class="fas fa-print mr-2"></i>Cetak</button>
    </div>
    <div class="p-6">
        <div class="text-center mb-6">
            <h2 class="text-xl font-bold">UNIVERSITAS SIAKAD</h2>
            <h3 class="text-lg">TRANSKRIP NILAI</h3>
            <p>Program Studi: {{ $mahasiswa->nama_jurusan }}</p>
        </div>
        
        <div class="mb-4">
            <p><strong>Nama:</strong> {{ $mahasiswa->nama_mahasiswa }}</p>
            <p><strong>NIM:</strong> {{ $mahasiswa->nim }}</p>
            <p><strong>Angkatan:</strong> {{ $mahasiswa->angkatan }}</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full border">
                <thead class="bg-gray-50"><tr><th class="px-4 py-2">Kode MK</th><th class="px-4 py-2">Mata Kuliah</th><th class="px-4 py-2">SKS</th><th class="px-4 py-2">Tugas</th><th class="px-4 py-2">UTS</th><th class="px-4 py-2">UAS</th><th class="px-4 py-2">Nilai Akhir</th><th class="px-4 py-2">Grade</th></tr></thead>
                <tbody>
                    @foreach($nilais as $n)
                    <tr><td class="px-4 py-2">{{ $n->kode_mk ?? '-' }}</td><td class="px-4 py-2">{{ $n->nama_mk }}</td><td class="px-4 py-2">{{ $n->sks }}</td><td class="px-4 py-2">{{ $n->nilai_tugas ?? '-' }}</td><td class="px-4 py-2">{{ $n->nilai_uts ?? '-' }}</td><td class="px-4 py-2">{{ $n->nilai_uas ?? '-' }}</td><td class="px-4 py-2 font-bold">{{ $n->nilai_akhir ?? '-' }}</td><td class="px-4 py-2">{{ $n->grade ?? '-' }}</td></tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50"><tr><td colspan="2" class="px-4 py-2 font-bold">Total SKS: {{ $totalSKS }}</td><td colspan="6" class="px-4 py-2 font-bold">IPK: {{ $ipk }}</td></tr></tfoot>
            </table>
        </div>
    </div>
</div>
@endsection