@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="bg-white rounded-lg shadow max-w-2xl mx-auto">
    <div class="p-6 border-b">
        <h1 class="text-2xl font-bold">Edit User</h1>
    </div>
    
    <div class="p-6">
        <form action="{{ route('user-management.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama *</label>
                    <input type="text" name="name" required class="w-full border rounded-lg px-4 py-2" value="{{ old('name', $user->name) }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" required class="w-full border rounded-lg px-4 py-2" value="{{ old('email', $user->email) }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password (kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" class="w-full border rounded-lg px-4 py-2">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role *</label>
                    <select name="role" id="role" required class="w-full border rounded-lg px-4 py-2">
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="dosen" {{ old('role', $user->role) == 'dosen' ? 'selected' : '' }}>Dosen</option>
                        <option value="mahasiswa" {{ old('role', $user->role) == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                    </select>
                </div>
                
                <div id="mahasiswa_field" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mahasiswa</label>
                    <select name="mahasiswa_id" class="w-full border rounded-lg px-4 py-2">
                        <option value="">Pilih Mahasiswa</option>
                        @foreach($mahasiswas as $m)
                        <option value="{{ $m->id }}" {{ old('mahasiswa_id', $user->mahasiswa_id) == $m->id ? 'selected' : '' }}>
                            {{ $m->nim }} - {{ $m->nama_mahasiswa }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <div id="dosen_field" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dosen</label>
                    <select name="dosen_id" class="w-full border rounded-lg px-4 py-2">
                        <option value="">Pilih Dosen</option>
                        @foreach($dosens as $d)
                        <option value="{{ $d->id }}" {{ old('dosen_id', $user->dosen_id) == $d->id ? 'selected' : '' }}>
                            {{ $d->nidn }} - {{ $d->nama_dosen }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="flex gap-3 mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
                <a href="{{ route('user-management.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleFields() {
        var role = document.getElementById('role').value;
        var mahasiswaField = document.getElementById('mahasiswa_field');
        var dosenField = document.getElementById('dosen_field');
        
        mahasiswaField.style.display = 'none';
        dosenField.style.display = 'none';
        
        if (role === 'mahasiswa') {
            mahasiswaField.style.display = 'block';
        } else if (role === 'dosen') {
            dosenField.style.display = 'block';
        }
    }
    
    document.getElementById('role').addEventListener('change', toggleFields);
    toggleFields();
</script>
@endsection