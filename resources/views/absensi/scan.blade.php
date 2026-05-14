@extends('layouts.app')

@section('title', 'Generate QR Code Absensi')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b">
        <h1 class="text-2xl font-bold">Generate QR Code Absensi</h1>
    </div>
    
    <div class="p-6">
        @if($jadwals->isEmpty())
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Belum ada jadwal yang tersedia. Silahkan tambahkan jadwal terlebih dahulu.
            </div>
            <div class="text-center">
                <a href="{{ route('jadwal.create') }}" class="bg-blue-500 text-white px-6 py-3 rounded-lg inline-block">
                    <i class="fas fa-plus mr-2"></i>Tambah Jadwal
                </a>
            </div>
        @else
            <div class="grid md:grid-cols-2 gap-4 mb-6">
                @foreach($jadwals as $jadwal)
                <div class="border rounded-lg p-4 hover:shadow-lg transition">
                    <h3 class="font-semibold text-lg text-blue-600">{{ $jadwal->nama_mk }}</h3>
                    <p class="text-gray-600 text-sm mt-1">
                        <i class="fas fa-chalkboard-user mr-1"></i> {{ $jadwal->nama_dosen }}
                    </p>
                    <p class="text-gray-500 text-sm">
                        <i class="fas fa-calendar mr-1"></i> {{ $jadwal->hari }}
                        <i class="fas fa-clock ml-2 mr-1"></i> {{ substr($jadwal->jam_mulai,0,5) }} - {{ substr($jadwal->jam_selesai,0,5) }}
                    </p>
                    <p class="text-gray-500 text-sm">
                        <i class="fas fa-door-open mr-1"></i> {{ $jadwal->ruangan }}
                    </p>
                    <a href="{{ route('absensi.generate-qr', $jadwal->id) }}" class="mt-3 inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                        <i class="fas fa-qrcode mr-2"></i>Generate QR Code
                    </a>
                </div>
                @endforeach
            </div>
            
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h4 class="font-semibold text-gray-700 mb-2">Cara Penggunaan:</h4>
                <ol class="text-sm text-gray-600 space-y-1 list-decimal list-inside">
                    <li>Pilih mata kuliah dan jadwal yang akan dihadiri</li>
                    <li>Klik tombol "Generate QR Code"</li>
                    <li>Tampilkan QR Code di layar atau cetak</li>
                    <li>Mahasiswa memindai QR Code menggunakan smartphone</li>
                    <li>Mahasiswa memasukkan NIM untuk konfirmasi absensi</li>
                    <li>Absensi otomatis tercatat dalam sistem</li>
                </ol>
            </div>
        @endif
    </div>
</div>
@endsection