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
        Schema::create('sub_kategori_dokumens', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kategori_dokumen_id')
                ->constrained('kategori_dokumens')
                ->cascadeOnDelete();

            $table->string('nama_sub_kategori');
            $table->text('deskripsi')->nullable();

            $table->boolean('butuh_student')->default(false);
            $table->boolean('butuh_pkl')->default(false);
            $table->boolean('butuh_pegawai')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_kategori_dokumens');
    }
};
