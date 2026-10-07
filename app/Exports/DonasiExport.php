<?php

namespace App\Exports;

use App\Models\Donasi;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DonasiExport implements FromCollection, WithHeadings, WithMapping
{
    protected ?string $startDate;
    protected ?string $endDate;
    protected ?int $kampanyeId;

    public function __construct(?string $startDate = null, ?string $endDate = null, ?int $kampanyeId = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->kampanyeId = $kampanyeId;
    }

    public function collection(): Enumerable
    {
        $query = Donasi::with('kampanye')->where('status', 'verified');

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        if ($this->kampanyeId) {
            $query->where('kampanye_id', $this->kampanyeId);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tanggal',
            'Nama Donatur',
            'No. WhatsApp',
            'Program Kampanye',
            'Nominal',
            'Pesan / Doa',
            'Status',
        ];
    }

    public function map(mixed $row): array
    {
        return [
            $row->id,
            $row->created_at->format('Y-m-d H:i'),
            $row->nama_donatur,
            $row->no_whatsapp,
            $row->kampanye?->judul ?? 'Donasi Umum',
            $row->nominal,
            $row->pesan_doa,
            $row->status,
        ];
    }
}
