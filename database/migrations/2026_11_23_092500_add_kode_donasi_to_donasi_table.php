<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('donasi', 'kode_donasi')) {
            Schema::table('donasi', function (Blueprint $table) {
                $table->string('kode_donasi', 32)->nullable()->unique()->after('id');
            });
        }

        // Backfill data donasi lama yang belum memiliki kode_donasi
        $existingRows = DB::table('donasi')->whereNull('kode_donasi')->select('id')->get();
        foreach ($existingRows as $row) {
            do {
                $code = sprintf('DNS-%08d-%03d', random_int(10000000, 99999999), random_int(100, 999));
                $exists = DB::table('donasi')->where('kode_donasi', $code)->exists();
            } while ($exists);

            DB::table('donasi')->where('id', $row->id)->update(['kode_donasi' => $code]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('donasi', 'kode_donasi')) {
            Schema::table('donasi', function (Blueprint $table) {
                $table->dropUnique(['kode_donasi']);
                $table->dropColumn('kode_donasi');
            });
        }
    }
};
