<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BeritaKegiatan;
use App\Models\PengajuanKegiatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PengajuanKegiatanController extends Controller
{
    /**
     * Tampilkan daftar pengajuan kegiatan.
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'semua');
        $search = $request->query('search');

        PengajuanKegiatan::ensureTableExists();
        $hasTable = \Illuminate\Support\Facades\Schema::hasTable('pengajuan_kegiatans');

        if (! $hasTable) {
            $daftarPengajuan = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
            $counts = ['total' => 0, 'menunggu' => 0, 'disetujui' => 0, 'ditolak' => 0];
            $bentrokMap = [];
            $agendaTerjadwal = [];
            return view('admin.pengajuan-kegiatan.index', compact('daftarPengajuan', 'counts', 'status', 'search', 'bentrokMap', 'agendaTerjadwal'));
        }

        $query = PengajuanKegiatan::query();

        if ($status !== 'semua' && in_array($status, ['menunggu', 'disetujui', 'ditolak'])) {
            $query->where('status', $status);
        }

        if (filled($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pemohon', 'like', "%{$search}%")
                  ->orWhere('nomor_whatsapp', 'like', "%{$search}%")
                  ->orWhere('jenis_kegiatan', 'like', "%{$search}%")
                  ->orWhere('rencana_kegiatan', 'like', "%{$search}%");
            });
        }

        $daftarPengajuan = $query
            ->orderByRaw("FIELD(status, 'menunggu', 'disetujui', 'ditolak')")
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        // Ambil seluruh kegiatan yang sudah terjadwal di kalender untuk pengecekan jeda 1 jam
        $semuaTerjadwal = BeritaKegiatan::query()
            ->where('terjadwal_di_kalender', true)
            ->orderBy('tanggal')
            ->get(['id', 'judul', 'tanggal', 'waktu_teks']);

        $agendaTerjadwal = $semuaTerjadwal->map(fn (BeritaKegiatan $ev) => [
            'id'       => $ev->id,
            'title'    => $ev->judul,
            'date_iso' => is_string($ev->tanggal) ? substr($ev->tanggal, 0, 10) : $ev->tanggal->toDateString(),
            'time'     => $ev->waktu_teks ?? '08.00 - 11.00 WIB',
        ])->values()->all();

        // Peta bentrok waktu (< 1 jam jeda) untuk setiap item pengajuan pada halaman aktif
        $bentrokMap = [];
        foreach ($daftarPengajuan as $item) {
            $konflik = $this->findConflictingEvent(
                $item->tanggal->toDateString(),
                (string) $item->waktu,
                $item->berita_kegiatan_id
            );
            if ($konflik) {
                $bentrokMap[$item->id] = [
                    'judul' => $konflik->judul,
                    'waktu' => $konflik->waktu_teks ?? '-',
                ];
            }
        }

        $counts = [
            'total'     => PengajuanKegiatan::count(),
            'menunggu'  => PengajuanKegiatan::where('status', 'menunggu')->count(),
            'disetujui' => PengajuanKegiatan::where('status', 'disetujui')->count(),
            'ditolak'   => PengajuanKegiatan::where('status', 'ditolak')->count(),
        ];

        return view('admin.pengajuan-kegiatan.index', compact(
            'daftarPengajuan',
            'counts',
            'status',
            'search',
            'bentrokMap',
            'agendaTerjadwal'
        ));
    }

    /**
     * Ekstrak rentang jam mulai dan selesai dalam bentuk desimal dari string waktu.
     * Contoh: "08.00 - 11.00 WIB" -> ['start' => 8.0, 'end' => 11.0]
     */
    public static function parseTimeRange(?string $timeStr): ?array
    {
        if (! $timeStr) {
            return null;
        }

        if (! preg_match('/(\d{1,2})[.:](\d{2})\s*(?:[-–—]|s\/d|sd)\s*(\d{1,2})[.:](\d{2})/iu', $timeStr, $m)) {
            return null;
        }

        $start = ((int) $m[1]) + (((int) $m[2]) / 60);
        $end   = ((int) $m[3]) + (((int) $m[4]) / 60);

        if ($end <= $start) {
            return null;
        }

        return ['start' => $start, 'end' => $end];
    }

    /**
     * Cari kegiatan terjadwal di tanggal yang sama yang bentrok atau kurang dari jeda 1 jam.
     */
    protected function findConflictingEvent(string $tanggalIso, string $waktuBaru, ?int $excludeBeritaKegiatanId = null): ?BeritaKegiatan
    {
        $existingEvents = BeritaKegiatan::query()
            ->where('terjadwal_di_kalender', true)
            ->whereDate('tanggal', $tanggalIso)
            ->where('id', '!=', $excludeBeritaKegiatanId ?? 0)
            ->get();

        if ($existingEvents->isEmpty()) {
            return null;
        }

        $newRange = self::parseTimeRange($waktuBaru);

        foreach ($existingEvents as $ev) {
            $evRange = self::parseTimeRange($ev->waktu_teks);

            // Apabila salah satu format jam tidak dapat dikenali, anggap bentrok demi keamanan jadwal
            if (! $newRange || ! $evRange) {
                return $ev;
            }

            // Syarat valid (minimal jeda 1 jam):
            // Selesai kegiatan baru <= (Mulai kegiatan lama - 1 jam) ATAU Mulai kegiatan baru >= (Selesai kegiatan lama + 1 jam)
            $finishesOneHourBefore = $newRange['end'] <= ($evRange['start'] - 1);
            $startsOneHourAfter    = $newRange['start'] >= ($evRange['end'] + 1);

            if (! $finishesOneHourBefore && ! $startsOneHourAfter) {
                return $ev;
            }
        }

        return null;
    }

    /**
     * Setujui (ACC) pengajuan kegiatan dan sinkronkan ke kalender publik.
     */
    public function setujui(Request $request, PengajuanKegiatan $pengajuan): RedirectResponse
    {
        // Validasi: Cek apakah pada tanggal tersebut ada kegiatan terjadwal yang bentrok atau jedanya < 1 jam
        $bentrok = $this->findConflictingEvent(
            $pengajuan->tanggal->toDateString(),
            (string) $pengajuan->waktu,
            $pengajuan->berita_kegiatan_id
        );

        if ($bentrok) {
            return back()->with(
                'warning',
                'Tidak dapat menyetujui (ACC). Waktu "' . $pengajuan->waktu . '" pada tanggal '
                . $pengajuan->tanggal->translatedFormat('d F Y')
                . ' bentrok atau kurang dari jeda minimal 1 jam dengan agenda "' . $bentrok->judul
                . '" (' . ($bentrok->waktu_teks ?? '-') . '). Silakan sesuaikan jam melalui tombol Edit atau tolak pengajuan.'
            );
        }

        DB::transaction(function () use ($pengajuan) {
            // 1. Buat atau perbarui record di berita_kegiatans agar muncul di kalender publik
            $slugBase = Str::slug($pengajuan->jenis_kegiatan . '-' . $pengajuan->nama_pemohon);
            $slug = $slugBase;
            $counter = 1;
            while (BeritaKegiatan::where('slug', $slug)->where('id', '!=', $pengajuan->berita_kegiatan_id)->exists()) {
                $slug = $slugBase . '-' . $counter++;
            }

            $kategoriMapping = [
                'Santunan & Bakti Sosial' => 'Sosial',
                'Buka Puasa Bersama (Bukber)' => 'Sosial',
                'Kunjungan / Silaturahmi Komunitas' => 'Sosial',
                'Edukasi / Pelatihan / Workshop' => 'Pendidikan',
                'Peringatan Hari Besar Islam (PHBI)' => 'Tahfidz Quran',
                'Perlombaan / Fun Games' => 'Kegiatan Luar Ruangan',
                'Lainnya' => 'Kegiatan Yayasan',
            ];
            $kategori = $kategoriMapping[$pengajuan->jenis_kegiatan] ?? 'Kegiatan Yayasan';

            $judul = $pengajuan->jenis_kegiatan . ' bersama ' . $pengajuan->nama_pemohon;

            $kegiatan = BeritaKegiatan::updateOrCreate(
                ['id' => $pengajuan->berita_kegiatan_id],
                [
                    'tipe' => 'kegiatan',
                    'kategori' => $kategori,
                    'judul' => $judul,
                    'slug' => $slug,
                    'ringkasan' => $pengajuan->rencana_kegiatan,
                    'konten' => $pengajuan->rencana_kegiatan,
                    'gambar' => 'potopalingatas.png',
                    'lokasi' => 'Asrama Cibiru, Bandung',
                    'penyelenggara' => $pengajuan->nama_pemohon,
                    'tanggal' => $pengajuan->tanggal,
                    'waktu_teks' => $pengajuan->waktu,
                    'terjadwal_di_kalender' => true,
                    'unggulan' => false,
                ]
            );

            // 2. Perbarui status pengajuan
            $pengajuan->update([
                'status' => 'disetujui',
                'berita_kegiatan_id' => $kegiatan->id,
            ]);
        });

        return back()->with('success', 'Pengajuan kegiatan dari "' . $pengajuan->nama_pemohon . '" berhasil disetujui (ACC) dan otomatis diterbitkan di kalender kegiatan!');
    }

    /**
     * Tolak pengajuan kegiatan.
     */
    public function tolak(Request $request, PengajuanKegiatan $pengajuan): RedirectResponse
    {
        DB::transaction(function () use ($request, $pengajuan) {
            // Jika sebelumnya terbit di kalender publik, hapus / batalkan kalender
            if ($pengajuan->berita_kegiatan_id) {
                BeritaKegiatan::where('id', $pengajuan->berita_kegiatan_id)->delete();
            }

            $pengajuan->update([
                'status' => 'ditolak',
                'catatan_admin' => $request->input('catatan_admin', 'Jadwal berbenturan atau fasilitas tidak mencukupi.'),
                'berita_kegiatan_id' => null,
            ]);
        });

        return back()->with('warning', 'Pengajuan kegiatan dari "' . $pengajuan->nama_pemohon . '" telah ditolak.');
    }

    /**
     * Perbarui data pengajuan kegiatan oleh admin.
     */
    public function update(Request $request, PengajuanKegiatan $pengajuan): RedirectResponse
    {
        $validated = $request->validate([
            'nama_pemohon'     => 'required|string|max:255',
            'nomor_whatsapp'   => 'required|string|max:50',
            'tanggal'          => 'required|date',
            'waktu'            => 'required|string|max:100',
            'jenis_kegiatan'   => 'required|string|max:150',
            'rencana_kegiatan' => 'required|string',
        ]);

        $parsedRange = self::parseTimeRange($validated['waktu']);
        if (! $parsedRange) {
            return back()->with('warning', 'Format waktu pelaksanaan tidak valid. Pastikan memilih Waktu Mulai dan Waktu Selesai yang sesuai (08.00 - 19.00 WIB).');
        }

        // Jika statusnya disetujui, cek apakah perubahan tanggal/waktu bentrok atau kurang dari jeda 1 jam dengan agenda lain
        if ($pengajuan->status === 'disetujui') {
            $bentrok = $this->findConflictingEvent(
                $validated['tanggal'],
                $validated['waktu'],
                $pengajuan->berita_kegiatan_id
            );

            if ($bentrok) {
                return back()->with(
                    'warning',
                    'Gagal mengubah jadwal. Waktu "' . $validated['waktu'] . '" pada tanggal '
                    . date('d F Y', strtotime($validated['tanggal']))
                    . ' bentrok atau kurang dari jeda minimal 1 jam dengan agenda "' . $bentrok->judul
                    . '" (' . ($bentrok->waktu_teks ?? '-') . ').'
                );
            }
        }

        DB::transaction(function () use ($validated, $pengajuan) {
            $pengajuan->update($validated);

            // Jika pengajuan sudah di-ACC dan terbit di kalender publik, sinkronkan juga perubahannya
            if ($pengajuan->berita_kegiatan_id && $pengajuan->status === 'disetujui') {
                $kegiatan = BeritaKegiatan::find($pengajuan->berita_kegiatan_id);
                if ($kegiatan) {
                    $kegiatan->update([
                        'judul'         => $validated['jenis_kegiatan'] . ' bersama ' . $validated['nama_pemohon'],
                        'ringkasan'     => $validated['rencana_kegiatan'],
                        'konten'        => $validated['rencana_kegiatan'],
                        'penyelenggara' => $validated['nama_pemohon'],
                        'tanggal'       => $validated['tanggal'],
                        'waktu_teks'    => $validated['waktu'],
                    ]);
                }
            }
        });

        return back()->with('success', 'Data pengajuan kegiatan dari "' . $pengajuan->nama_pemohon . '" berhasil diperbarui!');
    }

    /**
     * Hapus pengajuan kegiatan.
     */
    public function destroy(PengajuanKegiatan $pengajuan): RedirectResponse
    {
        DB::transaction(function () use ($pengajuan) {
            // Hapus juga agenda di kalender publik jika tertaut
            if ($pengajuan->berita_kegiatan_id) {
                BeritaKegiatan::where('id', $pengajuan->berita_kegiatan_id)->delete();
            }

            $pengajuan->delete();
        });

        return back()->with('success', 'Pengajuan kegiatan berhasil dihapus.');
    }
}

