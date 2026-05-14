<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi Absensi - SIAKAD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gradient-to-r from-blue-500 to-purple-600 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-8">
        <div class="text-center mb-6">
            <i class="fas fa-fingerprint text-5xl text-blue-500 mb-3"></i>
            <h1 class="text-2xl font-bold">Verifikasi Absensi</h1>
            <p class="text-gray-500 text-sm mt-2">Masukkan NIM Anda untuk konfirmasi kehadiran</p>
        </div>
        
        <div class="mb-4 p-3 bg-gray-50 rounded-lg">
            <h3 class="font-semibold text-gray-700">Informasi Mata Kuliah</h3>
            <p class="text-gray-600 text-sm mt-1">{{ $jadwal->nama_mk }}</p>
            <p class="text-gray-500 text-xs mt-1">
                {{ $jadwal->hari }} | {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
            </p>
        </div>
        
        <form id="absensiForm">
            @csrf
            <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}" id="jadwal_id">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">NIM Mahasiswa</label>
                <input type="text" id="nim" name="nim" required 
                       placeholder="Masukkan NIM (contoh: 2024000001)"
                       class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                       autocomplete="off">
            </div>
            
            <button type="submit" id="submitBtn" 
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-lg transition font-semibold">
                <i class="fas fa-check-circle mr-2"></i>Konfirmasi Kehadiran
            </button>
        </form>
        
        <div id="result" class="mt-4 hidden"></div>
        
        <div class="mt-6 text-center text-xs text-gray-500">
            <i class="fas fa-shield-alt mr-1"></i> Sistem Absensi Terintegrasi
        </div>
    </div>
    
    <script>
        $('#absensiForm').on('submit', function(e) {
            e.preventDefault();
            
            var nim = $('#nim').val();
            var jadwal_id = $('#jadwal_id').val();
            
            if (!nim) {
                alert('Silahkan masukkan NIM anda!');
                return;
            }
            
            $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...');
            
            $.ajax({
                url: "{{ route('absensi.process') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    nim: nim,
                    jadwal_id: jadwal_id
                },
                success: function(response) {
                    $('#result').removeClass('hidden').html(
                        '<div class="' + (response.success ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') + ' p-3 rounded-lg">' +
                        '<i class="fas ' + (response.success ? 'fa-check-circle' : 'fa-exclamation-circle') + ' mr-2"></i>' +
                        response.message +
                        '</div>'
                    );
                    
                    if (response.success) {
                        $('#nim').val('');
                        setTimeout(function() {
                            window.close();
                        }, 2000);
                    }
                },
                error: function(xhr) {
                    $('#result').removeClass('hidden').html(
                        '<div class="bg-red-100 text-red-700 p-3 rounded-lg">' +
                        '<i class="fas fa-exclamation-circle mr-2"></i>Terjadi kesalahan. Silahkan coba lagi.' +
                        '</div>'
                    );
                },
                complete: function() {
                    $('#submitBtn').prop('disabled', false).html('<i class="fas fa-check-circle mr-2"></i>Konfirmasi Kehadiran');
                }
            });
        });
        
        // Auto focus ke input NIM
        $('#nim').focus();
    </script>
</body>
</html>