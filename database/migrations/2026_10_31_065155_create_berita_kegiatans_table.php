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
        Schema::create('berita_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe', ['berita', 'kegiatan'])->default('berita');
            $table->string('kategori');
            $table->string('judul');
            $table->string('slug')->unique();
            $table->text('ringkasan');
            $table->longText('konten')->nullable();
            $table->string('gambar')->nullable();
            $table->string('lokasi')->default('Bandung');
            $table->date('tanggal');
            $table->boolean('unggulan')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita_kegiatans');
    }
};
