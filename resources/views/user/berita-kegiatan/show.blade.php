@extends('layouts.user')

@section('content')
<div class="overflow-x-hidden bg-white" data-page-animations>

    <!-- ========================================== -->
    <!-- HERO DETAIL BERITA                          -->
    <!-- ========================================== -->
    <section class="relative overflow-hidden">
        {{-- Latar lembut: hijau mint di kiri, krem hangat di kanan, memudar ke putih. --}}
        <div class="pointer-events-none absolute inset-0 bg-gradient-to-r from-brand-50 via-[#F7FBF8] to-[#FCF7E6]"></div>
        <div class="pointer-events-none absolute inset-x-0 bottom-0 h-24 bg-gradient-to-b from-transparent to-white"></div>

        <div class="relative mx-auto max-w-7xl px-4 pb-8 pt-5 sm:px-6 sm:pb-10 sm:pt-7 lg:px-8">

            {{-- Breadcrumb --}}
            <nav aria-label="Breadcrumb" class="mb-4 sm:mb-5"
                 data-animate="fade-in"
                 data-animate-duration="0.5">
                <ol class="inline-flex max-w-full flex-wrap items-center gap-1 rounded-full border border-white/70 bg-white/65 px-2 py-1.5 text-2xs font-medium text-muted-foreground-1 shadow-2xs backdrop-blur sm:gap-1.5 sm:px-2.5 sm:text-xs">
                    <li class="flex items-center">
                        <a href="{{ route('home') }}"
                           class="inline-flex items-center gap-1.5 rounded-full px-1.5 py-1 text-brand-800 transition hover:bg-brand-50 hover:text-brand-900 sm:px-2">
                            <svg class="size-3.5 text-brand-700" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                            <span>Beranda</span>
                        </a>
                    </li>

                    <li aria-hidden="true" class="flex items-center">
                        <svg class="size-3 text-brand-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </li>

                    <li class="flex items-center">
                        <a href="{{ route('berita-kegiatan.index') }}"
                           class="rounded-full px-1.5 py-1 transition hover:bg-brand-50 hover:text-brand-900 sm:px-2">
                            Berita &amp; Kegiatan
                        </a>
                    </li>

                    <li aria-hidden="true" class="flex items-center">
                        <svg class="size-3 text-brand-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </li>

                    <li class="flex min-w-0 items-center" aria-current="page">
                        <span class="max-w-[11rem] truncate rounded-full px-1.5 py-1 font-semibold text-brand-900 sm:max-w-xs sm:px-2 lg:max-w-md">
                            {{ $article['title'] }}
                        </span>
                    </li>
                </ol>
            </nav>

            <div class="max-w-5xl"
                 data-animate-group="fade-up"
                 data-animate-distance="20"
                 data-animate-duration="0.75"
                 data-animate-stagger="0.08">
                <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                    <span class="badge badge-success px-2.5 py-1 text-2xs sm:text-xs">{{ $article['type'] }}</span>
                    <span class="text-xs font-medium text-muted-foreground-1 sm:text-sm">{{ $article['category'] }}</span>
                </div>

                <h1 class="mt-4 text-3xl font-extrabold leading-[1.12] tracking-tight text-brand-900 sm:text-4xl lg:text-5xl">
                    {{ $article['title'] }}
                </h1>

                <div class="mt-3.5 flex flex-wrap items-center gap-x-5 gap-y-2 text-xs text-muted-foreground-1 sm:text-sm">
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="size-4 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        {{ $article['date'] }}
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="size-4 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        {{ $article['location'] }}
                    </span>
                </div>

                <p class="mt-4 max-w-3xl text-xs leading-relaxed text-muted-foreground-1 sm:text-sm lg:text-[15px]">
                    {{ $article['excerpt'] }}
                </p>
            </div>

            <figure class="mt-6 sm:mt-8"
                    data-animate-group="fade-up"
                    data-animate-distance="20"
                    data-animate-duration="0.8"
                    data-animate-stagger="0.12">
                <div class="overflow-hidden rounded-xl bg-surface shadow-2xs sm:rounded-2xl" data-animate-item="fade-scale">
                    <img src="{{ $article['image'] }}" alt="{{ $article['title'] }}"
                         class="aspect-[16/10] w-full object-cover sm:aspect-[16/9] lg:aspect-[2/1]">
                </div>
                <figcaption class="mt-2.5 text-2xs leading-relaxed text-muted-foreground-1 sm:text-xs" data-animate-item="fade-in">
                    {{ $article['image_caption'] }}
                </figcaption>
            </figure>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- ISI ARTIKEL & BERITA TERKAIT                -->
    <!-- ========================================== -->
    <section class="mx-auto max-w-7xl px-4 pb-12 pt-5 sm:px-6 sm:pb-16 sm:pt-7 lg:px-8">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:gap-10">

            {{-- Kolom utama --}}
            <div class="lg:col-span-7">
                <article class="article-content"
                         data-animate="fade-up"
                         data-animate-distance="18"
                         data-animate-duration="0.7">
                    {!! $article['content_html'] !!}
                </article>

                {{-- Topik --}}
                <div class="mt-8 border-t border-border pt-5"
                     data-animate="fade-up"
                     data-animate-distance="14"
                     data-animate-duration="0.6">
                    <div class="flex flex-wrap items-center gap-x-2.5 gap-y-2">
                        <span class="text-xs font-medium text-muted-foreground-1">Topik:</span>
                        @foreach ($article['tags'] as $tag)
                            <a href="{{ route('berita-kegiatan.index') }}"
                               class="rounded-full bg-brand-50 px-3 py-1 text-2xs font-semibold text-brand-800 transition hover:bg-brand-100">
                                {{ $tag }}
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Kembali --}}
                <div class="mt-5 border-t border-border pt-5"
                     data-animate="fade-in"
                     data-animate-duration="0.5">
                    <a href="{{ route('berita-kegiatan.index') }}"
                       class="inline-flex items-center gap-2 text-xs font-semibold text-brand-900 transition hover:text-brand-700 sm:text-sm">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                        </svg>
                        <span>Kembali ke Berita dan Kegiatan</span>
                    </a>
                </div>
            </div>

            {{-- Sidebar berita terkait --}}
            <aside class="lg:col-span-5"
                   data-animate-group="fade-up"
                   data-animate-distance="18"
                   data-animate-duration="0.65"
                   data-animate-stagger="0.08">
                <div class="inline-flex items-center gap-1.5 text-2xs font-bold uppercase tracking-wider text-brand-800">
                    <svg class="size-3.5 text-brand-700" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
                    </svg>
                    <span>Cerita Kebaikan Lainnya</span>
                </div>

                <h2 class="mt-1.5 text-xl font-extrabold tracking-tight text-brand-900 sm:text-2xl">
                    Berita &amp; Kegiatan Terkait
                </h2>
                <p class="mt-1.5 text-xs leading-relaxed text-muted-foreground-1">
                    Ikuti cerita dan langkah kebaikan lainnya dari keluarga yayasan.
                </p>

                <div class="mt-4 space-y-3">
                    @foreach ($related as $item)
                        <article class="rounded-xl border border-border bg-card p-3 shadow-2xs transition duration-200 hover:shadow-md sm:p-3.5">
                            <div class="flex gap-3">
                                <a href="{{ $item['url'] }}" class="relative block min-h-36 w-28 shrink-0 self-stretch overflow-hidden rounded-lg bg-surface sm:w-48">
                                    <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" loading="lazy"
                                         class="absolute inset-0 h-full w-full object-cover transition duration-500 hover:scale-[1.03]">
                                </a>

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        <span class="badge badge-success">{{ $item['type'] }}</span>
                                        <span class="text-2xs font-medium text-muted-foreground-1">{{ $item['category'] }}</span>
                                    </div>

                                    <div class="mt-1.5 flex flex-wrap items-center gap-x-2.5 gap-y-1 text-2xs text-muted-foreground-1">
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="size-3.5 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                            {{ $item['date'] }}
                                        </span>
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="size-3.5 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                            </svg>
                                            {{ $item['location'] }}
                                        </span>
                                    </div>

                                    <h3 class="mt-1.5 text-[13px] font-bold leading-snug text-brand-900 sm:text-[15px]">
                                        <a href="{{ $item['url'] }}" class="transition hover:text-brand-700">{{ $item['title'] }}</a>
                                    </h3>

                                    <a href="{{ $item['url'] }}"
                                       class="mt-2 inline-flex items-center gap-1 text-2xs font-semibold text-brand-800 transition hover:text-brand-700">
                                        <span>Lihat Selengkapnya</span>
                                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <a href="{{ route('berita-kegiatan.index') }}"
                   class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-brand-800 transition hover:text-brand-700">
                    <span>Lihat Semua Berita</span>
                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </aside>
        </div>
    </section>
</div>
@endsection
