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
        Schema::create('documents', function (Blueprint $table) {
                $table->id();
                $table->string('kode_dokumen')->unique();
                $table->string('nama_dokumen');
                $table->foreignId('kategori_dokumen_id')->constrained('kategori_dokumens');
                $table->foreignId('sub_kategori_dokumen_id')->constrained('sub_kategori_dokumens');
                $table->foreignId('unit_kerja_id')->constrained();
                $table->foreignId('student_id')->nullable()->constrained();
                $table->foreignId('pegawai_id')->nullable()->constrained('pegawais');
                $table->foreignId('pkl_id')->nullable()->constrained('pkls');
                $table->integer('tahun');
                $table->date('tanggal_dokumen');
                $table->string('status_dokumen');
                $table->string('tingkat_kerahasiaan');
                $table->string('file_path');
                $table->foreignId('created_by')->constrained('users');
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
