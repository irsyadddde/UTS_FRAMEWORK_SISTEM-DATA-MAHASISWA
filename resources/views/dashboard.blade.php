@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="p-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-gray-500 text-sm">Total Mahasiswa</div>
                    <div class="text-3xl font-bold text-blue-600">{{ number_format($totalMahasiswa) }}</div>
                </div>
                <i class="fas fa-users text-blue-600 text-4xl opacity-50"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-gray-500 text-sm">Total Dosen</div>
                    <div class="text-3xl font-bold text-green-600">{{ number_format($totalDosen) }}</div>
                </div>
                <i class="fas fa-chalkboard-user text-green-600 text-4xl opacity-50"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-gray-500 text-sm">Total Mata Kuliah</div>
                    <div class="text-3xl font-bold text-yellow-600">{{ number_format($totalMatakuliah) }}</div>
                </div>
                <i class="fas fa-book text-yellow-600 text-4xl opacity-50"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-gray-500 text-sm">Total Jadwal</div>
                    <div class="text-3xl font-bold text-purple-600">{{ number_format($totalJadwal) }}</div>
                </div>
                <i class="fas fa-calendar-alt text-purple-600 text-4xl opacity-50"></i>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Bar Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Jumlah Mahasiswa per Jurusan</h2>
            <canvas id="barChart" height="250"></canvas>
        </div>

        <!-- Line Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Aktivitas per Minggu</h2>
            <canvas id="lineChart" height="250"></canvas>
        </div>
    </div>

    <!-- Recent Activity & Info -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Aktivitas Terbaru</h2>
            <div class="space-y-3">
                <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded">
                    <i class="fas fa-user-plus text-blue-500"></i>
                    <div class="flex-1">Mahasiswa baru terdaftar: 45 orang</div>
                    <span class="text-sm text-gray-500">2 jam lalu</span>
                </div>
                <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded">
                    <i class="fas fa-calendar-check text-green-500"></i>
                    <div class="flex-1">Jadwal ujian semester genap telah dibuat</div>
                    <span class="text-sm text-gray-500">5 jam lalu</span>
                </div>
                <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded">
                    <i class="fas fa-check-circle text-purple-500"></i>
                    <div class="flex-1">Absensi mahasiswa minggu ini: 92%</div>
                    <span class="text-sm text-gray-500">1 hari lalu</span>
                </div>
                <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded">
                    <i class="fas fa-file-alt text-yellow-500"></i>
                    <div class="flex-1">Nilai UTS telah diinput untuk 12 mata kuliah</div>
                    <span class="text-sm text-gray-500">2 hari lalu</span>
                </div>
                <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded">
                    <i class="fas fa-edit text-red-500"></i>
                    <div class="flex-1">342 mahasiswa telah melakukan KRS online</div>
                    <span class="text-sm text-gray-500">3 hari lalu</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Informasi Akademik</h2>
            <div class="space-y-3">
                <div class="border-b pb-2">
                    <div class="flex justify-between items-center">
                        <span class="font-medium">Tahun Akademik Aktif:</span>
                        <span class="text-green-600 font-semibold">2024/2025 Ganjil</span>
                    </div>
                </div>
                <div class="border-b pb-2">
                    <div class="flex justify-between items-center">
                        <span class="font-medium">Minggu Ke-:</span>
                        <span class="text-blue-600 font-semibold">19 (Ujian Akhir Semester)</span>
                    </div>
                </div>
                <div class="border-b pb-2">
                    <div class="flex justify-between items-center">
                        <span class="font-medium">Persentase Kehadiran:</span>
                        <span class="text-green-600 font-semibold">87.5%</span>
                    </div>
                </div>
                <div class="border-b pb-2">
                    <div class="flex justify-between items-center">
                        <span class="font-medium">Jumlah Kelas Aktif:</span>
                        <span class="text-purple-600 font-semibold">156 Kelas</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Data from controller
    const jurusanData = @json($mahasiswaPerJurusan);
    const aktivitasData = @json($aktivitasPerMinggu);
    
    // Bar Chart
    const barCtx = document.getElementById('barChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: jurusanData.map(item => item.nama_jurusan),
            datasets: [{
                label: 'Jumlah Mahasiswa',
                data: jurusanData.map(item => item.total),
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false
                    }
                }
            }
        }
    });

    // Line Chart
    const lineCtx = document.getElementById('lineChart').getContext('2d');
    new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: aktivitasData.map(item => 'Minggu ' + item.minggu),
            datasets: [{
                label: 'Jumlah Aktivitas',
                data: aktivitasData.map(item => item.total),
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                pointBorderColor: '#fff',
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false
                    }
                }
            }
        }
    });
</script>
@endsection