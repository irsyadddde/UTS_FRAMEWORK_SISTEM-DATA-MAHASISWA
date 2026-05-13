<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Login - SIAKAD</title><script src="https://cdn.tailwindcss.com"></script><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></head>
<body class="bg-gradient-to-r from-blue-500 to-purple-600 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-8">
        <div class="text-center mb-8"><i class="fas fa-graduation-cap text-5xl text-blue-500"></i><h1 class="text-2xl font-bold mt-2">SIAKAD</h1><p class="text-gray-500">Sistem Informasi Akademik</p></div>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-4"><label class="block text-sm font-medium mb-2">Email</label><input type="email" name="email" required class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500" placeholder="admin@siakad.com"></div>
            <div class="mb-6"><label class="block text-sm font-medium mb-2">Password</label><input type="password" name="password" required class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500" placeholder="******"></div>
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg transition"><i class="fas fa-sign-in-alt mr-2"></i>Login</button>
        </form>
        <div class="mt-6 text-center text-sm text-gray-500"><p>Admin: admin@siakad.com / admin123</p><p>Dosen: (email dosen) / dosen123</p><p>Mahasiswa: (email mahasiswa) / mahasiswa123</p></div>
    </div>
</body>
</html>