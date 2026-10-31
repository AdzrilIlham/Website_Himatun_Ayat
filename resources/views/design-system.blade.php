<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Design System — Himmatun Ayat</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-background text-foreground">
    @php
        $brand = [
            50 => '#F2F9F4',
            100 => '#D8F3DC',
            200 => '#B7E4C7',
            300 => '#95D5B2',
            400 => '#74C69D',
            500 => '#52B788',
            600 => '#40916C',
            700 => '#2D6A4F',
            800 => '#24533C',
            900 => '#1B4332',
            950 => '#081C15',
        ];
        $status = [
            ['name' => 'Success', 'hex' => '#2D6A4F', 'soft' => '#D8F3DC', 'text' => '#1B4332'],
            ['name' => 'Warning', 'hex' => '#B45309', 'soft' => '#FEF3C7', 'text' => '#78350F'],
            ['name' => 'Danger', 'hex' => '#DC2626', 'soft' => '#FEE2E2', 'text' => '#991B1B'],
            ['name' => 'Info', 'hex' => '#0369A1', 'soft' => '#E0F2FE', 'text' => '#075985'],
        ];
    @endphp

    <header class="sticky top-0 z-40 border-b border-border bg-card/95 backdrop-blur">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-4 sm:px-6">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-700 text-sm font-bold text-white">
                    HA
                </div>
                <div>
                    <p class="text-2xs font-semibold uppercase tracking-wider text-brand-700 dark:text-brand-300">Design System</p>
                    <h1 class="text-lg font-bold leading-tight text-brand-900 dark:text-brand-200">Himmatun Ayat</h1>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="badge badge-success">v1.0</span>
                <button type="button" class="btn btn-secondary btn-sm" x-data @click="document.documentElement.classList.toggle('dark')">
                    Mode gelap
                </button>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-6xl space-y-12 px-4 py-10 sm:px-6">
        {{-- ============================== WARNA ============================== --}}
        <section>
            <div class="mb-5">
                <h2 class="text-xl font-bold text-brand-900 dark:text-brand-200">Warna Brand</h2>
                <p class="mt-1 text-sm text-muted-foreground">Palet “Nature Green” — dipakai untuk aksi utama, navigasi, dan aksen.</p>
            </div>

            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-6">
                @foreach ($brand as $shade => $hex)
                    <div class="overflow-hidden rounded-xl border border-border bg-card shadow-2xs">
                        <div class="flex h-16 items-end p-2.5" style="background-color: {{ $hex }}">
                            <span class="text-2xs font-bold {{ $shade >= 500 ? 'text-white/90' : 'text-brand-900 dark:text-brand-200/70' }}">
                                {{ $shade }}
                            </span>
                        </div>
                        <div class="px-2.5 py-2">
                            <p class="text-2xs font-semibold text-foreground">brand-{{ $shade }}</p>
                            <p class="text-2xs uppercase text-muted-foreground">{{ $hex }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($status as $item)
                    <div class="rounded-xl border border-border bg-card p-4 shadow-2xs">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-foreground">{{ $item['name'] }}</span>
                            <span class="h-6 w-6 rounded-lg" style="background-color: {{ $item['hex'] }}"></span>
                        </div>
                        <div class="mt-3 rounded-lg px-2.5 py-1.5 text-2xs font-semibold" style="background-color: {{ $item['soft'] }}; color: {{ $item['text'] }}">
                            Contoh pesan {{ strtolower($item['name']) }}
                        </div>
                        <p class="mt-2 text-2xs uppercase text-muted-foreground">{{ $item['hex'] }} · {{ $item['soft'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- ============================ TIPOGRAFI ============================ --}}
        <section class="grid gap-6 lg:grid-cols-2">
            <div class="card">
                <div class="card-header">
                    <h2 class="font-semibold text-brand-900 dark:text-brand-200">Tipografi</h2>
                    <span class="badge badge-neutral">Plus Jakarta Sans</span>
                </div>
                <div class="card-body space-y-3">
                    <p class="text-3xl font-bold tracking-tight text-brand-900 dark:text-brand-200">Judul halaman</p>
                    <p class="text-xl font-semibold text-foreground">Judul section</p>
                    <p class="text-sm text-muted-foreground-1">Teks paragraf menggunakan ukuran 14px untuk kenyamanan baca di panel admin.</p>
                    <p class="text-2xs font-semibold uppercase tracking-wider text-muted-foreground">Label micro · 11px</p>
                    <p class="tabular text-lg font-bold text-brand-700 dark:text-brand-300">Rp 1.250.000</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2 class="font-semibold text-brand-900 dark:text-brand-200">Token Semantik</h2>
                    <span class="badge badge-info">Preline</span>
                </div>
                <div class="card-body space-y-3">
                    <div class="rounded-xl bg-background-2 px-3.5 py-2.5 text-sm text-foreground">bg-background-2 · text-foreground</div>
                    <div class="rounded-xl bg-primary px-3.5 py-2.5 text-sm font-semibold text-primary-foreground">bg-primary · text-primary-foreground</div>
                    <div class="rounded-xl border border-border bg-card px-3.5 py-2.5 text-sm text-muted-foreground">bg-card · border-border</div>
                    <div class="flex flex-wrap gap-2">
                        <span class="badge badge-success">success</span>
                        <span class="badge badge-warning">warning</span>
                        <span class="badge badge-danger">danger</span>
                        <span class="badge badge-info">info</span>
                        <span class="badge badge-neutral">neutral</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- ============================= TOMBOL ============================= --}}
        <section>
            <div class="mb-5">
                <h2 class="text-xl font-bold text-brand-900 dark:text-brand-200">Tombol</h2>
                <p class="mt-1 text-sm text-muted-foreground">Gunakan <code class="text-brand-700 dark:text-brand-300">.btn</code> + varian. Ukuran: <code class="text-brand-700 dark:text-brand-300">.btn-sm</code>, <code class="text-brand-700 dark:text-brand-300">.btn-lg</code>.</p>
            </div>

            <div class="card">
                <div class="card-body flex flex-wrap items-center gap-3">
                    <button type="button" class="btn btn-primary">Donasi Sekarang</button>
                    <button type="button" class="btn btn-secondary">Simpan Draft</button>
                    <button type="button" class="btn btn-ghost">Batal</button>
                    <button type="button" class="btn btn-danger">Hapus</button>
                    <button type="button" class="btn btn-primary btn-sm">Kecil</button>
                    <button type="button" class="btn btn-primary btn-lg">Besar</button>
                    <button type="button" class="btn btn-primary" disabled>Nonaktif</button>
                </div>
            </div>
        </section>

        {{-- ========================= FORM & ALERT ========================== --}}
        <section class="grid gap-6 lg:grid-cols-2">
            <div class="card">
                <div class="card-header">
                    <h2 class="font-semibold text-brand-900 dark:text-brand-200">Form</h2>
                </div>
                <div class="card-body space-y-4">
                    <div>
                        <label class="form-label" for="ds-nama">Nama donatur</label>
                        <input id="ds-nama" type="text" class="form-input" placeholder="Masukkan nama lengkap">
                        <p class="form-hint">Sesuai dengan nama di rekening.</p>
                    </div>
                    <div>
                        <label class="form-label" for="ds-nominal">Nominal</label>
                        <input id="ds-nominal" type="text" class="form-input" value="Rp 250.000">
                        <p class="form-error">Nominal minimal Rp 10.000.</p>
                    </div>
                    <div>
                        <label class="form-label" for="ds-kategori">Kategori</label>
                        <x-preline-select id="ds-kategori" placeholder="Pilih kategori">
                            <option value="Pendidikan" selected>Pendidikan</option>
                            <option value="Kesehatan">Kesehatan</option>
                            <option value="Yatim & Dhuafa">Yatim &amp; Dhuafa</option>
                        </x-preline-select>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2 class="font-semibold text-brand-900 dark:text-brand-200">Alert & Progress</h2>
                </div>
                <div class="card-body space-y-3">
                    <div class="alert alert-success">Donasi berhasil diverifikasi.</div>
                    <div class="alert alert-warning">Menunggu konfirmasi transfer.</div>
                    <div class="alert alert-danger">Bukti transfer tidak valid.</div>
                    <div class="alert alert-info">Laporan akan dikirim setiap akhir bulan.</div>

                    <div class="pt-2">
                        <div class="mb-1.5 flex items-center justify-between text-xs font-medium text-muted-foreground-1">
                            <span>Target donasi</span>
                            <span class="tabular text-brand-700 dark:text-brand-300">75%</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-surface">
                            <div class="h-full w-3/4 rounded-full bg-primary"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ============================ PRECLINE ============================ --}}
        <section>
            <div class="mb-5">
                <h2 class="text-xl font-bold text-brand-900 dark:text-brand-200">Komponen Preline</h2>
                <p class="mt-1 text-sm text-muted-foreground">Contoh interaktif — warna otomatis mengikuti token brand.</p>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                {{-- Accordion --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-brand-900 dark:text-brand-200">Accordion</h3>
                    </div>
                    <div class="card-body">
                        <div class="hs-accordion-group space-y-2">
                            <div class="hs-accordion active overflow-hidden rounded-xl border border-border" id="ds-acc-1">
                                <button type="button" class="hs-accordion-toggle hs-accordion-active:text-primary flex w-full items-center justify-between gap-3 px-4 py-3 text-left text-sm font-semibold text-foreground" aria-expanded="true" aria-controls="ds-acc-1-content">
                                    Bagaimana cara berdonasi?
                                    <svg class="hs-accordion-active:rotate-180 size-4 shrink-0 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                                </button>
                                <div id="ds-acc-1-content" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300" role="region" aria-labelledby="ds-acc-1">
                                    <p class="px-4 pb-4 text-sm text-muted-foreground">Pilih program, isi nominal, lalu transfer sesuai instruksi. Konfirmasi akan diverifikasi admin.</p>
                                </div>
                            </div>
                            <div class="hs-accordion overflow-hidden rounded-xl border border-border" id="ds-acc-2">
                                <button type="button" class="hs-accordion-toggle hs-accordion-active:text-primary flex w-full items-center justify-between gap-3 px-4 py-3 text-left text-sm font-semibold text-foreground" aria-expanded="false" aria-controls="ds-acc-2-content">
                                    Apakah donasi mendapat laporan?
                                    <svg class="hs-accordion-active:rotate-180 size-4 shrink-0 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                                </button>
                                <div id="ds-acc-2-content" class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300" role="region" aria-labelledby="ds-acc-2">
                                    <p class="px-4 pb-4 text-sm text-muted-foreground">Ya. Laporan penggunaan dana dipublikasikan melalui halaman laporan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Dropdown + tooltip + switch --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-brand-900 dark:text-brand-200">Dropdown, Tooltip, Switch</h3>
                    </div>
                    <div class="card-body flex flex-wrap items-center gap-3">
                        <div class="hs-dropdown relative inline-flex">
                            <button id="ds-dropdown" type="button" class="hs-dropdown-toggle btn btn-secondary" aria-haspopup="menu" aria-expanded="false" aria-controls="ds-dropdown-menu">
                                Aksi
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                            </button>
                            <div id="ds-dropdown-menu" class="hs-dropdown-menu hs-dropdown-open:opacity-100 hidden mt-2 min-w-48 rounded-xl border border-border bg-dropdown p-1.5 opacity-0 shadow-lg transition-[opacity,margin] duration" role="menu" aria-labelledby="ds-dropdown">
                                <a href="#" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-foreground hover:bg-dropdown-item-hover">Edit</a>
                                <a href="#" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-foreground hover:bg-dropdown-item-hover">Duplikat</a>
                                <a href="#" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-danger hover:bg-danger-soft">Hapus</a>
                            </div>
                        </div>

                        <div class="hs-tooltip inline-block">
                            <button type="button" class="hs-tooltip-toggle btn btn-secondary">Hover saya</button>
                            <span class="hs-tooltip-content hs-tooltip-shown:visible hs-tooltip-shown:opacity-100 invisible absolute z-10 inline-block rounded-lg bg-tooltip px-2.5 py-1.5 text-xs font-medium text-tooltip-foreground opacity-0 shadow-lg transition-opacity" role="tooltip">
                                Tooltip dari Preline UI
                            </span>
                        </div>

                        <label class="inline-flex cursor-pointer items-center gap-3">
                            <input type="checkbox" class="peer sr-only" checked>
                            <span class="relative h-6 w-11 rounded-full bg-surface-2 transition-colors after:absolute after:left-0.5 after:top-0.5 after:size-5 after:rounded-full after:bg-white after:shadow-sm after:transition-transform peer-checked:bg-primary peer-checked:after:translate-x-5"></span>
                            <span class="text-sm font-medium text-foreground">Aktif</span>
                        </label>
                    </div>
                </div>
            </div>
        </section>

        <footer class="border-t border-border pt-6 text-center text-xs text-muted-foreground">
            Design system Himmatun Ayat · <a href="/" class="font-semibold text-brand-700 dark:text-brand-300 hover:underline">Kembali ke aplikasi</a>
        </footer>
    </main>
</body>
</html>
