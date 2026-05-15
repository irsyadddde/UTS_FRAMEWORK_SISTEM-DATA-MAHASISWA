@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
<div class="p-6">
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-6 mb-8 text-white">
        <h1 class="text-3xl font-bold mb-2">Welcome Back, {{ session('user_name', 'Admin') }}!</h1>
        <p class="text-blue-100">{{ date('l, d F Y') }}</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md p-5"><div class="flex justify-between"><div><p class="text-gray-500 text-sm">Mahasiswa</p><p class="text-2xl font-bold text-blue-600">{{ number_format($totalMahasiswa) }}</p></div><div class="bg-blue-100 p-3 rounded-full"><i class="fas fa-users text-blue-600"></i></div></div></div>
        <div class="bg-white rounded-xl shadow-md p-5"><div class="flex justify-between"><div><p class="text-gray-500 text-sm">Dosen</p><p class="text-2xl font-bold text-green-600">{{ number_format($totalDosen) }}</p></div><div class="bg-green-100 p-3 rounded-full"><i class="fas fa-chalkboard-user text-green-600"></i></div></div></div>
        <div class="bg-white rounded-xl shadow-md p-5"><div class="flex justify-between"><div><p class="text-gray-500 text-sm">Mata Kuliah</p><p class="text-2xl font-bold text-purple-600">{{ number_format($totalMatakuliah) }}</p></div><div class="bg-purple-100 p-3 rounded-full"><i class="fas fa-book text-purple-600"></i></div></div></div>
        <div class="bg-white rounded-xl shadow-md p-5"><div class="flex justify-between"><div><p class="text-gray-500 text-sm">Jadwal</p><p class="text-2xl font-bold text-yellow-600">{{ number_format($totalJadwal) }}</p></div><div class="bg-yellow-100 p-3 rounded-full"><i class="fas fa-calendar-alt text-yellow-600"></i></div></div></div>
        <div class="bg-white rounded-xl shadow-md p-5"><div class="flex justify-between"><div><p class="text-gray-500 text-sm">KRS</p><p class="text-2xl font-bold text-orange-600">{{ number_format($totalKRS) }}</p></div><div class="bg-orange-100 p-3 rounded-full"><i class="fas fa-edit text-orange-600"></i></div></div></div>
        <div class="bg-white rounded-xl shadow-md p-5"><div class="flex justify-between"><div><p class="text-gray-500 text-sm">Absensi</p><p class="text-2xl font-bold text-red-600">{{ number_format($totalAbsensi) }}</p></div><div class="bg-red-100 p-3 rounded-full"><i class="fas fa-fingerprint text-red-600"></i></div></div></div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md p-6"><h2 class="text-lg font-semibold mb-4">Mahasiswa per Jurusan</h2><canvas id="jurusanChart" height="200"></canvas></div>
        <div class="bg-white rounded-xl shadow-md p-6"><h2 class="text-lg font-semibold mb-4">Menu Administrator</h2><div class="grid grid-cols-2 md:grid-cols-3 gap-4"><a href="{{ route('admin.jurusan.index') }}" class="bg-gray-50 p-3 rounded-lg text-center hover:bg-gray-100"><i class="fas fa-building text-blue-500 text-xl mb-1 block"></i><span class="text-sm">Jurusan</span></a><a href="{{ route('admin.mahasiswa.index') }}" class="bg-gray-50 p-3 rounded-lg text-center hover:bg-gray-100"><i class="fas fa-users text-green-500 text-xl mb-1 block"></i><span class="text-sm">Mahasiswa</span></a><a href="{{ route('admin.dosen.index') }}" class="bg-gray-50 p-3 rounded-lg text-center hover:bg-gray-100"><i class="fas fa-chalkboard-user text-yellow-500 text-xl mb-1 block"></i><span class="text-sm">Dosen</span></a><a href="{{ route('admin.matakuliah.index') }}" class="bg-gray-50 p-3 rounded-lg text-center hover:bg-gray-100"><i class="fas fa-book text-purple-500 text-xl mb-1 block"></i><span class="text-sm">Mata Kuliah</span></a><a href="{{ route('admin.jadwal.index') }}" class="bg-gray-50 p-3 rounded-lg text-center hover:bg-gray-100"><i class="fas fa-calendar-alt text-red-500 text-xl mb-1 block"></i><span class="text-sm">Jadwal</span></a><a href="{{ route('admin.absensi.index') }}" class="bg-gray-50 p-3 rounded-lg text-center hover:bg-gray-100"><i class="fas fa-fingerprint text-indigo-500 text-xl mb-1 block"></i><span class="text-sm">Absensi</span></a></div></div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>new Chart(document.getElementById('jurusanChart'),{type:'bar',data:{labels:@json($mahasiswaPerJurusan->pluck('nama_jurusan')),datasets:[{label:'Jumlah Mahasiswa',data:@json($mahasiswaPerJurusan->pluck('total')),backgroundColor:['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6'],borderRadius:8}]},options:{responsive:true}});</script>
@endsection