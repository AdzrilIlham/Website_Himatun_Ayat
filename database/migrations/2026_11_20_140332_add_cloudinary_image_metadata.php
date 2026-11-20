<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const METADATA_COLUMNS = [
        'anak_asuh' => ['foto_cloudinary'],
        'berita_kegiatans' => ['gambar_cloudinary', 'gambar_konten_cloudinary'],
        'kampanye' => ['banner_cloudinary'],
        'donasi' => ['bukti_transfer_cloudinary'],
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('image_upload_intents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('purpose', 30);
            $table->unsignedBigInteger('record_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->char('session_hash', 64);
            $table->unsignedBigInteger('expires_at')->index();
        });

        foreach (self::METADATA_COLUMNS as $tableName => $columns) {
            Schema::table($tableName, function (Blueprint $table) use ($columns): void {
                foreach ($columns as $column) {
                    $table->json($column)->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (self::METADATA_COLUMNS as $tableName => $columns) {
            Schema::table($tableName, fn (Blueprint $table) => $table->dropColumn($columns));
        }

        Schema::dropIfExists('image_upload_intents');
    }
};
