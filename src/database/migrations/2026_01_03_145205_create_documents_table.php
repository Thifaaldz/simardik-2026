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
                $table->string('nama_dokumen')->nullable();
                $table->foreignId('kategori_dokumen_id')->constrained('kategori_dokumens');
                $table->foreignId('sub_kategori_dokumen_id')->constrained('sub_kategori_dokumens');
                $table->foreignId('unit_kerja_id')->constrained();
                $table->foreignId('student_id')->nullable()->constrained();
                $table->foreignId('pegawai_id')->nullable()->constrained('pegawais');
                $table->foreignId('pkl_id')->nullable()->constrained('pkls');
                $table->string('tahun');
                $table->date('tanggal_dokumen');
                $table->string('status_dokumen');
                $table->string('tingkat_kerahasiaan');
                $table->string('file_path');
                $table->string('disk')->default('local');
                $table->string('file_hash', 64)->nullable()->index();
                $table->string('mime_type')->nullable();
                $table->unsignedBigInteger('file_size')->nullable();
                $table->timestamp('expires_at')->nullable();
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


