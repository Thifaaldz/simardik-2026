<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
                $table->id();
                $table->string('nis')->unique();
                $table->string('nisn')->unique();
                $table->string('nama');
                $table->foreignId('kelas_id')->constrained();
                $table->foreignId('jurusan_id')->constrained();
                $table->foreignId('tahun_ajaran_id')->constrained();
                $table->string('status');
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
