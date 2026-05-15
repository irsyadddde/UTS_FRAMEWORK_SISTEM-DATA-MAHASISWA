@extends('layouts.app')

@section('title', 'Input Nilai')

@section('content')
<div class="bg-white rounded-lg shadow max-w-3xl mx-auto">
    <div class="p-6 border-b">
        <h1 class="text-2xl font-bold">Input Nilai Mahasiswa</h1>
        <p class="text-gray-500 text-sm mt-1">Masukkan nilai tugas, UTS, dan UAS untuk mahasiswa</p>
    </div>
    
    <div class="p-6">
        <form action="{{ route('nilai.store') }}" method="POST">
            @csrf
            
            <!-- Pilih KRS -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Pilih Mahasiswa & Mata Kuliah <span class="text-red-500">*</span>
                </label>
                <select name="krs_id" id="krs_id" required 
                        class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 @error('krs_id') border-red-500 @enderror">
                    <option value="">-- Pilih KRS --</option>
                    @foreach($krsList as $krs)
                    <option value="{{ $krs->krs_id }}" {{ old('krs_id') == $krs->krs_id ? 'selected' : '' }}>
                        {{ $krs->nim }} - {{ $krs->nama_mahasiswa }} ({{ $krs->nama_mk }})
                    </option>
                    @endforeach
                </select>
                @error('krs_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @else
                    <p class="text-gray-500 text-xs mt-1">Pilih mahasiswa dan mata kuliah yang akan dinilai</p>
                @enderror
            </div>
            
            <!-- Informasi KRS yang dipilih (ditampilkan dengan JS) -->
            <div id="krs_info" class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4 hidden">
                <p class="text-sm text-blue-700">
                    <i class="fas fa-info-circle mr-2"></i>
                    <span id="krs_info_text"></span>
                </p>
            </div>
            
            <!-- Form Nilai -->
            <div class="grid md:grid-cols-3 gap-4">
                <!-- Nilai Tugas -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nilai Tugas
                        <span class="text-gray-500 text-xs">(Bobot 30%)</span>
                    </label>
                    <input type="number" 
                           name="nilai_tugas" 
                           step="0.01" 
                           min="0" 
                           max="100"
                           class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 @error('nilai_tugas') border-red-500 @enderror"
                           value="{{ old('nilai_tugas') }}"
                           placeholder="0 - 100">
                    @error('nilai_tugas')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Nilai UTS -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nilai UTS
                        <span class="text-gray-500 text-xs">(Bobot 30%)</span>
                    </label>
                    <input type="number" 
                           name="nilai_uts" 
                           step="0.01" 
                           min="0" 
                           max="100"
                           class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 @error('nilai_uts') border-red-500 @enderror"
                           value="{{ old('nilai_uts') }}"
                           placeholder="0 - 100">
                    @error('nilai_uts')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Nilai UAS -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nilai UAS
                        <span class="text-gray-500 text-xs">(Bobot 40%)</span>
                    </label>
                    <input type="number" 
                           name="nilai_uas" 
                           step="0.01" 
                           min="0" 
                           max="100"
                           class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 @error('nilai_uas') border-red-500 @enderror"
                           value="{{ old('nilai_uas') }}"
                           placeholder="0 - 100">
                    @error('nilai_uas')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Preview Nilai Akhir -->
            <div id="preview" class="bg-gray-50 p-4 rounded-lg mb-4 hidden">
                <h4 class="font-semibold text-gray-700 mb-2">Preview Nilai Akhir:</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nilai Akhir: 
                            <span id="preview_nilai_akhir" class="font-bold text-blue-600">-</span>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Grade: 
                            <span id="preview_grade" class="font-bold text-green-600">-</span>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-4">
                <p class="text-sm text-yellow-700">
                    <i class="fas fa-calculator mr-2"></i>
                    <strong>Rumus Perhitungan:</strong> Nilai Akhir = (Tugas x 30%) + (UTS x 30%) + (UAS x 40%)
                </p>
                <p class="text-sm text-yellow-700 mt-1">
                    <strong>Grade:</strong> A (≥85), A- (80-84), B+ (75-79), B (70-74), B- (65-69), 
                    C+ (60-64), C (55-59), D (50-54), E (≤49)
                </p>
            </div>
            
            <div class="flex gap-3 mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-save mr-2"></i>Simpan Nilai
                </button>
                <a href="{{ route('nilai.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Ambil data KRS dari server (dijadikan JavaScript object)
    const krsData = @json($krsList);
    
    // Fungsi untuk menghitung nilai akhir
    function hitungNilaiAkhir(tugas, uts, uas) {
        tugas = parseFloat(tugas) || 0;
        uts = parseFloat(uts) || 0;
        uas = parseFloat(uas) || 0;
        return (tugas * 0.3) + (uts * 0.3) + (uas * 0.4);
    }
    
    // Fungsi untuk menentukan grade
    function getGrade(nilai) {
        if (nilai >= 85) return 'A';
        if (nilai >= 80) return 'A-';
        if (nilai >= 75) return 'B+';
        if (nilai >= 70) return 'B';
        if (nilai >= 65) return 'B-';
        if (nilai >= 60) return 'C+';
        if (nilai >= 55) return 'C';
        if (nilai >= 50) return 'D';
        return 'E';
    }
    
    // Update preview
    function updatePreview() {
        const tugas = document.querySelector('input[name="nilai_tugas"]').value;
        const uts = document.querySelector('input[name="nilai_uts"]').value;
        const uas = document.querySelector('input[name="nilai_uas"]').value;
        
        if (tugas !== '' || uts !== '' || uas !== '') {
            const nilaiAkhir = hitungNilaiAkhir(tugas, uts, uas);
            const grade = getGrade(nilaiAkhir);
            
            document.getElementById('preview_nilai_akhir').innerText = nilaiAkhir.toFixed(2);
            document.getElementById('preview_grade').innerText = grade;
            document.getElementById('preview').classList.remove('hidden');
        } else {
            document.getElementById('preview').classList.add('hidden');
        }
    }
    
    // Event listener untuk input nilai
    document.querySelectorAll('input[name="nilai_tugas"], input[name="nilai_uts"], input[name="nilai_uas"]').forEach(input => {
        input.addEventListener('input', updatePreview);
        
        // Validasi range
        input.addEventListener('change', function() {
            let val = parseFloat(this.value);
            if (this.value !== '' && (val < 0 || val > 100)) {
                alert('Nilai harus antara 0 - 100!');
                this.value = '';
                updatePreview();
            }
        });
    });
    
    // Tampilkan informasi KRS yang dipilih
    document.getElementById('krs_id').addEventListener('change', function() {
        const selectedId = parseInt(this.value);
        const krs = krsData.find(k => k.krs_id === selectedId);
        
        if (krs) {
            document.getElementById('krs_info_text').innerHTML = 
                '<strong>' + krs.nama_mahasiswa + '</strong> (' + krs.nim + ') - Mata Kuliah: <strong>' + krs.nama_mk + '</strong>';
            document.getElementById('krs_info').classList.remove('hidden');
        } else {
            document.getElementById('krs_info').classList.add('hidden');
        }
    });
    
    // Trigger change event if there's an old value
    if (document.getElementById('krs_id').value) {
        document.getElementById('krs_id').dispatchEvent(new Event('change'));
    }
</script>
@endsection