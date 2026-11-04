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

        $hasTable = \Illuminate\Support\Facades\Schema::hasTable('pengajuan_kegiatans');

        if (! $hasTable) {
            $daftarPengajuan = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
            $counts = ['total' => 0, 'menunggu' => 0, 'disetujui' => 0, 'ditolak' => 0];
            return view('admin.pengajuan-kegiatan.index', compact('daftarPengajuan', 'counts', 'status', 'search'));
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
            ->orderByDesc('tanggal')
            ->paginate(10)
            ->withQueryString();

        // Ambil daftar tanggal yang sudah terisi agenda di kalender
        $tanggalTerjadwal = BeritaKegiatan::query()
            ->where('terjadwal_di_kalender', true)
            ->pluck('tanggal')
            ->map(fn($t) => is_string($t) ? $t : $t->toDateString())
            ->unique()
            ->toArray();

        $counts = [
            'total'     => PengajuanKegiatan::count(),
            'menunggu'  => PengajuanKegiatan::where('status', 'menunggu')->count(),
            'disetujui' => PengajuanKegiatan::where('status', 'disetujui')->count(),
            'ditolak'   => PengajuanKegiatan::where('status', 'ditolak')->count(),
        ];

        return view('admin.pengajuan-kegiatan.index', compact('daftarPengajuan', 'counts', 'status', 'search', 'tanggalTerjadwal'));
    }

    /**
     * Setujui (ACC) pengajuan kegiatan dan sinkronkan ke kalender publik.
     */
    public function setujui(Request $request, PengajuanKegiatan $pengajuan): RedirectResponse
    {
        // Validasi: Cek apakah pada tanggal tersebut sudah ada kegiatan lain yang terjadwal di kalender
        $bentrok = BeritaKegiatan::query()
            ->where('terjadwal_di_kalender', true)
            ->whereDate('tanggal', $pengajuan->tanggal->toDateString())
            ->where('id', '!=', $pengajuan->berita_kegiatan_id ?? 0)
            ->first();

        if ($bentrok) {
            return back()->with('warning', 'Tidak dapat menyetujui (ACC). Tanggal ' . $pengajuan->tanggal->translatedFormat('d F Y') . ' sudah terisi oleh agenda "' . $bentrok->judul . '". Silakan tolak atau koordinasikan tanggal lain.');
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

        // Jika statusnya disetujui, cek apakah perubahan tanggal bentrok dengan agenda lain
        if ($pengajuan->status === 'disetujui') {
            $bentrok = BeritaKegiatan::query()
                ->where('terjadwal_di_kalender', true)
                ->whereDate('tanggal', $validated['tanggal'])
                ->where('id', '!=', $pengajuan->berita_kegiatan_id ?? 0)
                ->first();

            if ($bentrok) {
                return back()->with('warning', 'Gagal mengubah jadwal. Tanggal ' . date('d F Y', strtotime($validated['tanggal'])) . ' sudah terisi oleh agenda "' . $bentrok->judul . '".');
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

