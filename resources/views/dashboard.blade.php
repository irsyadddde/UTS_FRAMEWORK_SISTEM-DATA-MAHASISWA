@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="p-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-500 text-sm">Total Mahasiswa</div>
            <div class="text-3xl font-bold text-blue-600">{{ number_format($totalMahasiswa) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-500 text-sm">Total Dosen</div>
            <div class="text-3xl font-bold text-green-600">{{ number_format($totalDosen) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-500 text-sm">Total Mata Kuliah</div>
            <div class="text-3xl font-bold text-yellow-600">{{ number_format($totalMatakuliah) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-500 text-sm">Total Jadwal</div>
            <div class="text-3xl font-bold text-purple-600">{{ number_format($totalJadwal) }}</div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Jumlah Mahasiswa per Jurusan</h2>
            <canvas id="barChart" height="200"></canvas>
            <div class="mt-4">
                @foreach($mahasiswaPerJurusan as $item)
                    <div class="text-sm">{{ $item->nama_jurusan }}: {{ $item->total }} mahasiswa</div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Aktivitas per Minggu</h2>
            <canvas id="lineChart" height="200"></canvas>
            <div class="mt-4 text-sm text-gray-500">
                @foreach($aktivitasPerMinggu as $item)
                    Minggu {{ $item->minggu }}: {{ $item->total }} aktivitas
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Aktivitas Terbaru</h2>
        <div class="space-y-3">
            @foreach($aktivitasTerbaru as $aktivitas)
            <div class="border-b pb-2">
                <div class="flex justify-between">
                    <span>{{ $aktivitas->nama_mahasiswa }} - {{ $aktivitas->nama_mk }}</span>
                    <span class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($aktivitas->created_at)->diffForHumans() }}</span>
                </div>
                <div class="text-sm text-gray-600">Status: {{ ucfirst($aktivitas->status) }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Bar Chart
    const barCtx = document.getElementById('barChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: @json($mahasiswaPerJurusan->pluck('nama_jurusan')),
            datasets: [{
                label: 'Jumlah Mahasiswa',
                data: @json($mahasiswaPerJurusan->pluck('total')),
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } }
        }
    });

    // Line Chart
    const lineCtx = document.getElementById('lineChart').getContext('2d');
    new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: @json($aktivitasPerMinggu->map(fn($item) => 'Minggu ' . $item->minggu)),
            datasets: [{
                label: 'Aktivitas',
                data: @json($aktivitasPerMinggu->pluck('total')),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endsection