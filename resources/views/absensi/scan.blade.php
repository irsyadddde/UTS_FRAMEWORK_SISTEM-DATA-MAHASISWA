@extends('layouts.app')

@section('title', 'Scan QR Code Absensi')

@section('content')
<div class="bg-white rounded-lg shadow max-w-2xl mx-auto">
    <div class="p-6 border-b"><h1 class="text-2xl font-bold">Scan QR Code Absensi</h1></div>
    <div class="p-6 text-center">
        <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Pilih Jadwal</label>
            <select id="jadwal_id" class="w-full border rounded-lg px-4 py-2">
                <option value="">Pilih Jadwal...</option>
                @foreach($jadwals as $j)
                <option value="{{ $j->id }}">{{ $j->nama_mk }} - {{ $j->hari }} {{ substr($j->jam_mulai,0,5) }} ({{ $j->nama_dosen }})</option>
                @endforeach
            </select>
        </div>
        <div id="qrcode-container" class="my-6 p-4 bg-gray-100 rounded-lg hidden">
            <div id="qrcode" class="flex justify-center"></div>
            <p class="text-sm text-gray-500 mt-2">Scan QR Code ini menggunakan smartphone untuk absensi</p>
            <p class="text-xs text-red-500">QR Code berlaku 15 menit</p>
        </div>
        <button id="generate-btn" class="bg-blue-500 text-white px-6 py-2 rounded-lg"><i class="fas fa-qrcode mr-2"></i>Generate QR Code</button>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
document.getElementById('generate-btn').onclick = function() {
    var jadwal_id = document.getElementById('jadwal_id').value;
    if(!jadwal_id) { alert('Pilih jadwal terlebih dahulu'); return; }
    window.location.href = '/absensi-generate-qr/' + jadwal_id;
};
</script>
@endsection