<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dosens', function (Blueprint $table) {
            $table->id();
            $table->string('nidn', 20)->unique();
            $table->string('nama_dosen', 100);
            $table->string('email', 100)->unique();
            $table->string('no_telp', 15)->nullable();
            $table->text('alamat')->nullable();
            $table->string('pendidikan_terakhir', 50)->nullable();
            $table->string('jabatan', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dosens');
    }
};