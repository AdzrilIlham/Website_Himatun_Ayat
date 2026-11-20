<?php

namespace App\Models;

use App\Services\CloudflareR2Service;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnakAsuh extends Model
{
    use HasFactory;

    protected $table = 'anak_asuh';

    protected $fillable = [
        'nama_lengkap',
        'nama_panggilan',
        'jenis_kelamin',
        'tanggal_lahir',
        'pendidikan_terakhir',
        'status_asuhan',
        'foto_path',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'foto_cloudinary' => 'array',
    ];

    protected $appends = [
        'foto_url',
        'usia',
        'usia_formatted',
        'inisial',
    ];

    public function getFotoUrlAttribute(): ?string
    {
        if (empty($this->foto_path)) {
            return null;
        }

        if (str_starts_with($this->foto_path, 'http://') || str_starts_with($this->foto_path, 'https://')) {
            return $this->foto_path;
        }

        if (str_starts_with($this->foto_path, 'images/')) {
            return asset($this->foto_path);
        }

        $cleanPath = str_starts_with($this->foto_path, 'storage/')
            ? substr($this->foto_path, 8)
            : $this->foto_path;

        return app(CloudflareR2Service::class)->getPublicUrl($cleanPath);
    }

    public function getUsiaAttribute(): ?int
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age : null;
    }

    public function getUsiaFormattedAttribute(): string
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age.' Tahun' : '-';
    }

    public function getInisialAttribute(): string
    {
        $name = trim($this->nama_lengkap ?? '');
        if (empty($name)) {
            return 'A';
        }

        return strtoupper(mb_substr($name, 0, 1));
    }
}
