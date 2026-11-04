@extends('layouts.admin')

@section('content')
<div class="space-y-5 pb-12" x-data="{
    detailModalOpen: false,
    tolakModalOpen: false,
    editModalOpen: false,
    selected: {},
    openDetail(item) {
        this.selected = item;
        this.detailModalOpen = true;
    },
    openEdit(item) {
        this.selected = { ...item };
        // Format tanggal ke YYYY-MM-DD untuk input date
        if (this.selected.tanggal && this.selected.tanggal.includes('T')) {
            this.selected.tanggal = this.selected.tanggal.split('T')[0];
        }
        this.editModalOpen = true;
    },
    openTolak(item) {
        this.selected = item;
        this.tolakModalOpen = true;
    }
}">
    <!-- Flash Notifications -->
    @if(session('success'))
        <div class="alert alert-success justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="hover:opacity-70" aria-label="Tutup notifikasi">&times;</button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                <span>{{ session('warning') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="hover:opacity-70" aria-label="Tutup notifikasi">&times;</button>
        </div>
    @endif

    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-brand-900">Pengajuan Jadwal & Kunjungan</h1>
            <p class="text-xs text-muted-foreground-1 mt-0.5">Kelola verifikasi permohonan kunjungan dan agenda kegiatan dari masyarakat/komunitas.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('berita-kegiatan.index') }}#kalender-kegiatan" target="_blank"
               class="btn btn-secondary btn-sm rounded-full">
                <svg class="w-4 h-4 text-brand-700" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                </svg>
                <span>Lihat Kalender Publik</span>
            </a>
        </div>
    </div>

    <!-- 4 Summary Stat Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4">
        <!-- Semua -->
        <a href="{{ route('admin.pengajuan-kegiatan.index', ['status' => 'semua']) }}"
           class="bg-card rounded-2xl p-4 border transition {{ $status === 'semua' ? 'border-brand-700 ring-2 ring-brand-700/20' : 'border-border hover:border-brand-300' }}">
            <p class="text-2xs font-bold uppercase tracking-wider text-muted-foreground-1">Total Pengajuan</p>
            <p class="text-2xl sm:text-3xl font-extrabold text-foreground tabular mt-1">{{ $counts['total'] }}</p>
        </a>

        <!-- Menunggu -->
        <a href="{{ route('admin.pengajuan-kegiatan.index', ['status' => 'menunggu']) }}"
           class="bg-card rounded-2xl p-4 border transition {{ $status === 'menunggu' ? 'border-warning ring-2 ring-warning/20' : 'border-border hover:border-warning' }}">
            <div class="flex items-center justify-between">
                <p class="text-2xs font-bold uppercase tracking-wider text-warning">Menunggu Konfirmasi</p>
                <span class="size-2 rounded-full bg-warning animate-pulse"></span>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-warning tabular mt-1">{{ $counts['menunggu'] }}</p>
        </a>

        <!-- Disetujui (ACC) -->
        <a href="{{ route('admin.pengajuan-kegiatan.index', ['status' => 'disetujui']) }}"
           class="bg-card rounded-2xl p-4 border transition {{ $status === 'disetujui' ? 'border-success ring-2 ring-success/20' : 'border-border hover:border-success' }}">
            <p class="text-2xs font-bold uppercase tracking-wider text-brand-700">Disetujui / Terbit</p>
            <p class="text-2xl sm:text-3xl font-extrabold text-brand-700 tabular mt-1">{{ $counts['disetujui'] }}</p>
        </a>

        <!-- Ditolak -->
        <a href="{{ route('admin.pengajuan-kegiatan.index', ['status' => 'ditolak']) }}"
           class="bg-card rounded-2xl p-4 border transition {{ $status === 'ditolak' ? 'border-danger ring-2 ring-danger/20' : 'border-border hover:border-danger' }}">
            <p class="text-2xs font-bold uppercase tracking-wider text-danger">Ditolak</p>
            <p class="text-2xl sm:text-3xl font-extrabold text-danger tabular mt-1">{{ $counts['ditolak'] }}</p>
        </a>
    </div>

    <!-- Filter & Search Form -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-card p-3 rounded-2xl border border-border">
        <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar">
            <a href="{{ route('admin.pengajuan-kegiatan.index', ['status' => 'semua', 'search' => $search]) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ $status === 'semua' ? 'bg-brand-700 text-white' : 'text-muted-foreground-2 hover:bg-muted' }}">
                Semua
            </a>
            <a href="{{ route('admin.pengajuan-kegiatan.index', ['status' => 'menunggu', 'search' => $search]) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ $status === 'menunggu' ? 'bg-brand-700 text-white' : 'text-muted-foreground-2 hover:bg-muted' }}">
                Menunggu ({{ $counts['menunggu'] }})
            </a>
            <a href="{{ route('admin.pengajuan-kegiatan.index', ['status' => 'disetujui', 'search' => $search]) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ $status === 'disetujui' ? 'bg-brand-700 text-white' : 'text-muted-foreground-2 hover:bg-muted' }}">
                Disetujui ({{ $counts['disetujui'] }})
            </a>
            <a href="{{ route('admin.pengajuan-kegiatan.index', ['status' => 'ditolak', 'search' => $search]) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ $status === 'ditolak' ? 'bg-brand-700 text-white' : 'text-muted-foreground-2 hover:bg-muted' }}">
                Ditolak ({{ $counts['ditolak'] }})
            </a>
        </div>

        <form method="GET" action="{{ route('admin.pengajuan-kegiatan.index') }}" class="relative sm:w-64">
            <input type="hidden" name="status" value="{{ $status }}">
            <svg class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
            <input type="search" name="search" value="{{ $search }}" placeholder="Cari pemohon / acara..."
                   class="w-full rounded-xl border border-border bg-layer py-1.5 pl-9 pr-3 text-xs text-foreground placeholder:text-muted-foreground focus:border-brand-700 focus:outline-none">
        </form>
    </div>

    <!-- Data Table -->
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-muted text-muted-foreground-1 uppercase tracking-wider text-[11px] border-b border-border">
                    <tr>
                        <th class="py-3 px-4">Waktu Pengajuan</th>
                        <th class="py-3 px-4">Pemohon / Komunitas</th>
                        <th class="py-3 px-4">Jenis Kegiatan</th>
                        <th class="py-3 px-4">Jadwal Acara</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($daftarPengajuan as $item)
                        <tr class="hover:bg-muted/40 transition">
                            <td class="py-3 px-4 whitespace-nowrap">
                                <p class="font-bold text-foreground">{{ $item->created_at->translatedFormat('d M Y') }}</p>
                                <p class="text-[11px] text-muted-foreground-1 font-mono">Pukul {{ $item->created_at->format('H:i') }} WIB</p>
                                <p class="text-[10px] text-brand-700 font-medium mt-0.5">{{ $item->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="py-3 px-4">
                                <p class="font-bold text-foreground text-sm">{{ $item->nama_pemohon }}</p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->nomor_whatsapp) }}" target="_blank"
                                       class="inline-flex items-center gap-1 text-[11px] font-semibold text-brand-700 hover:underline">
                                        <span>WA: {{ $item->nomor_whatsapp }}</span>
                                    </a>
                                </div>
                            </td>
                            <td class="py-3 px-4 font-semibold text-brand-900">
                                {{ $item->jenis_kegiatan }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <p class="font-bold text-foreground">{{ $item->tanggal->translatedFormat('d F Y') }}</p>
                                <p class="text-[11px] text-muted-foreground-1">{{ $item->waktu }}</p>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                @php
                                    $tglStr = $item->tanggal->toDateString();
                                    $sudahAdaJadwal = in_array($tglStr, $tanggalTerjadwal ?? []) && $item->status === 'menunggu';
                                @endphp
                                @if($item->status === 'menunggu')
                                    @if($sudahAdaJadwal)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-2xs font-semibold bg-danger-soft text-danger" title="Tanggal ini sudah memiliki agenda yang di-ACC">
                                            Bentrok / Penuh
                                        </span>
                                    @else
                                        <span class="badge badge-warning">Menunggu</span>
                                    @endif
                                @elseif($item->status === 'disetujui')
                                    <span class="badge badge-success">Disetujui (ACC)</span>
                                @else
                                    <span class="badge badge-danger">Ditolak</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Tombol Detail -->
                                    <button type="button"
                                            @click="openDetail({
                                                ...{{ Js::from($item) }},
                                                waktu_diajukan_lengkap: 'Diajukan pada {{ $item->created_at->translatedFormat('d F Y, H:i') }} WIB ({{ $item->created_at->diffForHumans() }})'
                                            })"
                                            class="btn btn-secondary btn-sm p-1.5 rounded-lg"
                                            title="Lihat Rincian">
                                        <svg class="size-4 text-muted-foreground-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0Z" />
                                        </svg>
                                    </button>

                                    <!-- Tombol Edit -->
                                    <button type="button"
                                            @click="openEdit({{ Js::from($item) }})"
                                            class="btn btn-secondary btn-sm p-1.5 rounded-lg text-brand-700 hover:text-brand-800"
                                            title="Edit Data Pengajuan">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </button>

                                    @if($item->status === 'menunggu')
                                        @if($sudahAdaJadwal)
                                            <!-- Tombol ACC Disabled karena tanggal sudah terisi -->
                                            <button type="button"
                                                    disabled
                                                    class="btn btn-secondary btn-sm px-2.5 py-1.5 rounded-lg text-2xs opacity-40 cursor-not-allowed"
                                                    title="Tidak bisa di-ACC karena tanggal ini sudah terisi agenda lain">
                                                <span>Penuh</span>
                                            </button>
                                        @else
                                            <!-- Tombol Setujui (ACC) -->
                                            <form method="POST" action="{{ route('admin.pengajuan-kegiatan.setujui', $item->id) }}"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pengajuan ini? Agenda akan langsung tampil di Kalender Kegiatan publik.')">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-primary btn-sm px-2.5 py-1.5 rounded-lg text-2xs"
                                                        title="Setujui dan Terbitkan ke Kalender">
                                                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                                    </svg>
                                                    <span>ACC</span>
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Tombol Tolak -->
                                        <button type="button"
                                                @click="openTolak({{ Js::from($item) }})"
                                                class="btn btn-danger btn-sm p-1.5 rounded-lg"
                                                title="Tolak Pengajuan">
                                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    @endif

                                    <!-- Tombol Hapus (Delete) -->
                                    <form method="POST" action="{{ route('admin.pengajuan-kegiatan.destroy', $item->id) }}"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan ini secara permanen?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-secondary btn-sm p-1.5 rounded-lg text-danger hover:bg-danger-soft"
                                                title="Hapus Pengajuan">
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-muted-foreground-1">
                                Belum ada data pengajuan kegiatan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($daftarPengajuan->hasPages())
            <div class="p-4 border-t border-border">
                {{ $daftarPengajuan->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL DETAIL PENGAJUAN -->
    <div x-show="detailModalOpen" x-cloak
         class="fixed inset-0 z-80 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
         @click.self="detailModalOpen = false">
        <div class="relative w-full max-w-lg rounded-3xl bg-card border border-border p-6 sm:p-7 shadow-2xl space-y-4">
            <div class="flex items-start justify-between">
                <div>
                    <span class="badge"
                          :class="selected.status === 'disetujui' ? 'badge-success' : (selected.status === 'ditolak' ? 'badge-danger' : 'badge-warning')"
                          x-text="selected.status ? selected.status.toUpperCase() : ''"></span>
                    <h3 class="mt-2 text-xl font-extrabold text-foreground" x-text="selected.nama_pemohon"></h3>
                    <p class="text-xs text-muted-foreground-1" x-text="selected.waktu_diajukan_lengkap || ('Diajukan pada ' + selected.created_at)"></p>
                </div>
                <button type="button" @click="detailModalOpen = false" class="text-muted-foreground hover:text-foreground text-xl">&times;</button>
            </div>

            <div class="p-4 rounded-2xl bg-muted/70 space-y-3 text-xs">
                <div class="flex justify-between border-b border-border/60 pb-2">
                    <span class="text-muted-foreground-1">Jenis Kegiatan</span>
                    <span class="font-bold text-foreground" x-text="selected.jenis_kegiatan"></span>
                </div>
                <div class="flex justify-between border-b border-border/60 pb-2">
                    <span class="text-muted-foreground-1">Jadwal Tanggal</span>
                    <span class="font-bold text-foreground" x-text="selected.tanggal"></span>
                </div>
                <div class="flex justify-between border-b border-border/60 pb-2">
                    <span class="text-muted-foreground-1">Waktu Pelaksanaan</span>
                    <span class="font-bold text-foreground" x-text="selected.waktu"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-muted-foreground-1">Nomor WhatsApp</span>
                    <a :href="'https://wa.me/' + (selected.nomor_whatsapp ? selected.nomor_whatsapp.replace(/[^0-9]/g, '') : '')"
                       target="_blank"
                       class="font-bold text-brand-700 hover:underline inline-flex items-center gap-1">
                        <span x-text="selected.nomor_whatsapp"></span>
                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                    </a>
                </div>
            </div>

            <div>
                <p class="text-xs font-bold text-foreground mb-1">Rencana Kegiatan & Kebutuhan Fasilitas:</p>
                <p class="p-3.5 rounded-xl border border-border bg-layer text-xs leading-relaxed text-muted-foreground-2"
                   x-text="selected.rencana_kegiatan"></p>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" @click="detailModalOpen = false" class="btn btn-secondary btn-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL TOLAK PENGAJUAN -->
    <div x-show="tolakModalOpen" x-cloak
         class="fixed inset-0 z-80 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
         @click.self="tolakModalOpen = false">
        <div class="relative w-full max-w-md rounded-3xl bg-card border border-border p-6 shadow-2xl space-y-4">
            <h3 class="text-lg font-extrabold text-foreground">Tolak Pengajuan Kegiatan</h3>
            <p class="text-xs text-muted-foreground-1">
                Sampaikan alasan penolakan untuk arsip internal atau penjelasan kepada pemohon <span class="font-bold text-foreground" x-text="selected.nama_pemohon"></span>.
            </p>

            <form :action="'/admin/pengajuan-kegiatan/' + selected.id + '/tolak'" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="form-label text-xs">Catatan / Alasan Penolakan</label>
                    <textarea name="catatan_admin" rows="3" class="form-input text-xs" placeholder="Contoh: Jadwal berbenturan dengan agenda internal yayasan..."></textarea>
                </div>

                <div class="flex items-center justify-end gap-2">
                    <button type="button" @click="tolakModalOpen = false" class="btn btn-secondary btn-sm">Batal</button>
                    <button type="submit" class="btn btn-danger btn-sm">Tolak Pengajuan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT DATA PENGAJUAN -->
    <div x-show="editModalOpen" x-cloak
         class="fixed inset-0 z-80 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
         @click.self="editModalOpen = false">
        <div class="relative w-full max-w-lg rounded-3xl bg-card border border-border p-6 sm:p-7 shadow-2xl space-y-4 max-h-[92vh] overflow-y-auto">
            <div class="flex items-start justify-between border-b border-border pb-3">
                <div>
                    <h3 class="text-lg font-extrabold text-foreground">Edit Data Pengajuan Kegiatan</h3>
                    <p class="text-xs text-muted-foreground-1">Perbarui rincian waktu, pemohon, atau jadwal acara.</p>
                </div>
                <button type="button" @click="editModalOpen = false" class="text-muted-foreground hover:text-foreground text-xl">&times;</button>
            </div>

            <form :action="'/admin/pengajuan-kegiatan/' + selected.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="form-label text-xs">Nama Pemohon / Komunitas <span class="text-danger">*</span></label>
                    <input type="text" name="nama_pemohon" x-model="selected.nama_pemohon" required class="form-input text-xs">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="form-label text-xs">Nomor WhatsApp <span class="text-danger">*</span></label>
                        <input type="text" name="nomor_whatsapp" x-model="selected.nomor_whatsapp" required class="form-input text-xs">
                    </div>
                    <div>
                        <label class="form-label text-xs">Waktu Pelaksanaan <span class="text-danger">*</span></label>
                        <input type="text" name="waktu" x-model="selected.waktu" required class="form-input text-xs" placeholder="Contoh: 09.30 - 12.00 WIB">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="form-label text-xs">Tanggal Pelaksanaan <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" x-model="selected.tanggal" required class="form-input text-xs">
                    </div>
                    <div>
                        <label class="form-label text-xs">Jenis Kegiatan <span class="text-danger">*</span></label>
                        <select name="jenis_kegiatan" x-model="selected.jenis_kegiatan" required class="form-input text-xs">
                            <option value="Santunan & Bakti Sosial">Santunan & Bakti Sosial</option>
                            <option value="Buka Puasa Bersama (Bukber)">Buka Puasa Bersama (Bukber)</option>
                            <option value="Kunjungan / Silaturahmi Komunitas">Kunjungan / Silaturahmi Komunitas</option>
                            <option value="Edukasi / Pelatihan / Workshop">Edukasi / Pelatihan / Workshop</option>
                            <option value="Peringatan Hari Besar Islam (PHBI)">Peringatan Hari Besar Islam (PHBI)</option>
                            <option value="Perlombaan / Fun Games">Perlombaan / Fun Games</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="form-label text-xs">Rencana Kegiatan & Kebutuhan Fasilitas <span class="text-danger">*</span></label>
                    <textarea name="rencana_kegiatan" rows="3" x-model="selected.rencana_kegiatan" required class="form-input text-xs"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-border">
                    <button type="button" @click="editModalOpen = false" class="btn btn-secondary btn-sm">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

