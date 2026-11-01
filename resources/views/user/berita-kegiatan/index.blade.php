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

    <!-- AGENDA KEGIATAN MENDATANG -->
    <section class="mt-10 border-y border-brand-100 bg-brand-50 py-10 sm:mt-12 sm:py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-1.5 text-2xs font-bold uppercase tracking-wider text-brand-800">
                    <svg class="size-3.5 text-brand-700" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5" />
                    </svg>
                    <span>Agenda Kebaikan</span>
                </div>

                <h2 class="mt-1.5 text-xl sm:text-2xl font-extrabold tracking-tight text-brand-900">
                    Kegiatan Mendatang
                </h2>
                <p class="mt-1.5 text-xs sm:text-sm leading-relaxed text-muted-foreground-1">
                    Mari hadir, berbagi, dan menjadi bagian dari kebahagiaan adik-adik asuh.
                </p>
            </div>

            <div class="mt-6 grid gap-4 sm:gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($events as $event)
                    <article class="flex flex-col rounded-xl sm:rounded-2xl border border-border bg-card p-4 sm:p-5 shadow-2xs transition duration-200 hover:-translate-y-0.5 hover:shadow-md">
                        <div class="flex items-start justify-between gap-3">
                            <div class="rounded-xl bg-brand-100 px-2.5 py-1.5 text-center">
                                <div class="text-xl sm:text-2xl font-extrabold leading-none text-brand-900">{{ $event['day'] }}</div>
                                <div class="mt-1 text-[10px] font-bold uppercase tracking-wider text-brand-800">{{ $event['month'] }}</div>
                            </div>

                            @if ($event['badge'])
                                <span class="rounded-full bg-brand-100 px-2.5 py-0.5 text-2xs font-semibold text-brand-800">
                                    {{ $event['badge'] }}
                                </span>
                            @endif
                        </div>

                        <h3 class="mt-3.5 text-sm sm:text-base font-bold leading-snug text-brand-900">
                            {{ $event['title'] }}
                        </h3>

                        <div class="mt-3 space-y-1.5 text-xs text-muted-foreground-1">
                            <div class="flex items-center gap-2">
                                <svg class="size-3.5 shrink-0 text-brand-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <span>{{ $event['time'] }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="size-3.5 shrink-0 text-brand-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                </svg>
                                <span>{{ $event['location'] }}</span>
                            </div>
                        </div>

                        <p class="mt-3 text-xs leading-relaxed text-muted-foreground-1">
                            {{ $event['description'] }}
                        </p>

                        <a href="#" class="mt-auto inline-flex items-center gap-1 pt-3.5 text-xs font-semibold text-brand-800 transition hover:text-brand-700">
                            <span>Informasi Kegiatan</span>
                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

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
