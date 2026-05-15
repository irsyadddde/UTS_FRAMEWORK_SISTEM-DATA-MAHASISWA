<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - SIAKAD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-r from-blue-500 to-purple-600 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-8">
        <div class="text-center mb-8">
            <i class="fas fa-graduation-cap text-5xl text-blue-500 mb-3"></i>
            <h1 class="text-2xl font-bold text-gray-800">SIAKAD</h1>
            <p class="text-gray-500 text-sm">Sistem Informasi Akademik</p>
        </div>
        
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
        
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        
        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 rounded mb-4">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        
        <form action="/login" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" name="email" required 
                       class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500"
                       placeholder="admin@siakad.com" value="{{ old('email') }}">
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" name="password" required 
                       class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500"
                       placeholder="******">
            </div>
            
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 rounded-lg transition">
                <i class="fas fa-sign-in-alt mr-2"></i>Login
            </button>
        </form>
        
        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <p class="text-xs text-center text-gray-500 mb-2">Demo Login:</p>
            <div class="grid grid-cols-3 gap-2 text-xs">
                <div class="text-center">
                    <p class="font-semibold text-blue-600">Admin</p>
                    <p class="text-gray-500">admin@siakad.com</p>
                    <p class="text-gray-400">admin123</p>
                </div>
                <div class="text-center">
                    <p class="font-semibold text-green-600">Dosen</p>
                    <p class="text-gray-500">dosen1@univ.ac.id</p>
                    <p class="text-gray-400">dosen123</p>
                </div>
                <div class="text-center">
                    <p class="font-semibold text-orange-600">Mahasiswa</p>
                    <p class="text-gray-500">mahasiswa1@univ.ac.id</p>
                    <p class="text-gray-400">mahasiswa123</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>