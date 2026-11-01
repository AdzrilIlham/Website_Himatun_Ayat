<?php

namespace App\Exports;

use App\Models\Kampanye;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KampanyeExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        protected ?string $kategori = null,
        protected ?string $status = null,
        protected ?int $bulan = null,
        protected ?int $tahun = null
    ) {}

    public function collection(): Enumerable
    {
        $query = Kampanye::query();

        if ($this->bulan) {
            $query->whereMonth('created_at', $this->bulan);
        }

        if ($this->tahun) {
            $query->whereYear('created_at', $this->tahun);
        }

        if ($this->kategori && $this->kategori !== 'Semua Kategori' && $this->kategori !== 'all') {
            if (in_array(strtolower($this->kategori), ['aktif', 'selesai'])) {
                $query->where('status', strtolower($this->kategori));
            } else {
                $query->where('kategori', 'like', "%{$this->kategori}%");
            }
        }

        if ($this->status && $this->status !== 'Semua Status' && $this->status !== 'all') {
            $query->where('status', strtolower($this->status));
        }

        return $query->latest('id')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Judul Kampanye',
            'Kategori',
            'Target Dana (Rp)',
            'Dana Terkumpul (Rp)',
            'Persentase Tercapai (%)',
            'Status',
            'Tenggat Waktu',
            'Tanggal Dibuat',
        ];
    }

    public function map(mixed $row): array
    {
        return [
            $row->id,
            $row->judul,
            $row->kategori ?? 'Operasional',
            (float) $row->target_dana,
            (float) $row->dana_terkumpul,
            $row->persentase_tercapai,
            ucfirst($row->status ?? 'aktif'),
            $row->tenggat_waktu ? $row->tenggat_waktu->format('d/m/Y') : '-',
            $row->created_at ? $row->created_at->format('d/m/Y H:i') : '-',
        ];
    }
}
