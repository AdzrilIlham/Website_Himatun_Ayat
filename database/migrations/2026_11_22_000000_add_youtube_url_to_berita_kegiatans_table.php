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
        if (! Schema::hasColumn('berita_kegiatans', 'youtube_url')) {
            Schema::table('berita_kegiatans', function (Blueprint $table) {
                $table->string('youtube_url')->nullable()->after('keterangan_gambar');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('berita_kegiatans', 'youtube_url')) {
            Schema::table('berita_kegiatans', function (Blueprint $table) {
                $table->dropColumn('youtube_url');
            });
        }
    }
};
