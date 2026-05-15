@extends('layouts.app')

@section('title', 'Detail User')

@section('content')
<div class="bg-white rounded-lg shadow max-w-2xl mx-auto">
    <div class="p-6 border-b flex justify-between items-center">
        <h1 class="text-2xl font-bold">Detail User</h1>
        <div class="flex gap-2">
            <a href="{{ route('user-management.edit', $user->id) }}" class="bg-green-500 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('user-management.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>
    
    <div class="p-6">
        <table class="w-full">
            <tr><td class="py-2 w-32">Nama</td><td>: {{ $user->name }}</td></tr>
            <tr><td class="py-2">Email</td><td>: {{ $user->email }}</td></tr>
            <tr><td class="py-2">Role</td><td>: {{ $user->role }}</td></tr>
            @if($user->role == 'mahasiswa' && $user->nama_mahasiswa)
            <tr><td class="py-2">Mahasiswa</td><td>: {{ $user->nama_mahasiswa }}</td></tr>
            @endif
            @if($user->role == 'dosen' && $user->nama_dosen)
            <tr><td class="py-2">Dosen</td><td>: {{ $user->nama_dosen }}</td></tr>
            @endif
        </table>
    </div>
</div>
@endsection