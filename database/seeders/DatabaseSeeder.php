<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key checks sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Kosongkan tabel (urutan yang benar berdasarkan foreign key)
        DB::table('nilais')->truncate();
        DB::table('k_r_s')->truncate();
        DB::table('absensis')->truncate();
        DB::table('jadwals')->truncate();
        DB::table('mahasiswas')->truncate();
        DB::table('matakuliahs')->truncate();
        DB::table('dosens')->truncate();
        DB::table('kelas')->truncate();
        DB::table('ruangans')->truncate();
        DB::table('tahun_akademiks')->truncate();
        DB::table('jurusans')->truncate();
        DB::table('users')->truncate();
        
        // 1. Insert Jurusan
        $jurusans = [
            ['kode_jurusan' => 'TI', 'nama_jurusan' => 'Teknik Informatika', 'deskripsi' => 'Program studi yang mempelajari komputasi dan teknologi informasi', 'created_at' => now(), 'updated_at' => now()],
            ['kode_jurusan' => 'SI', 'nama_jurusan' => 'Sistem Informasi', 'deskripsi' => 'Program studi yang mempelajari analisis dan perancangan sistem informasi', 'created_at' => now(), 'updated_at' => now()],
            ['kode_jurusan' => 'MJ', 'nama_jurusan' => 'Manajemen', 'deskripsi' => 'Program studi yang mempelajari manajemen bisnis dan organisasi', 'created_at' => now(), 'updated_at' => now()],
            ['kode_jurusan' => 'AK', 'nama_jurusan' => 'Akuntansi', 'deskripsi' => 'Program studi yang mempelajari akuntansi dan keuangan', 'created_at' => now(), 'updated_at' => now()],
            ['kode_jurusan' => 'HK', 'nama_jurusan' => 'Hukum', 'deskripsi' => 'Program studi yang mempelajari ilmu hukum dan perundang-undangan', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('jurusans')->insert($jurusans);

        // 2. Insert Ruangan
        $ruangans = [];
        $ruangan_names = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
        $lokasi = ['Gedung Utama Lt.1', 'Gedung Utama Lt.2', 'Gedung Timur Lt.1', 'Gedung Barat Lt.1', 'Laboratorium'];
        
        for ($i = 1; $i <= 20; $i++) {
            $ruangans[] = [
                'kode_ruangan' => 'R.' . $ruangan_names[array_rand($ruangan_names)] . $i,
                'nama_ruangan' => 'Ruangan ' . $ruangan_names[array_rand($ruangan_names)] . $i,
                'kapasitas' => rand(30, 100),
                'lokasi' => $lokasi[array_rand($lokasi)],
                'fasilitas' => 'AC, Proyektor, Whiteboard, Kursi',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('ruangans')->insert($ruangans);

        // 3. Insert Tahun Akademik
        $tahun_akademiks = [
            [
                'tahun' => '2023/2024',
                'semester' => 'Ganjil',
                'is_active' => false,
                'tgl_mulai' => '2023-08-01',
                'tgl_selesai' => '2023-12-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tahun' => '2023/2024',
                'semester' => 'Genap',
                'is_active' => false,
                'tgl_mulai' => '2024-02-01',
                'tgl_selesai' => '2024-06-30',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tahun' => '2024/2025',
                'semester' => 'Ganjil',
                'is_active' => true,
                'tgl_mulai' => '2024-08-01',
                'tgl_selesai' => '2024-12-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('tahun_akademiks')->insert($tahun_akademiks);
        
        $active_year_id = DB::table('tahun_akademiks')->where('is_active', true)->first()->id;

        // 4. Insert Dosen (86 data)
        $dosens = [];
        for ($i = 1; $i <= 86; $i++) {
            $dosens[] = [
                'nidn' => str_pad($i, 10, '0', STR_PAD_LEFT),
                'nama_dosen' => $i <= 3 ? 
                    ['Dr. Ahmad Suherman, M.Kom', 'Prof. Siti Aminah, M.Sc', 'Dr. Budi Santoso, M.Eng'][$i-1] : 
                    'Dosen ' . $i,
                'email' => $i <= 3 ?
                    ['ahmad@univ.ac.id', 'siti@univ.ac.id', 'budi@univ.ac.id'][$i-1] :
                    'dosen' . $i . '@univ.ac.id',
                'no_telp' => '0812' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'alamat' => 'Jl. Dosen No. ' . $i,
                'pendidikan_terakhir' => collect(['S2', 'S3'])->random(),
                'jabatan' => collect(['Lektor', 'Lektor Kepala', 'Guru Besar', 'Asisten Ahli'])->random(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('dosens')->insert($dosens);

        // 5. Insert Mata Kuliah (124 data) - DIPERBAIKI UNTUK HINDARI DUPLIKASI
        $used_kode_mk = []; // Track used kode_mk
        $matakuliahs = [];
        
        // Daftar mata kuliah awal dengan kode unik
        $initial_matakuliahs = [
            // Teknik Informatika (jurusan_id = 1)
            ['kode_mk' => 'TI101', 'nama_mk' => 'Pemrograman Web', 'sks' => 3, 'semester' => 3, 'jurusan_id' => 1, 'deskripsi' => 'Mempelajari pemrograman web modern'],
            ['kode_mk' => 'TI102', 'nama_mk' => 'Database', 'sks' => 3, 'semester' => 3, 'jurusan_id' => 1, 'deskripsi' => 'Mempelajari sistem database'],
            ['kode_mk' => 'TI103', 'nama_mk' => 'Jaringan Komputer', 'sks' => 3, 'semester' => 4, 'jurusan_id' => 1, 'deskripsi' => 'Mempelajari jaringan komputer'],
            ['kode_mk' => 'TI104', 'nama_mk' => 'Algoritma dan Struktur Data', 'sks' => 4, 'semester' => 2, 'jurusan_id' => 1, 'deskripsi' => 'Dasar-dasar algoritma'],
            ['kode_mk' => 'TI105', 'nama_mk' => 'Pemrograman Mobile', 'sks' => 3, 'semester' => 5, 'jurusan_id' => 1, 'deskripsi' => 'Pemrograman Android/iOS'],
            ['kode_mk' => 'TI106', 'nama_mk' => 'Machine Learning', 'sks' => 3, 'semester' => 7, 'jurusan_id' => 1, 'deskripsi' => 'Pengenalan machine learning'],
            ['kode_mk' => 'TI107', 'nama_mk' => 'Keamanan Siber', 'sks' => 3, 'semester' => 6, 'jurusan_id' => 1, 'deskripsi' => 'Keamanan sistem komputer'],
            ['kode_mk' => 'TI108', 'nama_mk' => 'Cloud Computing', 'sks' => 3, 'semester' => 7, 'jurusan_id' => 1, 'deskripsi' => 'Teknologi cloud computing'],
            ['kode_mk' => 'TI109', 'nama_mk' => 'Sistem Operasi', 'sks' => 3, 'semester' => 4, 'jurusan_id' => 1, 'deskripsi' => 'Sistem operasi komputer'],
            ['kode_mk' => 'TI110', 'nama_mk' => 'Rekayasa Perangkat Lunak', 'sks' => 3, 'semester' => 5, 'jurusan_id' => 1, 'deskripsi' => 'Metodologi pengembangan software'],
            
            // Sistem Informasi (jurusan_id = 2)
            ['kode_mk' => 'SI101', 'nama_mk' => 'Sistem Informasi Manajemen', 'sks' => 3, 'semester' => 3, 'jurusan_id' => 2, 'deskripsi' => 'SIM dalam organisasi'],
            ['kode_mk' => 'SI102', 'nama_mk' => 'Analisis Sistem', 'sks' => 3, 'semester' => 4, 'jurusan_id' => 2, 'deskripsi' => 'Analisis kebutuhan sistem'],
            ['kode_mk' => 'SI103', 'nama_mk' => 'Perancangan Sistem', 'sks' => 3, 'semester' => 4, 'jurusan_id' => 2, 'deskripsi' => 'Perancangan sistem informasi'],
            ['kode_mk' => 'SI104', 'nama_mk' => 'Business Intelligence', 'sks' => 3, 'semester' => 6, 'jurusan_id' => 2, 'deskripsi' => 'Business intelligence'],
            ['kode_mk' => 'SI105', 'nama_mk' => 'E-Commerce', 'sks' => 3, 'semester' => 5, 'jurusan_id' => 2, 'deskripsi' => 'E-commerce system'],
            ['kode_mk' => 'SI106', 'nama_mk' => 'ERP Sistem', 'sks' => 3, 'semester' => 6, 'jurusan_id' => 2, 'deskripsi' => 'Enterprise resource planning'],
            ['kode_mk' => 'SI107', 'nama_mk' => 'Data Warehouse', 'sks' => 3, 'semester' => 7, 'jurusan_id' => 2, 'deskripsi' => 'Data warehouse dan ETL'],
            ['kode_mk' => 'SI108', 'nama_mk' => 'Manajemen Proyek SI', 'sks' => 3, 'semester' => 7, 'jurusan_id' => 2, 'deskripsi' => 'Manajemen proyek SI'],
            
            // Manajemen (jurusan_id = 3)
            ['kode_mk' => 'MJ101', 'nama_mk' => 'Manajemen Strategik', 'sks' => 3, 'semester' => 5, 'jurusan_id' => 3, 'deskripsi' => 'Manajemen strategis'],
            ['kode_mk' => 'MJ102', 'nama_mk' => 'Pemasaran Digital', 'sks' => 3, 'semester' => 4, 'jurusan_id' => 3, 'deskripsi' => 'Pemasaran digital'],
            ['kode_mk' => 'MJ103', 'nama_mk' => 'Manajemen Keuangan', 'sks' => 3, 'semester' => 4, 'jurusan_id' => 3, 'deskripsi' => 'Manajemen keuangan'],
            ['kode_mk' => 'MJ104', 'nama_mk' => 'Manajemen SDM', 'sks' => 3, 'semester' => 4, 'jurusan_id' => 3, 'deskripsi' => 'Manajemen SDM'],
            ['kode_mk' => 'MJ105', 'nama_mk' => 'Kewirausahaan', 'sks' => 3, 'semester' => 6, 'jurusan_id' => 3, 'deskripsi' => 'Kewirausahaan'],
            ['kode_mk' => 'MJ106', 'nama_mk' => 'Perilaku Organisasi', 'sks' => 3, 'semester' => 3, 'jurusan_id' => 3, 'deskripsi' => 'Perilaku organisasi'],
            ['kode_mk' => 'MJ107', 'nama_mk' => 'Manajemen Operasi', 'sks' => 3, 'semester' => 5, 'jurusan_id' => 3, 'deskripsi' => 'Manajemen operasi'],
            
            // Akuntansi (jurusan_id = 4)
            ['kode_mk' => 'AK101', 'nama_mk' => 'Akuntansi Keuangan', 'sks' => 3, 'semester' => 3, 'jurusan_id' => 4, 'deskripsi' => 'Akuntansi keuangan'],
            ['kode_mk' => 'AK102', 'nama_mk' => 'Akuntansi Biaya', 'sks' => 3, 'semester' => 4, 'jurusan_id' => 4, 'deskripsi' => 'Akuntansi biaya'],
            ['kode_mk' => 'AK103', 'nama_mk' => 'Auditing', 'sks' => 3, 'semester' => 6, 'jurusan_id' => 4, 'deskripsi' => 'Audit keuangan'],
            ['kode_mk' => 'AK104', 'nama_mk' => 'Perpajakan', 'sks' => 3, 'semester' => 5, 'jurusan_id' => 4, 'deskripsi' => 'Perpajakan'],
            ['kode_mk' => 'AK105', 'nama_mk' => 'Sistem Informasi Akuntansi', 'sks' => 3, 'semester' => 5, 'jurusan_id' => 4, 'deskripsi' => 'SIA'],
            ['kode_mk' => 'AK106', 'nama_mk' => 'Akuntansi Manajemen', 'sks' => 3, 'semester' => 4, 'jurusan_id' => 4, 'deskripsi' => 'Akuntansi manajemen'],
            
            // Hukum (jurusan_id = 5)
            ['kode_mk' => 'HK101', 'nama_mk' => 'Hukum Dagang', 'sks' => 3, 'semester' => 4, 'jurusan_id' => 5, 'deskripsi' => 'Hukum dagang'],
            ['kode_mk' => 'HK102', 'nama_mk' => 'Hukum Pidana', 'sks' => 3, 'semester' => 3, 'jurusan_id' => 5, 'deskripsi' => 'Hukum pidana'],
            ['kode_mk' => 'HK103', 'nama_mk' => 'Hukum Perdata', 'sks' => 3, 'semester' => 3, 'jurusan_id' => 5, 'deskripsi' => 'Hukum perdata'],
            ['kode_mk' => 'HK104', 'nama_mk' => 'Hukum Tata Negara', 'sks' => 3, 'semester' => 5, 'jurusan_id' => 5, 'deskripsi' => 'Hukum tata negara'],
            ['kode_mk' => 'HK105', 'nama_mk' => 'Hukum Internasional', 'sks' => 3, 'semester' => 6, 'jurusan_id' => 5, 'deskripsi' => 'Hukum internasional'],
            ['kode_mk' => 'HK106', 'nama_mk' => 'Hukum Administrasi Negara', 'sks' => 3, 'semester' => 5, 'jurusan_id' => 5, 'deskripsi' => 'Hukum administrasi'],
        ];
        
        // Tambahkan initial matakuliahs ke array
        foreach ($initial_matakuliahs as $mk) {
            $used_kode_mk[] = $mk['kode_mk'];
            $matakuliahs[] = array_merge($mk, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Generate hingga 124 mata kuliah (pastikan tidak duplikat)
        $current_count = count($matakuliahs);
        $jurusan_kode = ['TI', 'SI', 'MJ', 'AK', 'HK'];
        
        for ($i = $current_count + 1; $i <= 124; $i++) {
            $jurusan_id = rand(1, 5);
            $kode_jurusan = $jurusan_kode[$jurusan_id - 1];
            
            // Generate unique kode_mk
            do {
                $nomor = rand(200, 999);
                $kode_mk = $kode_jurusan . $nomor;
            } while (in_array($kode_mk, $used_kode_mk));
            
            $used_kode_mk[] = $kode_mk;
            $matakuliahs[] = [
                'kode_mk' => $kode_mk,
                'nama_mk' => 'Mata Kuliah ' . $i,
                'sks' => rand(2, 4),
                'semester' => rand(1, 8),
                'jurusan_id' => $jurusan_id,
                'deskripsi' => 'Deskripsi mata kuliah ' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        DB::table('matakuliahs')->insert($matakuliahs);

        // 6. Insert Mahasiswa (1480 data)
        $mahasiswas = [];
        for ($i = 1; $i <= 1480; $i++) {
            $mahasiswas[] = [
                'nim' => '2024' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'nama_mahasiswa' => 'Mahasiswa ' . $i,
                'jurusan_id' => rand(1, 5),
                'angkatan' => (string) rand(2020, 2025),
                'email' => 'mahasiswa' . $i . '@univ.ac.id',
                'no_hp' => '0812' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            // Insert per 500 data untuk menghindari memory overload
            if ($i % 500 === 0) {
                DB::table('mahasiswas')->insert($mahasiswas);
                $mahasiswas = [];
            }
        }
        if (!empty($mahasiswas)) {
            DB::table('mahasiswas')->insert($mahasiswas);
        }

        // 7. Insert Kelas
        $kelas = [];
        $jurusan_ids = DB::table('jurusans')->pluck('id')->toArray();
        $angkatan_list = ['2021', '2022', '2023', '2024', '2025'];
        
        for ($i = 1; $i <= 50; $i++) {
            $jurusan_id = $jurusan_ids[array_rand($jurusan_ids)];
            $angkatan = $angkatan_list[array_rand($angkatan_list)];
            $kelas[] = [
                'kode_kelas' => 'KLS-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama_kelas' => 'Kelas ' . chr(rand(65, 75)) . ' - ' . $angkatan,
                'jurusan_id' => $jurusan_id,
                'angkatan' => $angkatan,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('kelas')->insert($kelas);

        // 8. Insert Jadwal (342 data)
        $jadwals = [];
        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $matakuliah_count = DB::table('matakuliahs')->count();
        $dosen_count = DB::table('dosens')->count();
        $ruangan_list = DB::table('ruangans')->pluck('kode_ruangan')->toArray();
        
        for ($i = 1; $i <= 342; $i++) {
            $jadwals[] = [
                'matakuliah_id' => rand(1, $matakuliah_count),
                'dosen_id' => rand(1, $dosen_count),
                'hari' => $hari[array_rand($hari)],
                'jam_mulai' => sprintf('%02d:00:00', rand(7, 15)),
                'jam_selesai' => sprintf('%02d:00:00', rand(8, 17)),
                'ruangan' => $ruangan_list[array_rand($ruangan_list)],
                'tahun_akademik_id' => $active_year_id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            // Insert per 100 data
            if ($i % 100 === 0) {
                DB::table('jadwals')->insert($jadwals);
                $jadwals = [];
            }
        }
        if (!empty($jadwals)) {
            DB::table('jadwals')->insert($jadwals);
        }

        // 9. Insert KRS (untuk mahasiswa yang mengambil mata kuliah)
        $krs = [];
        $mahasiswa_ids = DB::table('mahasiswas')->pluck('id')->toArray();
        $jadwal_ids = DB::table('jadwals')->pluck('id')->toArray();
        $status_list = ['pending', 'approved', 'rejected'];
        
        // Create KRS for some students
        for ($i = 1; $i <= 2000; $i++) {
            $krs[] = [
                'kode_krs' => 'KRS' . date('Ymd') . str_pad($i, 4, '0', STR_PAD_LEFT),
                'mahasiswa_id' => $mahasiswa_ids[array_rand($mahasiswa_ids)],
                'jadwal_id' => $jadwal_ids[array_rand($jadwal_ids)],
                'tahun_akademik_id' => $active_year_id,
                'status' => $status_list[array_rand($status_list)],
                'tgl_krs' => Carbon::now()->subDays(rand(1, 30)),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            if ($i % 500 === 0) {
                DB::table('k_r_s')->insert($krs);
                $krs = [];
            }
        }
        if (!empty($krs)) {
            DB::table('k_r_s')->insert($krs);
        }
        
        // 10. Insert Nilai (untuk KRS yang sudah approved)
        $krs_ids = DB::table('k_r_s')->where('status', 'approved')->pluck('id')->toArray();
        $nilais = [];
        
        foreach ($krs_ids as $krs_id) {
            $nilai_tugas = rand(60, 100);
            $nilai_uts = rand(60, 100);
            $nilai_uas = rand(60, 100);
            $nilai_akhir = ($nilai_tugas * 0.3) + ($nilai_uts * 0.3) + ($nilai_uas * 0.4);
            
            // Determine grade
            if ($nilai_akhir >= 85) $grade = 'A';
            elseif ($nilai_akhir >= 80) $grade = 'A-';
            elseif ($nilai_akhir >= 75) $grade = 'B+';
            elseif ($nilai_akhir >= 70) $grade = 'B';
            elseif ($nilai_akhir >= 65) $grade = 'B-';
            elseif ($nilai_akhir >= 60) $grade = 'C+';
            elseif ($nilai_akhir >= 55) $grade = 'C';
            elseif ($nilai_akhir >= 50) $grade = 'D';
            else $grade = 'E';
            
            $nilais[] = [
                'krs_id' => $krs_id,
                'nilai_tugas' => $nilai_tugas,
                'nilai_uts' => $nilai_uts,
                'nilai_uas' => $nilai_uas,
                'nilai_akhir' => round($nilai_akhir, 2),
                'grade' => $grade,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        foreach (array_chunk($nilais, 500) as $chunk) {
            DB::table('nilais')->insert($chunk);
        }

        // 11. Insert Absensi (untuk grafik aktivitas)
        $absensis = [];
        $mahasiswa_count = DB::table('mahasiswas')->count();
        $jadwal_count = DB::table('jadwals')->count();
        
        // Buat data untuk 6 minggu terakhir
        for ($week = 1; $week <= 6; $week++) {
            $total_absensi_per_minggu = rand(40, 150);
            for ($i = 1; $i <= $total_absensi_per_minggu; $i++) {
                $absensis[] = [
                    'mahasiswa_id' => rand(1, $mahasiswa_count),
                    'jadwal_id' => rand(1, $jadwal_count),
                    'tanggal' => Carbon::now()->subWeeks(6 - $week)->format('Y-m-d'),
                    'status' => collect(['hadir', 'izin', 'sakit', 'alpha'])->random(),
                    'keterangan' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                // Insert per 200 data
                if (count($absensis) >= 200) {
                    DB::table('absensis')->insert($absensis);
                    $absensis = [];
                }
            }
        }
        if (!empty($absensis)) {
            DB::table('absensis')->insert($absensis);
        }

        // 12. Insert Users (Admin, Dosen, Mahasiswa)
        $users = [];
        
        // Admin user
        $users[] = [
            'name' => 'Administrator',
            'email' => 'admin@siakad.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'mahasiswa_id' => null,
            'dosen_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        
        // Dosen users (first 10 dosens)
        $dosens_list = DB::table('dosens')->limit(10)->get();
        foreach ($dosens_list as $dosen) {
            $users[] = [
                'name' => $dosen->nama_dosen,
                'email' => $dosen->email,
                'password' => Hash::make('dosen123'),
                'role' => 'dosen',
                'mahasiswa_id' => null,
                'dosen_id' => $dosen->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // Mahasiswa users (first 20 mahasiswas)
        $mahasiswas_list = DB::table('mahasiswas')->limit(20)->get();
        foreach ($mahasiswas_list as $mahasiswa) {
            $users[] = [
                'name' => $mahasiswa->nama_mahasiswa,
                'email' => $mahasiswa->email,
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa',
                'mahasiswa_id' => $mahasiswa->id,
                'dosen_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        DB::table('users')->insert($users);

        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Output statistics
        $this->command->info('==========================================');
        $this->command->info('Database seeding completed successfully!');
        $this->command->info('==========================================');
        $this->command->info('Total Data:');
        $this->command->info('- Jurusan: ' . DB::table('jurusans')->count());
        $this->command->info('- Ruangan: ' . DB::table('ruangans')->count());
        $this->command->info('- Tahun Akademik: ' . DB::table('tahun_akademiks')->count());
        $this->command->info('- Dosen: ' . DB::table('dosens')->count());
        $this->command->info('- Mata Kuliah: ' . DB::table('matakuliahs')->count());
        $this->command->info('- Mahasiswa: ' . DB::table('mahasiswas')->count());
        $this->command->info('- Kelas: ' . DB::table('kelas')->count());
        $this->command->info('- Jadwal: ' . DB::table('jadwals')->count());
        $this->command->info('- KRS: ' . DB::table('k_r_s')->count());
        $this->command->info('- Nilai: ' . DB::table('nilais')->count());
        $this->command->info('- Absensi: ' . DB::table('absensis')->count());
        $this->command->info('- Users: ' . DB::table('users')->count());
        $this->command->info('==========================================');
        $this->command->info('Login Credentials:');
        $this->command->info('Admin    - Email: admin@siakad.com | Password: admin123');
        $this->command->info('Dosen    - Email: (email dosen) | Password: dosen123');
        $this->command->info('Mahasiswa - Email: (email mahasiswa) | Password: mahasiswa123');
        $this->command->info('==========================================');
    }
}