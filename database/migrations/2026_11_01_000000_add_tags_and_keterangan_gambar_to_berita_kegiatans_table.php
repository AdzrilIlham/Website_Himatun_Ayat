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
        Schema::table('berita_kegiatans', function (Blueprint $table) {
            $table->json('tags')->nullable()->after('konten');
            $table->string('keterangan_gambar')->nullable()->after('gambar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('berita_kegiatans', function (Blueprint $table) {
            $table->dropColumn(['tags', 'keterangan_gambar']);
        });
    }
};
