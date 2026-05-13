<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIAKAD - Sistem Informasi Akademik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .sidebar-item.active {
            background-color: #374151;
            border-left: 4px solid #3b82f6;
        }
        .table-container {
            overflow-x: auto;
        }
        .btn-primary {
            background-color: #3b82f6;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background-color: #2563eb;
        }
        @media print {
            .sidebar, .top-bar, .btn-print, .btn-back {
                display: none;
            }
            body {
                background: white;
            }
            .main-content {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <div class="sidebar w-64 bg-gradient-to-b from-gray-900 to-gray-800 text-white min-h-screen sticky top-0">
            <div class="p-4 text-xl font-bold border-b border-gray-700 flex items-center gap-2">
                <i class="fas fa-graduation-cap"></i>
                <span>SIAKAD</span>
            </div>
            <nav class="mt-4">
                <a href="{{ route('dashboard') }}" class="sidebar-item block py-2.5 px-4 hover:bg-gray-700 transition {{ request()->routeIs('dashboard') ? 'active bg-gray-700' : '' }}">
                    <i class="fas fa-tachometer-alt w-5 mr-2"></i> Dashboard
                </a>
                <a href="{{ route('mahasiswa.index') }}" class="sidebar-item block py-2.5 px-4 hover:bg-gray-700 transition {{ request()->routeIs('mahasiswa.*') ? 'active bg-gray-700' : '' }}">
                    <i class="fas fa-users w-5 mr-2"></i> Mahasiswa
                </a>
                <a href="{{ route('jurusan.index') }}" class="sidebar-item block py-2.5 px-4 hover:bg-gray-700 transition {{ request()->routeIs('jurusan.*') ? 'active bg-gray-700' : '' }}">
                    <i class="fas fa-building w-5 mr-2"></i> Jurusan
                </a>
                <a href="{{ route('kelas.index') }}" class="sidebar-item block py-2.5 px-4 hover:bg-gray-700 transition {{ request()->routeIs('kelas.*') ? 'active bg-gray-700' : '' }}">
                    <i class="fas fa-chalkboard w-5 mr-2"></i> Kelas
                </a>
                <a href="{{ route('dosen.index') }}" class="sidebar-item block py-2.5 px-4 hover:bg-gray-700 transition {{ request()->routeIs('dosen.*') ? 'active bg-gray-700' : '' }}">
                    <i class="fas fa-chalkboard-user w-5 mr-2"></i> Dosen
                </a>
                <a href="{{ route('matakuliah.index') }}" class="sidebar-item block py-2.5 px-4 hover:bg-gray-700 transition {{ request()->routeIs('matakuliah.*') ? 'active bg-gray-700' : '' }}">
                    <i class="fas fa-book w-5 mr-2"></i> Mata Kuliah
                </a>
                <a href="{{ route('ruangan.index') }}" class="sidebar-item block py-2.5 px-4 hover:bg-gray-700 transition {{ request()->routeIs('ruangan.*') ? 'active bg-gray-700' : '' }}">
                    <i class="fas fa-door-open w-5 mr-2"></i> Ruangan
                </a>
                <a href="{{ route('jadwal.index') }}" class="sidebar-item block py-2.5 px-4 hover:bg-gray-700 transition {{ request()->routeIs('jadwal.*') ? 'active bg-gray-700' : '' }}">
                    <i class="fas fa-calendar-alt w-5 mr-2"></i> Jadwal
                </a>
                <a href="{{ route('krs.index') }}" class="sidebar-item block py-2.5 px-4 hover:bg-gray-700 transition {{ request()->routeIs('krs.*') ? 'active bg-gray-700' : '' }}">
                    <i class="fas fa-edit w-5 mr-2"></i> KRS
                </a>
                <a href="{{ route('tahun-akademik.index') }}" class="sidebar-item block py-2.5 px-4 hover:bg-gray-700 transition {{ request()->routeIs('tahun-akademik.*') ? 'active bg-gray-700' : '' }}">
                    <i class="fas fa-calendar w-5 mr-2"></i> Tahun Akademik
                </a>
                <a href="{{ route('nilai.index') }}" class="sidebar-item block py-2.5 px-4 hover:bg-gray-700 transition {{ request()->routeIs('nilai.*') ? 'active bg-gray-700' : '' }}">
                    <i class="fas fa-chart-line w-5 mr-2"></i> Nilai
                </a>
                <a href="{{ route('absensi.index') }}" class="sidebar-item block py-2.5 px-4 hover:bg-gray-700 transition {{ request()->routeIs('absensi.*') ? 'active bg-gray-700' : '' }}">
                    <i class="fas fa-fingerprint w-5 mr-2"></i> Absensi
                </a>
                <a href="{{ route('user-management.index') }}" class="sidebar-item block py-2.5 px-4 hover:bg-gray-700 transition {{ request()->routeIs('user-management.*') ? 'active bg-gray-700' : '' }}">
                    <i class="fas fa-user-shield w-5 mr-2"></i> User Management
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Top Bar -->
            <div class="top-bar bg-white shadow px-6 py-3 flex justify-between items-center sticky top-0 z-10">
                <h1 class="text-xl font-semibold text-gray-800">
                    @yield('title', 'Dashboard')
                </h1>
                @auth
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <i class="fas fa-bell text-gray-600 cursor-pointer hover:text-gray-800"></i>
                    </div>
                    <div class="flex items-center gap-2 border-l pl-4">
                        <i class="fas fa-user-circle text-gray-600 text-2xl"></i>
                        <div>
                            <span class="text-gray-700 text-sm block">{{ Auth::user()->name ?? 'Admin' }}</span>
                            <span class="text-gray-500 text-xs">{{ Auth::user()->role ?? 'Administrator' }}</span>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="inline ml-2">
                            @csrf
                            <button type="submit" class="text-red-500 hover:text-red-700">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @endauth
            </div>
            
            <!-- Content -->
            <div class="p-6">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-4 flex justify-between items-center">
                        <div>
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-green-700">&times;</button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-4 flex justify-between items-center">
                        <div>
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ session('error') }}
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-red-700">&times;</button>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(event, message = 'Apakah Anda yakin ingin menghapus data ini?') {
            if (!confirm(message)) {
                event.preventDefault();
                return false;
            }
            return true;
        }
        
        function printPage() {
            window.print();
        }
        
        $(document).ready(function() {
            // Auto hide alert after 5 seconds
            setTimeout(function() {
                $('.bg-green-100, .bg-red-100').fadeOut('slow');
            }, 5000);
        });
    </script>
</body>
</html>