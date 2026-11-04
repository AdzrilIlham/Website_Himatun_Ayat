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
            if (! Schema::hasColumn('berita_kegiatans', 'terjadwal_di_kalender')) {
                $table->boolean('terjadwal_di_kalender')->default(false)->after('unggulan');
            }
            if (! Schema::hasColumn('berita_kegiatans', 'penyelenggara')) {
                $table->string('penyelenggara')->nullable()->after('lokasi');
            }
            if (! Schema::hasColumn('berita_kegiatans', 'waktu_teks')) {
                $table->string('waktu_teks')->nullable()->after('tanggal');
            }
            if (! Schema::hasColumn('berita_kegiatans', 'waktu_mulai')) {
                $table->time('waktu_mulai')->nullable()->after('waktu_teks');
            }
            if (! Schema::hasColumn('berita_kegiatans', 'waktu_selesai')) {
                $table->time('waktu_selesai')->nullable()->after('waktu_mulai');
            }
        });

        if (\App\Models\BeritaKegiatan::query()->exists()) {
            (new \Database\Seeders\BeritaKegiatanSeeder())->run();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('berita_kegiatans', function (Blueprint $table) {
            $columns = array_filter(
                ['terjadwal_di_kalender', 'penyelenggara', 'waktu_teks', 'waktu_mulai', 'waktu_selesai'],
                fn (string $column) => Schema::hasColumn('berita_kegiatans', $column)
            );

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
