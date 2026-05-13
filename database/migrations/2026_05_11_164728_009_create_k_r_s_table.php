<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('k_r_s', function (Blueprint $table) {
            $table->id();
            $table->string('kode_krs', 20)->unique();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->foreignId('jadwal_id')->constrained('jadwals')->onDelete('cascade');
            $table->foreignId('tahun_akademik_id')->constrained('tahun_akademiks')->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->date('tgl_krs');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('k_r_s');
    }
};