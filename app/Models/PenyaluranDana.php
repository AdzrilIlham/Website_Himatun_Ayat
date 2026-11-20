<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenyaluranDana extends Model
{
    protected $table = 'penyaluran_dana';

    protected $fillable = [
        'klaster_asnaf',
        'keterangan',
        'nominal',
        'tanggal_penyaluran',
        'penerima_manfaat',
        'catatan',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tanggal_penyaluran' => 'date',
    ];

    /**
     * Label nama klaster yang ditampilkan ke UI.
     *
     * @return array<string, string>
     */
    public static function klasterLabels(): array
    {
        return [
            'pendidikan' => 'Pendidikan & Beasiswa',
            'nutrisi' => 'Nutrisi & Permakanan',
            'kesehatan' => 'Layanan Kesehatan',
            'asrama' => 'Fasilitas Asrama',
            'lainnya' => 'Lainnya',
        ];
    }
}
