@extends('layouts.user')

@section('content')

    <!-- HERO — BERITA & KEGIATAN -->
    <section class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 sm:pt-6 lg:px-8">
        <div class="max-w-3xl">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-brand-900">
                Berita dan <span class="text-brand-700">Kegiatan</span>
            </h1>
            <p class="mt-2 sm:mt-2.5 max-w-2xl text-xs sm:text-sm leading-relaxed text-muted-foreground-1">
                Cerita kebaikan, penyaluran amanah, dan keseharian anak asuh Yayasan Himmatun Ayat Bandung.
                Ikuti setiap langkah kecil menuju masa depan yang lebih baik.
            </p>
        </div>

        @if ($featured)
        <div class="mt-6 sm:mt-8">
            <div class="inline-flex items-center gap-1.5 text-2xs font-bold uppercase tracking-wider text-brand-800">
                <svg class="size-3.5 text-brand-700" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                </svg>
                <span>Kabar Pilihan Yayasan</span>
            </div>

            <article class="mt-2.5 overflow-hidden rounded-2xl border border-border bg-card p-2 shadow-2xs sm:p-2.5">
                <div class="grid gap-0 md:grid-cols-2">
                    <a href="{{ $featured['url'] }}" class="group relative block min-h-56 sm:min-h-64 overflow-hidden rounded-xl bg-surface md:min-h-full">
                        <img src="{{ $featured['image'] }}" alt="{{ $featured['title'] }}"
                             class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-[1.02]">
                    </a>

                    <div class="flex flex-col justify-center p-4 sm:p-6 lg:p-7">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="badge badge-success">{{ $featured['type'] }}</span>
                            <span class="text-2xs font-medium text-muted-foreground-1">{{ $featured['category'] }}</span>
                        </div>

                        <h2 class="mt-2.5 text-lg font-bold leading-tight text-brand-900 sm:text-xl lg:text-2xl">
                            {{ $featured['title'] }}
                        </h2>

                        <div class="mt-2 flex flex-wrap items-center gap-x-3.5 gap-y-1 text-2xs text-muted-foreground-1">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="size-3.5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                {{ $featured['date'] }}
                            </span>
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="size-3.5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                </svg>
                                {{ $featured['location'] }}
                            </span>
                        </div>

                        <p class="mt-2 text-xs leading-relaxed text-muted-foreground-1 sm:text-[13px]">
                            {{ $featured['excerpt'] }}
                        </p>

                        <div class="mt-4">
                            <a href="{{ $featured['url'] }}"
                               class="inline-flex items-center justify-center rounded-full bg-primary px-5 py-2.5 text-xs font-semibold text-primary-foreground shadow-xs transition hover:bg-primary-hover focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 sm:text-sm">
                                Baca Selengkapnya
                            </a>
                        </div>
                    </div>
                </div>
            </article>
        </div>
        @endif
    </section>

    <!-- KABAR & AKTIVITAS TERBARU -->
    <section class="relative mt-10 bg-white sm:mt-12">
        {{-- Fade dari area hero mint ke putih --}}
        <div class="pointer-events-none absolute inset-x-0 -top-16 h-16 bg-gradient-to-b from-transparent to-white"></div>

        <div class="relative mx-auto max-w-7xl px-4 pt-3 sm:px-6 sm:pt-4 lg:px-8"
             x-data="{
                 searchQuery: '',
                 selectedCategory: 'semua',
                 selectedSort: 'terbaru',
                 currentPage: 1,
                 perPage: 6,
                 articles: {{ Js::from($articles) }},
                 get filteredArticles() {
                     const query = this.searchQuery.toLowerCase().trim();
                     const list = this.articles.filter((article) => {
                         const matchesCategory = this.selectedCategory === 'semua'
                             || article.type.toLowerCase() === this.selectedCategory;
                         const matchesSearch = query === ''
                             || article.title.toLowerCase().includes(query)
                             || article.category.toLowerCase().includes(query)
                             || article.excerpt.toLowerCase().includes(query);
                         return matchesCategory && matchesSearch;
                     });

                     return list.sort((a, b) => this.selectedSort === 'terbaru'
                         ? b.date_iso.localeCompare(a.date_iso)
                         : a.date_iso.localeCompare(b.date_iso));
                 },
                 get totalPages() {
                     return Math.max(1, Math.ceil(this.filteredArticles.length / this.perPage));
                 },
                 get paginatedArticles() {
                     const page = Math.min(this.currentPage, this.totalPages);
                     const start = (page - 1) * this.perPage;
                     return this.filteredArticles.slice(start, start + this.perPage);
                 }
             }"
             x-init="$watch('searchQuery', () => currentPage = 1); $watch('selectedCategory', () => currentPage = 1); $watch('selectedSort', () => currentPage = 1)">

            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-1.5 text-2xs font-bold uppercase tracking-wider text-brand-800">
                    <svg class="size-3.5 text-brand-700" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
                    </svg>
                    <span>Kabar Kegiatan &amp; Dokumentasi</span>
                </div>

                <h2 class="mt-1.5 text-xl sm:text-2xl font-extrabold tracking-tight text-brand-900">
                    Kabar &amp; Aktivitas Terbaru Yayasan
                </h2>
                <p class="mt-1.5 text-xs sm:text-sm leading-relaxed text-muted-foreground-1">
                    Dokumentasi penyaluran amanah donatur, senyum syukur adik-adik asuh, dan perkembangan program binaan di Bandung.
                </p>
            </div>

            {{-- Filter & Pencarian --}}
            <div class="mt-4 sm:mt-5 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center gap-1.5 sm:gap-2 overflow-x-auto py-1 no-scrollbar">
                    <button type="button" @click="selectedCategory = 'semua'"
                            :class="selectedCategory === 'semua'
                                ? 'bg-brand-700 text-white shadow-xs'
                                : 'bg-brand-100 text-brand-800 hover:bg-brand-200'"
                            class="rounded-full px-3.5 py-1.5 text-xs font-semibold transition">
                        Semua
                    </button>
                    <button type="button" @click="selectedCategory = 'berita'"
                            :class="selectedCategory === 'berita'
                                ? 'bg-brand-700 text-white shadow-xs'
                                : 'bg-brand-100 text-brand-800 hover:bg-brand-200'"
                            class="rounded-full px-3.5 py-1.5 text-xs font-semibold transition">
                        Berita
                    </button>
                    <button type="button" @click="selectedCategory = 'kegiatan'"
                            :class="selectedCategory === 'kegiatan'
                                ? 'bg-brand-700 text-white shadow-xs'
                                : 'bg-brand-100 text-brand-800 hover:bg-brand-200'"
                            class="rounded-full px-3.5 py-1.5 text-xs font-semibold transition">
                        Kegiatan
                    </button>
                </div>

                <div class="relative w-full lg:w-72">
                    <svg class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <input type="search" x-model="searchQuery" placeholder="Cari berita atau kegiatan..."
                           class="w-full rounded-full border border-brand-100 bg-brand-50/70 py-2 pl-10 pr-4 text-xs text-foreground shadow-2xs transition placeholder:text-muted-foreground focus:border-brand-700 focus:ring-2 focus:ring-brand-700/20 focus:outline-none sm:text-sm">
                </div>
            </div>

            <div class="mt-4 border-t border-border"></div>

            {{-- Info hasil & urutan --}}
            <div class="mt-3.5 flex flex-wrap items-center justify-between gap-2 text-2xs text-muted-foreground-1">
                <p>
                    Menampilkan
                    <span class="font-semibold text-brand-900" x-text="paginatedArticles.length"></span>
                    dari
                    <span class="font-semibold text-brand-900" x-text="filteredArticles.length"></span>
                    berita dan kegiatan
                </p>

                <div class="relative" x-data="{ open: false }">
                    <button type="button" @click="open = !open"
                            class="inline-flex items-center gap-1.5 text-2xs font-medium text-muted-foreground-1 transition hover:text-brand-800">
                        Urutkan:
                        <span class="font-semibold text-brand-900" x-text="selectedSort === 'terbaru' ? 'Terbaru' : 'Terlama'"></span>
                        <svg class="size-3.5 transition" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
                        </svg>
                    </button>

                    <div x-show="open" x-cloak @click.outside="open = false"
                         class="absolute right-0 z-20 mt-2 w-32 rounded-xl border border-border bg-card p-1 shadow-lg">
                        <button type="button" @click="selectedSort = 'terbaru'; open = false"
                                class="block w-full rounded-lg px-3 py-2 text-left text-xs font-medium text-foreground transition hover:bg-brand-50"
                                :class="selectedSort === 'terbaru' ? 'bg-brand-50 text-brand-800' : ''">
                            Terbaru
                        </button>
                        <button type="button" @click="selectedSort = 'terlama'; open = false"
                                class="block w-full rounded-lg px-3 py-2 text-left text-xs font-medium text-foreground transition hover:bg-brand-50"
                                :class="selectedSort === 'terlama' ? 'bg-brand-50 text-brand-800' : ''">
                            Terlama
                        </button>
                    </div>
                </div>
            </div>

            {{-- Grid berita --}}
            <div x-cloak class="mt-4 sm:mt-5 grid grid-cols-1 gap-4 sm:gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <template x-for="article in paginatedArticles" :key="article.id">
                    <article class="group flex flex-col rounded-xl sm:rounded-2xl border border-border bg-card p-3 shadow-2xs transition duration-200 hover:-translate-y-0.5 hover:shadow-md">
                        <a :href="article.url" class="block overflow-hidden rounded-lg sm:rounded-xl bg-surface">
                            <div class="relative aspect-[16/9]">
                                <img :src="article.image" :alt="article.title" loading="lazy"
                                     class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]">
                            </div>
                        </a>

                        <div class="mt-2.5 flex flex-1 flex-col">
                            <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                                <span class="badge badge-success" x-text="article.type"></span>
                                <span class="text-2xs font-medium text-muted-foreground-1" x-text="article.category"></span>
                            </div>

                            <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1 text-2xs text-muted-foreground-1">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="size-3.5 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <span x-text="article.date"></span>
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <svg class="size-3.5 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                    </svg>
                                    <span x-text="article.location"></span>
                                </span>
                            </div>

                            <h3 class="mt-1.5 text-sm sm:text-[15px] font-bold leading-snug text-brand-900">
                                <a :href="article.url" class="transition hover:text-brand-700" x-text="article.title"></a>
                            </h3>

                            <p class="mt-1.5 text-xs leading-relaxed text-muted-foreground-1 line-clamp-2 sm:line-clamp-3" x-text="article.excerpt"></p>

                            <a :href="article.url"
                               class="mt-auto inline-flex items-center gap-1 pt-3 text-xs font-semibold text-brand-800 transition hover:text-brand-700">
                                <span>Lihat Selengkapnya</span>
                                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </a>
                        </div>
                    </article>
                </template>
            </div>

            {{-- Empty state --}}
            <div x-show="filteredArticles.length === 0" x-cloak
                 class="mt-5 rounded-2xl border border-border bg-card px-6 py-14 text-center shadow-2xs">
                <svg class="mx-auto size-10 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <p class="mt-3 text-sm font-semibold text-brand-900">Berita tidak ditemukan</p>
                <p class="mt-1 text-xs text-muted-foreground-1">Coba kata kunci lain atau pilih kategori yang berbeda.</p>
                <button type="button" @click="searchQuery = ''; selectedCategory = 'semua'"
                        class="mt-4 inline-flex items-center justify-center rounded-full bg-brand-100 px-4 py-2 text-xs font-semibold text-brand-800 transition hover:bg-brand-200">
                    Reset Pencarian
                </button>
            </div>

            {{-- Pagination --}}
            <nav x-show="filteredArticles.length > 0" class="mt-8 flex flex-wrap items-center justify-center gap-2 text-xs font-semibold">
                <button type="button" @click="if (currentPage > 1) currentPage--"
                        :disabled="currentPage === 1"
                        class="inline-flex items-center gap-1 rounded-lg px-2.5 py-2 text-muted-foreground-1 transition hover:bg-brand-50 disabled:cursor-not-allowed disabled:opacity-40">
                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                    <span class="hidden sm:inline">Sebelumnya</span>
                </button>

                <template x-for="page in totalPages" :key="page">
                    <button type="button" @click="currentPage = page"
                            :class="currentPage === page
                                ? 'bg-brand-700 text-white shadow-xs'
                                : 'bg-surface text-brand-900 hover:bg-brand-100'"
                            class="size-8 rounded-lg transition"
                            x-text="page"></button>
                </template>

                <button type="button" @click="if (currentPage < totalPages) currentPage++"
                        :disabled="currentPage === totalPages"
                        class="inline-flex items-center gap-1 rounded-lg px-2.5 py-2 text-brand-900 transition hover:bg-brand-50 disabled:cursor-not-allowed disabled:opacity-40">
                    <span class="hidden sm:inline">Selanjutnya</span>
                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            </nav>
        </div>
    </section>

    <!-- KALENDER KEGIATAN & PENGAJUAN JADWAL INTERAKTIF (KEGIATAN MENDATANG V.2) -->
    <section id="kalender-kegiatan"
             class="mt-10 border-y border-brand-100 bg-brand-50/70 py-10 sm:mt-12 sm:py-14"
             x-data="kalenderKegiatanSection({{ Js::from($calendarEvents ?? $events) }}, '{{ config('app.whatsapp_admin', '6281268765420') }}')"
             @keydown.escape.window="closeModal()">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {{-- Header Section --}}
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-1.5 text-2xs font-bold uppercase tracking-wider text-brand-800">
                    <svg class="size-3.5 text-brand-700" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                    </svg>
                    <span>AGENDA KEBAIKAN</span>
                </div>

                <h2 class="mt-1.5 text-xl sm:text-2xl lg:text-3xl font-extrabold tracking-tight text-brand-900">
                    Kalender Kegiatan &amp; Pengajuan Jadwal
                </h2>
                <p class="mt-1.5 text-xs sm:text-sm leading-relaxed text-muted-foreground-1">
                    Mari hadir, berbagi, dan menjadi bagian dari kebahagiaan adik-adik asuh.
                </p>
            </div>

            {{-- Layout Grid: Sisi Kiri (Kalender Utama) & Sisi Kanan (Panel Informasi & Panduan) --}}
            <div class="mt-6 sm:mt-8 grid grid-cols-1 gap-6 lg:grid-cols-12 lg:gap-7 items-start">

                {{-- SISI KIRI: Area Kalender Utama --}}
                <div class="lg:col-span-8 rounded-2xl sm:rounded-3xl border border-border bg-card p-4 sm:p-6 lg:p-7 shadow-2xs">
                    {{-- Kontrol Kalender --}}
                    <div class="flex flex-wrap items-center justify-between gap-4 pb-5 border-b border-border-line-1">
                        <div class="flex items-center gap-3 sm:gap-3.5">
                            <div class="flex size-10 sm:size-11 shrink-0 items-center justify-center rounded-full bg-brand-700 text-white shadow-xs">
                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5m-9-3.75h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg sm:text-xl font-extrabold tracking-tight text-brand-950" x-text="monthYearLabel"></h3>
                                <p class="mt-0.5 flex flex-wrap items-center gap-1.5 text-2xs sm:text-xs text-muted-foreground-1">
                                    <span class="tabular" x-text="scheduledCount + ' Agenda Terjadwal'"></span>
                                    <span class="text-brand-600">•</span>
                                    <span class="font-bold text-brand-700 tabular" x-text="availableCount + ' Slot Tersedia'"></span>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-1.5 sm:gap-2">
                            <button type="button"
                                    @click="prevMonth()"
                                    aria-label="Bulan Sebelumnya"
                                    class="inline-flex size-8 sm:size-9 items-center justify-center rounded-full border border-border bg-card text-foreground transition hover:border-brand-300 hover:bg-brand-50 hover:text-brand-800 cursor-pointer">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                                </svg>
                            </button>

                            <button type="button"
                                    @click="goToDefaultMonth()"
                                    class="rounded-full border border-brand-200 bg-brand-50 px-3.5 sm:px-4 py-1.5 text-xs font-bold text-brand-800 transition hover:bg-brand-100 cursor-pointer">
                                Bulan Ini
                            </button>

                            <button type="button"
                                    @click="nextMonth()"
                                    aria-label="Bulan Selanjutnya"
                                    class="inline-flex size-8 sm:size-9 items-center justify-center rounded-full border border-border bg-card text-foreground transition hover:border-brand-300 hover:bg-brand-50 hover:text-brand-800 cursor-pointer">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Header Hari (Senin - Ahad) --}}
                    <div class="mt-4 grid grid-cols-7 gap-1.5 sm:gap-2.5 text-center">
                        <template x-for="(dayName, idx) in dayHeaders" :key="dayName">
                            <div class="py-1.5 text-[10px] sm:text-2xs font-bold uppercase tracking-wider"
                                 :class="idx === 6 ? 'text-danger' : 'text-muted-foreground-1'"
                                 x-text="dayName"></div>
                        </template>
                    </div>

                    {{-- Grid Tanggal (Senin - Ahad) --}}
                    <div x-cloak class="mt-1 grid grid-cols-7 gap-1.5 sm:gap-2.5">
                        <template x-for="cell in calendarCells" :key="cell.key">
                            <div>
                                {{-- Tanggal di luar bulan aktif (Redup / Disabled) --}}
                                <template x-if="!cell.isCurrentMonth">
                                    <div class="flex min-h-18 sm:min-h-22 flex-col justify-between rounded-xl sm:rounded-2xl bg-slate-50/70 p-2 sm:p-2.5 text-center select-none opacity-60">
                                        <span class="text-2xs sm:text-xs font-semibold text-muted-foreground" x-text="cell.outsideLabel"></span>
                                    </div>
                                </template>

                                {{-- Tanggal dengan status ADA KEGIATAN TERJADWAL --}}
                                <template x-if="cell.isCurrentMonth && cell.events && cell.events.length > 0">
                                    <button type="button"
                                            @click="handleDateClick(cell)"
                                            class="group relative flex min-h-18 sm:min-h-22 w-full flex-col justify-between rounded-xl sm:rounded-2xl p-2 sm:p-2.5 text-left transition duration-200 cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-700"
                                            :class="highlightedDateIso === cell.dateIso
                                                ? 'bg-brand-700 text-white shadow-md ring-2 ring-brand-700 ring-offset-2'
                                                : 'border-2 border-brand-400 bg-brand-50 text-brand-950 hover:bg-brand-100/80 hover:shadow-xs'">
                                        <div class="flex w-full items-start justify-between">
                                            <span class="text-xs sm:text-sm font-extrabold tabular leading-none"
                                                  :class="highlightedDateIso === cell.dateIso ? 'text-white' : 'text-brand-950'"
                                                  x-text="cell.day"></span>
                                            <div class="flex items-center gap-1">
                                                <template x-if="cell.events.length > 1">
                                                    <span class="rounded-full px-1.5 py-0.5 text-[8px] sm:text-[9px] font-extrabold leading-none"
                                                          :class="highlightedDateIso === cell.dateIso ? 'bg-amber-300 text-brand-950' : 'bg-brand-200 text-brand-900'"
                                                          x-text="cell.events.length + ' Agenda'"></span>
                                                </template>
                                                <span class="size-2 sm:size-2.5 rounded-full shrink-0"
                                                      :class="highlightedDateIso === cell.dateIso ? 'bg-amber-300' : 'bg-brand-700'"></span>
                                            </div>
                                        </div>

                                        <div class="mt-2 w-full space-y-1">
                                            <span class="flex w-full items-center justify-center gap-1 truncate rounded-full px-1.5 py-0.5 text-[9px] sm:text-[10px] font-bold leading-tight shadow-2xs transition"
                                                  :class="highlightedDateIso === cell.dateIso
                                                      ? 'bg-white text-brand-900'
                                                      : 'bg-brand-700 text-white group-hover:bg-brand-800'">
                                                <svg class="hidden sm:inline size-2.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                                                </svg>
                                                <span class="truncate" x-text="cell.event.badge_title"></span>
                                            </span>
                                            <template x-if="cell.events.length > 1">
                                                <span class="block w-full truncate text-center text-[8px] sm:text-[9px] font-bold leading-none"
                                                      :class="highlightedDateIso === cell.dateIso ? 'text-brand-100' : 'text-brand-800'"
                                                      x-text="'+' + (cell.events.length - 1) + ' kegiatan lainnya'"></span>
                                            </template>
                                        </div>
                                    </button>
                                </template>

                                {{-- Tanggal dengan status SLOT TERSEDIA / JADWAL TERBUKA --}}
                                <template x-if="cell.isCurrentMonth && (!cell.events || cell.events.length === 0)">
                                    <button type="button"
                                            @click="handleDateClick(cell)"
                                            class="group flex min-h-18 sm:min-h-22 w-full flex-col justify-between rounded-xl sm:rounded-2xl border border-slate-200/80 bg-white p-2 sm:p-2.5 text-left transition duration-150 hover:border-brand-300 hover:bg-brand-50/40 hover:shadow-2xs cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-700/40">
                                        <div class="flex w-full items-start justify-between">
                                            <span class="text-xs sm:text-sm font-bold tabular leading-none"
                                                  :class="cell.isSunday ? 'text-danger' : 'text-brand-950'"
                                                  x-text="cell.day"></span>
                                            <span class="size-1.5 sm:size-2 rounded-full bg-slate-200 transition group-hover:bg-brand-400"></span>
                                        </div>

                                        <div class="mt-2 w-full">
                                            <span class="block w-full truncate rounded-md border border-slate-100 bg-slate-50 px-1 py-0.5 text-center text-[9px] sm:text-[10px] font-medium text-muted-foreground transition group-hover:border-brand-200 group-hover:bg-brand-100/70 group-hover:text-brand-800">
                                                Slot Tersedia
                                            </span>
                                        </div>
                                    </button>
                                </template>
                            </div>
                        </template>
                    </div>

                    {{-- Legenda di Bagian Bawah Kalender --}}
                    <div class="mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-border-line-1 pt-4">
                        <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-2xs sm:text-xs text-muted-foreground-2">
                            <span class="inline-flex items-center gap-2 font-medium text-brand-900">
                                <span class="size-2.5 rounded-full bg-brand-700"></span>
                                <span>Ada Kegiatan Terjadwal (Klik untuk rincian)</span>
                            </span>
                            <span class="inline-flex items-center gap-2">
                                <span class="size-2.5 rounded-full border border-slate-300 bg-white"></span>
                                <span>Slot Tersedia / Jadwal Terbuka (Klik untuk ajukan)</span>
                            </span>
                        </div>

                        <div class="inline-flex items-center gap-1.5 rounded-full border border-brand-100 bg-brand-50 px-3 py-1 text-2xs font-bold text-brand-800">
                            <svg class="size-3.5 text-brand-700" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM13.707 8.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Pelayanan &amp; Respon Cepat 1x24 Jam</span>
                        </div>
                    </div>
                </div>

                {{-- SISI KANAN: Panel Informasi & Panduan Interaktif --}}
                <aside class="lg:col-span-4 flex flex-col gap-5 rounded-2xl sm:rounded-3xl border border-border bg-card p-5 sm:p-6 lg:p-7 shadow-2xs">
                    {{-- Header Card --}}
                    <div class="flex items-start gap-3.5">
                        <div class="flex size-11 shrink-0 items-center justify-center rounded-2xl bg-brand-100 text-brand-700">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5" />
                            </svg>
                        </div>
                        <div>
                            <span class="badge badge-success">Panduan Interaktif</span>
                            <h3 class="mt-1.5 text-base sm:text-lg font-extrabold leading-snug text-brand-900">
                                Eksplorasi Jadwal &amp; Kunjungan
                            </h3>
                        </div>
                    </div>

                    {{-- Pesan Panduan --}}
                    <p class="rounded-2xl border border-brand-100 bg-brand-50/70 p-3.5 sm:p-4 text-xs sm:text-[13px] leading-relaxed text-muted-foreground-2">
                        Pilih salah satu tanggal pada kalender untuk melihat rincian kegiatan yang sedang berlangsung, atau pilih tanggal kosong (<strong class="font-semibold text-brand-800">Slot Tersedia</strong>) untuk mengajukan rencana kunjungan dan kegiatan baru bersama adik-adik santri asuh.
                    </p>

                    {{-- Quick Steps / Poin Interaksi --}}
                    <div class="space-y-3.5">
                        <div class="flex items-start gap-3">
                            <span class="flex size-6 shrink-0 items-center justify-center rounded-full bg-brand-700 text-2xs font-extrabold text-white">
                                1
                            </span>
                            <div>
                                <h4 class="text-xs sm:text-sm font-bold text-brand-900">
                                    Klik Tanggal Berisi Agenda
                                </h4>
                                <p class="mt-0.5 text-xs leading-relaxed text-muted-foreground-1">
                                    Untuk melihat detail waktu, pelaksana, dan rincian agenda yayasan.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="flex size-6 shrink-0 items-center justify-center rounded-full bg-brand-700 text-2xs font-extrabold text-white">
                                2
                            </span>
                            <div>
                                <h4 class="text-xs sm:text-sm font-bold text-brand-900">
                                    Klik Slot Tersedia
                                </h4>
                                <p class="mt-0.5 text-xs leading-relaxed text-muted-foreground-1">
                                    Untuk mengajukan jadwal kegiatan sosial, santunan, atau kolaborasi.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="flex size-6 shrink-0 items-center justify-center rounded-full bg-brand-700 text-2xs font-extrabold text-white">
                                3
                            </span>
                            <div>
                                <h4 class="text-xs sm:text-sm font-bold text-brand-900">
                                    Konfirmasi WhatsApp
                                </h4>
                                <p class="mt-0.5 text-xs leading-relaxed text-muted-foreground-1">
                                    Terhubung langsung dengan narahubung resmi yayasan secara instan.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Ringkasan Agenda Bulan Aktif di Panel Kanan --}}
                    <div class="border-t border-border-line-1 pt-4">
                        <p class="text-2xs font-bold uppercase tracking-wider text-muted-foreground-1">
                            Agenda Terjadwal (<span x-text="monthYearLabel"></span>)
                        </p>

                        <div class="mt-2.5 space-y-2" x-show="currentMonthEvents.length > 0">
                            <template x-for="ev in currentMonthEvents" :key="ev.id">
                                <button type="button"
                                        @click="openEventDetail(ev)"
                                        class="flex w-full items-center justify-between gap-2.5 rounded-xl border border-brand-100 bg-brand-50/50 px-3 py-2 text-left transition hover:border-brand-300 hover:bg-brand-100/60 cursor-pointer">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <span class="flex size-8 shrink-0 flex-col items-center justify-center rounded-lg bg-brand-700 text-white font-extrabold text-xs tabular"
                                              x-text="ev.day"></span>
                                        <div class="min-w-0">
                                            <p class="truncate text-xs font-bold text-brand-900" x-text="ev.title"></p>
                                            <p class="truncate text-[11px] text-muted-foreground-1" x-text="ev.time"></p>
                                        </div>
                                    </div>
                                    <svg class="size-4 shrink-0 text-brand-700" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                    </svg>
                                </button>
                            </template>
                        </div>

                        <p x-show="currentMonthEvents.length === 0" class="mt-2 text-xs text-muted-foreground-1">
                            Belum ada agenda terjadwal di bulan ini. Seluruh tanggal terbuka untuk pengajuan kunjungan.
                        </p>
                    </div>
                </aside>
            </div>
        </div>

        {{-- =====================================================================
             MODAL / POPUP INTERAKTIF KALENDER (FRAME 215, 216, 217, 218)
             ===================================================================== --}}
        <div x-show="modalState !== null"
             x-cloak
             class="fixed inset-0 z-80 flex items-center justify-center overflow-x-hidden overflow-y-auto p-4 sm:p-6"
             role="dialog"
             aria-modal="true">

            {{-- Backdrop Overlay --}}
            <div x-show="modalState !== null"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="closeModal()"
                 class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs"></div>

            {{-- A. MODAL FRAME 215: Detail Kegiatan Terjadwal --}}
            <div x-show="modalState === 'detail' && selectedDateEvents.length > 0"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                 class="relative z-10 w-full max-w-md rounded-3xl border border-border bg-card p-6 sm:p-8 shadow-2xl max-h-[92vh] overflow-y-auto">

                {{-- Header apabila terdapat lebih dari 1 agenda di tanggal yang sama --}}
                <template x-if="selectedDateEvents.length > 1">
                    <div class="mb-4 flex items-center justify-between gap-2 rounded-xl border border-brand-200 bg-brand-50 px-3.5 py-2">
                        <span class="text-xs font-extrabold text-brand-900"
                              x-text="selectedDateEvents.length + ' Agenda Terjadwal di Tanggal Ini'"></span>
                        <span class="text-2xs font-semibold text-brand-700"
                              x-text="selectedCell ? selectedCell.dateShortFormatted : ''"></span>
                    </div>
                </template>

                {{-- Daftar Kegiatan pada Tanggal Terpilih --}}
                <div class="space-y-6">
                    <template x-for="(evItem, idx) in selectedDateEvents" :key="evItem.id || idx">
                        <div :class="idx > 0 ? 'border-t border-border-line-1 pt-5' : ''">
                            <template x-if="selectedDateEvents.length > 1">
                                <span class="mb-1.5 inline-flex items-center rounded-full bg-brand-700 px-2.5 py-0.5 text-[10px] font-bold text-white"
                                      x-text="'Agenda #' + (idx + 1)"></span>
                            </template>

                            <h3 class="text-xl sm:text-2xl font-extrabold leading-snug text-foreground"
                                x-text="evItem.title"></h3>

                            {{-- Container Info Highlight --}}
                            <div class="mt-4 space-y-4 rounded-2xl bg-brand-100/60 border border-brand-200/70 p-4 sm:p-5">
                                {{-- Penyelenggara Kegiatan --}}
                                <div class="flex items-center gap-3.5">
                                    <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-white text-brand-700 shadow-2xs">
                                        <svg class="size-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm-6 1a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Zm12 0a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Zm-6 1.5c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4Zm-6.5.25C3.47 14.18 1 15.17 1 17.5v2h1.5v-2c0-1.48 1.24-2.68 3-3.75Zm13 0c1.76 1.07 3 2.27 3 3.75v2H23v-2c0-2.33-2.47-3.32-4.5-3.75Z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-medium text-muted-foreground-2">Penyelenggara Kegiatan</p>
                                        <p class="mt-0.5 text-sm font-bold text-foreground"
                                           x-text="evItem.organizer"></p>
                                    </div>
                                </div>

                                {{-- Waktu Pelaksanaan --}}
                                <div class="flex items-center gap-3.5">
                                    <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-white text-brand-700 shadow-2xs">
                                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-medium text-muted-foreground-2">Waktu Pelaksanaan</p>
                                        <p class="mt-0.5 text-sm font-bold text-foreground"
                                           x-text="evItem.time"></p>
                                    </div>
                                </div>
                            </div>

                            {{-- Rincian Kegiatan --}}
                            <div class="mt-4">
                                <p class="text-xs font-medium text-muted-foreground-2">Rincian Kegiatan</p>
                                <p class="mt-1.5 text-sm font-bold leading-relaxed text-foreground/90"
                                   x-text="evItem.description"></p>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Info & Tombol Aksi: Ajukan Kegiatan di Tanggal Ini / Tutup --}}
                <div class="mt-6 border-t border-border-line-1 pt-5 space-y-3">
                    <template x-if="selectedDateHasAvailableSlot">
                        <div class="rounded-xl border border-brand-200 bg-brand-50/70 px-3.5 py-2.5 text-xs leading-relaxed text-brand-900">
                            Masih tersedia slot jam kosong di tanggal ini (minimal <strong>jeda 1 jam</strong> dari kegiatan yang sudah terjadwal).
                        </div>
                    </template>
                    <template x-if="!selectedDateHasAvailableSlot">
                        <div class="rounded-xl border border-amber-200 bg-amber-50 px-3.5 py-2.5 text-xs font-medium leading-relaxed text-amber-900">
                            Seluruh slot jam operasional (08.00 – 19.00 WIB) pada tanggal ini sudah penuh.
                        </div>
                    </template>

                    <div class="flex flex-wrap items-center gap-2.5">
                        <template x-if="selectedDateHasAvailableSlot">
                            <button type="button"
                                    @click="openSubmissionFormFromDetail()"
                                    class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-brand-700 px-4 py-2.5 text-xs sm:text-sm font-bold text-white shadow-xs transition hover:bg-brand-800 cursor-pointer">
                                <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.25" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                <span>Ajukan Kegiatan di Tanggal Ini</span>
                            </button>
                        </template>

                        <button type="button"
                                @click="closeModal()"
                                class="inline-flex items-center justify-center rounded-xl border border-brand-200 bg-brand-100/80 px-6 py-2.5 text-xs sm:text-sm font-bold text-brand-800 transition hover:bg-brand-200 cursor-pointer">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>

            {{-- B. MODAL FRAME 216: Formulir Ajukan Jadwal Kegiatan/Kunjungan Baru --}}
            <div x-show="modalState === 'form'"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                 class="relative z-10 w-full max-w-lg rounded-3xl border border-border bg-card p-6 sm:p-8 shadow-2xl max-h-[92vh] overflow-y-auto">

                <h3 class="text-xl sm:text-2xl font-extrabold leading-tight text-foreground">
                    Ajukan Jadwal Kegiatan/<br>Kunjungan Baru
                </h3>
                <p class="mt-2.5 text-xs sm:text-sm leading-relaxed text-muted-foreground-1">
                    Slot tanggal ini masih terbuka! Silakan lengkapi formulir singkat di bawah untuk mengajukan agenda bersama santri asuh.
                </p>

                {{-- Info Jadwal Terisi pada Tanggal Ini (Jika sudah ada kegiatan di hari yang sama) --}}
                <template x-if="selectedDateEvents.length > 0">
                    <div class="mt-4 rounded-2xl border border-brand-200 bg-brand-50/80 p-3.5 sm:p-4">
                        <p class="text-xs font-extrabold text-brand-900">
                            Agenda Terjadwal di Tanggal Ini (Wajib Jeda Minimal 1 Jam):
                        </p>
                        <ul class="mt-1.5 space-y-1 text-xs text-brand-800">
                            <template x-for="(ev, idx) in selectedDateEvents" :key="'form-ev-' + (ev.id || idx)">
                                <li class="flex items-center gap-2">
                                    <span class="size-1.5 rounded-full bg-brand-700 shrink-0"></span>
                                    <span class="font-bold" x-text="ev.time"></span>
                                    <span class="text-muted-foreground-1">—</span>
                                    <span class="truncate font-medium" x-text="ev.title"></span>
                                </li>
                            </template>
                        </ul>
                        <p class="mt-2 text-[11px] leading-relaxed text-muted-foreground-2">
                            Pilihan <strong>Waktu Mulai</strong> dan <strong>Waktu Selesai</strong> di bawah telah disesuaikan otomatis agar memiliki jeda minimal 1 jam dari kegiatan di atas.
                        </p>
                    </div>
                </template>

                <form @submit.prevent="submitFormToConfirm()" class="mt-5 space-y-4" novalidate>
                    {{-- 1. Tanggal Terpilih (Readonly) --}}
                    <div>
                        <label for="form-tanggal-terpilih" class="block text-xs sm:text-sm font-bold text-foreground mb-1.5">
                            Tanggal Terpilih
                        </label>
                        <input id="form-tanggal-terpilih"
                               type="text"
                               readonly
                               :value="formData.tanggalLabel"
                               class="w-full rounded-xl border border-brand-200 bg-brand-50/70 px-4 py-2.5 text-xs sm:text-sm font-semibold text-brand-900 focus:outline-none">
                    </div>

                    {{-- 2. Nama Pemohon/Komunitas/Lembaga --}}
                    <div>
                        <label for="form-nama-pemohon" class="block text-xs sm:text-sm font-bold text-foreground mb-1.5">
                            Nama Pemohon/Komunitas/Lembaga
                        </label>
                        <input id="form-nama-pemohon"
                               type="text"
                               x-model="formData.namaPemohon"
                               @input="formErrors.namaPemohon = ''"
                               placeholder="Contoh: Komunitas Peduli / H. Ahmad Ramadhan"
                               class="w-full rounded-xl border border-border bg-muted px-4 py-2.5 text-xs sm:text-sm text-foreground placeholder:text-muted-foreground focus:border-brand-700 focus:bg-card focus:ring-2 focus:ring-brand-700/20 focus:outline-none transition">
                        <p x-show="formErrors.namaPemohon" x-text="formErrors.namaPemohon" class="form-error"></p>
                    </div>

                    {{-- 3. Nomor WhatsApp & 4. Jenis Kegiatan --}}
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="form-nomor-wa" class="block text-xs sm:text-sm font-bold text-foreground mb-1.5">
                                Nomor WhatsApp
                            </label>
                            <input id="form-nomor-wa"
                                   type="tel"
                                   x-model="formData.nomorWhatsapp"
                                   @input="formErrors.nomorWhatsapp = ''"
                                   placeholder="0812-xxxx-xxxx"
                                   class="w-full rounded-xl border border-border bg-muted px-4 py-2.5 text-xs sm:text-sm text-foreground placeholder:text-muted-foreground focus:border-brand-700 focus:bg-card focus:ring-2 focus:ring-brand-700/20 focus:outline-none transition">
                            <p x-show="formErrors.nomorWhatsapp" x-text="formErrors.nomorWhatsapp" class="form-error"></p>
                        </div>

                        <div>
                            <label for="form-jenis-kegiatan" class="block text-xs sm:text-sm font-bold text-foreground mb-1.5">
                                Jenis Kegiatan
                            </label>
                            <div class="relative">
                                <select id="form-jenis-kegiatan"
                                        x-model="formData.jenisKegiatan"
                                        @change="formErrors.jenisKegiatan = ''"
                                        class="w-full appearance-none rounded-xl border border-border bg-muted py-2.5 pl-4 pr-10 text-xs sm:text-sm text-foreground focus:border-brand-700 focus:bg-card focus:ring-2 focus:ring-brand-700/20 focus:outline-none transition cursor-pointer">
                                    <option value="">Pilih jenis kegiatan...</option>
                                    <template x-for="opsi in jenisKegiatanOptions" :key="opsi">
                                        <option :value="opsi" x-text="opsi"></option>
                                    </template>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-3.5 flex items-center text-foreground">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2.25" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </div>
                            </div>
                            <p x-show="formErrors.jenisKegiatan" x-text="formErrors.jenisKegiatan" class="form-error"></p>
                        </div>
                    </div>

                    {{-- 5. Waktu Pelaksanaan (Dropdown Waktu Mulai & Waktu Selesai: 08.00 - 19.00 WIB) --}}
                    <div>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="form-waktu-mulai" class="block text-xs sm:text-sm font-bold text-foreground mb-1.5">
                                    Waktu Mulai
                                </label>
                                <div class="relative">
                                    <select id="form-waktu-mulai"
                                            x-model="formData.waktuMulai"
                                            @change="onWaktuMulaiChange()"
                                            class="w-full appearance-none rounded-xl border border-border bg-muted py-2.5 pl-4 pr-10 text-xs sm:text-sm text-foreground focus:border-brand-700 focus:bg-card focus:ring-2 focus:ring-brand-700/20 focus:outline-none transition cursor-pointer">
                                        <option value="">Pilih jam mulai...</option>
                                        <template x-for="jam in waktuMulaiOptions" :key="'mulai-' + jam">
                                            <option :value="jam" x-text="jam + ' WIB'"></option>
                                        </template>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-3.5 flex items-center text-foreground">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2.25" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </div>
                                </div>
                                <p x-show="formErrors.waktuMulai" x-text="formErrors.waktuMulai" class="form-error"></p>
                            </div>

                            <div>
                                <label for="form-waktu-selesai" class="block text-xs sm:text-sm font-bold text-foreground mb-1.5">
                                    Waktu Selesai
                                </label>
                                <div class="relative">
                                    <select id="form-waktu-selesai"
                                            x-model="formData.waktuSelesai"
                                            @change="onWaktuSelesaiChange()"
                                            :disabled="!formData.waktuMulai"
                                            class="w-full appearance-none rounded-xl border border-border bg-muted py-2.5 pl-4 pr-10 text-xs sm:text-sm text-foreground focus:border-brand-700 focus:bg-card focus:ring-2 focus:ring-brand-700/20 focus:outline-none transition cursor-pointer disabled:cursor-not-allowed disabled:opacity-50">
                                        <option value="" x-text="formData.waktuMulai ? 'Pilih jam selesai...' : 'Pilih jam mulai dahulu...'"></option>
                                        <template x-for="jam in waktuSelesaiOptions" :key="'selesai-' + jam">
                                            <option :value="jam" x-text="jam + ' WIB'"></option>
                                        </template>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-3.5 flex items-center text-foreground">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2.25" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </div>
                                </div>
                                <p x-show="formErrors.waktuSelesai" x-text="formErrors.waktuSelesai" class="form-error"></p>
                            </div>
                        </div>
                    </div>

                    {{-- 6. Rencana Kegiatan/Kebutuhan Fasilitas --}}
                    <div>
                        <label for="form-rencana-kegiatan" class="block text-xs sm:text-sm font-bold text-foreground mb-1.5">
                            Rencana Kegiatan/Kebutuhan Fasilitas
                        </label>
                        <textarea id="form-rencana-kegiatan"
                                  rows="3"
                                  x-model="formData.rencanaKegiatan"
                                  @input="formErrors.rencanaKegiatan = ''"
                                  placeholder="Sampaikan ringkasan susunan agenda atau perlengkapan ruangan yang dibutuhkan..."
                                  class="w-full rounded-xl border border-border bg-muted px-4 py-2.5 text-xs sm:text-sm text-foreground placeholder:text-muted-foreground focus:border-brand-700 focus:bg-card focus:ring-2 focus:ring-brand-700/20 focus:outline-none transition resize-none"></textarea>
                        <p x-show="formErrors.rencanaKegiatan" x-text="formErrors.rencanaKegiatan" class="form-error"></p>
                    </div>

                    {{-- Tombol Aksi: Kirim & Batal --}}
                    <div class="pt-2 flex items-center gap-3">
                        <button type="submit"
                                class="inline-flex min-w-28 items-center justify-center rounded-xl bg-brand-700 px-6 py-2.5 text-xs sm:text-sm font-bold text-white shadow-xs transition hover:bg-brand-800 cursor-pointer">
                            Kirim
                        </button>
                        <button type="button"
                                @click="closeModal()"
                                class="inline-flex min-w-28 items-center justify-center rounded-xl border border-brand-200 bg-brand-100/80 px-6 py-2.5 text-xs sm:text-sm font-bold text-brand-800 transition hover:bg-brand-200 cursor-pointer">
                            Batal
                        </button>
                    </div>
                </form>
            </div>

            {{-- C. MODAL FRAME 217: Tahap 1 — Verifikasi Data --}}
            <div x-show="modalState === 'confirm'"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                 class="relative z-10 w-full max-w-md rounded-3xl border border-border bg-card p-6 sm:p-8 shadow-2xl">

                <h3 class="text-xl sm:text-2xl font-extrabold leading-snug text-foreground">
                    Data yang anda masukkan sudah benar?
                </h3>

                <div class="mt-6 flex items-center justify-center gap-3.5">
                    <button type="button"
                            @click="confirmFormSubmission()"
                            class="inline-flex min-w-28 items-center justify-center rounded-xl bg-brand-700 px-7 py-2.5 text-xs sm:text-sm font-bold text-white shadow-xs transition hover:bg-brand-800 cursor-pointer">
                        Ya
                    </button>
                    <button type="button"
                            @click="modalState = 'form'"
                            class="inline-flex min-w-28 items-center justify-center rounded-xl border border-brand-200 bg-brand-100/80 px-7 py-2.5 text-xs sm:text-sm font-bold text-brand-800 transition hover:bg-brand-200 cursor-pointer">
                        Tidak
                    </button>
                </div>
            </div>

            {{-- D. MODAL FRAME 218: Tahap 2 — Pengalihan Konfirmasi Lanjutan via WhatsApp --}}
            <div x-show="modalState === 'whatsapp'"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                 class="relative z-10 w-full max-w-md rounded-3xl border border-border bg-card p-6 sm:p-8 shadow-2xl">

                <h3 class="text-base sm:text-lg font-extrabold leading-snug text-foreground">
                    Informasi Selanjutnya Silahkan Hubungi Narahubung dibawah
                </h3>

                <div class="mt-5">
                    <a :href="whatsappUrl"
                       target="_blank"
                       rel="noopener noreferrer"
                       @click="closeModal()"
                       class="flex w-full items-center justify-center gap-2.5 rounded-full bg-brand-700 px-6 py-3.5 text-xs sm:text-sm font-bold text-white shadow-sm transition hover:bg-brand-800">
                        <svg class="size-5 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2zm.01 1.67c2.2 0 4.26.86 5.82 2.41 1.56 1.56 2.41 3.63 2.41 5.83 0 4.54-3.7 8.24-8.24 8.24-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31c-.82-1.31-1.26-2.83-1.26-4.38 0-4.54 3.7-8.24 8.24-8.24zm4.52 11.66c-.25-.13-1.47-.72-1.7-.81-.23-.08-.39-.13-.56.13-.17.25-.64.81-.79.97-.14.17-.29.19-.54.06-.25-.13-1.07-.39-2.03-1.25-.75-.67-1.26-1.5-1.41-1.75-.14-.25-.02-.39.11-.51.11-.11.25-.29.38-.44.13-.14.17-.25.25-.42.08-.17.04-.31-.02-.44-.06-.13-.56-1.35-.77-1.85-.2-.49-.41-.42-.56-.43h-.48c-.17 0-.44.06-.67.31-.23.25-.88.86-.88 2.1 0 1.24.9 2.44 1.03 2.61.13.17 1.77 2.7 4.29 3.79.6.26 1.07.41 1.43.53.6.19 1.15.16 1.58.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.15-1.18-.06-.11-.23-.17-.48-.29z"/>
                        </svg>
                        <span>Admin Himmatun Ayat Bandung</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <script>
        function kalenderKegiatanSection(initialEvents, adminWhatsappNumber = '6281268765420') {
            const monthNames = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            const shortMonthNames = [
                'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
            ];
            const fullDayNames = [
                'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
            ];

            // Langsung buka ke bulan berjalan (Bulan Ini) dan abaikan data dari bulan sebelumnya
            const now = new Date();
            const defaultYear = now.getFullYear();
            const defaultMonth = now.getMonth();
            const defaultHighlightMm = String(defaultMonth + 1).padStart(2, '0');

            const filteredInitialEvents = Array.isArray(initialEvents)
                ? initialEvents.filter((e) => e.year > defaultYear || (e.year === defaultYear && e.month >= defaultMonth))
                : [];

            return {
                events: filteredInitialEvents,
                dayHeaders: ['SEN', 'SEL', 'RAB', 'KAM', 'JUM', 'SAB', 'AHD'],
                activeYear: defaultYear,
                activeMonth: defaultMonth,
                defaultYear: defaultYear,
                defaultMonth: defaultMonth,
                highlightedDateIso: defaultYear + '-' + defaultHighlightMm + '-19',
                modalState: null, // null | 'detail' | 'form' | 'confirm' | 'whatsapp'
                selectedEvent: null,
                selectedCell: null,
                selectedDateEvents: [],
                adminPhone: adminWhatsappNumber || '6281268765420',
                jenisKegiatanOptions: [
                    'Santunan & Bakti Sosial',
                    'Buka Puasa Bersama (Bukber)',
                    'Kunjungan / Silaturahmi Komunitas',
                    'Edukasi / Pelatihan / Workshop',
                    'Peringatan Hari Besar Islam (PHBI)',
                    'Perlombaan / Fun Games',
                    'Lainnya'
                ],
                jamKegiatanOptions: [
                    '08.00', '09.00', '10.00', '11.00', '12.00', '13.00',
                    '14.00', '15.00', '16.00', '17.00', '18.00', '19.00'
                ],
                formData: {
                    tanggalIso: '',
                    tanggalLabel: '',
                    namaPemohon: '',
                    nomorWhatsapp: '',
                    waktuMulai: '',
                    waktuSelesai: '',
                    waktuPelaksanaan: '',
                    jenisKegiatan: '',
                    rencanaKegiatan: ''
                },
                formErrors: {
                    namaPemohon: '',
                    nomorWhatsapp: '',
                    waktuMulai: '',
                    waktuSelesai: '',
                    waktuPelaksanaan: '',
                    jenisKegiatan: '',
                    rencanaKegiatan: ''
                },

                parseEventTimeRange(timeStr) {
                    if (!timeStr || typeof timeStr !== 'string') {
                        return null;
                    }
                    const match = timeStr.match(/(\d{1,2})[.:](\d{2})\s*[-–—]\s*(\d{1,2})[.:](\d{2})/);
                    if (!match) {
                        return null;
                    }
                    const startHour = parseInt(match[1], 10) + (parseInt(match[2], 10) / 60);
                    const endHour = parseInt(match[3], 10) + (parseInt(match[4], 10) / 60);
                    return { start: startHour, end: endHour };
                },

                getEventsForDate(dateIso) {
                    if (!dateIso) {
                        return [];
                    }
                    return this.events
                        .filter((e) => e.date_iso === dateIso)
                        .slice()
                        .sort((a, b) => {
                            const rangeA = this.parseEventTimeRange(a.time);
                            const rangeB = this.parseEventTimeRange(b.time);
                            const startA = rangeA ? rangeA.start : 0;
                            const startB = rangeB ? rangeB.start : 0;
                            return startA - startB;
                        });
                },

                isTimeRangeValidForDate(startHour, endHour, dateIso) {
                    if (isNaN(startHour) || isNaN(endHour) || endHour <= startHour) {
                        return false;
                    }
                    if (!dateIso) {
                        return true;
                    }
                    const existingEvents = this.getEventsForDate(dateIso);
                    for (let i = 0; i < existingEvents.length; i++) {
                        const range = this.parseEventTimeRange(existingEvents[i].time);
                        if (!range) {
                            continue;
                        }
                        const finishesWithOneHourGapBefore = endHour <= (range.start - 1);
                        const startsWithOneHourGapAfter = startHour >= (range.end + 1);
                        if (!finishesWithOneHourGapBefore && !startsWithOneHourGapAfter) {
                            return false;
                        }
                    }
                    return true;
                },

                getAvailableStartHoursForDate(dateIso) {
                    const candidateStarts = this.jamKegiatanOptions.slice(0, -1);
                    return candidateStarts.filter((jam) => {
                        const startH = parseInt(jam, 10);
                        return this.isTimeRangeValidForDate(startH, startH + 1, dateIso);
                    });
                },

                get selectedDateHasAvailableSlot() {
                    const dateIso = (this.selectedCell && this.selectedCell.dateIso)
                        || (this.selectedEvent && this.selectedEvent.date_iso)
                        || '';
                    if (!dateIso) {
                        return false;
                    }
                    return this.getAvailableStartHoursForDate(dateIso).length > 0;
                },

                get waktuMulaiOptions() {
                    return this.getAvailableStartHoursForDate(this.formData.tanggalIso);
                },

                get waktuSelesaiOptions() {
                    if (!this.formData.waktuMulai) {
                        return [];
                    }
                    const mulaiHour = parseInt(this.formData.waktuMulai, 10);
                    return this.jamKegiatanOptions.filter((jam) => {
                        const selesaiHour = parseInt(jam, 10);
                        return selesaiHour > mulaiHour && this.isTimeRangeValidForDate(mulaiHour, selesaiHour, this.formData.tanggalIso);
                    });
                },

                get monthYearLabel() {
                    return monthNames[this.activeMonth] + ' ' + this.activeYear;
                },

                get currentMonthEvents() {
                    return this.events.filter(
                        (e) => e.year === this.activeYear && e.month === this.activeMonth
                    );
                },

                get daysInActiveMonth() {
                    return new Date(this.activeYear, this.activeMonth + 1, 0).getDate();
                },

                get scheduledCount() {
                    const uniqueDates = new Set(this.currentMonthEvents.map((e) => e.date_iso));
                    return uniqueDates.size;
                },

                get availableCount() {
                    return Math.max(0, this.daysInActiveMonth - this.scheduledCount);
                },

                get calendarCells() {
                    const cells = [];
                    const year = this.activeYear;
                    const month = this.activeMonth;

                    const firstDayJs = new Date(year, month, 1).getDay(); // 0=Minggu .. 6=Sabtu
                    const startOffset = (firstDayJs + 6) % 7; // 0=Senin .. 6=Ahad
                    const daysInMonth = this.daysInActiveMonth;
                    const daysInPrevMonth = new Date(year, month, 0).getDate();

                    // Tanggal dari bulan sebelumnya (redup/disabled)
                    for (let i = startOffset - 1; i >= 0; i--) {
                        const prevDay = daysInPrevMonth - i;
                        cells.push({
                            key: 'prev-' + year + '-' + month + '-' + prevDay,
                            day: prevDay,
                            outsideLabel: String(prevDay),
                            isCurrentMonth: false,
                            isSunday: false,
                            dateIso: null,
                            event: null,
                            events: [],
                            hasAvailableSlot: false
                        });
                    }

                    // Tanggal di bulan aktif
                    for (let day = 1; day <= daysInMonth; day++) {
                        const mm = String(month + 1).padStart(2, '0');
                        const dd = String(day).padStart(2, '0');
                        const dateIso = year + '-' + mm + '-' + dd;
                        const jsDate = new Date(year, month, day);
                        const isSunday = jsDate.getDay() === 0;
                        const matchedEvents = this.getEventsForDate(dateIso);
                        const matchedEvent = matchedEvents[0] || null;

                        cells.push({
                            key: 'cur-' + dateIso,
                            day: day,
                            outsideLabel: String(day),
                            isCurrentMonth: true,
                            isSunday: isSunday,
                            dateIso: dateIso,
                            dateFormatted: fullDayNames[jsDate.getDay()] + ', ' + day + ' ' + monthNames[month] + ' ' + year,
                            dateShortFormatted: day + ' ' + monthNames[month] + ' ' + year,
                            event: matchedEvent,
                            events: matchedEvents,
                            hasAvailableSlot: this.getAvailableStartHoursForDate(dateIso).length > 0
                        });
                    }

                    // Tanggal awal bulan berikutnya (minimal 1 sel atau genapkan baris)
                    const totalSoFar = cells.length;
                    const remainder = totalSoFar % 7;
                    const trailingCount = remainder === 0 ? 0 : (7 - remainder);
                    const nextMonthIdx = (month + 1) % 12;

                    for (let n = 1; n <= trailingCount; n++) {
                        cells.push({
                            key: 'next-' + year + '-' + month + '-' + n,
                            day: n,
                            outsideLabel: n === 1 ? ('1 ' + shortMonthNames[nextMonthIdx]) : String(n),
                            isCurrentMonth: false,
                            isSunday: false,
                            dateIso: null,
                            event: null,
                            events: [],
                            hasAvailableSlot: false
                        });
                    }

                    return cells;
                },

                get whatsappUrl() {
                    const nama = this.formData.namaPemohon.trim() || '-';
                    const jenis = this.formData.jenisKegiatan.trim() || '-';
                    const tanggal = this.formData.tanggalLabel.trim() || '-';

                    const message = 'Assalamualaikum wr. wb. / Halo Pengurus Yayasan Himmatun Ayat Bandung,\n\n'
                        + 'Saya telah mengisi formulir pengajuan jadwal kegiatan melalui website resmi yayasan atas nama ' + nama
                        + ' untuk rencana kegiatan ' + jenis
                        + ' pada tanggal ' + tanggal + '.\n\n'
                        + 'Pesan ini merupakan konfirmasi bahwa pengajuan telah kami kirimkan. Besar harapan kami dapat bersilaturahmi dan menjalin kerja sama kebaikan bersama adik-adik santri serta pengurus yayasan. \n\n'
                        + 'Mohon arahan dan informasi terkait tindak lanjut serta perizinan selanjutnya. Terima kasih banyak.';

                    return 'https://wa.me/' + this.adminPhone + '?text=' + encodeURIComponent(message);
                },

                prevMonth() {
                    if (this.activeMonth === 0) {
                        this.activeMonth = 11;
                        this.activeYear--;
                    } else {
                        this.activeMonth--;
                    }
                },

                nextMonth() {
                    if (this.activeMonth === 11) {
                        this.activeMonth = 0;
                        this.activeYear++;
                    } else {
                        this.activeMonth++;
                    }
                },

                goToDefaultMonth() {
                    this.activeYear = this.defaultYear;
                    this.activeMonth = this.defaultMonth;
                },

                handleDateClick(cell) {
                    if (!cell.isCurrentMonth) return;

                    this.selectedCell = cell;
                    if (cell.events && cell.events.length > 0) {
                        this.highlightedDateIso = cell.dateIso;
                        this.selectedEvent = cell.events[0];
                        this.selectedDateEvents = cell.events;
                        this.modalState = 'detail';
                    } else {
                        this.openSubmissionForm(cell);
                    }
                },

                openEventDetail(eventObj) {
                    this.highlightedDateIso = eventObj.date_iso;
                    this.selectedEvent = eventObj;
                    const matchingCell = this.calendarCells.find((c) => c.isCurrentMonth && c.dateIso === eventObj.date_iso) || null;
                    this.selectedCell = matchingCell;
                    const allOnDate = this.getEventsForDate(eventObj.date_iso);
                    this.selectedDateEvents = allOnDate.length > 0 ? allOnDate : [eventObj];
                    this.modalState = 'detail';
                },

                openSubmissionFormFromDetail() {
                    if (this.selectedCell) {
                        this.openSubmissionForm(this.selectedCell);
                        return;
                    }
                    if (this.selectedEvent && this.selectedEvent.date_iso) {
                        const parts = this.selectedEvent.date_iso.split('-');
                        const y = parseInt(parts[0], 10);
                        const m = parseInt(parts[1], 10) - 1;
                        const d = parseInt(parts[2], 10);
                        const jsDate = new Date(y, m, d);
                        this.openSubmissionForm({
                            dateIso: this.selectedEvent.date_iso,
                            dateFormatted: fullDayNames[jsDate.getDay()] + ', ' + d + ' ' + monthNames[m] + ' ' + y,
                            events: this.getEventsForDate(this.selectedEvent.date_iso)
                        });
                    }
                },

                openSubmissionForm(cell) {
                    this.resetErrors();
                    this.selectedCell = cell;
                    this.selectedDateEvents = (cell && cell.events) ? cell.events : this.getEventsForDate(cell.dateIso);
                    this.formData.tanggalIso = cell.dateIso;
                    this.formData.tanggalLabel = cell.dateFormatted;
                    this.formData.waktuMulai = '';
                    this.formData.waktuSelesai = '';
                    this.formData.waktuPelaksanaan = '';
                    this.modalState = 'form';
                },

                resetErrors() {
                    this.formErrors = {
                        namaPemohon: '',
                        nomorWhatsapp: '',
                        waktuMulai: '',
                        waktuSelesai: '',
                        waktuPelaksanaan: '',
                        jenisKegiatan: '',
                        rencanaKegiatan: ''
                    };
                },

                onWaktuMulaiChange() {
                    this.formErrors.waktuMulai = '';
                    if (this.formData.waktuSelesai) {
                        const mulaiHour = parseInt(this.formData.waktuMulai, 10);
                        const selesaiHour = parseInt(this.formData.waktuSelesai, 10);
                        if (
                            !this.formData.waktuMulai ||
                            selesaiHour <= mulaiHour ||
                            !this.isTimeRangeValidForDate(mulaiHour, selesaiHour, this.formData.tanggalIso)
                        ) {
                            this.formData.waktuSelesai = '';
                        }
                    }
                    this.syncWaktuPelaksanaan();
                },

                onWaktuSelesaiChange() {
                    this.formErrors.waktuSelesai = '';
                    this.syncWaktuPelaksanaan();
                },

                syncWaktuPelaksanaan() {
                    if (this.formData.waktuMulai && this.formData.waktuSelesai) {
                        this.formData.waktuPelaksanaan = this.formData.waktuMulai + ' - ' + this.formData.waktuSelesai + ' WIB';
                    } else {
                        this.formData.waktuPelaksanaan = '';
                    }
                },

                validateForm() {
                    this.resetErrors();
                    let valid = true;

                    if (!this.formData.namaPemohon.trim()) {
                        this.formErrors.namaPemohon = 'Nama pemohon/komunitas/lembaga wajib diisi.';
                        valid = false;
                    }
                    if (!this.formData.nomorWhatsapp.trim()) {
                        this.formErrors.nomorWhatsapp = 'Nomor WhatsApp wajib diisi.';
                        valid = false;
                    }
                    if (!this.formData.waktuMulai) {
                        this.formErrors.waktuMulai = 'Waktu mulai wajib dipilih.';
                        valid = false;
                    }
                    if (!this.formData.waktuSelesai) {
                        this.formErrors.waktuSelesai = 'Waktu selesai wajib dipilih.';
                        valid = false;
                    } else if (this.formData.waktuMulai) {
                        const mulaiHour = parseInt(this.formData.waktuMulai, 10);
                        const selesaiHour = parseInt(this.formData.waktuSelesai, 10);
                        if (selesaiHour <= mulaiHour) {
                            this.formErrors.waktuSelesai = 'Waktu selesai harus setelah waktu mulai.';
                            valid = false;
                        } else if (!this.isTimeRangeValidForDate(mulaiHour, selesaiHour, this.formData.tanggalIso)) {
                            this.formErrors.waktuSelesai = 'Rentang waktu harus memiliki jeda minimal 1 jam dari kegiatan lain di tanggal ini.';
                            valid = false;
                        }
                    }
                    this.syncWaktuPelaksanaan();

                    if (!this.formData.jenisKegiatan.trim()) {
                        this.formErrors.jenisKegiatan = 'Silakan pilih jenis kegiatan.';
                        valid = false;
                    }
                    if (!this.formData.rencanaKegiatan.trim()) {
                        this.formErrors.rencanaKegiatan = 'Rencana kegiatan atau kebutuhan fasilitas wajib diisi.';
                        valid = false;
                    }

                    return valid;
                },

                submitFormToConfirm() {
                    if (!this.validateForm()) {
                        return;
                    }
                    this.modalState = 'confirm';
                },

                async confirmFormSubmission() {
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        await fetch('{{ route('pengajuan-kegiatan.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': token || ''
                            },
                            body: JSON.stringify({
                                nama_pemohon: this.formData.namaPemohon,
                                nomor_whatsapp: this.formData.nomorWhatsapp,
                                tanggal: this.formData.tanggalIso,
                                waktu: this.formData.waktuPelaksanaan,
                                jenis_kegiatan: this.formData.jenisKegiatan,
                                rencana_kegiatan: this.formData.rencanaKegiatan
                            })
                        });
                    } catch (e) {
                        console.error('Gagal menyimpan pengajuan ke database:', e);
                    }

                    this.modalState = 'whatsapp';
                },

                closeModal() {
                    this.modalState = null;
                }
            };
        }
    </script>

    <!-- CTA MEDIA SOSIAL -->
    <section class="bg-white">
        <div class="mx-auto max-w-7xl px-4 pt-8 sm:px-6 sm:pt-10 lg:px-8">
            <div class="relative overflow-hidden rounded-2xl sm:rounded-3xl bg-gradient-to-r from-brand-700 via-brand-800 to-brand-700 px-5 py-6 sm:px-8 sm:py-8">
                {{-- Ornamen lingkaran --}}
                <div class="pointer-events-none absolute -left-10 -top-16 size-48 rounded-full border border-white/10"></div>
                <div class="pointer-events-none absolute -bottom-24 left-1/3 size-64 rounded-full border border-white/10"></div>
                <div class="pointer-events-none absolute -right-16 -top-20 size-56 rounded-full border border-white/10"></div>
                <div class="pointer-events-none absolute -bottom-16 right-1/4 size-40 rounded-full border border-white/10"></div>

                <div class="relative flex flex-col gap-5 sm:gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div class="max-w-2xl">
                        <p class="text-2xs font-bold uppercase tracking-wider text-brand-200">Terhubung Bersama Kami</p>
                        <h2 class="mt-1.5 text-lg sm:text-xl lg:text-2xl font-bold leading-tight text-white">
                            Ikuti Keseharian &amp; Live Story Adik-adik Asuh di Media Sosial
                        </h2>
                        <p class="mt-1.5 text-xs leading-relaxed text-brand-100/90 sm:text-[13px]">
                            Saksikan langsung penyaluran doa dan kebaikan Anda melalui akun resmi Instagram Yayasan Himmatun Ayat Bandung.
                        </p>
                    </div>

                    <a href="https://instagram.com/himmatunayatbandung" target="_blank" rel="noopener noreferrer"
                       class="inline-flex w-fit shrink-0 items-center gap-2 rounded-full bg-brand-50 px-4 sm:px-5 py-2.5 text-xs font-semibold text-brand-900 shadow-xs transition hover:bg-white sm:text-sm">
                        <svg class="size-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 0 1 1.772 1.153 4.902 4.902 0 0 1 1.153 1.772c.247.636.416 1.363.465 2.427.048 1.024.06 1.378.06 3.808s-.012 2.784-.06 3.808c-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 0 1-1.153 1.772 4.902 4.902 0 0 1-1.772 1.153c-.636.247-1.363.416-2.427.465-1.024.048-1.378.06-3.808.06s-2.784-.012-3.808-.06c-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 0 1-1.772-1.153 4.902 4.902 0 0 1-1.153-1.772c-.247-.636-.416-1.363-.465-2.427C2.013 15.1 2 14.745 2 12.315s.013-2.784.06-3.808c.049-1.064.218-1.791.465-2.427a4.902 4.902 0 0 1 1.153-1.772A4.902 4.902 0 0 1 5.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 12.315 2Zm0 1.802c-2.39 0-2.67.009-3.61.052-.87.04-1.343.185-1.658.308-.417.162-.715.355-1.028.668-.313.313-.506.611-.668 1.028-.123.315-.268.788-.308 1.658-.043.94-.052 1.22-.052 3.61s.009 2.67.052 3.61c.04.87.185 1.343.308 1.658.162.417.355.715.668 1.028.313.313.611.506 1.028.668.315.123.788.268 1.658.308.94.043 1.22.052 3.61.052s2.67-.009 3.61-.052c.87-.04 1.343-.185 1.658-.308.417-.162.715-.355 1.028-.668.313-.313.506-.611.668-1.028.123-.315.268-.788.308-1.658.043-.94.052-1.22.052-3.61s-.009-2.67-.052-3.61c-.04-.87-.185-1.343-.308-1.658a2.757 2.757 0 0 0-.668-1.028 2.757 2.757 0 0 0-1.028-.668c-.315-.123-.788-.268-1.658-.308-.94-.043-1.22-.052-3.61-.052Zm0 3.064a5.449 5.449 0 1 1 0 10.898 5.449 5.449 0 0 1 0-10.898Zm0 1.802a3.647 3.647 0 1 0 0 7.294 3.647 3.647 0 0 0 0-7.294Zm5.66-3.203a1.273 1.273 0 1 1 0 2.546 1.273 1.273 0 0 1 0-2.546Z"/>
                        </svg>
                        <span>@himmatunayatbandung</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
