@extends('layouts.admin')

@section('content')
<!-- Initial JSON Data Container (W3C Standard JSON Script to avoid HTML attribute quote escaping issues) -->
<script id="laporan-initial-data" type="application/json">
    {!! json_encode([
        'jenisLaporan'    => $jenisLaporan,
        'bulan'           => $bulan,
        'tahun'           => $tahun,
        'kategori'        => $kategori,
        'stats'           => $stats,
        'columns'         => $columns,
        'previewData'     => $previewData,
        'pagination'      => $pagination,
        'daftarKategori'  => $daftarKategori,
        'categoriesMap'   => $categoriesMap,
        'routes'          => [
            'index'  => route('admin.laporan-ekspor.index', [], false),
            'excel'  => route('admin.laporan-ekspor.export.excel', [], false),
            'pdf'    => route('admin.laporan-ekspor.export.pdf', [], false),
        ],
    ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}
</script>

<div class="space-y-4 sm:space-y-5 pb-16 w-full max-w-full min-w-0"
     x-data="laporanEksporApp()"
     x-init="init()">

    <!-- Header Page: Title, Subtitle & Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-brand-900 tracking-tight">Laporan & Ekspor</h1>
            <p class="text-xs sm:text-sm text-muted-foreground-1 mt-0.5 font-medium">Filter laporan sesuai kebutuhan, preview format PDF/Excel sebelum diunduh</p>
        </div>
        <div class="w-full sm:w-auto">
            <button type="button"
                    data-hs-overlay="#history-export-modal"
                    aria-haspopup="dialog"
                    aria-expanded="false"
                    aria-controls="history-export-modal"
                    class="btn btn-secondary w-full sm:w-auto">
                <svg class="w-4 h-4 text-muted-foreground-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                <span>Riwayat Ekspor</span>
            </button>
        </div>
    </div>

    <!-- 1. Card Filter: Pilih Jenis Laporan & Filter -->
    <div class="bg-card rounded-2xl border border-border p-5 sm:p-6 shadow-2xs space-y-4">
        <div class="flex items-center justify-between gap-2 border-b border-border-line-1 pb-3">
            <div class="flex items-center gap-2 text-foreground font-bold text-sm sm:text-base">
                <svg class="w-4.5 h-4.5 text-muted-foreground-2" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                <span>Filter Laporan</span>
            </div>
            <span class="text-xs text-muted-foreground font-medium hidden sm:inline" x-text="'Laporan aktif: ' + jenisLaporan">Laporan aktif: {{ $jenisLaporan }}</span>
        </div>

        <form action="{{ route('admin.laporan-ekspor.index', [], false) }}" method="GET" @submit.prevent="triggerPreview(1)" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Jenis Laporan -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-foreground">Jenis Laporan</label>
                    <x-preline-select name="jenis_laporan"
                                      x-model="jenisLaporan"
                                      @change="handleJenisChange()"
                                      placeholder="Pilih jenis laporan">
                        <option value="Data Anak Asuh" @selected($jenisLaporan === 'Data Anak Asuh')>Data Anak Asuh</option>
                        <option value="Kelola Donasi" @selected($jenisLaporan === 'Kelola Donasi')>Kelola Donasi</option>
                        <option value="Kelola Kampanye" @selected($jenisLaporan === 'Kelola Kampanye')>Kelola Kampanye</option>
                    </x-preline-select>
                </div>

                <!-- Bulan -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-foreground">Bulan</label>
                    <x-preline-select name="bulan"
                                      x-model="bulan"
                                      @change="handleFilterChange()"
                                      placeholder="Pilih bulan">
                        @foreach($daftarBulan as $b)
                            <option value="{{ $b }}" @selected($bulan === $b)>{{ $b }}</option>
                        @endforeach
                    </x-preline-select>
                </div>

                <!-- Tahun -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-foreground">Tahun</label>
                    <x-preline-select name="tahun"
                                      x-model="tahun"
                                      @change="handleFilterChange()"
                                      placeholder="Pilih tahun">
                        @foreach($daftarTahun as $t)
                            <option value="{{ $t }}" @selected($tahun === $t)>{{ $t }}</option>
                        @endforeach
                    </x-preline-select>
                </div>

                <!-- Kategori -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-foreground">Kategori</label>
                    <x-preline-select name="kategori"
                                      x-ref="kategoriSelect"
                                      x-model="kategori"
                                      @change="handleFilterChange()"
                                      placeholder="Pilih kategori">
                        @foreach($daftarKategori as $opt)
                            <option value="{{ $opt }}" @selected($kategori === $opt)>{{ $opt }}</option>
                        @endforeach
                    </x-preline-select>
                </div>
            </div>

            <!-- Tombol Aksi Filter: Tampilkan Preview & Reset -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                <div class="text-xs text-muted-foreground-1 font-medium">
                    <span x-show="previewLoading" class="inline-flex items-center gap-1.5 text-brand-700 font-semibold">
                        <svg class="animate-spin h-3.5 w-3.5 text-brand-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        Memuat data preview...
                    </span>
                    <span x-show="!previewLoading" class="text-muted-foreground">
                        Pilih filter di atas, lalu klik tombol untuk memperbarui preview tabel, PDF, atau Excel.
                    </span>
                </div>
                <div class="flex items-center gap-2.5 w-full sm:w-auto">
                    <button type="button"
                            @click="resetFilter()"
                            :disabled="previewLoading"
                            class="btn btn-secondary flex-1 sm:flex-none">
                        <svg class="w-3.5 h-3.5 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                        <span>Reset</span>
                    </button>
                    <button type="submit"
                            :disabled="previewLoading"
                            class="btn btn-primary flex-1 sm:flex-none">
                        <svg x-show="previewLoading" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <svg x-show="!previewLoading" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span x-text="previewLoading ? 'Memuat...' : 'Tampilkan Preview'">Tampilkan Preview</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- 2. Stat Cards (4 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4">
        <!-- Card 1 -->
        <div class="bg-card rounded-2xl p-4 sm:p-5 border border-border shadow-2xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-brand-50 text-brand-700 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs text-muted-foreground-1 font-medium truncate" x-text="stats.cards?.[0]?.title ?? 'Total Data'">{{ $stats['cards'][0]['title'] ?? 'Total Data' }}</p>
                <p class="text-2xl sm:text-3xl font-black text-foreground tracking-tight mt-0.5 truncate" x-text="stats.cards?.[0]?.value ?? stats.total">{{ $stats['cards'][0]['value'] ?? $stats['total'] }}</p>
                <p class="text-2xs text-muted-foreground mt-0.5 font-medium truncate" x-text="stats.cards?.[0]?.sub ?? 'Data sesuai filter'">{{ $stats['cards'][0]['sub'] ?? 'Data sesuai filter' }}</p>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-card rounded-2xl p-4 sm:p-5 border border-border shadow-2xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-brand-50 text-brand-700 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-5.25 6.557c.725 0 1.436-.033 2.138-.098" />
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs text-muted-foreground-1 font-medium truncate" x-text="stats.cards?.[1]?.title ?? 'Status Utama'">{{ $stats['cards'][1]['title'] ?? 'Status Utama' }}</p>
                <p class="text-2xl sm:text-3xl font-black text-foreground tracking-tight mt-0.5 truncate" x-text="stats.cards?.[1]?.value ?? stats.aktif_pendidikan">{{ $stats['cards'][1]['value'] ?? $stats['aktif_pendidikan'] }}</p>
                <p class="text-2xs text-muted-foreground mt-0.5 font-medium truncate" x-text="stats.cards?.[1]?.sub ?? (stats.aktif_pendidikan_persen + ' dari total')">{{ $stats['cards'][1]['sub'] ?? ($stats['aktif_pendidikan_persen'] . ' dari total') }}</p>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-card rounded-2xl p-4 sm:p-5 border border-border shadow-2xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-brand-50 text-brand-700 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs text-muted-foreground-1 font-medium truncate" x-text="stats.cards?.[2]?.title ?? 'Aktivitas'">{{ $stats['cards'][2]['title'] ?? 'Aktivitas' }}</p>
                <p class="text-2xl sm:text-3xl font-black text-foreground tracking-tight mt-0.5 truncate" x-text="stats.cards?.[2]?.value ?? stats.aktif_asrama">{{ $stats['cards'][2]['value'] ?? $stats['aktif_asrama'] }}</p>
                <p class="text-2xs text-muted-foreground mt-0.5 font-medium truncate" x-text="stats.cards?.[2]?.sub ?? (stats.aktif_asrama_persen + ' dari total')">{{ $stats['cards'][2]['sub'] ?? ($stats['aktif_asrama_persen'] . ' dari total') }}</p>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-card rounded-2xl p-4 sm:p-5 border border-border shadow-2xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-warning-soft text-warning flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs text-muted-foreground-1 font-medium truncate" x-text="stats.cards?.[3]?.title ?? 'Perlu Tindakan'">{{ $stats['cards'][3]['title'] ?? 'Perlu Tindakan' }}</p>
                <p class="text-2xl sm:text-3xl font-black text-warning tracking-tight mt-0.5 truncate" x-text="stats.cards?.[3]?.value ?? stats.perlu_perhatian">{{ $stats['cards'][3]['value'] ?? $stats['perlu_perhatian'] }}</p>
                <p class="text-2xs text-muted-foreground mt-0.5 font-medium truncate" x-text="stats.cards?.[3]?.sub ?? (stats.perlu_perhatian_persen + ' dari total')">{{ $stats['cards'][3]['sub'] ?? ($stats['perlu_perhatian_persen'] . ' dari total') }}</p>
            </div>
        </div>
    </div>

    <!-- 3. Grid 2 Kolom: Preview Laporan (Kiri) & Ekspor Laporan + Catatan (Kanan) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-5">
        <!-- Kolom Kiri: Preview Laporan Lengkap (8 cols) -->
        <div class="lg:col-span-8 bg-card rounded-2xl border border-border shadow-2xs p-5 sm:p-6 space-y-4">

            <!-- Header Preview: Mode Switcher (Tabel / PDF / Excel) & Tombol Refresh -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-border-line-1">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-muted-foreground-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        <h2 class="font-bold text-foreground text-sm sm:text-base">Preview Laporan</h2>
                    </div>
                    <p class="text-xs text-muted-foreground-1 font-medium">Pilih mode tampilan untuk melihat data dalam format Tabel, PDF resmi, atau Excel.</p>
                </div>

                <!-- Preview Mode Segmented Tabs -->
                <div class="inline-flex p-1 rounded-xl bg-muted/80 border border-border-line-1 shrink-0 self-start sm:self-auto">
                    <!-- Tab Tabel -->
                    <button type="button"
                            @click="setPreviewMode('table')"
                            :class="previewMode === 'table' ? 'bg-card text-foreground shadow-2xs font-bold' : 'text-muted-foreground-2 hover:text-foreground font-medium'"
                            class="px-3 py-1.5 text-xs rounded-lg transition inline-flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        <span>Tabel</span>
                    </button>

                    <!-- Tab PDF -->
                    <button type="button"
                            @click="setPreviewMode('pdf')"
                            :class="previewMode === 'pdf' ? 'bg-card text-danger shadow-2xs font-bold' : 'text-muted-foreground-2 hover:text-danger font-medium'"
                            class="px-3 py-1.5 text-xs rounded-lg transition inline-flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-3.5 h-3.5 text-danger" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        <span>Preview PDF</span>
                    </button>

                    <!-- Tab Excel -->
                    <button type="button"
                            @click="setPreviewMode('excel')"
                            :class="previewMode === 'excel' ? 'bg-card text-brand-800 shadow-2xs font-bold' : 'text-muted-foreground-2 hover:text-brand-800 font-medium'"
                            class="px-3 py-1.5 text-xs rounded-lg transition inline-flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-3.5 h-3.5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0112 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M12 10.875v7.5m0-7.5a1.125 1.125 0 011.125-1.125h7.125" />
                        </svg>
                        <span>Preview Excel</span>
                    </button>
                </div>
            </div>

            <!-- Loading Spinner Global -->
            <div x-show="previewLoading" class="py-12 flex flex-col items-center justify-center gap-3 text-muted-foreground-1">
                <div class="w-8 h-8 border-3 border-brand-700 border-t-transparent rounded-full animate-spin"></div>
                <p class="text-xs font-semibold text-muted-foreground-2">Menyiapkan preview data laporan...</p>
            </div>

            <!-- ========================================== -->
            <!-- 1. MODE TAMPILAN TABEL INTERAKTIF          -->
            <!-- ========================================== -->
            <div x-show="!previewLoading && previewMode === 'table'" class="space-y-4">
                <!-- Empty State jika tidak ada data -->
                <div x-show="previewData.length === 0" class="py-12 text-center text-muted-foreground bg-muted/70 border border-border-line-1 rounded-xl space-y-2">
                    <svg class="w-10 h-10 mx-auto text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    <p class="text-sm font-bold text-foreground">Tidak ada data yang sesuai filter</p>
                    <p class="text-xs text-muted-foreground">Silakan ubah pilihan bulan, tahun, atau kategori untuk melihat data lainnya.</p>
                </div>

                <!-- Desktop Table (md ke atas) -->
                <div x-show="previewData.length > 0" class="hidden md:block overflow-x-auto w-full rounded-xl border border-border-line-1">
                    <table class="w-full text-left border-collapse text-xs sm:text-sm min-w-[650px]">
                        <thead>
                            <tr class="text-muted-foreground-2 font-bold border-b border-border bg-muted/80">
                                <template x-for="col in columns" :key="col.key">
                                    <th scope="col" :class="'py-3 px-3 ' + (col.width || '')" x-text="col.label"></th>
                                </template>
                                <th scope="col" class="py-3 px-2 text-center w-10"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-line-1 bg-card">
                            <template x-for="(item, idx) in previewData" :key="item.id_anak || idx">
                                <tr class="hover:bg-muted/70 transition">
                                    <td class="py-3 px-3 text-muted-foreground-1 font-medium" x-text="item.no"></td>
                                    <td class="py-3 px-3 font-bold text-foreground" x-text="item.id_anak"></td>
                                    <td class="py-3 px-4 font-semibold text-foreground" x-text="item.nama"></td>
                                    <td class="py-3 px-3">
                                        <span class="badge text-xs"
                                              :class="getCategoryBadgeClass(item.kategori)"
                                              x-text="item.kategori">
                                        </span>
                                    </td>
                                    <td class="py-3 px-3">
                                        <span class="badge text-xs"
                                              :class="getStatusBadgeClass(item.status)"
                                              x-text="item.status">
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-muted-foreground-1 font-medium" x-text="item.tanggal"></td>
                                    <td class="py-3 px-2 text-center text-muted-foreground">
                                        <button type="button"
                                                data-hs-overlay="#detail-laporan-modal"
                                                aria-controls="detail-laporan-modal"
                                                @click="openDetail(item)"
                                                class="p-1 hover:text-foreground rounded-md hover:bg-muted transition cursor-pointer"
                                                title="Lihat Detail"
                                                aria-label="Lihat detail">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card List (< md) -->
                <div x-show="previewData.length > 0" class="md:hidden space-y-3">
                    <template x-for="(item, idx) in previewData" :key="item.id_anak || idx">
                        <div class="bg-muted/70 border border-border rounded-xl p-3.5 space-y-2.5">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-muted-foreground" x-text="'#' + item.no"></span>
                                    <span class="px-2 py-0.5 rounded-md bg-card border border-border text-xs font-black text-foreground shadow-2xs" x-text="item.id_anak"></span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="badge text-[11px]"
                                          :class="getStatusBadgeClass(item.status)"
                                          x-text="item.status"></span>
                                    <button type="button"
                                            data-hs-overlay="#detail-laporan-modal"
                                            aria-controls="detail-laporan-modal"
                                            @click="openDetail(item)"
                                            class="p-1 text-muted-foreground hover:text-foreground cursor-pointer"
                                            aria-label="Lihat detail">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-bold text-foreground text-sm leading-snug" x-text="item.nama"></h4>
                            </div>
                            <div class="flex items-center justify-between text-xs pt-2 border-t border-border">
                                <span class="badge text-[11px]"
                                      :class="getCategoryBadgeClass(item.kategori)"
                                      x-text="item.kategori"></span>
                                <span class="text-muted-foreground font-medium flex items-center gap-1 text-right">
                                    <svg class="w-3.5 h-3.5 text-muted-foreground shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5" />
                                    </svg>
                                    <span x-text="item.tanggal"></span>
                                </span>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Footer Paginasi -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-3 border-t border-border-line-1 text-xs text-muted-foreground-1 font-medium">
                    <div class="text-center sm:text-left">
                        Menampilkan <span class="font-bold text-foreground" x-text="pagination.from || 0">{{ $pagination['from'] ?? 0 }}</span> - <span class="font-bold text-foreground" x-text="pagination.to || 0">{{ $pagination['to'] ?? 0 }}</span> dari <span class="font-bold text-foreground" x-text="pagination.total || 0">{{ $pagination['total'] ?? 0 }}</span> data
                    </div>
                    <div class="flex items-center justify-center gap-1 self-center sm:self-auto" x-show="pagination.last_page > 1">
                        <button type="button"
                                @click="triggerPreview(pagination.current_page - 1)"
                                :disabled="pagination.current_page <= 1"
                                class="size-8 rounded-lg border border-border bg-card text-muted-foreground hover:bg-muted disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center transition cursor-pointer"
                                aria-label="Halaman sebelumnya">
                            &lt;
                        </button>
                        <template x-for="p in getPageNumbers()" :key="p">
                            <button type="button"
                                    @click="triggerPreview(p)"
                                    class="size-8 rounded-lg font-bold flex items-center justify-center transition cursor-pointer"
                                    :class="pagination.current_page === p ? 'bg-primary text-primary-foreground shadow-2xs' : 'border border-border bg-card text-muted-foreground-2 hover:bg-muted font-medium'"
                                    x-text="p">
                            </button>
                        </template>
                        <button type="button"
                                @click="triggerPreview(pagination.current_page + 1)"
                                :disabled="pagination.current_page >= pagination.last_page"
                                class="size-8 rounded-lg border border-border bg-card text-muted-foreground hover:bg-muted disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center transition cursor-pointer"
                                aria-label="Halaman berikutnya">
                            &gt;
                        </button>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- 2. MODE TAMPILAN PREVIEW PDF DOKUMEN       -->
            <!-- ========================================== -->
            <div x-show="!previewLoading && previewMode === 'pdf'" class="space-y-3">
                <!-- Toolbar Preview PDF -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2.5 p-3 rounded-xl bg-muted border border-border">
                    <div class="flex items-center gap-2 text-xs">
                        <span class="badge badge-danger">
                            <svg class="w-3.5 h-3.5 text-danger" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" />
                            </svg>
                            <span>PDF Siap Cetak</span>
                        </span>
                        <span class="text-muted-foreground-2 font-medium truncate max-w-[260px] sm:max-w-none" x-text="`Laporan-${jenisLaporan.replace(/\s+/g, '_')}-${bulan}-${tahun}.pdf`"></span>
                    </div>

                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <!-- Tombol Buka Tab Baru -->
                        <a :href="getPreviewPdfUrl()"
                           target="_blank"
                           class="btn btn-secondary btn-sm flex-1 sm:flex-none">
                            <svg class="w-3.5 h-3.5 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                            </svg>
                            <span>Layar Penuh</span>
                        </a>

                        <!-- Tombol Download PDF Langsung -->
                        <a :href="getExportPdfUrl()"
                           @click="recordExport('PDF (.pdf)')"
                           class="btn btn-danger btn-sm flex-1 sm:flex-none">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            <span>Unduh PDF</span>
                        </a>
                    </div>
                </div>

                <!-- Iframe Viewer PDF Asli -->
                <div class="relative w-full h-[580px] sm:h-[680px] rounded-xl border border-border overflow-hidden bg-muted shadow-inner">
                    <iframe :src="getPreviewPdfUrl()"
                            class="w-full h-full border-0 rounded-xl"
                            title="Preview Dokumen PDF">
                    </iframe>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- 3. MODE TAMPILAN PREVIEW EXCEL SPREADSHEET -->
            <!-- ========================================== -->
            <div x-show="!previewLoading && previewMode === 'excel'" class="space-y-3">
                <!-- Excel Window Wrapper -->
                <div class="rounded-xl border border-border overflow-hidden shadow-xs bg-card">
                    <!-- Excel Ribbon Header -->
                    <div class="bg-[#107C41] text-white px-4 py-2.5 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-md bg-white/20 flex items-center justify-center font-black text-xs">
                                X
                            </div>
                            <div>
                                <p class="text-xs font-bold leading-tight" x-text="`Laporan_${jenisLaporan.replace(/\s+/g, '_')}_${bulan}_${tahun}.xlsx`"></p>
                                <p class="text-[10px] text-emerald-100">Microsoft Excel Worksheet (.xlsx) &bull; Mode Pratinjau Lembar Kerja</p>
                            </div>
                        </div>

                        <a :href="getExportExcelUrl()"
                           @click="recordExport('Excel (.xlsx)')"
                           class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-lg bg-white hover:bg-emerald-50 text-[#107C41] text-xs font-bold transition shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            <span>Unduh File Excel</span>
                        </a>
                    </div>

                    <!-- Formula Bar Simulasi -->
                    <div class="bg-slate-50 border-b border-slate-200 px-3 py-1.5 flex items-center gap-2 text-xs text-slate-600 font-mono">
                        <span class="px-2 py-0.5 rounded bg-white border border-slate-300 font-bold text-slate-700 text-[11px]">A1</span>
                        <span class="text-slate-400 font-sans italic text-[11px]">fx</span>
                        <span class="text-slate-800 font-sans font-semibold truncate" x-text="'Laporan ' + jenisLaporan + ' Yayasan Himatun Ayat Bandung (' + bulan + ' ' + tahun + ')'"></span>
                    </div>

                    <!-- Grid Spreadsheet Table -->
                    <div class="overflow-x-auto max-h-[500px]">
                        <table class="w-full text-left border-collapse text-xs font-sans min-w-[700px]">
                            <thead>
                                <!-- Baris Huruf Kolom Excel (A, B, C, D, E, F) -->
                                <tr class="bg-slate-100 text-slate-500 font-semibold text-center border-b border-slate-300">
                                    <th class="w-10 py-1 border-r border-slate-300 bg-slate-200/80"></th>
                                    <th class="py-1 px-3 border-r border-slate-300">A</th>
                                    <th class="py-1 px-3 border-r border-slate-300">B</th>
                                    <th class="py-1 px-3 border-r border-slate-300">C</th>
                                    <th class="py-1 px-3 border-r border-slate-300">D</th>
                                    <th class="py-1 px-3 border-r border-slate-300">E</th>
                                    <th class="py-1 px-3">F</th>
                                </tr>
                                <!-- Baris 1: Header Judul Kolom (Background Hijau Lembut Khas Excel) -->
                                <tr class="bg-emerald-50 text-emerald-950 font-bold border-b border-slate-300">
                                    <td class="text-center font-mono text-[11px] text-slate-500 bg-slate-100 border-r border-slate-300">1</td>
                                    <template x-for="col in columns" :key="col.key">
                                        <td class="py-2.5 px-3 border-r border-slate-200 text-slate-900 font-bold" x-text="col.label"></td>
                                    </template>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white font-mono text-xs">
                                <template x-for="(item, idx) in previewData" :key="item.id_anak || idx">
                                    <tr class="hover:bg-emerald-50/40 transition">
                                        <!-- Nomor Baris Excel (2, 3, 4, ...) -->
                                        <td class="text-center text-[11px] text-slate-500 bg-slate-100 border-r border-slate-300 font-sans" x-text="idx + 2"></td>
                                        <td class="py-2 px-3 border-r border-slate-200 text-slate-600" x-text="item.no"></td>
                                        <td class="py-2 px-3 border-r border-slate-200 font-bold text-slate-900 font-sans" x-text="item.id_anak"></td>
                                        <td class="py-2 px-3 border-r border-slate-200 text-slate-800 font-sans font-medium" x-text="item.nama"></td>
                                        <td class="py-2 px-3 border-r border-slate-200 text-slate-700 font-sans" x-text="item.kategori"></td>
                                        <td class="py-2 px-3 border-r border-slate-200 text-slate-700 font-sans" x-text="item.status"></td>
                                        <td class="py-2 px-3 text-slate-700 font-sans" x-text="item.tanggal"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Excel Sheet Bar di Bawah -->
                    <div class="bg-slate-100 border-t border-slate-300 px-3 py-1.5 flex items-center justify-between text-xs text-slate-500">
                        <div class="flex items-center gap-1">
                            <span class="px-3 py-1 bg-white border border-slate-300 rounded-t-md text-emerald-800 font-bold text-[11px] shadow-2xs" x-text="'Sheet1 - Data ' + jenisLaporan"></span>
                            <span class="px-2 py-1 text-slate-400 text-xs">+</span>
                        </div>
                        <span class="text-[11px] text-slate-400 font-medium" x-text="'Total ' + (pagination.total || previewData.length) + ' baris siap diekspor'"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Ekspor Laporan & Catatan (4 cols) -->
        <div class="lg:col-span-4 space-y-4">
            <!-- Card Ekspor Laporan -->
            <div class="bg-card rounded-2xl border border-border shadow-2xs p-5 sm:p-6 space-y-4">
                <div class="flex items-start gap-2.5">
                    <svg class="w-5 h-5 text-muted-foreground-2 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    <div>
                        <h2 class="font-bold text-foreground text-sm sm:text-base">Ekspor Laporan</h2>
                        <p class="text-xs text-muted-foreground-1 font-medium mt-0.5 leading-relaxed">Unduh file atau tinjau langsung dalam format PDF & Excel.</p>
                    </div>
                </div>

                <div class="space-y-3 pt-2">
                    <!-- Tombol Ekspor Excel -->
                    <div class="space-y-1">
                        <a :href="getExportExcelUrl()"
                           @click="recordExport('Excel (.xlsx)')"
                           class="w-full flex items-center justify-between p-3.5 rounded-xl border border-border hover:border-brand-500 bg-card hover:bg-brand-50/30 transition group shadow-2xs cursor-pointer">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-brand-50 text-brand-700 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-bold text-xs sm:text-sm text-foreground group-hover:text-brand-800 transition block">Ekspor Excel</span>
                                    <span class="text-[11px] text-muted-foreground font-normal">Format spreadsheet (.xlsx)</span>
                                </div>
                            </div>
                            <svg class="w-4.5 h-4.5 text-muted-foreground group-hover:text-brand-700 transition" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                        </a>
                        <button type="button"
                                @click="setPreviewMode('excel')"
                                class="text-[11px] text-brand-700 hover:text-brand-900 font-semibold flex items-center gap-1 pl-2 pt-0.5 cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Lihat bentuk lembar kerja Excel</span>
                        </button>
                    </div>

                    <!-- Tombol Ekspor PDF -->
                    <div class="space-y-1 pt-2">
                        <a :href="getExportPdfUrl()"
                           @click="recordExport('PDF (.pdf)')"
                           class="w-full flex items-center justify-between p-3.5 rounded-xl border border-border hover:border-danger bg-card hover:bg-danger-soft/30 transition group shadow-2xs cursor-pointer">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-danger-soft text-danger flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-bold text-xs sm:text-sm text-foreground group-hover:text-danger transition block">Ekspor PDF</span>
                                    <span class="text-[11px] text-muted-foreground font-normal">Dokumen resmi cetak (.pdf)</span>
                                </div>
                            </div>
                            <svg class="w-4.5 h-4.5 text-muted-foreground group-hover:text-danger transition" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                        </a>
                        <button type="button"
                                @click="setPreviewMode('pdf')"
                                class="text-[11px] text-danger hover:text-red-700 font-semibold flex items-center gap-1 pl-2 pt-0.5 cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Lihat bentuk dokumen PDF resmi</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card Catatan -->
            <div class="bg-brand-50 border border-brand-100 rounded-2xl p-4 sm:p-5 flex items-start gap-3">
                <div class="w-5 h-5 rounded-full bg-brand-100 dark:bg-brand-900/50 text-brand-800 dark:text-brand-200 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.007v.008H12v-.008zM12 3a9 9 0 100 18 9 9 0 000-18z" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-brand-950 text-xs sm:text-sm">Catatan</h4>
                    <p class="text-xs text-brand-800 font-medium mt-1 leading-relaxed">
                        Anda dapat memeriksa format laporan secara langsung melalui tab <b>Preview PDF</b> dan <b>Preview Excel</b> di sebelah kiri sebelum mengunduh berkas.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Riwayat Ekspor (Preline Modal) -->
    <div id="history-export-modal"
         class="hs-overlay hidden size-full fixed top-0 inset-s-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none"
         role="dialog"
         tabindex="-1"
         aria-labelledby="history-export-modal-label">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
            <div class="flex flex-col bg-overlay border border-overlay-line shadow-2xs rounded-2xl pointer-events-auto max-h-[85vh]">
                <div class="flex justify-between items-center gap-3 py-3 px-5 border-b border-border-line-1">
                    <div>
                        <h3 id="history-export-modal-label" class="text-base font-bold text-foreground">Riwayat Unduhan Laporan</h3>
                        <p class="text-xs text-muted-foreground-1 mt-0.5">Daftar file laporan yang telah diunduh dari sistem</p>
                    </div>
                    <button type="button"
                            data-hs-overlay="#history-export-modal"
                            class="size-8 inline-flex justify-center items-center rounded-full bg-surface border border-surface-line text-surface-foreground hover:bg-surface-hover focus:outline-hidden focus:bg-surface-focus"
                            aria-label="Tutup">
                        <span class="sr-only">Tutup</span>
                        <svg class="shrink-0 size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-5 space-y-2.5 text-xs overflow-y-auto">
                    <template x-if="riwayatEkspor.length === 0">
                        <div class="p-8 text-center text-muted-foreground bg-muted rounded-xl space-y-1">
                            <svg class="w-8 h-8 mx-auto text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            <p class="font-medium text-muted-foreground-2">Belum ada riwayat unduhan laporan.</p>
                            <p class="text-[11px] text-muted-foreground">Unduh laporan dalam format Excel atau PDF untuk melihat catatan riwayat di sini.</p>
                        </div>
                    </template>

                    <template x-for="item in riwayatEkspor" :key="item.id">
                        <div class="p-3 rounded-xl bg-muted border border-border-line-1 flex items-center justify-between hover:bg-muted-hover/70 transition">
                            <div class="space-y-0.5 min-w-0 pr-3">
                                <p class="font-bold text-foreground truncate" x-text="item.judul"></p>
                                <p class="text-muted-foreground">
                                    Format: <span class="font-semibold text-muted-foreground-2" x-text="item.format"></span> &bull;
                                    <span x-text="item.waktu"></span>
                                </p>
                            </div>
                            <span class="badge badge-success shrink-0" x-text="item.status">Selesai</span>
                        </div>
                    </template>
                </div>

                <div class="py-3 px-5 border-t border-border-line-1 flex items-center justify-between">
                    <button type="button"
                            x-show="riwayatEkspor.length > 0"
                            @click="clearHistory()"
                            class="btn btn-ghost btn-sm text-danger hover:text-danger">
                        Hapus Riwayat
                    </button>
                    <button type="button"
                            data-hs-overlay="#history-export-modal"
                            class="btn btn-ghost btn-sm ml-auto">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Item (Saat Klik Aksi 3 Titik) -->
    <div id="detail-laporan-modal"
         class="hs-overlay hidden size-full fixed top-0 inset-s-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none"
         role="dialog"
         tabindex="-1"
         aria-labelledby="detail-laporan-modal-label">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-md sm:w-full m-3 sm:mx-auto">
            <div class="flex flex-col bg-overlay border border-overlay-line shadow-2xs rounded-2xl pointer-events-auto">
                <div class="flex justify-between items-center gap-3 py-3 px-5 border-b border-border-line-1">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="px-2.5 py-1 rounded-md bg-brand-50 text-brand-700 font-bold text-xs shrink-0" x-text="selectedDetailItem?.id_anak"></span>
                        <h3 id="detail-laporan-modal-label" class="text-sm sm:text-base font-bold text-foreground truncate" x-text="selectedDetailItem?.nama">Detail Data</h3>
                    </div>
                    <button type="button"
                            data-hs-overlay="#detail-laporan-modal"
                            class="size-8 inline-flex justify-center items-center rounded-full bg-surface border border-surface-line text-surface-foreground hover:bg-surface-hover focus:outline-hidden focus:bg-surface-focus"
                            aria-label="Tutup">
                        <span class="sr-only">Tutup</span>
                        <svg class="shrink-0 size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-5 space-y-2 text-xs">
                    <div class="p-3 bg-muted rounded-xl border border-border-line-1 space-y-1.5">
                        <div class="flex justify-between py-1 border-b border-border/50">
                            <span class="text-muted-foreground-1 font-medium">Kategori / Program</span>
                            <span class="text-foreground font-bold" x-text="selectedDetailItem?.kategori"></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-border/50">
                            <span class="text-muted-foreground-1 font-medium">Status</span>
                            <span class="badge" :class="getStatusBadgeClass(selectedDetailItem?.status)" x-text="selectedDetailItem?.status"></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-border/50">
                            <span class="text-muted-foreground-1 font-medium">Waktu / Nilai</span>
                            <span class="text-foreground font-bold" x-text="selectedDetailItem?.tanggal"></span>
                        </div>

                        <!-- Info tambahan dari detail -->
                        <template x-if="selectedDetailItem?.detail">
                            <div>
                                <template x-for="(val, label) in selectedDetailItem.detail" :key="label">
                                    <div class="flex justify-between py-1 border-b border-border/50 last:border-none">
                                        <span class="text-muted-foreground-1 font-medium" x-text="label"></span>
                                        <span class="text-foreground font-bold text-right" x-text="val"></span>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="py-3 px-5 border-t border-border-line-1 flex justify-end">
                    <button type="button"
                            data-hs-overlay="#detail-laporan-modal"
                            class="btn btn-ghost btn-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div x-show="showToast"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="fixed bottom-5 right-5 z-50 max-w-sm bg-brand-950 text-white text-xs sm:text-sm px-4 py-3 rounded-xl shadow-lg flex items-center gap-2.5">
        <svg class="w-4 h-4 text-brand-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span x-text="toastMessage"></span>
    </div>
</div>

<!-- Dedicated Alpine.js Component Script -->
<script>
function laporanEksporApp() {
    const rawData = document.getElementById('laporan-initial-data')?.textContent || '{}';
    let initData = {};
    try {
        initData = JSON.parse(rawData);
    } catch (e) {
        console.error('Gagal membaca data awal laporan:', e);
    }

    return {
        previewMode: 'table', // 'table' | 'pdf' | 'excel'
        jenisLaporan: initData.jenisLaporan || 'Data Anak Asuh',
        bulan: initData.bulan || 'Semua Bulan',
        tahun: initData.tahun || 'Semua Tahun',
        kategori: initData.kategori || 'Semua Kategori',
        previewLoading: false,
        selectedDetailItem: null,
        showToast: false,
        toastMessage: '',
        toastTimeout: null,
        pagination: initData.pagination || { current_page: 1, last_page: 1, total: 0, from: 0, to: 0, per_page: 5 },
        stats: initData.stats || { total: '0', aktif_pendidikan: '0', aktif_asrama: '0', perlu_perhatian: '0' },
        columns: initData.columns || [],
        previewData: initData.previewData || [],
        kategoriOptions: initData.daftarKategori || ['Semua Kategori'],
        categoriesMap: initData.categoriesMap || {},
        routes: initData.routes || {
            index: '{{ route('admin.laporan-ekspor.index', [], false) }}',
            excel: '{{ route('admin.laporan-ekspor.export.excel', [], false) }}',
            pdf: '{{ route('admin.laporan-ekspor.export.pdf', [], false) }}'
        },
        riwayatEkspor: [],
        defaultRiwayat: [
            { id: 1, judul: 'Laporan Data Anak Asuh - September 2025', format: 'Excel (.xlsx)', waktu: '2 jam yang lalu', status: 'Selesai' },
            { id: 2, judul: 'Laporan Kelola Donasi - Agustus 2025', format: 'PDF (.pdf)', waktu: '1 hari yang lalu', status: 'Selesai' }
        ],

        init() {
            // Muat riwayat ekspor dari localStorage
            const saved = localStorage.getItem('riwayat_ekspor_laporan');
            if (saved) {
                try {
                    this.riwayatEkspor = JSON.parse(saved);
                } catch (e) {
                    this.riwayatEkspor = this.defaultRiwayat;
                }
            } else {
                this.riwayatEkspor = this.defaultRiwayat;
                localStorage.setItem('riwayat_ekspor_laporan', JSON.stringify(this.riwayatEkspor));
            }

            // Inisialisasi dropdown kategori
            this.syncKategoriDropdown();

            // Tombol detail dirender Alpine (x-for), daftarkan ulang toggle overlay Preline.
            this.refreshOverlayToggles();
        },

        refreshOverlayToggles() {
            this.$nextTick(() => {
                window.HSStaticMethods?.autoInit(['overlay']);
            });
        },

        // Samakan tampilan semua Preline Select dengan nilai state Alpine
        // (dipakai saat filter diubah secara programatik, mis. tombol Reset).
        syncPrelineSelects() {
            this.$nextTick(() => {
                this.$root.querySelectorAll('select[data-hs-select]').forEach((el) => {
                    window.syncPrelineSelect?.(el);
                });
            });
        },

        setPreviewMode(mode) {
            this.previewMode = mode;
            if (mode === 'pdf') {
                this.showToastNotification('Memuat format pratinjau dokumen PDF...');
            } else if (mode === 'excel') {
                this.showToastNotification('Memuat format pratinjau lembar kerja Excel...');
            }
        },

        syncKategoriDropdown() {
            const select = this.$refs.kategoriSelect;
            if (!select) return;

            const options = this.categoriesMap[this.jenisLaporan] || ['Semua Kategori'];
            this.kategoriOptions = options;

            if (!options.includes(this.kategori)) {
                this.kategori = 'Semua Kategori';
            }

            // Sinkronkan opsi ke instance Preline Select (bukan hanya <select> aslinya),
            // agar dropdown yang terlihat ikut berubah saat jenis laporan diganti.
            const instance = window.HSSelect?.getInstance(select, true)?.element;

            if (instance) {
                const existing = Array.from(select.options).map((option) => option.value);
                if (existing.length) {
                    instance.removeOption(existing);
                }

                options.forEach((opt) => instance.addOption({ title: opt, val: opt }));

                select.value = this.kategori;
                window.syncPrelineSelect?.(select);
                return;
            }

            // Fallback bila Preline Select belum terpasang.
            select.innerHTML = '';
            options.forEach((opt) => {
                const el = document.createElement('option');
                el.value = opt;
                el.textContent = opt;
                if (opt === this.kategori) {
                    el.selected = true;
                }
                select.appendChild(el);
            });
            select.value = this.kategori;
        },

        handleJenisChange() {
            this.kategori = 'Semua Kategori';
            this.syncKategoriDropdown();
            this.syncPrelineSelects();
            this.triggerPreview(1);
        },

        handleFilterChange() {
            this.triggerPreview(1);
        },

        resetFilter() {
            this.jenisLaporan = 'Data Anak Asuh';
            this.bulan = 'Semua Bulan';
            this.tahun = 'Semua Tahun';
            this.kategori = 'Semua Kategori';
            this.syncKategoriDropdown();
            this.syncPrelineSelects();
            this.triggerPreview(1);
        },

        async triggerPreview(page = 1) {
            this.previewLoading = true;
            try {
                const params = new URLSearchParams({
                    json: '1',
                    jenis_laporan: this.jenisLaporan,
                    bulan: this.bulan,
                    tahun: this.tahun,
                    kategori: this.kategori,
                    page: page
                });

                const targetUrl = (this.routes.index || '/admin/laporan-ekspor') + '?' + params.toString();
                const res = await fetch(targetUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!res.ok) {
                    throw new Error('Gagal merespons (HTTP ' + res.status + ')');
                }

                const data = await res.json();
                if (data.success) {
                    this.stats = data.stats;
                    this.columns = data.columns;
                    this.previewData = data.previewData;
                    this.pagination = data.pagination;
                    if (data.kategoriOptions) {
                        this.kategoriOptions = data.kategoriOptions;
                    }

                    // Baris tabel di-render ulang, refresh toggle modal detail.
                    this.refreshOverlayToggles();
                }
            } catch (err) {
                console.error('Gagal memuat preview data laporan:', err);
                this.showToastNotification('Gagal memuat preview data laporan. Periksa koneksi Anda.');
            } finally {
                this.previewLoading = false;
            }
        },

        getExportExcelUrl() {
            const params = new URLSearchParams({
                jenis_laporan: this.jenisLaporan,
                bulan: this.bulan,
                tahun: this.tahun,
                kategori: this.kategori
            });
            return (this.routes.excel || '/admin/laporan-ekspor/export/excel') + '?' + params.toString();
        },

        getExportPdfUrl() {
            const params = new URLSearchParams({
                jenis_laporan: this.jenisLaporan,
                bulan: this.bulan,
                tahun: this.tahun,
                kategori: this.kategori
            });
            return (this.routes.pdf || '/admin/laporan-ekspor/export/pdf') + '?' + params.toString();
        },

        getPreviewPdfUrl() {
            const params = new URLSearchParams({
                preview: '1',
                jenis_laporan: this.jenisLaporan,
                bulan: this.bulan,
                tahun: this.tahun,
                kategori: this.kategori
            });
            return (this.routes.pdf || '/admin/laporan-ekspor/export/pdf') + '?' + params.toString();
        },

        recordExport(format) {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB, Hari ini';
            const item = {
                id: Date.now(),
                judul: `Laporan ${this.jenisLaporan} - ${this.bulan} ${this.tahun}`,
                format: format,
                waktu: timeStr,
                status: 'Selesai'
            };

            this.riwayatEkspor.unshift(item);
            if (this.riwayatEkspor.length > 25) {
                this.riwayatEkspor.pop();
            }
            localStorage.setItem('riwayat_ekspor_laporan', JSON.stringify(this.riwayatEkspor));
            this.showToastNotification(`Mengunduh ${this.jenisLaporan} (${format})...`);
        },

        clearHistory() {
            this.riwayatEkspor = [];
            localStorage.removeItem('riwayat_ekspor_laporan');
            this.showToastNotification('Riwayat ekspor berhasil dibersihkan.');
        },

        openDetail(item) {
            this.selectedDetailItem = item;
        },

        showToastNotification(msg) {
            this.toastMessage = msg;
            this.showToast = true;
            clearTimeout(this.toastTimeout);
            this.toastTimeout = setTimeout(() => {
                this.showToast = false;
            }, 3000);
        },

        getStatusBadgeClass(status) {
            const s = String(status || '').toLowerCase();
            if (s === 'aktif' || s === 'terverifikasi' || s === 'verified') {
                return 'bg-success-soft text-brand-800 dark:bg-brand-900/60 dark:text-brand-200';
            }
            if (s.includes('perlu') || s === 'pending' || s.includes('menunggu')) {
                return 'bg-warning-soft text-warning dark:bg-amber-950/50 dark:text-amber-200';
            }
            if (s === 'selesai' || s === 'alumni') {
                return 'bg-muted text-muted-foreground-2';
            }
            return 'bg-muted text-muted-foreground-2';
        },

        getCategoryBadgeClass(cat) {
            const c = String(cat || '').toLowerCase();
            if (c.includes('pendidikan') || c.includes('tahfidz') || c.includes('sd') || c.includes('smp') || c.includes('sma')) {
                return 'bg-success-soft text-brand-800 dark:bg-brand-900/60 dark:text-brand-200';
            }
            if (c.includes('yatim') || c.includes('kemanusiaan')) {
                return 'bg-info-soft text-info dark:bg-sky-950/50 dark:text-sky-300';
            }
            if (c.includes('dhuafa') || c.includes('sembako') || c.includes('gizi')) {
                return 'bg-warning-soft text-warning dark:bg-amber-950/50 dark:text-amber-200';
            }
            return 'bg-purple-100 text-purple-700 dark:bg-purple-950/50 dark:text-purple-300';
        },

        getPageNumbers() {
            const current = this.pagination.current_page || 1;
            const last = this.pagination.last_page || 1;
            const pages = [];
            const maxButtons = 5;
            let start = Math.max(1, current - Math.floor(maxButtons / 2));
            let end = Math.min(last, start + maxButtons - 1);
            if (end - start + 1 < maxButtons) {
                start = Math.max(1, end - maxButtons + 1);
            }
            for (let i = start; i <= end; i++) {
                pages.push(i);
            }
            return pages;
        }
    };
}
</script>
@endsection
