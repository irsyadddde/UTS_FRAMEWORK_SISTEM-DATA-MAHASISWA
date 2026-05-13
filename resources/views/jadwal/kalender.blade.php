@extends('layouts.app')

@section('title', 'Kalender Jadwal')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b">
        <h1 class="text-2xl font-bold">Kalender Jadwal</h1>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @php
                $hari_list = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                $jadwal_by_hari = [];
                foreach($jadwals as $j) {
                    $jadwal_by_hari[$j->hari][] = $j;
                }
            @endphp
            
            @foreach($hari_list as $hari)
            <div class="border rounded-lg">
                <div class="bg-blue-500 text-white p-3 rounded-t-lg font-bold">{{ $hari }}</div>
                <div class="p-3">
                    @if(isset($jadwal_by_hari[$hari]))
                        @foreach($jadwal_by_hari[$hari] as $j)
                        <div class="mb-2 p-2 bg-gray-50 rounded">
                            <div class="font-semibold">{{ $j->nama_mk }}</div>
                            <div class="text-sm text-gray-600">{{ substr($j->jam_mulai,0,5) }} - {{ substr($j->jam_selesai,0,5) }}</div>
                            <div class="text-sm text-gray-600">{{ $j->nama_dosen }}</div>
                            <div class="text-sm text-gray-500">Ruang: {{ $j->ruangan }}</div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center text-gray-500 py-4">Tidak ada jadwal</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection