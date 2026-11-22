@extends('layouts.admin')

@section('title', 'CMS Berita - Admin Panel Himmatun Ayat')
@section('breadcrumb', 'CMS Berita')

@section('content')
<div class="space-y-4 sm:space-y-5 pb-12 w-full max-w-full min-w-0">
    <!-- Flash Notifications -->
    @if(session('success'))
        <div class="p-3.5 rounded-xl bg-brand-50 border border-brand-200 text-brand-800 text-xs sm:text-sm flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-brand-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-brand-600 hover:text-brand-900 text-lg leading-none cursor-pointer">&times;</button>
        </div>
    @endif

    <!-- Header Page: Title + Tambah Berita Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-brand-900 tracking-tight">CMS Berita</h1>
            <p class="text-xs sm:text-sm text-muted-foreground-1 mt-0.5 font-medium">Kelola dan Publikasikan berita terbaru yayasan</p>
        </div>
        <div>
            <a href="{{ route('admin.berita.create') }}" 
               class="inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl bg-brand-700 hover:bg-brand-800 text-white font-semibold text-xs sm:text-sm shadow-xs transition cursor-pointer">
                <svg class="w-4 h-4 text-white shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Tambah Berita</span>
            </a>
        </div>
    </div>

    <!-- 4 Summary Cards (Statistik Berita) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-5">
        <!-- Card 1: TOTAL BERITA -->
        <div class="relative bg-card rounded-2xl sm:rounded-3xl border border-border p-5 shadow-xs overflow-hidden flex flex-col justify-between">
            <!-- Decorative circle accent -->
            <div class="w-28 h-28 rounded-full bg-brand-200/35 absolute -bottom-8 -right-8 pointer-events-none"></div>

            <div class="flex items-start justify-between relative z-10">
                <span class="text-[11px] font-bold text-muted-foreground-1 tracking-wider uppercase">TOTAL BERITA</span>
                <div class="w-9 h-9 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </div>
            </div>

            <div class="mt-4 relative z-10">
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl sm:text-4xl font-black text-foreground tracking-tight">{{ $stats['total_berita'] }}</span>
                    <span class="text-sm font-semibold text-muted-foreground-2">Berita</span>
                </div>
                <div class="flex items-center gap-1.5 mt-3">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-surface text-muted-foreground-2">
                        {{ $stats['reguler'] }} Tayang
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-danger-soft text-danger">
                        {{ $stats['mendesak'] }} Draft
                    </span>
                </div>
            </div>
        </div>

        <!-- Card 2: BERITA DI PUBLIKASIKAN -->
        <div class="relative bg-card rounded-2xl sm:rounded-3xl border border-border p-5 shadow-xs overflow-hidden flex flex-col justify-between">
            <!-- Decorative circle accent -->
            <div class="w-28 h-28 rounded-full bg-warning-soft/50 absolute -bottom-8 -right-8 pointer-events-none"></div>

            <div class="flex items-start justify-between relative z-10">
                <span class="text-[11px] font-bold text-muted-foreground-1 tracking-wider uppercase leading-tight">
                    BERITA<br>DI PUBLIKASIKAN
                </span>
                <div class="w-9 h-9 rounded-xl bg-warning-soft text-warning flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </div>
            </div>

            <div class="mt-4 relative z-10">
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl sm:text-4xl font-black text-foreground tracking-tight">{{ $stats['dipublikasikan'] }}</span>
                    <span class="text-sm font-semibold text-muted-foreground-2">Rilis Berita</span>
                </div>
                <div class="flex items-center gap-1.5 mt-3 text-xs text-muted-foreground-2 font-medium">
                    <svg class="w-4 h-4 text-brand-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Berita yang sudah tampil di halaman publik</span>
                </div>
            </div>
        </div>

        <!-- Card 3: DRAFT -->
        <div class="relative bg-card rounded-2xl sm:rounded-3xl border border-border p-5 shadow-xs overflow-hidden flex flex-col justify-between">
            <!-- Decorative circle accent -->
            <div class="w-28 h-28 rounded-full bg-danger-soft/40 absolute -bottom-8 -right-8 pointer-events-none"></div>

            <div class="flex items-start justify-between relative z-10">
                <span class="text-[11px] font-bold text-danger tracking-wider uppercase">DRAFT</span>
                <div class="w-9 h-9 rounded-xl bg-danger-soft text-danger flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div class="mt-4 relative z-10">
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl sm:text-4xl font-black text-danger tracking-tight">{{ $stats['draft'] }}</span>
                    <span class="text-sm font-semibold text-muted-foreground-2">Draft Berita</span>
                </div>
                <div class="flex items-center gap-1.5 mt-3 text-xs text-muted-foreground-2 font-medium">
                    <svg class="w-4 h-4 text-danger shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                    <span>Perlu Peninjauan & Verifikasi</span>
                </div>
            </div>
        </div>

        <!-- Card 4: ARSIP -->
        <div class="relative bg-card rounded-2xl sm:rounded-3xl border border-border p-5 shadow-xs overflow-hidden flex flex-col justify-between">
            <!-- Decorative circle accent -->
            <div class="w-28 h-28 rounded-full bg-danger-soft/40 absolute -bottom-8 -right-8 pointer-events-none"></div>

            <div class="flex items-start justify-between relative z-10">
                <span class="text-[11px] font-bold text-danger tracking-wider uppercase">ARSIP</span>
                <div class="w-9 h-9 rounded-xl bg-danger-soft text-danger flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                </div>
            </div>

            <div class="mt-4 relative z-10">
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl sm:text-4xl font-black text-danger tracking-tight">{{ $stats['arsip'] }}</span>
                    <span class="text-sm font-semibold text-muted-foreground-2">Arsip Berita</span>
                </div>
                <div class="flex items-center gap-1.5 mt-3 text-xs text-muted-foreground-2 font-medium">
                    <svg class="w-4 h-4 text-danger shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                    <span>Tersimpan di Direktori Arsip</span>
                </div>
            </div>
        </div>
    </div>

    <div data-admin-filter-scope="cms-berita" class="space-y-4">
    <!-- Search Bar & Filters -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 pt-2">
        <!-- Input Pencarian -->
        <form method="GET" action="{{ route('admin.berita.index') }}" data-admin-filter-form data-admin-filter-sync="search-form" class="relative flex-1 w-full max-w-xl">
            @if($sort)<input type="hidden" name="sort" value="{{ $sort }}">@endif
            @if(!empty($status))<input type="hidden" name="status" value="{{ $status }}">@endif
            @if(!empty($kategori))<input type="hidden" name="kategori" value="{{ $kategori }}">@endif
            <div class="relative">
                <input type="text" 
                       name="search" 
                       value="{{ $search }}"
                       placeholder="Cari Berita yayasan..." 
                       class="w-full bg-card border border-border-line-3 rounded-full px-5 py-2.5 pr-10 text-xs sm:text-sm text-foreground placeholder:text-muted-foreground focus:outline-hidden focus:ring-2 focus:ring-brand-700 focus:border-brand-700 transition shadow-xs">
                @if($search)
                    <a href="{{ route('admin.berita.index', array_filter(['sort' => $sort, 'status' => $status ?? null, 'kategori' => $kategori ?? null])) }}" 
                       data-admin-filter-link
                       class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-muted-foreground hover:text-muted-foreground-2 transition"
                       title="Hapus pencarian">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif
            </div>
        </form>

        <!-- Filter Urutan -->
        <div class="flex items-center justify-between sm:justify-start gap-2 shrink-0">
            <span class="text-xs sm:text-sm text-muted-foreground-2 font-medium mr-1">Urutkan:</span>
            <div data-sliding-pills data-admin-filter-sync="sort-pills" class="relative inline-flex items-center p-1 rounded-full bg-card border border-border-line-3 shadow-xs gap-1">
                <span data-sliding-pill-bg aria-hidden="true" class="absolute top-0 left-0 rounded-full bg-brand-700 shadow-xs transition-[transform,width,height] duration-250 ease-out pointer-events-none z-0"></span>
                <a href="{{ route('admin.berita.index', array_filter(['search' => $search, 'sort' => 'status', 'status' => $status ?? null, 'kategori' => $kategori ?? null])) }}" 
                   data-admin-filter-link
                   data-pill-active="{{ $sort === 'status' || !$sort ? 'true' : 'false' }}"
                   class="relative z-10 px-3.5 py-1.5 rounded-full text-xs font-semibold transition-colors duration-200 cursor-pointer {{ $sort === 'status' || !$sort ? 'text-white' : 'text-muted-foreground-2 hover:text-brand-600 hover:bg-brand-50/70' }}">
                    Status
                </a>
                <a href="{{ route('admin.berita.index', array_filter(['search' => $search, 'sort' => 'kategori', 'status' => $status ?? null, 'kategori' => $kategori ?? null])) }}" 
                   data-admin-filter-link
                   data-pill-active="{{ $sort === 'kategori' ? 'true' : 'false' }}"
                   class="relative z-10 px-3.5 py-1.5 rounded-full text-xs font-semibold transition-colors duration-200 cursor-pointer {{ $sort === 'kategori' ? 'text-white' : 'text-muted-foreground-2 hover:text-brand-600 hover:bg-brand-50/70' }}">
                    Kategori
                </a>
            </div>
        </div>
    </div>

    <div data-admin-filter-results class="space-y-4">
    <!-- 1. Tampilan Desktop & Tablet (md ke atas): Tabel Lengkap Sesuai Mockup -->
    <div class="hidden md:block bg-card rounded-2xl border border-border overflow-hidden shadow-xs">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse min-w-[750px]">
                <!-- Header Tabel dengan Latar Lembut Sage/Green -->
                <thead>
                    <tr class="bg-brand-100 dark:bg-brand-900/40 text-muted-foreground-2 text-xs font-bold uppercase tracking-wider border-b border-brand-200 dark:border-brand-800">
                        <th scope="col" class="py-3.5 px-4 text-center w-16">No</th>
                        <th scope="col" class="py-3.5 px-6">JUDUL BERITA</th>
                        <th scope="col" class="py-3.5 px-4 text-center">KATEGORI</th>
                        <th scope="col" class="py-3.5 px-4 text-center">STATUS</th>
                        <th scope="col" class="py-3.5 px-6 text-center">TANGGAL</th>
                        <th scope="col" class="py-3.5 px-6 text-center w-28">AKSI</th>
                    </tr>
                </thead>

                <!-- Body Tabel -->
                <tbody class="divide-y divide-border-line-1 text-sm">
                    @forelse($daftarBerita as $index => $item)
                        @php
                            $normalizedStatus = strtolower(trim((string) $item->status));
                            $statusKey = $normalizedStatus === 'tayang' && $item->tanggal->gt(today())
                                ? 'terjadwal'
                                : $normalizedStatus;
                            $statusLabel = match ($statusKey) {
                                'tayang' => 'Tayang',
                                'terjadwal' => 'Terjadwal',
                                'draft' => 'Draft',
                                'arsip' => 'Arsip',
                                default => trim((string) $item->status) !== '' ? trim((string) $item->status) : 'Tidak diketahui',
                            };
                            $statusBadgeClass = match ($statusKey) {
                                'tayang' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300',
                                'terjadwal' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900/50 dark:text-cyan-300',
                                'draft' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300',
                                'arsip' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
                                default => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
                            };
                            $formattedDate = $item->tanggal->locale('id')->translatedFormat('d M Y');
                            $editUrl = route('admin.berita.edit', $item);
                            $deleteUrl = route('admin.berita.destroy', $item);
                        @endphp
                        <tr class="hover:bg-muted transition">
                            <!-- No -->
                            <td class="py-4 px-4 text-center text-muted-foreground-2 font-medium">
                                {{ $daftarBerita->firstItem() + $index }}
                            </td>

                            <!-- Judul Berita + Thumbnail -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-lg bg-surface shrink-0 flex items-center justify-center overflow-hidden text-muted-foreground">
                                        @if($item->gambar)
                                            <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-6 h-6 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <span class="font-bold text-foreground leading-snug">{{ $item->judul }}</span>
                                </div>
                            </td>

                            <!-- Kategori Badge -->
                            <td class="py-4 px-4 text-center">
                                <span class="inline-block px-3.5 py-1 rounded-full text-xs font-semibold {{ str_contains($item->kategori, 'Dakwah') ? 'bg-info-soft text-info' : 'bg-warning-soft text-warning' }}">
                                    {{ $item->kategori }}
                                </span>
                            </td>

                            <!-- Status Badge -->
                            <td class="py-4 px-4 text-center">
                                <span class="inline-block px-3.5 py-1 rounded-full text-xs font-semibold {{ $statusBadgeClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            <!-- Tanggal -->
                            <td class="py-4 px-6 text-center text-xs text-muted-foreground-2 font-medium leading-tight">
                                {{ $formattedDate }}
                            </td>

                            <!-- Aksi (Edit & Delete) -->
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <!-- Edit Button -->
                                    <a href="{{ $editUrl }}"
                                       title="Edit Berita"
                                       class="text-muted-foreground-2 hover:text-brand-700 transition p-1.5 rounded-md hover:bg-muted">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </a>

                                    <!-- Delete Button -->
                                    <form method="POST" action="{{ $deleteUrl }}"
                                          data-confirm="Apakah Anda yakin ingin menghapus berita ini? Data berita yang dihapus tidak dapat dipulihkan."
                                          data-confirm-title="Hapus Berita"
                                          data-confirm-type="danger"
                                          data-confirm-button="Ya, Hapus">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                title="Hapus Berita"
                                                class="text-danger hover:text-danger transition p-1.5 rounded-md hover:bg-danger-soft cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-muted-foreground-1 text-sm">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-10 h-10 text-muted-foreground/60 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                    </svg>
                                    <p class="font-medium text-muted-foreground-2">Tidak ada berita yang sesuai dengan kata kunci pencarian.</p>
                                    @if($search)
                                        <a href="{{ route('admin.berita.index', array_filter(['sort' => $sort, 'status' => $status ?? null, 'kategori' => $kategori ?? null])) }}" data-admin-filter-link class="mt-2 text-xs text-brand-700 hover:underline font-semibold cursor-pointer">
                                            Reset Pencarian
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 2. Tampilan Khusus Mobile (< md): Kartu Responsif -->
    <div class="md:hidden space-y-3">
        @forelse($daftarBerita as $index => $item)
            @php
                $normalizedStatus = strtolower(trim((string) $item->status));
                $statusKey = $normalizedStatus === 'tayang' && $item->tanggal->gt(today())
                    ? 'terjadwal'
                    : $normalizedStatus;
                $statusLabel = match ($statusKey) {
                    'tayang' => 'Tayang',
                    'terjadwal' => 'Terjadwal',
                    'draft' => 'Draft',
                    'arsip' => 'Arsip',
                    default => trim((string) $item->status) !== '' ? trim((string) $item->status) : 'Tidak diketahui',
                };
                $statusBadgeClass = match ($statusKey) {
                    'tayang' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300',
                    'terjadwal' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900/50 dark:text-cyan-300',
                    'draft' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300',
                    'arsip' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
                    default => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
                };
                $formattedDate = $item->tanggal->locale('id')->translatedFormat('d M Y');
                $editUrl = route('admin.berita.edit', $item);
                $deleteUrl = route('admin.berita.destroy', $item);
            @endphp
            <div class="bg-card rounded-2xl border border-border shadow-2xs p-4 space-y-3 transition hover:border-brand-700">
                <!-- Baris 1: Thumbnail, Judul Berita, & Tombol Aksi -->
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-start gap-3 min-w-0 flex-1">
                        <!-- Thumbnail Box -->
                        <div class="w-12 h-12 rounded-xl bg-surface shrink-0 flex items-center justify-center overflow-hidden text-muted-foreground">
                            @if($item->gambar)
                                <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-6 h-6 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="inline-block text-[11px] font-bold text-muted-foreground uppercase tracking-wider">Berita #{{ $daftarBerita->firstItem() + $index }}</span>
                            <h3 class="font-bold text-foreground text-sm leading-snug line-clamp-2 mt-0.5">{{ $item->judul }}</h3>
                        </div>
                    </div>

                    <!-- Tombol Aksi (Edit & Hapus) -->
                    <div class="flex items-center gap-1 shrink-0">
                        <a href="{{ $editUrl }}"
                           title="Edit Berita"
                           class="p-2 text-muted-foreground-1 hover:text-brand-700 hover:bg-brand-50 rounded-xl transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                        </a>
                        <form method="POST" action="{{ $deleteUrl }}"
                              data-confirm="Apakah Anda yakin ingin menghapus berita ini? Data berita yang dihapus tidak dapat dipulihkan."
                              data-confirm-title="Hapus Berita"
                              data-confirm-type="danger"
                              data-confirm-button="Ya, Hapus">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    title="Hapus Berita"
                                    class="p-2 text-danger hover:text-danger hover:bg-danger-soft rounded-xl transition cursor-pointer">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Baris 2: Badges Kategori & Status -->
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ str_contains($item->kategori, 'Dakwah') ? 'bg-info-soft text-info' : 'bg-warning-soft text-warning' }}">
                        {{ $item->kategori }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusBadgeClass }}">
                        {{ $statusLabel }}
                    </span>
                </div>

                <!-- Baris 3: Tanggal & Tautan Ubah -->
                <div class="flex items-center justify-between text-xs text-muted-foreground-1 pt-2.5 border-t border-border-line-1">
                    <div class="flex items-center gap-1.5 font-medium">
                        <svg class="w-3.5 h-3.5 text-muted-foreground shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5" />
                        </svg>
                        <span>{{ $formattedDate }}</span>
                    </div>
                    <a href="{{ $editUrl }}" class="text-xs font-semibold text-brand-700 hover:underline">
                        Ubah &rarr;
                    </a>
                </div>
            </div>
        @empty
            <div class="bg-card rounded-2xl border border-border py-10 px-4 text-center text-muted-foreground-1 text-xs sm:text-sm">
                <svg class="w-10 h-10 text-muted-foreground/60 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <p class="font-medium text-muted-foreground-2">Tidak ada berita yang sesuai dengan pencarian.</p>
                @if($search)
                    <a href="{{ route('admin.berita.index', array_filter(['sort' => $sort, 'status' => $status ?? null, 'kategori' => $kategori ?? null])) }}" data-admin-filter-link class="mt-2 text-xs text-brand-700 hover:underline font-semibold cursor-pointer">
                        Reset Pencarian
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Card Footer: Menampilkan Counter & Pagination -->
    @if($daftarBerita->total() > 0)
        <div class="bg-card rounded-2xl border border-border/70 p-4 sm:p-4.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs sm:text-sm text-muted-foreground-1 shadow-2xs">
            <div class="font-medium text-center sm:text-left text-muted-foreground-2">
                Menampilkan {{ $daftarBerita->firstItem() ?? 1 }} - {{ $daftarBerita->lastItem() ?? $daftarBerita->count() }} dari {{ $daftarBerita->total() }} Berita
            </div>

            @if($daftarBerita->total() > 0)
                <nav class="flex items-center justify-center sm:justify-end gap-1.5" aria-label="Navigasi Halaman">
                    {{-- Prev Link --}}
                    @if ($daftarBerita->onFirstPage())
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center text-muted-foreground cursor-not-allowed border border-border bg-muted">
                            &lt;
                        </span>
                    @else
                        <a href="{{ $daftarBerita->previousPageUrl() }}" 
                           class="w-8 h-8 rounded-lg flex items-center justify-center text-muted-foreground-2 hover:bg-muted hover:text-foreground border border-border transition"
                           title="Halaman Sebelumnya">
                            &lt;
                        </a>
                    @endif

                    {{-- Numbered Page Links --}}
                    @if ($daftarBerita->hasPages())
                        @php
                            $start = max(1, $daftarBerita->currentPage() - 2);
                            $end = min($daftarBerita->lastPage(), $daftarBerita->currentPage() + 2);
                        @endphp
                        @for ($p = $start; $p <= $end; $p++)
                            @if ($p == $daftarBerita->currentPage())
                                <span class="w-8 h-8 rounded-lg bg-brand-700 text-white flex items-center justify-center font-bold text-xs sm:text-sm shadow-2xs" aria-current="page">
                                    {{ $p }}
                                </span>
                            @else
                                <a href="{{ $daftarBerita->url($p) }}"
                                   class="w-8 h-8 rounded-lg text-muted-foreground-2 hover:bg-muted flex items-center justify-center font-medium text-xs sm:text-sm border border-border transition">
                                    {{ $p }}
                                </a>
                            @endif
                        @endfor
                    @else
                        <span class="w-8 h-8 rounded-lg bg-brand-700 text-white flex items-center justify-center font-bold text-xs sm:text-sm shadow-2xs" aria-current="page">
                            1
                        </span>
                    @endif
                    {{-- Next Link --}}
                    @if ($daftarBerita->hasMorePages())
                        <a href="{{ $daftarBerita->nextPageUrl() }}" 
                           class="w-8 h-8 rounded-lg flex items-center justify-center text-muted-foreground-2 hover:bg-muted hover:text-foreground border border-border transition"
                           title="Halaman Selanjutnya">
                            &gt;
                        </a>
                    @else
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center text-muted-foreground cursor-not-allowed border border-border bg-muted">
                            &gt;
                        </span>
                    @endif
                </nav>
            @endif
        </div>
    @endif
    </div>
    </div>
</div>
@endsection
