<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengeluaranOperasional extends Model
{
    protected $table = 'pengeluaran_operasional';

    protected $fillable = [
        'kategori',
        'keterangan',
        'nominal',
        'tanggal_pengeluaran',
        'catatan',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tanggal_pengeluaran' => 'date',
    ];
}
