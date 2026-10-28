<?php

namespace App\Services;

use App\Models\AnakAsuh;
use App\Models\Donasi;
use App\Models\Kampanye;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LaporanService
{
    /**
     * Get summary metrics for admin dashboard.
     */
    public function getDashboardSummary(): array
    {
        return [
            'total_donasi_bulan_ini'     => (float) Donasi::where('status', 'verified')
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->sum('nominal'),
            'total_anak_asuh_aktif'      => AnakAsuh::where('status_asuhan', 'Aktif')->count(),
            'kampanye_aktif'             => Kampanye::where('status', 'aktif')->count(),
            'total_donasi_pending'       => Donasi::where('status', 'pending')->count(),
            'total_donasi_terverifikasi' => (float) Donasi::where('status', 'verified')->sum('nominal'),
            'total_transaksi_verified'   => Donasi::where('status', 'verified')->count(),
        ];
    }

    /**
     * Get counts for filter tabs in dashboard.
     */
    public function getCountByMetode(?string $tanggal = null): array
    {
        $base = Donasi::where('status', 'pending');

        if ($tanggal) {
            $base->whereDate('created_at', Carbon::parse($tanggal));
        }

        $all = (clone $base)->count();
        $transfer = (clone $base)->where(function ($q) {
            $q->where('metode_pembayaran', 'like', '%Transfer%')
              ->orWhere('metode_pembayaran', 'like', '%Bank%');
        })->count();
        $qris = (clone $base)->where('metode_pembayaran', 'like', '%QRIS%')->count();

        return [
            'semua'         => $all,
            'transfer_bank' => $transfer,
            'qris_manual'   => $qris,
        ];
    }

    /**
     * Get pending donations with search, method, and date filters.
     */
    public function getPendingDonasi(
        ?string $search = null,
        ?string $metode = null,
        ?string $tanggal = null,
        int $perPage = 5
    ): LengthAwarePaginator {
        $query = Donasi::with('kampanye')->where('status', 'pending');

        if ($tanggal) {
            $query->whereDate('created_at', Carbon::parse($tanggal));
        }

        if ($metode && $metode !== 'all') {
            if ($metode === 'transfer_bank') {
                $query->where(function ($q) {
                    $q->where('metode_pembayaran', 'like', '%Transfer%')
                      ->orWhere('metode_pembayaran', 'like', '%Bank%');
                });
            } elseif ($metode === 'qris_manual') {
                $query->where('metode_pembayaran', 'like', '%QRIS%');
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('nama_donatur', 'like', "%{$search}%")
                  ->orWhere('no_whatsapp', 'like', "%{$search}%")
                  ->orWhereHas('kampanye', function ($kq) use ($search) {
                      $kq->where('judul', 'like', "%{$search}%");
                  });
            });
        }

        return $query->latest('id')->paginate($perPage);
    }

    /**
     * Filter verified donations for reporting and export.
     */
    public function getLaporanDonasi(?string $startDate = null, ?string $endDate = null, ?int $kampanyeId = null)
    {
        $query = Donasi::with('kampanye')->where('status', 'verified');

        if ($startDate) {
            $query->whereDate('created_at', '>=', Carbon::parse($startDate));
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', Carbon::parse($endDate));
        }

        if ($kampanyeId) {
            $query->where('kampanye_id', $kampanyeId);
        }

        return $query->latest()->get();
    }
}

