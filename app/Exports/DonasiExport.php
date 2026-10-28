<?php

namespace App\Exports;

use App\Models\Donasi;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DonasiExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        protected ?string $startDate = null,
        protected ?string $endDate = null,
        protected ?int $kampanyeId = null,
        protected ?int $bulan = null,
        protected ?int $tahun = null,
        protected ?string $kategori = null
    ) {}

    public function collection(): Enumerable
    {
        $query = Donasi::with('kampanye');

        // Jika ada filter kategori status tertentu, ikuti filter tsb, jika tidak default verified
        if ($this->kategori && in_array(strtolower($this->kategori), ['pending', 'verified', 'rejected', 'terverifikasi'])) {
            $statusVal = strtolower($this->kategori) === 'terverifikasi' ? 'verified' : strtolower($this->kategori);
            $query->where('status', $statusVal);
        } else {
            $query->where('status', 'verified');
        }

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        if ($this->bulan) {
            $query->whereMonth('created_at', $this->bulan);
        }

        if ($this->tahun) {
            $query->whereYear('created_at', $this->tahun);
        }

        if ($this->kategori && $this->kategori !== 'Semua Kategori' && $this->kategori !== 'all') {
            if ($this->kategori === 'Transfer Bank') {
                $query->where(function ($q) {
                    $q->where('metode_pembayaran', 'like', '%Transfer%')
                      ->orWhere('metode_pembayaran', 'like', '%Bank%');
                });
            } elseif ($this->kategori === 'QRIS Manual') {
                $query->where('metode_pembayaran', 'like', '%QRIS%');
            }
        }

        if ($this->kampanyeId) {
            $query->where('kampanye_id', $this->kampanyeId);
        }

        return $query->latest('id')->get();
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
