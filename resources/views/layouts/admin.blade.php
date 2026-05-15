<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIAKAD - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .sidebar-item.active { background-color: #374151; border-left: 4px solid #3b82f6; }
        .sidebar-item:hover { background-color: #374151; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #888; border-radius: 4px; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-72 bg-gradient-to-b from-gray-900 to-gray-800 text-white flex-shrink-0 overflow-y-auto">
            <div class="p-5 border-b border-gray-700">
                <div class="flex items-center gap-3">
                    <i class="fas fa-graduation-cap text-2xl text-blue-400"></i>
                    <div>
                        <h1 class="text-xl font-bold">SIAKAD</h1>
                        <p class="text-xs text-gray-400">Admin Panel</p>
                    </div>
                </div>
            </div>
            <nav class="p-4">
                <div class="mb-4">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">Main Menu</p>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.home') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt w-5"></i><span>Dashboard</span>
                    </a>
                </div>
                
                <div class="mb-4">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">Master Data</p>
                    <a href="{{ route('admin.jurusan.index') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg transition"><i class="fas fa-building w-5"></i><span>Jurusan</span></a>
                    <a href="{{ route('admin.mahasiswa.index') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg transition"><i class="fas fa-users w-5"></i><span>Mahasiswa</span></a>
                    <a href="{{ route('admin.dosen.index') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg transition"><i class="fas fa-chalkboard-user w-5"></i><span>Dosen</span></a>
                    <a href="{{ route('admin.matakuliah.index') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg transition"><i class="fas fa-book w-5"></i><span>Mata Kuliah</span></a>
                    <a href="{{ route('admin.kelas.index') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg transition"><i class="fas fa-chalkboard w-5"></i><span>Kelas</span></a>
                    <a href="{{ route('admin.ruangan.index') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg transition"><i class="fas fa-door-open w-5"></i><span>Ruangan</span></a>
                </div>
                
                <div class="mb-4">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">Akademik</p>
                    <a href="{{ route('admin.jadwal.index') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg transition"><i class="fas fa-calendar-alt w-5"></i><span>Jadwal</span></a>
                    <a href="{{ route('admin.tahun-akademik.index') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg transition"><i class="fas fa-calendar w-5"></i><span>Tahun Akademik</span></a>
                    <a href="{{ route('admin.krs.index') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg transition"><i class="fas fa-edit w-5"></i><span>KRS</span></a>
                    <a href="{{ route('admin.nilai.index') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg transition"><i class="fas fa-chart-line w-5"></i><span>Nilai</span></a>
                    <a href="{{ route('admin.absensi.index') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg transition"><i class="fas fa-fingerprint w-5"></i><span>Absensi</span></a>
                </div>
                
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">System</p>
                    <a href="{{ route('admin.user-management.index') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg transition"><i class="fas fa-user-shield w-5"></i><span>User Management</span></a>
                    <a href="{{ route('admin.profile') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg transition"><i class="fas fa-user-circle w-5"></i><span>Profile</span></a>
                    <form action="{{ route('logout') }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-red-400 hover:bg-red-900/20 transition">
                            <i class="fas fa-sign-out-alt w-5"></i><span>Logout</span>
                        </button>
                    </form>
                </div>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto">
            <div class="bg-white shadow-sm sticky top-0 z-10 px-6 py-4 flex justify-between items-center">
                <h1 class="text-xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>
                <div class="flex items-center gap-4">
                    <div class="relative"><i class="fas fa-bell text-gray-500 cursor-pointer"></i><span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">3</span></div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white"><i class="fas fa-user"></i></div>
                        <div><p class="text-sm font-medium text-gray-700">{{ session('user_name', 'Admin') }}</p><p class="text-xs text-gray-500">Administrator</p></div>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-4 flex justify-between"><span>{{ session('success') }}</span><button onclick="this.parentElement.remove()" class="text-green-700">&times;</button></div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4 flex justify-between"><span>{{ session('error') }}</span><button onclick="this.parentElement.remove()" class="text-red-700">&times;</button></div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>
    
    <script>
        function confirmDelete(event, message = 'Apakah Anda yakin ingin menghapus data ini?') {
            if (!confirm(message)) { event.preventDefault(); return false; }
            return true;
        }
        $(document).ready(function() { setTimeout(function() { $('.bg-green-100, .bg-red-100').fadeOut('slow'); }, 5000); });
    </script>
</body>
</html>