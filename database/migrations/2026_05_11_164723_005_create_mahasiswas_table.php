<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('nim', 20)->unique();
            $table->string('nama_mahasiswa', 100);
            $table->foreignId('jurusan_id')->constrained('jurusans')->onDelete('cascade');
            $table->string('angkatan', 4);
            $table->string('email', 100)->unique();
            $table->string('no_hp', 15)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};