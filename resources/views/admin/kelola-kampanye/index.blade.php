@extends('layouts.admin')

@section('title', 'Kelola Kampanye - Admin Panel Himmatun Ayat')
@section('breadcrumb', 'Kelola Kampanye')

@section('content')
<div class="space-y-4 sm:space-y-5 pb-12 w-full max-w-full min-w-0">
    <!-- Top Header: Title, Subtitle, & Action Button (Persis Screenshot 1) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-brand-900">Kelola Kampanye</h1>
            <p class="text-xs sm:text-sm text-muted-foreground-1 mt-0.5 font-medium">Buat, Kelola, dan pantau hasil kampanye donasi Anda.</p>
        </div>
        <div class="flex items-center gap-2">
            <!-- Tombol Buat Kampanye Baru -->
            <a href="{{ route('admin.kelola-kampanye.create') }}"
               class="btn btn-primary btn-sm sm:btn-md rounded-xl shadow-xs cursor-pointer">
                <svg class="w-4 h-4 text-white shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Buat Kampanye Baru</span>
            </a>
        </div>
    </div>

    <!-- 3 Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 sm:gap-4">
        <!-- Card 1: TOTAL KAMPANYE AKTIF -->
        <div class="bg-card rounded-2xl p-4 sm:p-5 border border-border shadow-2xs hover:border-brand-600 hover:shadow-sm transition-all duration-200 relative overflow-hidden flex flex-col justify-between">
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-muted-foreground-1">TOTAL KAMPANYE AKTIF</span>
                    <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-700 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2.5">
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-3xl font-extrabold tracking-tight text-foreground tabular">{{ $metrics['total_aktif'] }}</span>
                        <span class="text-sm font-bold text-muted-foreground-2">Program</span>
                    </div>
                </div>
            </div>
            <div class="mt-3.5 pt-1 relative z-10 flex items-center gap-1.5 flex-wrap">
                <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-surface text-muted-foreground-2 border border-border">
                    {{ $metrics['total_reguler'] }} Reguler
                </span>
                <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-danger-soft text-danger border border-danger/30">
                    {{ $metrics['total_mendesak'] }} Mendesak
                </span>
            </div>
            <!-- Decorative circle shape -->
            <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-brand-50/60 pointer-events-none"></div>
        </div>

        <!-- Card 2: KABAR PENYALURAN TERBIT -->
        <div class="bg-card rounded-2xl p-4 sm:p-5 border border-border shadow-2xs hover:border-warning hover:shadow-sm transition-all duration-200 relative overflow-hidden flex flex-col justify-between">
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-muted-foreground-1">KABAR PENYALURAN TERBIT</span>
                    <div class="w-8 h-8 rounded-lg bg-warning-soft text-warning flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2.5">
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-3xl font-extrabold tracking-tight text-foreground tabular">{{ $metrics['kabar_penyaluran'] }}</span>
                        <span class="text-sm font-bold text-muted-foreground-2">Rilis Berita</span>
                    </div>
                </div>
            </div>
            <div class="mt-3.5 pt-1 relative z-10 flex items-center gap-1.5 text-[11px] font-medium text-muted-foreground-2">
                <svg class="w-3.5 h-3.5 text-brand-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>100% Disertai Bukti Dokumentasi</span>
            </div>
            <!-- Decorative circle shape -->
            <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-warning-soft/60 pointer-events-none"></div>
        </div>

        <!-- Card 3: MENDEKATI BATAS WAKTU -->
        <div class="bg-card rounded-2xl p-4 sm:p-5 border border-border shadow-2xs hover:border-danger hover:shadow-sm transition-all duration-200 relative overflow-hidden flex flex-col justify-between">
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-danger">MENDEKATI BATAS WAKTU</span>
                    <div class="w-8 h-8 rounded-lg bg-danger-soft text-danger flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2.5">
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-3xl font-extrabold tracking-tight text-danger tabular">{{ $metrics['mendekati_batas_waktu'] }}</span>
                        <span class="text-sm font-bold text-foreground">Program &lt; 14 Hari</span>
                    </div>
                </div>
            </div>
            <div class="mt-3.5 pt-1 relative z-10 flex items-center gap-1.5 text-[11px] font-medium text-muted-foreground-2">
                <svg class="w-3.5 h-3.5 text-warning shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                </svg>
                <span>Perlu Perpanjangan / Akselerasi</span>
            </div>
            <!-- Decorative circle shape -->
            <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-danger-soft/60 pointer-events-none"></div>
        </div>
    </div>

    <div data-admin-filter-scope="kelola-kampanye" class="space-y-4">
    <!-- Toolbar: Search Bar & Segmented Sorting -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
        <!-- Search Input Bar -->
        <form method="GET" action="{{ route('admin.kelola-kampanye.index') }}" data-admin-filter-form data-admin-filter-sync="search-form" class="w-full sm:max-w-md">
            <input type="hidden" name="sort" value="{{ $sort }}">
            @if($status)<input type="hidden" name="status" value="{{ $status }}">@endif
            @if($kategori)<input type="hidden" name="kategori" value="{{ $kategori }}">@endif
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="w-4 h-4 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Cari Kampanye / Donasi..." 
                       class="form-input bg-card pl-10 pr-9 py-2 sm:py-2.5 text-xs sm:text-sm rounded-xl shadow-2xs">
                @if($search)
                    <a href="{{ route('admin.kelola-kampanye.index', array_filter(['sort' => $sort, 'status' => $status, 'kategori' => $kategori])) }}" 
                       data-admin-filter-link
                       class="absolute inset-y-0 right-0 flex items-center pr-3 text-muted-foreground hover:text-muted-foreground-2 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif
            </div>
        </form>

        <!-- Segmented Sorting Buttons (Status / Kategori) -->
        <div class="flex items-center gap-2 self-start sm:self-auto text-xs sm:text-sm">
            <span class="text-muted-foreground-1 font-medium">Urutkan:</span>
            <div data-sliding-pills data-admin-filter-sync="sort-pills" class="relative inline-flex items-center p-1 rounded-xl bg-card border border-border shadow-2xs gap-1">
                <span data-sliding-pill-bg aria-hidden="true" class="absolute top-0 left-0 rounded-lg bg-brand-700 shadow-xs transition-[transform,width,height] duration-250 ease-out pointer-events-none z-0"></span>
                <!-- Button Status -->
                <a href="{{ route('admin.kelola-kampanye.index', array_filter(['search' => $search, 'sort' => 'status', 'status' => $status, 'kategori' => $kategori])) }}"
                   data-admin-filter-link
                   data-pill-active="{{ $sort === 'status' || !$sort ? 'true' : 'false' }}"
                   class="relative z-10 px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors duration-200 cursor-pointer {{ $sort === 'status' || !$sort ? 'text-white' : 'text-muted-foreground-2 hover:text-brand-600 hover:bg-brand-50/70' }}">
                    Status
                </a>

                <!-- Button Kategori -->
                <a href="{{ route('admin.kelola-kampanye.index', array_filter(['search' => $search, 'sort' => 'kategori', 'status' => $status, 'kategori' => $kategori])) }}"
                   data-admin-filter-link
                   data-pill-active="{{ $sort === 'kategori' ? 'true' : 'false' }}"
                   class="relative z-10 px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors duration-200 cursor-pointer {{ $sort === 'kategori' ? 'text-white' : 'text-muted-foreground-2 hover:text-brand-600 hover:bg-brand-50/70' }}">
                    Kategori
                </a>
            </div>
        </div>
    </div>

    <!-- ================= DATA CONTAINER ================= -->
    <div data-admin-filter-results class="space-y-4">

    <!-- 1. Tampilan Desktop & Tablet (md ke atas): Tabel Semantik Rapi -->
    <div class="hidden md:block bg-card rounded-2xl border border-border shadow-2xs overflow-x-auto w-full">
        <table class="w-full text-left text-xs">
            <thead>
                <tr class="bg-brand-100 text-muted-foreground-2 font-bold uppercase text-2xs tracking-wider border-b border-brand-200">
                    <th class="py-3.5 px-4 text-center w-12 whitespace-nowrap">NO</th>
                    <th class="py-3.5 px-4 min-w-[220px]">KAMPANYE</th>
                    <th class="py-3.5 px-4 whitespace-nowrap">KATEGORI</th>
                    <th class="py-3.5 px-4 whitespace-nowrap">TARGET</th>
                    <th class="py-3.5 px-4 whitespace-nowrap">TERKUMPUL</th>
                    <th class="py-3.5 px-3 text-center whitespace-nowrap">STATUS</th>
                    <th class="py-3.5 px-4 whitespace-nowrap">TANGGAL</th>
                    <th class="py-3.5 px-4 text-center whitespace-nowrap">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-line-1">
                @forelse($kampanyeList as $index => $item)
                    <tr class="hover:bg-muted/60 transition align-middle">
                        <!-- NO -->
                        <td class="py-3.5 px-4 text-center font-semibold text-muted-foreground-2 tabular whitespace-nowrap">
                            {{ $kampanyeList->firstItem() + $index }}
                        </td>

                        <!-- KAMPANYE (Judul Program Kampanye) -->
                        <td class="py-3.5 px-4 max-w-[280px]">
                            <span class="font-bold text-foreground text-xs sm:text-sm block truncate" title="{{ $item->judul }}">
                                {{ $item->judul }}
                            </span>
                        </td>

                        <!-- KATEGORI (Badge Hijau Seragam) -->
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-2xs font-semibold border whitespace-nowrap {{ $item->kategori_badge_classes }}">
                                {{ $item->kategori }}
                            </span>
                        </td>

                        <!-- TARGET -->
                        <td class="py-3.5 px-4 font-bold text-foreground tabular whitespace-nowrap">
                            Rp {{ number_format($item->target_dana, 0, ',', '.') }}
                        </td>

                        <!-- TERKUMPUL -->
                        <td class="py-3.5 px-4 font-bold text-brand-900 tabular whitespace-nowrap">
                            Rp {{ number_format($item->total_terkumpul, 0, ',', '.') }}
                        </td>

                        <!-- STATUS -->
                        <td class="py-3.5 px-3 text-center whitespace-nowrap">
                            @if($item->status === 'selesai')
                                <span class="badge badge-info">Selesai</span>
                            @elseif($item->status === 'aktif')
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-neutral">Draft</span>
                            @endif
                        </td>

                        <!-- TANGGAL (Periode) -->
                        <td class="py-3.5 px-4 text-muted-foreground-1 font-medium tabular whitespace-nowrap">
                            {{ $item->periode_text }}
                        </td>

                        <!-- AKSI -->
                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                            <div class="inline-flex items-center justify-center gap-1.5">
                                <!-- Edit -->
                                <a href="{{ route('admin.kelola-kampanye.edit', $item->id) }}" 
                                   title="Edit Kampanye"
                                   class="text-muted-foreground-1 hover:text-brand-700 transition p-1.5 rounded-lg hover:bg-brand-50 cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </a>

                                <!-- Delete -->
                                <form method="POST" action="{{ route('admin.kelola-kampanye.destroy', $item->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            data-confirm="Apakah Anda yakin ingin menghapus kampanye '{{ $item->judul }}'? Tindakan ini tidak dapat dibatalkan."
                                            data-confirm-title="Hapus Kampanye"
                                            data-confirm-type="danger"
                                            data-confirm-button="Ya, Hapus"
                                            title="Hapus Kampanye"
                                            class="text-danger hover:text-danger transition p-1.5 rounded-lg hover:bg-danger-soft cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-muted-foreground text-sm">
                            Tidak ada data kampanye yang sesuai dengan filter atau pencarian.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- 2. Tampilan Khusus Mobile (< md): Kartu Responsif, Bersih, dan Bebas Menumpuk -->
    <div class="md:hidden space-y-3.5">
        @forelse($kampanyeList as $item)
            <div class="bg-card rounded-2xl border border-border shadow-2xs p-4 space-y-3 transition hover:border-brand-700">
                <!-- Header Kartu: Judul & Badge Status -->
                <div class="flex items-start justify-between gap-2.5">
                    <div class="min-w-0">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-2xs font-semibold border mb-1.5 {{ $item->kategori_badge_classes }}">
                            {{ $item->kategori }}
                        </span>
                        <h2 class="font-bold text-foreground text-sm leading-snug">{{ $item->judul }}</h2>
                    </div>

                    @if($item->status === 'selesai')
                        <span class="badge badge-info shrink-0">Selesai</span>
                    @elseif($item->status === 'aktif')
                        <span class="badge badge-success shrink-0">Aktif</span>
                    @else
                        <span class="badge badge-neutral shrink-0">Draft</span>
                    @endif
                </div>

                <!-- Informasi Nominal & Progres -->
                <div class="grid grid-cols-2 gap-2 text-xs bg-muted p-2.5 rounded-xl border border-border-line-1">
                    <div>
                        <span class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">TARGET</span>
                        <span class="font-bold text-foreground tabular">Rp {{ number_format($item->target_dana, 0, ',', '.') }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">TERKUMPUL</span>
                        <span class="font-bold text-brand-800 tabular">Rp {{ number_format($item->total_terkumpul, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Periode & Tombol Aksi -->
                <div class="flex items-center justify-between gap-2 pt-2 border-t border-border-line-1 text-xs">
                    <div class="flex items-center gap-1.5 text-muted-foreground-1">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.253 18.75m3-18.75H3.75a2.25 2.25 0 00-2.25 2.25v13.5a2.25 2.25 0 002.25 2.25h16.5a2.25 2.25 0 002.25-2.25V5.25a2.25 2.25 0 00-2.25-2.25z" />
                        </svg>
                        <span>{{ $item->periode_text }}</span>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <!-- Edit Button -->
                        <a href="{{ route('admin.kelola-kampanye.edit', $item->id) }}" 
                           class="p-1.5 rounded-lg bg-surface text-muted-foreground-2 hover:bg-surface-hover transition"
                           title="Edit">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" />
                            </svg>
                        </a>

                        <!-- Delete Button -->
                        <form method="POST" action="{{ route('admin.kelola-kampanye.destroy', $item->id) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    data-confirm="Apakah Anda yakin ingin menghapus kampanye '{{ $item->judul }}'? Tindakan ini tidak dapat dibatalkan."
                                    data-confirm-title="Hapus Kampanye"
                                    data-confirm-type="danger"
                                    data-confirm-button="Ya, Hapus"
                                    class="p-1.5 rounded-lg bg-danger-soft text-danger hover:bg-danger-soft transition cursor-pointer"
                                    title="Hapus">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-card rounded-2xl border border-border py-10 text-center text-muted-foreground text-xs">
                Tidak ada data kampanye yang sesuai.
            </div>
        @endforelse
    </div>

    <!-- Card Footer: Menampilkan Counter & Pagination (Persis Screenshot 1) -->
    <div class="bg-card rounded-2xl border border-border/70 p-4 sm:p-4.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs sm:text-sm text-muted-foreground-1 shadow-2xs">
        <div class="font-medium text-center sm:text-left text-muted-foreground-2">
            Menampilkan {{ $kampanyeList->firstItem() ?? 1 }} - {{ $kampanyeList->lastItem() ?? $kampanyeList->count() }} dari {{ $kampanyeList->total() }} kampanye
        </div>

        <nav class="flex items-center justify-center sm:justify-end gap-1.5">
            {{-- Prev Link --}}
            @if ($kampanyeList->onFirstPage())
                <span class="w-8 h-8 rounded-lg flex items-center justify-center text-muted-foreground cursor-not-allowed border border-border bg-muted">
                    &lt;
                </span>
            @else
                <a href="{{ $kampanyeList->previousPageUrl() }}" 
                   class="w-8 h-8 rounded-lg flex items-center justify-center text-muted-foreground-2 hover:bg-muted hover:text-foreground border border-border transition">
                    &lt;
                </a>
            @endif

            {{-- Numbered Page Links --}}
            @for ($p = 1; $p <= $kampanyeList->lastPage(); $p++)
                @if ($p == $kampanyeList->currentPage())
                    <span class="w-8 h-8 rounded-lg bg-brand-700 text-white flex items-center justify-center font-bold text-xs sm:text-sm shadow-2xs">
                        {{ $p }}
                    </span>
                @else
                    <a href="{{ $kampanyeList->url($p) }}" 
                       class="w-8 h-8 rounded-lg text-muted-foreground-2 hover:bg-muted flex items-center justify-center font-medium text-xs sm:text-sm border border-border transition">
                        {{ $p }}
                    </a>
                @endif
            @endfor

            {{-- Next Link --}}
            @if ($kampanyeList->hasMorePages())
                <a href="{{ $kampanyeList->nextPageUrl() }}" 
                   class="w-8 h-8 rounded-lg flex items-center justify-center text-muted-foreground-2 hover:bg-muted hover:text-foreground border border-border transition">
                    &gt;
                </a>
            @else
                <span class="w-8 h-8 rounded-lg flex items-center justify-center text-muted-foreground cursor-not-allowed border border-border bg-muted">
                    &gt;
                </span>
            @endif
        </nav>
    </div>
    </div>
    </div>
</div>
@endsection
