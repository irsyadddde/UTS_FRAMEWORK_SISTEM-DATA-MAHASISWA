<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Verifikasi - SIAKAD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-r from-blue-500 to-purple-600 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-8 text-center">
        @if($success)
            <div class="text-green-500 mb-4">
                <i class="fas fa-check-circle text-6xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Berhasil!</h1>
            <p class="text-gray-600">{{ $message }}</p>
        @else
            <div class="text-red-500 mb-4">
                <i class="fas fa-times-circle text-6xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Gagal!</h1>
            <p class="text-gray-600">{{ $message }}</p>
        @endif
        
        <div class="mt-6">
            <button onclick="window.close()" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
                <i class="fas fa-times mr-2"></i>Tutup
            </button>
        </div>
    </div>
</body>
</html>