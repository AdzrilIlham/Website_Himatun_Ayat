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
        Schema::create('pengajuan_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pemohon');
            $table->string('nomor_whatsapp');
            $table->date('tanggal');
            $table->string('waktu');
            $table->string('jenis_kegiatan');
            $table->text('rencana_kegiatan');
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->text('catatan_admin')->nullable();
            $table->foreignId('berita_kegiatan_id')->nullable()->constrained('berita_kegiatans')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_kegiatans');
    }
};

