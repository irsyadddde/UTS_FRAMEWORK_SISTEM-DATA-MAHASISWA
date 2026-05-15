@extends('layouts.app')

@section('title', 'QR Code Absensi')

@section('content')
<div class="bg-white rounded-lg shadow max-w-2xl mx-auto">
    <div class="p-6 border-b">
        <h1 class="text-2xl font-bold">QR Code Absensi</h1>
    </div>
    
    <div class="p-6 text-center">
        <div class="mb-4 p-4 bg-gray-50 rounded-lg">
            <h3 class="font-semibold text-lg mb-2">{{ $jadwal->nama_mk }}</h3>
            <p class="text-gray-600">{{ $jadwal->hari }} | {{ substr($jadwal->jam_mulai,0,5) }} - {{ substr($jadwal->jam_selesai,0,5) }}</p>
            <p class="text-gray-600 mt-1">Dosen: {{ $jadwal->nama_dosen ?? '-' }}</p>
            <p class="text-gray-600">Ruangan: {{ $jadwal->ruangan }}</p>
        </div>
        
        <div class="flex justify-center my-6">
            @if(isset($qrCode))
                {!! $qrCode !!}
            @else
                <div id="qrcode"></div>
            @endif
        </div>
        
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 text-left mb-4">
            <p class="text-sm text-yellow-800">
                <i class="fas fa-clock mr-2"></i> QR Code ini berlaku selama 15 menit ke depan.
            </p>
            <p class="text-sm text-yellow-800 mt-1">
                <i class="fas fa-mobile-alt mr-2"></i> Mahasiswa dapat memindai QR Code ini menggunakan smartphone.
            </p>
            <p class="text-sm text-yellow-800 mt-1">
                <i class="fas fa-id-card mr-2"></i> Mahasiswa akan diminta memasukkan NIM untuk konfirmasi absensi.
            </p>
        </div>
        
        <div class="flex gap-3 justify-center">
            <button onclick="window.print()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-print mr-2"></i>Cetak QR Code
            </button>
            <a href="{{ route('absensi.scan') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>
</div>

@if(!isset($qrCode))
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    // Generate QR Code jika tidak ada qrCode dari server
    const qrData = {
        jadwal_id: {{ $jadwal->id }},
        timestamp: Date.now(),
        expires: Date.now() + (15 * 60 * 1000)
    };
    
    const verifyUrl = "{{ url('/absensi-verify') }}?jadwal_id=" + qrData.jadwal_id + "&expires=" + qrData.expires;
    
    new QRCode(document.getElementById("qrcode"), {
        text: verifyUrl,
        width: 250,
        height: 250,
        correctLevel: QRCode.CorrectLevel.H
    });
</script>
@endif

<style>
    @media print {
        .sidebar, .top-bar, .flex.gap-3, .bg-yellow-50, button, a {
            display: none !important;
        }
        body {
            background: white !important;
        }
        .bg-white {
            box-shadow: none !important;
        }
        .container {
            margin: 0;
            padding: 0;
        }
    }
</style>
@endsection