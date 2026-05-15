@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="bg-white rounded-lg shadow max-w-2xl mx-auto">
    <div class="p-6 border-b">
        <h1 class="text-2xl font-bold">Profile Saya</h1>
    </div>
    
    <div class="p-6">
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                <input type="text" name="name" required class="w-full border rounded-lg px-4 py-2" value="{{ old('name', $user->name) }}">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email" required class="w-full border rounded-lg px-4 py-2" value="{{ old('email', $user->email) }}">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru (kosongkan jika tidak diubah)</label>
                <input type="password" name="password" class="w-full border rounded-lg px-4 py-2">
            </div>
            
            @if($profile)
            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <h3 class="font-semibold mb-2">Informasi Tambahan</h3>
                @if($user->role == 'mahasiswa')
                    <p>NIM: {{ $profile->nim }}</p>
                    <p>Angkatan: {{ $profile->angkatan }}</p>
                @elseif($user->role == 'dosen')
                    <p>NIDN: {{ $profile->nidn }}</p>
                @endif
            </div>
            @endif
            
            <div class="flex gap-3">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-save mr-2"></i>Update Profile
                </button>
                <a href="/" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection