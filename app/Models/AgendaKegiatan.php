<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @deprecated Legacy model. Seluruh agenda kegiatan/kalender sudah digabungkan ke App\Models\BeritaKegiatan.
 */
class AgendaKegiatan extends Model
{
    protected $table = 'agenda_kegiatans';
}
