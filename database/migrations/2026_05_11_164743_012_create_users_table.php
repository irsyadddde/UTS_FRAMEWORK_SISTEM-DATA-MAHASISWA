<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'dosen', 'mahasiswa'])->default('mahasiswa');
            $table->foreignId('mahasiswa_id')->nullable()->constrained('mahasiswas')->onDelete('set null');
            $table->foreignId('dosen_id')->nullable()->constrained('dosens')->onDelete('set null');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};