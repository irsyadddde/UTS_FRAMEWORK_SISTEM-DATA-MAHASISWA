@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b flex justify-between items-center">
        <h1 class="text-2xl font-bold">User Management</h1>
        <a href="{{ route('user-management.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg"><i class="fas fa-plus mr-2"></i>Tambah User</a>
    </div>
    <div class="p-6">
        <form method="GET" class="mb-4 flex gap-2">
            <input type="text" name="search" placeholder="Cari..." class="flex-1 border rounded-lg px-4 py-2" value="{{ request('search') }}">
            <select name="role" class="border rounded-lg px-4 py-2"><option value="">Semua Role</option><option value="admin">Admin</option><option value="dosen">Dosen</option><option value="mahasiswa">Mahasiswa</option></select>
            <button type="submit" class="bg-gray-500 text-white px-6 py-2 rounded-lg"><i class="fas fa-search"></i></button>
        </form>
        <div class="overflow-x-auto">
            <table class="min-w-full"><thead class="bg-gray-50"><tr><th>Nama</th><th>Email</th><th>Role</th><th>Terhubung Dengan</th><th>Aksi</th></tr></thead>
                <tbody>@foreach($users as $u)<tr><td>{{ $u->name }}</td><td>{{ $u->email }}</td><td><span class="px-2 py-1 rounded text-xs @if($u->role=='admin') bg-red-100 text-red-700 @elseif($u->role=='dosen') bg-blue-100 text-blue-700 @else bg-green-100 text-green-700 @endif">{{ $u->role }}</span></td><td>@if($u->role=='mahasiswa') {{ $u->nim }} - {{ $u->mahasiswa_name }} @elseif($u->role=='dosen') {{ $u->nidn }} - {{ $u->dosen_name }} @else - @endif</td><td><div class="flex gap-2"><a href="{{ route('user-management.edit', $u->id) }}" class="text-green-500"><i class="fas fa-edit"></i></a><a href="{{ route('user-management.reset-password', $u->id) }}" class="text-yellow-500" onclick="return confirm('Reset password?')"><i class="fas fa-key"></i></a><form action="{{ route('user-management.destroy', $u->id) }}" method="POST" onsubmit="return confirmDelete(event)" class="inline">@csrf @method('DELETE')<button class="text-red-500"><i class="fas fa-trash"></i></button></form></div></td></tr>@endforeach</tbody>
            </table>
        </div>
        <div class="mt-4">{{ $users->links() }}</div>
    </div>
</div>
@endsection