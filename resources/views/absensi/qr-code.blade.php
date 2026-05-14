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
            <p class="text-gray-600">
                <i class="fas fa-calendar mr-1"></i> {{ $jadwal->hari }}
                <i class="fas fa-clock ml-3 mr-1"></i> {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
            </p>
            <p class="text-gray-600 mt-1">
                <i class="fas fa-door-open mr-1"></i> Ruangan: {{ $jadwal->ruangan }}
            </p>
        </div>
        
        <div id="qr-container" class="flex justify-center my-6">
            <div id="qrcode"></div>
        </div>
        
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 text-left mb-4">
            <p class="text-sm text-yellow-800">
                <i class="fas fa-clock mr-2"></i>
                QR Code ini berlaku selama 15 menit ke depan.
            </p>
            <p class="text-sm text-yellow-800 mt-1">
                <i class="fas fa-mobile-alt mr-2"></i>
                Mahasiswa dapat memindai QR Code ini menggunakan smartphone.
            </p>
            <p class="text-sm text-yellow-800 mt-1">
                <i class="fas fa-id-card mr-2"></i>
                Mahasiswa akan diminta memasukkan NIM untuk konfirmasi absensi.
            </p>
        </div>
        
        <div class="flex gap-3 justify-center">
            <button onclick="window.print()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-print mr-2"></i>Cetak QR Code
            </button>
            <a href="{{ route('absensi.scan') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    // Data untuk QR Code (jadwal_id dan timestamp)
    const qrData = {
        jadwal_id: {{ $jadwal->id }},
        timestamp: Date.now(),
        expires: Date.now() + (15 * 60 * 1000) // 15 menit
    };
    
    // Buat URL untuk verifikasi
    const verifyUrl = "{{ url('/absensi-verify') }}?jadwal_id=" + qrData.jadwal_id + "&expires=" + qrData.expires;
    
    // Generate QR Code
    new QRCode(document.getElementById("qrcode"), {
        text: verifyUrl,
        width: 250,
        height: 250,
        correctLevel: QRCode.CorrectLevel.H
    });
    
    // Optional: Tambahkan countdown timer
    let timeLeft = 15 * 60; // 15 menit dalam detik
    const timerDisplay = document.createElement('div');
    timerDisplay.className = 'text-sm text-gray-500 mt-2';
    document.getElementById('qr-container').appendChild(timerDisplay);
    
    const countdown = setInterval(function() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerDisplay.innerHTML = '<i class="fas fa-hourglass-half mr-1"></i> QR Code berlaku selama ' + minutes + ' menit ' + seconds + ' detik';
        
        if (timeLeft <= 0) {
            clearInterval(countdown);
            timerDisplay.innerHTML = '<i class="fas fa-exclamation-triangle text-red-500 mr-1"></i> QR Code sudah kadaluarsa! Silahkan generate ulang.';
            timerDisplay.classList.add('text-red-500');
        }
        timeLeft--;
    }, 1000);
</script>

<style>
    @media print {
        .sidebar, .top-bar, .flex.gap-3, .bg-yellow-50, .btn-print {
            display: none !important;
        }
        body {
            background: white !important;
        }
        .bg-white {
            box-shadow: none !important;
        }
        #qr-container {
            margin: 20px 0;
        }
    }
</style>
@endsection