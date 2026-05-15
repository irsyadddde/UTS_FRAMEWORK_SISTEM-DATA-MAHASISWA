<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIAKAD - Sistem Informasi Akademik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gradient-to-b from-gray-900 to-gray-800 text-white min-h-screen">
            <div class="p-4 text-xl font-bold border-b border-gray-700 flex items-center gap-2">
                <i class="fas fa-graduation-cap"></i>
                <span>SIAKAD</span>
            </div>
            <nav class="mt-4">
                <a href="{{ url('/') }}" class="block py-2.5 px-4 hover:bg-gray-700 transition">
                    <i class="fas fa-tachometer-alt w-5 mr-2"></i> Dashboard
                </a>
                <a href="{{ url('/mahasiswa') }}" class="block py-2.5 px-4 hover:bg-gray-700 transition">
                    <i class="fas fa-users w-5 mr-2"></i> Mahasiswa
                </a>
                <a href="{{ url('/jurusan') }}" class="block py-2.5 px-4 hover:bg-gray-700 transition">
                    <i class="fas fa-building w-5 mr-2"></i> Jurusan
                </a>
                <a href="{{ url('/dosen') }}" class="block py-2.5 px-4 hover:bg-gray-700 transition">
                    <i class="fas fa-chalkboard-user w-5 mr-2"></i> Dosen
                </a>
                <a href="{{ url('/matakuliah') }}" class="block py-2.5 px-4 hover:bg-gray-700 transition">
                    <i class="fas fa-book w-5 mr-2"></i> Mata Kuliah
                </a>
                <a href="{{ url('/kelas') }}" class="block py-2.5 px-4 hover:bg-gray-700 transition">
                    <i class="fas fa-chalkboard w-5 mr-2"></i> Kelas
                </a>
                <a href="{{ url('/ruangan') }}" class="block py-2.5 px-4 hover:bg-gray-700 transition">
                    <i class="fas fa-door-open w-5 mr-2"></i> Ruangan
                </a>
                <a href="{{ url('/jadwal') }}" class="block py-2.5 px-4 hover:bg-gray-700 transition">
                    <i class="fas fa-calendar-alt w-5 mr-2"></i> Jadwal
                </a>
                <a href="{{ url('/krs') }}" class="block py-2.5 px-4 hover:bg-gray-700 transition">
                    <i class="fas fa-edit w-5 mr-2"></i> KRS
                </a>
                <a href="{{ url('/tahun-akademik') }}" class="block py-2.5 px-4 hover:bg-gray-700 transition">
                    <i class="fas fa-calendar w-5 mr-2"></i> Tahun Akademik
                </a>
                <a href="{{ url('/nilai') }}" class="block py-2.5 px-4 hover:bg-gray-700 transition">
                    <i class="fas fa-chart-line w-5 mr-2"></i> Nilai
                </a>
                <a href="{{ url('/absensi') }}" class="block py-2.5 px-4 hover:bg-gray-700 transition">
                    <i class="fas fa-fingerprint w-5 mr-2"></i> Absensi
                </a>
                <a href="{{ url('/user-management') }}" class="block py-2.5 px-4 hover:bg-gray-700 transition">
                    <i class="fas fa-user-shield w-5 mr-2"></i> User Management
                </a>
                <form action="{{ route('logout') }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full text-left block py-2.5 px-4 hover:bg-gray-700 text-red-400">
                        <i class="fas fa-sign-out-alt w-5 mr-2"></i> Logout
                    </button>
                </form>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <div class="bg-white shadow px-6 py-3 flex justify-between items-center sticky top-0 z-10">
                <h1 class="text-xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>
                <div class="flex items-center gap-4">
                    <i class="fas fa-bell text-gray-600 cursor-pointer"></i>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-user-circle text-gray-600 text-2xl"></i>
                        <div>
                            <span class="text-gray-700 text-sm block">{{ session('user_name', 'User') }}</span>
                            <span class="text-gray-500 text-xs">{{ ucfirst(session('user_role', 'Guest')) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
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
        
        $(document).ready(function() {
            setTimeout(function() {
                $('.bg-green-100, .bg-red-100').fadeOut('slow');
            }, 5000);
        });
    </script>
</body>
</html>