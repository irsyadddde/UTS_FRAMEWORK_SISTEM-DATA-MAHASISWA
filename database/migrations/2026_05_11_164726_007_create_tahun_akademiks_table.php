<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tahun_akademiks', function (Blueprint $table) {
            $table->id();
            $table->string('tahun', 9);
            $table->string('semester', 10);
            $table->boolean('is_active')->default(false);
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->timestamps();
            
            $table->unique(['tahun', 'semester']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tahun_akademiks');
    }
};