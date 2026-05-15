@extends('layouts.app')

@section('title', 'Tambah Mahasiswa')

@section('content')
<div class="bg-white rounded-lg shadow max-w-2xl mx-auto">
    <div class="p-6 border-b">
        <h1 class="text-2xl font-bold">Tambah Mahasiswa</h1>
        <p class="text-gray-500 text-sm mt-1">Isi form berikut untuk menambahkan data mahasiswa baru</p>
    </div>
    
    <div class="p-6">
        <form action="{{ route('mahasiswa.store') }}" method="POST">
            @csrf
            
            <div class="grid md:grid-cols-2 gap-4">
                <!-- NIM -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        NIM <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="nim" 
                           required 
                           maxlength="20"
                           class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 @error('nim') border-red-500 @enderror"
                           value="{{ old('nim') }}"
                           placeholder="Contoh: 2024000001">
                    @error('nim')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @else
                        <p class="text-gray-500 text-xs mt-1">Masukkan NIM mahasiswa (unik)</p>
                    @enderror
                </div>
                
                <!-- Nama Mahasiswa -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Mahasiswa <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="nama_mahasiswa" 
                           required 
                           maxlength="100"
                           class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 @error('nama_mahasiswa') border-red-500 @enderror"
                           value="{{ old('nama_mahasiswa') }}"
                           placeholder="Contoh: Ahmad Faiz">
                    @error('nama_mahasiswa')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Jurusan -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jurusan <span class="text-red-500">*</span>
                    </label>
                    <select name="jurusan_id" 
                            required 
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 @error('jurusan_id') border-red-500 @enderror">
                        <option value="">-- Pilih Jurusan --</option>
                        @foreach($jurusans as $j)
                        <option value="{{ $j->id }}" {{ old('jurusan_id') == $j->id ? 'selected' : '' }}>
                            {{ $j->kode_jurusan }} - {{ $j->nama_jurusan }}
                        </option>
                        @endforeach
                    </select>
                    @error('jurusan_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Angkatan -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Angkatan <span class="text-red-500">*</span>
                    </label>
                    <select name="angkatan" 
                            required 
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 @error('angkatan') border-red-500 @enderror">
                        <option value="">-- Pilih Angkatan --</option>
                        @for($i = 2020; $i <= 2026; $i++)
                        <option value="{{ $i }}" {{ old('angkatan') == $i ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                        @endfor
                    </select>
                    @error('angkatan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" 
                           name="email" 
                           required 
                           class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 @error('email') border-red-500 @enderror"
                           value="{{ old('email') }}"
                           placeholder="contoh: mahasiswa@email.com">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- No HP -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        No HP
                    </label>
                    <input type="text" 
                           name="no_hp" 
                           maxlength="15"
                           class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 @error('no_hp') border-red-500 @enderror"
                           value="{{ old('no_hp') }}"
                           placeholder="Contoh: 081234567890">
                    @error('no_hp')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @else
                        <p class="text-gray-500 text-xs mt-1">Nomor telepon/handphone (opsional)</p>
                    @enderror
                </div>
            </div>
            
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4 mt-2">
                <p class="text-sm text-blue-700">
                    <i class="fas fa-info-circle mr-2"></i>
                    Pastikan data yang dimasukkan sudah benar. NIM dan Email harus unik.
                </p>
            </div>
            
            <div class="flex gap-3 mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
                <a href="{{ route('mahasiswa.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Validasi nim hanya angka
    document.querySelector('input[name="nim"]').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    
    // Validasi no hp hanya angka
    document.querySelector('input[name="no_hp"]').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
</script>
@endsection