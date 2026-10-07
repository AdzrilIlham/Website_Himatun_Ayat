<?php

namespace App\Exports;

use App\Models\AnakAsuh;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AnakAsuhExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        protected ?string $search = null,
        protected ?string $status = null,
        protected ?string $pendidikan = null
    ) {}

    public function collection(): Enumerable
    {
        $query = AnakAsuh::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nama_lengkap', 'like', "%{$this->search}%")
                  ->orWhere('nama_panggilan', 'like', "%{$this->search}%");
            });
        }

        if ($this->status && $this->status !== 'all' && $this->status !== 'Semua Status') {
            if (in_array($this->status, ['SD', 'SMP', 'SMA', 'Perguruan Tinggi'])) {
                $query->where(function ($q) {
                    $q->where('pendidikan_terakhir', $this->status)
                      ->orWhere('pendidikan_terakhir', 'like', "%{$this->status}%");
                });
            } else {
                $query->where('status_asuhan', $this->status);
            }
        }

        if ($this->pendidikan && $this->pendidikan !== 'all') {
            $query->where('pendidikan_terakhir', $this->pendidikan);
        }

        return $query->latest('id')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Lengkap',
            'Nama Panggilan',
            'Jenis Kelamin',
            'Tanggal Lahir',
            'Usia',
            'Pendidikan Terakhir',
            'Status Asuhan',
            'Tanggal Bergabung',
            'Keterangan',
        ];
    }

    public function map(mixed $row): array
    {
        return [
            $row->id,
            $row->nama_lengkap,
            $row->nama_panggilan ?? '-',
            $row->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
            $row->tanggal_lahir ? $row->tanggal_lahir->format('d/m/Y') : '-',
            $row->usia_formatted,
            $row->pendidikan_terakhir ?? '-',
            $row->status_asuhan,
            $row->created_at->format('d M Y'),
            $row->keterangan ?? '-',
        ];
    }
}
