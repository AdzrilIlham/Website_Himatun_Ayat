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
        Schema::table('kampanye', function (Blueprint $table) {
            if (!Schema::hasColumn('kampanye', 'tanggal_mulai')) {
                $table->date('tanggal_mulai')->nullable()->after('dana_terkumpul');
            }
            $table->string('status', 20)->default('aktif')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kampanye', function (Blueprint $table) {
            if (Schema::hasColumn('kampanye', 'tanggal_mulai')) {
                $table->dropColumn('tanggal_mulai');
            }
        });
    }
};
