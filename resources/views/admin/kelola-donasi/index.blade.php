@extends('layouts.admin')

@section('title', 'Kelola Donasi - Admin Panel Himmatun Ayat')
@section('breadcrumb', 'Kelola Donasi')

@section('content')
<div class="space-y-4 sm:space-y-5 pb-12 w-full max-w-full min-w-0"
     x-data="{
         previewSrc: '',
         previewDonatur: '',
         previewNominal: '',
         editDonasi: { id: '', nama: '', nominal: '', status: 'verified', verifikasiUrl: '', tolakUrl: '' },
         confirmModal: { type: 'verifikasi', title: '', message: '', id: '', nama: '', nominal: '', actionUrl: '', confirmText: '' },
         cols: { id_donasi: true, donatur: true, kontak: true, email: true, kategori: true, nominal: true, doa: true, bukti: true, status: true, aksi: true },
         resetColumns() { Object.keys(this.cols).forEach(k => this.cols[k] = true); }
     }"
     @open-bukti-modal.window="previewSrc = $event.detail.src; previewDonatur = $event.detail.nama; previewNominal = $event.detail.nominal"
     @open-edit-status-modal.window="editDonasi = $event.detail"
     @open-confirm-modal.window="confirmModal = $event.detail">

    @if($errors->any())
        <div class="alert alert-danger justify-between shadow-2xs">
            <div class="flex items-start gap-2.5">
                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
                <div class="text-xs sm:text-sm">
                    <p class="font-bold">Terjadi kesalahan pada input data:</p>
                    <ul class="list-disc pl-4 mt-1 space-y-0.5">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-lg leading-none hover:opacity-70 cursor-pointer shrink-0">&times;</button>
        </div>
    @endif

    <!-- Top Header: Title, Count, & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-brand-900">Kelola Donasi</h1>
            <p class="text-xs sm:text-sm text-muted-foreground-1 mt-0.5 font-medium">{{ $totalDonaturTerkini }} donatur terdaftar &bull; {{ $statusCounts['semua'] }} total transaksi</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <!-- Tombol Kelola Operasional -->
            <button type="button"
                    data-hs-overlay="#modal-operasional"
                    onclick="resetOperasionalForm()"
                    class="btn btn-secondary btn-sm sm:btn-md rounded-xl cursor-pointer">
                <svg class="w-4 h-4 text-brand-700 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Operasional</span>
            </button>

            <!-- Tombol ↓ Export -->
            <a href="{{ route('admin.kelola-donasi.export') }}"
               class="btn btn-primary btn-sm sm:btn-md rounded-xl shadow-xs cursor-pointer">
                <svg class="w-4 h-4 text-white shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.5V16.5M7.5 12l4.5 4.5m0 0l4.5-4.5M12 3v13.5" />
                </svg>
                <span>Export</span>
            </a>
        </div>
    </div>

    <!-- 4 Top Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <!-- Card 1: TOTAL PENERIMAAN ZIS -->
        <div class="bg-card rounded-2xl p-4 sm:p-5 border border-border/70 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-muted-foreground-1">TOTAL PENERIMAAN ZIS</span>
                    <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-700 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <rect x="2" y="6" width="20" height="12" rx="2" />
                            <circle cx="12" cy="12" r="2.5" />
                            <path d="M6 12h.01M18 12h.01" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="text-xl sm:text-2xl font-extrabold tracking-tight text-foreground tabular">
                        Rp {{ number_format($metrics['total_zis'], 0, ',', '.') }}
                    </span>
                    <p class="text-[11px] text-muted-foreground-1 font-medium mt-0.5">Akumulasi Infaq, Zakat, &amp; Wakaf Masuk</p>
                </div>
            </div>
            <div class="mt-3 pt-2">
                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-brand-800">
                    <svg class="w-3.5 h-3.5 text-brand-700" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.318 4.318L20.25 7.5m0 0h-5.25m5.25 0v5.25" />
                    </svg>
                    <span>{{ $metrics['persen_bulan_lalu'] >= 0 ? '+' : '' }}{{ $metrics['persen_bulan_lalu'] }}% dibanding bulan lalu</span>
                </span>
            </div>
        </div>

        <!-- Card 2: PENYALURAN PROGRAM -->
        <div class="bg-card rounded-2xl p-4 sm:p-5 border border-border/70 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-muted-foreground-1">PENYALURAN PROGRAM</span>
                    <button type="button" data-hs-overlay="#modal-penyaluran" onclick="resetPenyaluranForm()" title="Kelola Penyaluran Dana" class="w-8 h-8 rounded-lg bg-warning-soft text-warning hover:opacity-80 transition flex items-center justify-center shrink-0 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-5.25 6.557c0 1.455.517 2.822 1.407 3.896" />
                        </svg>
                    </button>
                </div>
                <div class="mt-2">
                    <span class="text-xl sm:text-2xl font-extrabold tracking-tight text-foreground tabular">
                        Rp {{ number_format($metrics['penyaluran_program'], 0, ',', '.') }}
                    </span>
                    <p class="text-[11px] text-muted-foreground-1 font-medium mt-0.5">Total realisasi penyaluran program</p>
                </div>
            </div>
            <div class="mt-3 pt-2">
                <div class="w-full bg-surface rounded-full h-1 overflow-hidden">
                    <div class="bg-brand-700 h-1 rounded-full" style="width: {{ min($metrics['rasio_efektivitas'], 100) }}%"></div>
                </div>
                <div class="flex items-center justify-between mt-2 text-[10px]">
                    <span class="font-bold text-foreground">Rasio Penyaluran</span>
                    <span class="font-bold text-warning">{{ $metrics['rasio_efektivitas'] }}% Efektivitas</span>
                </div>
            </div>
        </div>

        <!-- Card 3: OPERASIONAL HAK MILIK -->
        <div class="bg-card rounded-2xl p-4 sm:p-5 border border-border/70 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-muted-foreground-1">OPERASIONAL HAK MILIK</span>
                    <button type="button" data-hs-overlay="#modal-operasional" onclick="resetOperasionalForm()" title="Kelola Pengeluaran Operasional" class="w-8 h-8 rounded-lg bg-muted text-muted-foreground-2 hover:bg-surface-hover transition flex items-center justify-center shrink-0 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0012 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52l2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 01-2.031.352 5.988 5.988 0 01-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.97zm-13.5 0C4.24 5.113 3.24 5.287 2.25 5.49m3-.52L2.63 15.696c-.122.499.106 1.028.589 1.202a5.989 5.989 0 002.031.352 5.988 5.988 0 002.031-.352c.483-.174.711-.703.59-1.202L5.25 4.97z" />
                        </svg>
                    </button>
                </div>
                <div class="mt-2">
                    <span class="text-xl sm:text-2xl font-extrabold tracking-tight text-foreground tabular">
                        Rp {{ number_format($metrics['operasional_hak_milik'], 0, ',', '.') }}
                    </span>
                    <p class="text-[11px] text-muted-foreground-1 font-medium mt-0.5">Rasio {{ $metrics['rasio_operasional'] }}% dari dana terkumpul</p>
                </div>
            </div>
            <div class="mt-3 pt-2">
                <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-warning">
                    <svg class="w-3.5 h-3.5 text-warning shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Di bawah batas maks. BAZNAS (12.5%)</span>
                </span>
            </div>
        </div>

        <!-- Card 4: SALDO KAS & CADANGAN -->
        <div class="bg-card rounded-2xl p-4 sm:p-5 border border-border/70 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-muted-foreground-1">SALDO KAS &amp; CADANGAN</span>
                    <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-700 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.333A48.357 48.357 0 0012 9.75c-2.551 0-5.056.2-7.5.583V21" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="text-xl sm:text-2xl font-extrabold tracking-tight text-foreground tabular">
                        Rp {{ number_format($metrics['saldo_kas'], 0, ',', '.') }}
                    </span>
                    <p class="text-[11px] text-muted-foreground-1 font-medium mt-0.5">Rekening giro amanah BRI Syariah</p>
                </div>
            </div>
            <div class="mt-3 pt-2">
                <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-brand-800">
                    <svg class="w-3.5 h-3.5 text-brand-700 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                    <span>Cadangan darurat &amp; asrama binaan</span>
                </span>
            </div>
        </div>
    </div>

    <!-- Alokasi Penyaluran Program per Klaster Asnaf Card -->
    <div class="bg-brand-100 rounded-2xl p-4 sm:p-5 border border-brand-900/5 shadow-2xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h2 class="text-sm sm:text-base font-bold text-foreground">Alokasi Penyaluran Program per Klaster Asnaf</h2>
                <p class="text-xs text-muted-foreground-2 mt-0.5">Distribusi realisasi pemanfaatan dana sedekah dan zakat secara terperinci.</p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <div class="text-xs text-muted-foreground-2 sm:text-right">
                    Total Realisasi: <span class="font-bold text-foreground tabular">Rp {{ number_format($asnaf['total_realisasi'], 0, ',', '.') }}</span>
                </div>
                <button type="button"
                        data-hs-overlay="#modal-penyaluran"
                        onclick="resetPenyaluranForm()"
                        class="btn btn-primary btn-sm rounded-xl cursor-pointer">
                    + Catat Penyaluran
                </button>
            </div>
        </div>

        <!-- Multi-segment Colored Progress Bar -->
        @php
            $klasterKeys = ['pendidikan', 'nutrisi', 'kesehatan', 'asrama', 'lainnya'];
        @endphp
        <div class="mt-3.5 w-full h-3 rounded-full overflow-hidden flex bg-border-line-3/40">
            @foreach($klasterKeys as $key)
                @if(isset($asnaf[$key]) && $asnaf[$key]['persen'] > 0)
                    <div style="width: {{ $asnaf[$key]['persen'] }}%;"
                         class="{{ $asnaf[$key]['bg'] }} h-full"
                         title="{{ $asnaf[$key]['nama'] }}: {{ $asnaf[$key]['persen'] }}%"></div>
                @endif
            @endforeach
        </div>

        <!-- Legend Breakdown Items -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mt-3.5 pt-0.5">
            @foreach($klasterKeys as $key)
                @if(isset($asnaf[$key]) && $asnaf[$key]['nominal'] > 0)
                    <div class="flex items-start gap-2">
                        <span class="w-2.5 h-2.5 rounded-full {{ $asnaf[$key]['dot'] }} shrink-0 mt-1"></span>
                        <div class="text-xs">
                            <p class="font-bold text-foreground leading-tight">{{ $asnaf[$key]['nama'] }}</p>
                            <p class="text-muted-foreground-2 text-[11px] mt-0.5 font-medium tabular">
                                Rp {{ number_format($asnaf[$key]['nominal'], 0, ',', '.') }} ({{ $asnaf[$key]['persen'] }}%)
                            </p>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        @if($asnaf['total_realisasi'] == 0)
            <p class="mt-4 text-center text-xs text-muted-foreground-2">Belum ada data penyaluran dana.</p>
        @endif
    </div>

    <!-- ========================================================================= -->
    <!-- FILTER & DATA DONASI (DIPINDAHKAN DARI DASHBOARD + FILTER TANPA RELOAD)   -->
    <!-- ========================================================================= -->
    <div data-admin-filter-scope="kelola-donasi" class="space-y-3.5 pt-1">
        @php
            $filterBaseParams = array_filter([
                'search' => $search,
                'status' => $statusFilter,
                'sort_waktu' => $sortWaktu,
                'sort_nama' => $sortNama,
                'kampanye_id' => $kampanyeFilter,
            ], fn ($val) => $val !== null && $val !== '');
            $activeFilterCount = ($sortWaktu === 'terlama' ? 1 : 0) + ($sortNama !== '' ? 1 : 0) + ($kampanyeFilter !== '' ? 1 : 0);
        @endphp

        <!-- Toolbar Satu Baris (Desktop) / Responsif (Tablet & Mobile): Search, Status Pills, Filter, & Column Manager -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-2.5">
            <!-- Search Input -->
            <form method="GET" action="{{ route('admin.kelola-donasi.index') }}" data-admin-filter-form data-admin-filter-sync="search-form" class="w-full lg:w-64 xl:w-72 shrink-0">
                <input type="hidden" name="status" value="{{ $statusFilter }}">
                <input type="hidden" name="sort" value="{{ $sort }}">
                <input type="hidden" name="sort_waktu" value="{{ $sortWaktu }}">
                @if($sortNama !== '')<input type="hidden" name="sort_nama" value="{{ $sortNama }}">@endif
                @if($kampanyeFilter !== '')<input type="hidden" name="kampanye_id" value="{{ $kampanyeFilter }}">@endif
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                        <svg class="w-4 h-4 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Cari ID, donatur, kontak, program..."
                           class="form-input bg-card pl-10 pr-9 py-2 text-xs sm:text-sm rounded-xl shadow-2xs">
                    @if($search)
                        <a href="{{ route('admin.kelola-donasi.index', collect($filterBaseParams)->except('search')->all()) }}"
                           data-admin-filter-link
                           title="Hapus pencarian"
                           class="absolute inset-y-0 right-0 flex items-center pr-3 text-muted-foreground hover:text-muted-foreground-2 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>
            </form>

            <!-- Status Sliding Pills + Unified Filter + Column Manager -->
            <div class="flex flex-wrap items-center justify-between lg:justify-end gap-2 w-full lg:w-auto min-w-0">
                <!-- Status Donasi (Sliding Pills) -->
                <div data-sliding-pills data-admin-filter-sync="status-pills" class="relative inline-flex flex-wrap items-center rounded-xl bg-surface p-1 border border-border gap-0.5 sm:gap-1 max-w-full">
                    <span data-sliding-pill-bg aria-hidden="true" class="absolute top-0 left-0 rounded-lg bg-brand-700 shadow-xs transition-[transform,width,height] duration-250 ease-out pointer-events-none z-0"></span>

                    <a href="{{ route('admin.kelola-donasi.index', array_merge($filterBaseParams, ['status' => 'semua'])) }}"
                       data-admin-filter-link
                       data-pill-active="{{ $statusFilter === 'semua' || !$statusFilter ? 'true' : 'false' }}"
                       class="relative z-10 px-2.5 sm:px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors duration-200 whitespace-nowrap {{ $statusFilter === 'semua' || !$statusFilter ? 'text-white' : 'text-muted-foreground-2 hover:text-brand-600 hover:bg-brand-50/70' }}">
                        Semua ({{ $statusCounts['semua'] }})
                    </a>
                    <a href="{{ route('admin.kelola-donasi.index', array_merge($filterBaseParams, ['status' => 'pending'])) }}"
                       data-admin-filter-link
                       data-pill-active="{{ $statusFilter === 'pending' ? 'true' : 'false' }}"
                       class="relative z-10 px-2.5 sm:px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors duration-200 whitespace-nowrap {{ $statusFilter === 'pending' ? 'text-white' : 'text-muted-foreground-2 hover:text-brand-600 hover:bg-brand-50/70' }}">
                        Menunggu ({{ $statusCounts['pending'] }})
                    </a>
                    <a href="{{ route('admin.kelola-donasi.index', array_merge($filterBaseParams, ['status' => 'verified'])) }}"
                       data-admin-filter-link
                       data-pill-active="{{ $statusFilter === 'verified' ? 'true' : 'false' }}"
                       class="relative z-10 px-2.5 sm:px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors duration-200 whitespace-nowrap {{ $statusFilter === 'verified' ? 'text-white' : 'text-muted-foreground-2 hover:text-brand-600 hover:bg-brand-50/70' }}">
                        Terverifikasi ({{ $statusCounts['verified'] }})
                    </a>
                    <a href="{{ route('admin.kelola-donasi.index', array_merge($filterBaseParams, ['status' => 'rejected'])) }}"
                       data-admin-filter-link
                       data-pill-active="{{ $statusFilter === 'rejected' ? 'true' : 'false' }}"
                       class="relative z-10 px-2.5 sm:px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors duration-200 whitespace-nowrap {{ $statusFilter === 'rejected' ? 'text-white' : 'text-muted-foreground-2 hover:text-brand-600 hover:bg-brand-50/70' }}">
                        Ditolak ({{ $statusCounts['rejected'] }})
                    </a>
                </div>

                <!-- Unified Filter Popover & Column Manager -->
                <div class="flex items-center gap-2 shrink-0">
                    <!-- Filter Popover -->
                    <div data-admin-filter-sync="filter-popover">
                        <div class="hs-dropdown [--auto-close:inside] relative inline-flex [--placement:bottom-right]">
                            <button id="hs-donasi-filter-dropdown"
                                    type="button"
                                    class="hs-dropdown-toggle inline-flex items-center gap-1.5 rounded-xl border px-3 py-2 text-xs font-semibold transition cursor-pointer whitespace-nowrap {{ $activeFilterCount > 0 ? 'bg-brand-50 text-brand-800 border-brand-300 shadow-2xs' : 'bg-card text-foreground border-border hover:bg-muted shadow-2xs' }}"
                                    aria-haspopup="menu"
                                    aria-expanded="false"
                                    aria-label="Filter dan urutkan donasi">
                                <svg class="w-3.5 h-3.5 {{ $activeFilterCount > 0 ? 'text-brand-700' : 'text-muted-foreground-1' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                                </svg>
                                <span>Filter</span>
                                @if($activeFilterCount > 0)
                                    <span class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-full bg-brand-700 text-white text-[10px] font-bold leading-none">{{ $activeFilterCount }}</span>
                                @endif
                            </button>

                            <div id="hs-donasi-filter-menu"
                                 class="hs-dropdown-menu hs-dropdown-open:opacity-100 hidden w-72 sm:w-80 max-w-[calc(100vw-2rem)] rounded-2xl border border-border bg-overlay p-4 opacity-0 shadow-xl transition-[opacity,margin] duration-150 z-40"
                                 role="menu"
                                 aria-labelledby="hs-donasi-filter-dropdown">
                                <div class="flex items-center justify-between pb-3 border-b border-border-line-1">
                                    <span class="text-sm font-bold text-foreground">Filters</span>
                                    <a href="{{ route('admin.kelola-donasi.index', array_filter(['search' => $search, 'status' => $statusFilter])) }}"
                                       data-admin-filter-link
                                       class="text-xs font-semibold text-danger hover:underline">
                                        Reset
                                    </a>
                                </div>

                                <form method="GET" action="{{ route('admin.kelola-donasi.index') }}" data-admin-filter-form data-manual-submit="true" class="mt-3.5 space-y-3.5">
                                    <input type="hidden" name="search" value="{{ $search }}">
                                    <input type="hidden" name="status" value="{{ $statusFilter }}">

                                    <div>
                                        <label for="filter-sort-waktu" class="form-label text-xs font-semibold mb-1.5">Urutan Waktu</label>
                                        <x-preline-select
                                            id="filter-sort-waktu"
                                            name="sort_waktu"
                                            placeholder="Pilih urutan waktu"
                                            toggle-classes="hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2 ps-3.5 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-layer border border-border rounded-xl text-start text-xs sm:text-sm text-foreground shadow-2xs focus:outline-hidden focus:border-primary focus:ring-2 focus:ring-primary/20"
                                            dropdown-classes="mt-2 z-[90] w-full max-h-60 p-1 space-y-0.5 bg-dropdown border border-dropdown-line rounded-xl shadow-lg overflow-hidden overflow-y-auto">
                                            <option value="terbaru" @selected($sortWaktu !== 'terlama')>Donasi Terbaru</option>
                                            <option value="terlama" @selected($sortWaktu === 'terlama')>Donasi Terlama</option>
                                        </x-preline-select>
                                    </div>

                                    <div>
                                        <label for="filter-sort-nama" class="form-label text-xs font-semibold mb-1.5">Urutan Nama Donatur</label>
                                        <x-preline-select
                                            id="filter-sort-nama"
                                            name="sort_nama"
                                            placeholder="Tanpa urutan nama"
                                            toggle-classes="hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2 ps-3.5 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-layer border border-border rounded-xl text-start text-xs sm:text-sm text-foreground shadow-2xs focus:outline-hidden focus:border-primary focus:ring-2 focus:ring-primary/20"
                                            dropdown-classes="mt-2 z-[90] w-full max-h-60 p-1 space-y-0.5 bg-dropdown border border-dropdown-line rounded-xl shadow-lg overflow-hidden overflow-y-auto">
                                            <option value="" @selected($sortNama === '')>Tanpa Urutan Nama (-)</option>
                                            <option value="nama_asc" @selected($sortNama === 'nama_asc')>Nama A - Z</option>
                                            <option value="nama_desc" @selected($sortNama === 'nama_desc')>Nama Z - A</option>
                                        </x-preline-select>
                                    </div>

                                    <div>
                                        <label for="filter-kampanye-id" class="form-label text-xs font-semibold mb-1.5">Kategori / Program Donasi</label>
                                        <x-preline-select
                                            id="filter-kampanye-id"
                                            name="kampanye_id"
                                            placeholder="Semua Program"
                                            toggle-classes="hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2 ps-3.5 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-layer border border-border rounded-xl text-start text-xs sm:text-sm text-foreground shadow-2xs focus:outline-hidden focus:border-primary focus:ring-2 focus:ring-primary/20"
                                            dropdown-classes="mt-2 z-[90] w-full max-h-60 p-1 space-y-0.5 bg-dropdown border border-dropdown-line rounded-xl shadow-lg overflow-hidden overflow-y-auto">
                                            <option value="" @selected($kampanyeFilter === '')>Semua Program</option>
                                            <option value="umum" @selected($kampanyeFilter === 'umum')>Donasi Umum</option>
                                            @foreach($kampanyeList as $kampanye)
                                                <option value="{{ $kampanye->id }}" @selected((string) $kampanyeFilter === (string) $kampanye->id)>{{ $kampanye->judul }}</option>
                                            @endforeach
                                        </x-preline-select>
                                    </div>

                                    <div class="pt-1">
                                        <button type="submit" class="btn btn-primary btn-sm rounded-xl cursor-pointer">
                                            Terapkan Filter
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Column Manager Popover -->
                    <div class="hs-dropdown [--auto-close:inside] relative inline-flex [--placement:bottom-right]">
                        <button id="hs-column-manager-dropdown"
                                type="button"
                                :class="Object.values(cols).some(v => !v) ? 'bg-brand-50 text-brand-800 border-brand-300' : 'bg-card text-foreground border-border hover:bg-muted'"
                                class="hs-dropdown-toggle inline-flex items-center gap-1.5 rounded-xl border px-3 py-2 text-xs font-semibold transition cursor-pointer whitespace-nowrap shadow-2xs"
                                aria-haspopup="menu"
                                aria-expanded="false"
                                aria-label="Atur kolom tabel">
                            <svg class="w-3.5 h-3.5 text-muted-foreground-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 4.5v15m6-15v15m-10.875 0h15.75c.621 0 1.125-.504 1.125-1.125V5.625c0-.621-.504-1.125-1.125-1.125H4.125C3.504 4.5 3 5.004 3 5.625v12.75c0 .621.504 1.125 1.125 1.125Z" />
                            </svg>
                            <span>Column Manager</span>
                            <template x-if="Object.values(cols).some(v => !v)">
                                <span class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-full bg-brand-700 text-white text-[10px] font-bold leading-none"
                                      x-text="Object.values(cols).filter(Boolean).length"></span>
                            </template>
                        </button>

                        <div id="hs-column-manager-menu"
                             class="hs-dropdown-menu hs-dropdown-open:opacity-100 hidden w-64 sm:w-72 max-w-[calc(100vw-2rem)] rounded-2xl border border-border bg-overlay p-4 opacity-0 shadow-xl transition-[opacity,margin] duration-150 z-40"
                             role="menu"
                             aria-labelledby="hs-column-manager-dropdown">
                            <div class="flex items-center justify-between pb-3 border-b border-border-line-1">
                                <span class="text-sm font-bold text-foreground">Column Manager</span>
                                <button type="button"
                                        @click="resetColumns()"
                                        class="text-xs font-semibold text-danger hover:underline cursor-pointer">
                                    Reset
                                </button>
                            </div>

                            <div class="mt-3 grid grid-cols-1 gap-1.5 text-xs">
                                @php
                                    $columnOptions = [
                                        'id_donasi' => 'ID Donasi',
                                        'donatur' => 'Donatur',
                                        'kontak' => 'Kontak',
                                        'email' => 'Email',
                                        'kategori' => 'Kategori',
                                        'nominal' => 'Nominal',
                                        'doa' => 'Doa & Harapan',
                                        'bukti' => 'Bukti Transfer',
                                        'status' => 'Status',
                                        'aksi' => 'Aksi',
                                    ];
                                @endphp
                                @foreach($columnOptions as $colKey => $colLabel)
                                    <label class="flex items-center gap-2.5 p-1.5 rounded-lg hover:bg-brand-50/70 cursor-pointer transition select-none">
                                        <input type="checkbox" x-model="cols.{{ $colKey }}" class="peer sr-only">
                                        <span class="w-4 h-4 rounded-md border flex items-center justify-center transition shrink-0"
                                              :class="cols.{{ $colKey }} ? 'bg-brand-700 border-brand-700 text-white shadow-2xs' : 'bg-layer border-border text-transparent'">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                            </svg>
                                        </span>
                                        <span class="font-medium text-foreground">{{ $colLabel }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= DATA CONTAINER (AJAX SWAPPED) ================= -->
        <div data-admin-filter-results class="space-y-3.5">
            <!-- 1. Tampilan Desktop & Tablet (md ke atas) -->
            <div class="hidden md:block bg-card rounded-2xl border border-border/70 shadow-2xs overflow-x-auto w-full">
                <table class="w-full text-left text-xs" :class="Object.values(cols).filter(Boolean).length >= 8 ? 'min-w-[1120px]' : ''">
                    <thead>
                        <tr class="bg-brand-100 text-muted-foreground-2 font-bold uppercase text-2xs tracking-wider border-b border-brand-200">
                            <th x-show="cols.id_donasi" class="py-3.5 px-4 whitespace-nowrap">ID DONASI</th>
                            <th x-show="cols.donatur" class="py-3.5 px-4">DONATUR</th>
                            <th x-show="cols.kontak" class="py-3.5 px-4">KONTAK</th>
                            <th x-show="cols.email" class="py-3.5 px-4">EMAIL</th>
                            <th x-show="cols.kategori" class="py-3.5 px-4">KATEGORI</th>
                            <th x-show="cols.nominal" class="py-3.5 px-4 whitespace-nowrap">NOMINAL</th>
                            <th x-show="cols.doa" class="py-3.5 px-4">DOA &amp; HARAPAN</th>
                            <th x-show="cols.bukti" class="py-3.5 px-3 text-center whitespace-nowrap">BUKTI TRANSFER</th>
                            <th x-show="cols.status" class="py-3.5 px-3 text-center whitespace-nowrap">STATUS</th>
                            <th x-show="cols.aksi" class="py-3.5 px-4 text-center whitespace-nowrap">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-line-1">
                        @forelse($donasiList as $donasi)
                            @php
                                $proofUrl = $donasi->bukti_transfer_path ? route('admin.donasi.bukti-transfer.show', $donasi) : null;
                                $donaturName = filled($donasi->nama_donatur) ? $donasi->nama_donatur : 'Hamba Allah';
                                $kodeDonasi = '#'.($donasi->kode_donasi ?: ('DNS-'.str_pad((string) $donasi->id, 4, '0', STR_PAD_LEFT)));
                            @endphp
                            <tr class="hover:bg-muted/60 transition align-middle">
                                <!-- ID DONASI -->
                                <td x-show="cols.id_donasi" class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="font-mono font-bold text-foreground">{{ $kodeDonasi }}</span>
                                    <span class="block text-[10px] text-muted-foreground mt-0.5">{{ $donasi->created_at?->format('d M Y, H:i') }}</span>
                                </td>

                                <!-- DONATUR -->
                                <td x-show="cols.donatur" class="py-3.5 px-4">
                                    <div class="flex items-center gap-2.5 min-w-0 max-w-[180px]">
                                        <div class="w-8 h-8 rounded-full bg-brand-900 text-white flex items-center justify-center font-bold text-[11px] shrink-0">
                                            {{ strtoupper(mb_substr($donaturName, 0, 2)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <span class="font-bold text-foreground text-xs sm:text-sm truncate block">{{ $donaturName }}</span>
                                            <span class="text-[10px] text-muted-foreground-1 truncate block">{{ $donasi->metode_pembayaran ?: '-' }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- KONTAK -->
                                <td x-show="cols.kontak" class="py-3.5 px-4 whitespace-nowrap font-medium text-foreground">
                                    {{ $donasi->no_whatsapp ?: '-' }}
                                </td>

                                <!-- EMAIL -->
                                <td x-show="cols.email" class="py-3.5 px-4 max-w-[170px] truncate text-muted-foreground-2">
                                    {{ $donasi->email ?: '-' }}
                                </td>

                                <!-- KATEGORI (Program Donasi) -->
                                <td x-show="cols.kategori" class="py-3.5 px-4 max-w-[180px]">
                                    <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-semibold bg-brand-100 text-brand-900 truncate max-w-full" title="{{ $donasi->kampanye ? $donasi->kampanye->judul : 'Donasi Umum' }}">
                                        {{ $donasi->kampanye ? $donasi->kampanye->judul : 'Donasi Umum' }}
                                    </span>
                                </td>

                                <!-- NOMINAL -->
                                <td x-show="cols.nominal" class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="font-extrabold text-sm text-brand-900 tabular">
                                        Rp {{ number_format($donasi->nominal, 0, ',', '.') }}
                                    </span>
                                </td>

                                <!-- DOA & HARAPAN -->
                                <td x-show="cols.doa" class="py-3.5 px-4 max-w-[200px]">
                                    <p class="text-xs text-muted-foreground-2 line-clamp-2 break-words" title="{{ $donasi->pesan_doa ?: '' }}">
                                        {{ $donasi->pesan_doa ?: '-' }}
                                    </p>
                                </td>

                                <!-- BUKTI TRANSFER -->
                                <td x-show="cols.bukti" class="py-3.5 px-3 text-center">
                                    @if($proofUrl)
                                        <button type="button"
                                                data-hs-overlay="#proof-preview-modal"
                                                aria-haspopup="dialog"
                                                aria-controls="proof-preview-modal"
                                                @click="$dispatch('open-bukti-modal', { src: @js($proofUrl), nama: @js($donaturName), nominal: @js('Rp ' . number_format($donasi->nominal, 0, ',', '.')) })"
                                                class="group inline-flex items-center justify-center w-9 h-10 rounded-lg overflow-hidden border border-border shadow-2xs hover:border-brand-700 transition bg-muted cursor-pointer"
                                                title="Lihat Bukti Transfer">
                                            <img src="{{ $proofUrl }}" alt="Bukti Transfer {{ $donaturName }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                                        </button>
                                    @else
                                        <span class="text-xs text-muted-foreground italic">-</span>
                                    @endif
                                </td>

                                <!-- STATUS -->
                                <td x-show="cols.status" class="py-3.5 px-3 text-center whitespace-nowrap">
                                    @if($donasi->status === 'verified')
                                        <span class="badge badge-success">Terverifikasi</span>
                                    @elseif($donasi->status === 'rejected')
                                        <span class="badge badge-danger">Ditolak</span>
                                    @else
                                        <span class="badge badge-warning">Menunggu</span>
                                    @endif
                                </td>

                                <!-- AKSI: VERIFIKASI & TOLAK (Saat Pending) / EDIT STATUS (Saat Sudah Terverifikasi atau Ditolak) -->
                                <td x-show="cols.aksi" class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center justify-center gap-1.5">
                                        @if($donasi->status === 'pending')
                                            <button type="button"
                                                    data-hs-overlay="#modal-konfirmasi-aksi"
                                                    aria-haspopup="dialog"
                                                    aria-controls="modal-konfirmasi-aksi"
                                                    @click="$dispatch('open-confirm-modal', {
                                                        type: 'verifikasi',
                                                        title: 'Verifikasi Donasi',
                                                        message: 'Pastikan bukti pembayaran QRIS sudah valid dan dana telah diterima sebelum memverifikasi donasi ini.',
                                                        id: @js($kodeDonasi),
                                                        nama: @js($donaturName),
                                                        nominal: @js('Rp ' . number_format($donasi->nominal, 0, ',', '.')),
                                                        actionUrl: @js(route('admin.kelola-donasi.verifikasi', $donasi->id)),
                                                        confirmText: 'Ya, Verifikasi'
                                                    })"
                                                    class="px-3 py-1 rounded-lg text-xs font-bold border border-success/40 bg-success-soft text-success hover:bg-brand-700 hover:text-white transition cursor-pointer">
                                                Verifikasi
                                            </button>

                                            <button type="button"
                                                    data-hs-overlay="#modal-konfirmasi-aksi"
                                                    aria-haspopup="dialog"
                                                    aria-controls="modal-konfirmasi-aksi"
                                                    @click="$dispatch('open-confirm-modal', {
                                                        type: 'tolak',
                                                        title: 'Tolak Donasi',
                                                        message: 'Donasi yang ditolak tidak akan dihitung ke dalam dana terkumpul kampanye.',
                                                        id: @js($kodeDonasi),
                                                        nama: @js($donaturName),
                                                        nominal: @js('Rp ' . number_format($donasi->nominal, 0, ',', '.')),
                                                        actionUrl: @js(route('admin.kelola-donasi.tolak', $donasi->id)),
                                                        confirmText: 'Ya, Tolak Donasi'
                                                    })"
                                                    class="px-3 py-1 rounded-lg text-xs font-bold border border-danger/40 bg-danger-soft text-danger hover:bg-danger hover:text-white transition cursor-pointer">
                                                Tolak
                                            </button>
                                        @else
                                            <button type="button"
                                                    data-hs-overlay="#modal-edit-status-donasi"
                                                    aria-haspopup="dialog"
                                                    aria-controls="modal-edit-status-donasi"
                                                    @click="$dispatch('open-edit-status-modal', { id: @js($kodeDonasi), nama: @js($donaturName), nominal: @js('Rp ' . number_format($donasi->nominal, 0, ',', '.')), status: @js($donasi->status), verifikasiUrl: @js(route('admin.kelola-donasi.verifikasi', $donasi->id)), tolakUrl: @js(route('admin.kelola-donasi.tolak', $donasi->id)) })"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold border border-border bg-surface text-foreground hover:border-brand-700 hover:bg-brand-50 hover:text-brand-800 transition cursor-pointer">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                </svg>
                                                <span>Edit</span>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="py-12 text-center text-muted-foreground text-sm">
                                    Tidak ada data donasi yang sesuai dengan filter atau pencarian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- 2. Tampilan Mobile (< md) -->
            <div class="md:hidden space-y-3.5">
                @forelse($donasiList as $donasi)
                    @php
                        $proofUrl = $donasi->bukti_transfer_path ? route('admin.donasi.bukti-transfer.show', $donasi) : null;
                        $donaturName = filled($donasi->nama_donatur) ? $donasi->nama_donatur : 'Hamba Allah';
                        $kodeDonasi = '#'.($donasi->kode_donasi ?: ('DNS-'.str_pad((string) $donasi->id, 4, '0', STR_PAD_LEFT)));
                    @endphp
                    <div class="bg-card rounded-2xl border border-border shadow-2xs p-4 space-y-3 transition hover:border-brand-700">
                        <!-- Baris 1: ID, Avatar, Nama Donatur, & Status -->
                        <div class="flex items-start justify-between gap-2.5">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-full bg-brand-900 text-white flex items-center justify-center font-bold text-xs shrink-0">
                                    {{ strtoupper(mb_substr($donaturName, 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[10px] font-mono font-bold text-muted-foreground block">{{ $kodeDonasi }}</span>
                                    <p class="font-bold text-foreground text-sm truncate leading-snug">{{ $donaturName }}</p>
                                    <p class="text-xs text-muted-foreground-1 font-medium truncate mt-0.5">{{ $donasi->email ?: '-' }}</p>
                                </div>
                            </div>
                            <div class="shrink-0">
                                @if($donasi->status === 'verified')
                                    <span class="badge badge-success">Terverifikasi</span>
                                @elseif($donasi->status === 'rejected')
                                    <span class="badge badge-danger">Ditolak</span>
                                @else
                                    <span class="badge badge-warning">Menunggu</span>
                                @endif
                            </div>
                        </div>

                        <!-- Baris 2: Kategori / Program & Kontak WA -->
                        <div class="flex flex-wrap items-center justify-between gap-2 text-xs text-muted-foreground-2 bg-muted px-3 py-2 rounded-xl border border-border-line-1">
                            <span class="font-medium truncate">{{ $donasi->no_whatsapp ?: '-' }}</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-brand-100 text-brand-900 truncate max-w-[180px]">
                                {{ $donasi->kampanye ? $donasi->kampanye->judul : 'Donasi Umum' }}
                            </span>
                        </div>

                        @if($donasi->pesan_doa)
                            <div class="p-2.5 rounded-xl bg-muted/60 border border-border-line-1 text-xs text-muted-foreground-2 italic">
                                &ldquo;{{ $donasi->pesan_doa }}&rdquo;
                            </div>
                        @endif

                        <!-- Baris 3: Nominal, Bukti Transfer, & Tombol Verifikasi/Tolak -->
                        <div class="flex items-center justify-between gap-2 pt-2 border-t border-border-line-1">
                            <div>
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-muted-foreground">NOMINAL</span>
                                <span class="font-extrabold text-base text-brand-900 leading-tight tabular">
                                    Rp {{ number_format($donasi->nominal, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="inline-flex items-center gap-1.5 shrink-0">
                                @if($proofUrl)
                                    <button type="button"
                                            data-hs-overlay="#proof-preview-modal"
                                            aria-haspopup="dialog"
                                            aria-controls="proof-preview-modal"
                                            @click="$dispatch('open-bukti-modal', { src: @js($proofUrl), nama: @js($donaturName), nominal: @js('Rp ' . number_format($donasi->nominal, 0, ',', '.')) })"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl border border-border bg-surface text-xs font-semibold text-brand-700 hover:bg-muted transition cursor-pointer"
                                            title="Lihat Bukti">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>Bukti</span>
                                    </button>
                                @endif

                                @if($donasi->status === 'pending')
                                    <button type="button"
                                            data-hs-overlay="#modal-konfirmasi-aksi"
                                            aria-haspopup="dialog"
                                            aria-controls="modal-konfirmasi-aksi"
                                            @click="$dispatch('open-confirm-modal', {
                                                type: 'verifikasi',
                                                title: 'Verifikasi Donasi',
                                                message: 'Pastikan bukti pembayaran QRIS sudah valid dan dana telah diterima sebelum memverifikasi donasi ini.',
                                                id: @js($kodeDonasi),
                                                nama: @js($donaturName),
                                                nominal: @js('Rp ' . number_format($donasi->nominal, 0, ',', '.')),
                                                actionUrl: @js(route('admin.kelola-donasi.verifikasi', $donasi->id)),
                                                confirmText: 'Ya, Verifikasi'
                                            })"
                                            class="px-3 py-1.5 rounded-xl text-xs font-bold border border-success/40 bg-success-soft text-success hover:bg-brand-700 hover:text-white transition cursor-pointer">
                                        Verifikasi
                                    </button>

                                    <button type="button"
                                            data-hs-overlay="#modal-konfirmasi-aksi"
                                            aria-haspopup="dialog"
                                            aria-controls="modal-konfirmasi-aksi"
                                            @click="$dispatch('open-confirm-modal', {
                                                type: 'tolak',
                                                title: 'Tolak Donasi',
                                                message: 'Donasi yang ditolak tidak akan dihitung ke dalam dana terkumpul kampanye.',
                                                id: @js($kodeDonasi),
                                                nama: @js($donaturName),
                                                nominal: @js('Rp ' . number_format($donasi->nominal, 0, ',', '.')),
                                                actionUrl: @js(route('admin.kelola-donasi.tolak', $donasi->id)),
                                                confirmText: 'Ya, Tolak Donasi'
                                            })"
                                            class="px-3 py-1.5 rounded-xl text-xs font-bold border border-danger/40 bg-danger-soft text-danger hover:bg-danger hover:text-white transition cursor-pointer">
                                        Tolak
                                    </button>
                                @else
                                    <button type="button"
                                            data-hs-overlay="#modal-edit-status-donasi"
                                            aria-haspopup="dialog"
                                            aria-controls="modal-edit-status-donasi"
                                            @click="$dispatch('open-edit-status-modal', { id: @js($kodeDonasi), nama: @js($donaturName), nominal: @js('Rp ' . number_format($donasi->nominal, 0, ',', '.')), status: @js($donasi->status), verifikasiUrl: @js(route('admin.kelola-donasi.verifikasi', $donasi->id)), tolakUrl: @js(route('admin.kelola-donasi.tolak', $donasi->id)) })"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold border border-border bg-surface text-foreground hover:border-brand-700 hover:bg-brand-50 hover:text-brand-800 transition cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                        <span>Edit</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-card rounded-2xl border border-border py-10 text-center text-muted-foreground text-xs">
                        Tidak ada data donasi yang sesuai dengan filter atau pencarian.
                    </div>
                @endforelse
            </div>

            <!-- Card Footer: Pagination (Penuh & Tanpa Reload Halaman) -->
            <div class="bg-card rounded-2xl border border-border/70 p-4 sm:p-4.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs sm:text-sm text-muted-foreground-1 shadow-2xs">
                <div class="font-medium text-center sm:text-left text-muted-foreground-2">
                    Menampilkan {{ $donasiList->firstItem() ?? 0 }}-{{ $donasiList->lastItem() ?? 0 }} dari {{ $donasiList->total() }} Data Donasi
                </div>

                @if ($donasiList->hasPages())
                    <nav class="flex items-center justify-center sm:justify-end gap-1.5 flex-wrap">
                        @if ($donasiList->onFirstPage())
                            <span class="w-8 h-8 rounded-lg flex items-center justify-center text-muted-foreground cursor-not-allowed">&lt;</span>
                        @else
                            <a href="{{ $donasiList->previousPageUrl() }}"
                               class="w-8 h-8 rounded-lg flex items-center justify-center text-muted-foreground-2 hover:bg-muted hover:text-foreground transition">&lt;</a>
                        @endif

                        @php
                            $startPage = max(1, $donasiList->currentPage() - 2);
                            $endPage = min($donasiList->lastPage(), $donasiList->currentPage() + 2);
                        @endphp
                        @foreach ($donasiList->getUrlRange($startPage, $endPage) as $page => $url)
                            @if ($page == $donasiList->currentPage())
                                <span class="w-8 h-8 rounded-lg bg-brand-700 text-white flex items-center justify-center font-bold text-xs sm:text-sm shadow-2xs">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}"
                                   class="w-8 h-8 rounded-lg text-muted-foreground-2 hover:bg-muted flex items-center justify-center font-medium text-xs sm:text-sm transition">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if ($donasiList->hasMorePages())
                            <a href="{{ $donasiList->nextPageUrl() }}"
                               class="w-8 h-8 rounded-lg flex items-center justify-center text-muted-foreground-2 hover:bg-muted hover:text-foreground transition">&gt;</a>
                        @else
                            <span class="w-8 h-8 rounded-lg flex items-center justify-center text-muted-foreground cursor-not-allowed">&gt;</span>
                        @endif
                    </nav>
                @endif
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL: LIHAT BUKTI TRANSFER (Preline Overlay)                 --}}
    {{-- ============================================================ --}}
    <div id="proof-preview-modal"
         class="hs-overlay hidden size-full fixed top-0 inset-s-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none"
         role="dialog"
         tabindex="-1"
         aria-labelledby="proof-preview-modal-label">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-md sm:w-full m-3 sm:mx-auto">
            <div class="relative bg-overlay rounded-2xl shadow-2xl border border-overlay-line overflow-hidden w-full flex flex-col max-h-[90vh] pointer-events-auto">
                <div class="px-5 py-3.5 border-b border-border-line-1 flex items-center justify-between bg-muted/60">
                    <div class="min-w-0 pr-3">
                        <h3 id="proof-preview-modal-label" class="font-bold text-sm text-foreground leading-tight">Bukti Transfer</h3>
                        <p class="text-xs text-muted-foreground-1 font-medium mt-0.5 truncate">
                            <span x-text="previewDonatur" class="font-semibold text-foreground"></span> &bull; <span x-text="previewNominal" class="font-bold text-brand-700 tabular"></span>
                        </p>
                    </div>
                    <button type="button"
                            data-hs-overlay="#proof-preview-modal"
                            class="size-8 inline-flex justify-center items-center rounded-full bg-surface border border-surface-line text-surface-foreground hover:bg-surface-hover transition shrink-0 cursor-pointer"
                            aria-label="Tutup">
                        <span class="sr-only">Tutup</span>
                        <svg class="shrink-0 size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-4 overflow-y-auto flex items-center justify-center bg-muted/60">
                    <img :src="previewSrc"
                         alt="Bukti Transfer Donasi"
                         class="max-h-[60vh] sm:max-h-[65vh] w-auto rounded-xl border border-border shadow-xs object-contain bg-card">
                </div>

                <div class="px-5 py-3 border-t border-border-line-1 flex items-center justify-between text-xs bg-overlay">
                    <a :href="previewSrc" target="_blank" rel="noopener"
                       class="btn btn-primary btn-sm rounded-xl">
                        Buka / Unduh
                    </a>
                    <button type="button"
                            data-hs-overlay="#proof-preview-modal"
                            class="btn btn-ghost btn-sm cursor-pointer">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL: UBAH STATUS VERIFIKASI DONASI (Preline Overlay)        --}}
    {{-- ============================================================ --}}
    <div id="modal-edit-status-donasi"
         class="hs-overlay hidden size-full fixed top-0 inset-s-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none"
         role="dialog"
         tabindex="-1"
         aria-labelledby="modal-edit-status-donasi-label">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-md sm:w-full m-3 sm:mx-auto">
            <div class="relative bg-overlay rounded-2xl shadow-xl w-full border border-overlay-line overflow-hidden pointer-events-auto">
                <div class="px-5 py-4 border-b border-border-line-1 flex items-center justify-between">
                    <div>
                        <h3 id="modal-edit-status-donasi-label" class="font-bold text-foreground text-sm sm:text-base">
                            Ubah Status Verifikasi Donasi
                        </h3>
                        <p class="text-xs text-muted-foreground-2 mt-0.5">Koreksi keputusan verifikasi atau penolakan transaksi.</p>
                    </div>
                    <button type="button"
                            data-hs-overlay="#modal-edit-status-donasi"
                            class="size-8 inline-flex justify-center items-center rounded-full bg-surface border border-surface-line text-surface-foreground hover:bg-surface-hover transition shrink-0 cursor-pointer"
                            aria-label="Tutup">
                        <span class="sr-only">Tutup</span>
                        <svg class="shrink-0 size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form method="POST" :action="editDonasi.status === 'verified' ? editDonasi.verifikasiUrl : editDonasi.tolakUrl" class="p-5 space-y-4">
                    @csrf

                    <!-- Ringkasan Transaksi -->
                    <div class="p-3.5 rounded-xl bg-muted/60 border border-border-line-1 space-y-1">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-mono font-bold text-brand-800" x-text="editDonasi.id"></span>
                            <span class="font-extrabold text-brand-900 tabular" x-text="editDonasi.nominal"></span>
                        </div>
                        <p class="text-sm font-bold text-foreground" x-text="editDonasi.nama"></p>
                    </div>

                    <!-- Pilihan Status -->
                    <div class="space-y-2.5">
                        <span class="form-label text-xs font-semibold mb-1">Pilih Status Terbaru</span>

                        <label class="flex items-start gap-3 p-3.5 rounded-xl border cursor-pointer transition select-none"
                               :class="editDonasi.status === 'verified' ? 'border-brand-700 bg-brand-50/70 shadow-2xs' : 'border-border bg-card hover:bg-muted/50'">
                            <input type="radio"
                                   name="status_pilihan"
                                   value="verified"
                                   x-model="editDonasi.status"
                                   class="peer sr-only">
                            <span class="mt-0.5 w-4 h-4 rounded-full border-2 flex items-center justify-center shrink-0 transition"
                                  :class="editDonasi.status === 'verified' ? 'border-brand-700 bg-brand-700' : 'border-border bg-layer'">
                                <span class="w-1.5 h-1.5 rounded-full bg-white transition"
                                      :class="editDonasi.status === 'verified' ? 'scale-100 opacity-100' : 'scale-0 opacity-0'"></span>
                            </span>
                            <div class="min-w-0">
                                <span class="block text-xs font-bold text-foreground">Terverifikasi</span>
                                <span class="block text-[11px] text-muted-foreground-1 mt-0.5">Dana telah diterima dan masuk dalam total kampanye.</span>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 p-3.5 rounded-xl border cursor-pointer transition select-none"
                               :class="editDonasi.status === 'rejected' ? 'border-danger bg-danger-soft/40 shadow-2xs' : 'border-border bg-card hover:bg-muted/50'">
                            <input type="radio"
                                   name="status_pilihan"
                                   value="rejected"
                                   x-model="editDonasi.status"
                                   class="peer sr-only">
                            <span class="mt-0.5 w-4 h-4 rounded-full border-2 flex items-center justify-center shrink-0 transition"
                                  :class="editDonasi.status === 'rejected' ? 'border-danger bg-danger' : 'border-border bg-layer'">
                                <span class="w-1.5 h-1.5 rounded-full bg-white transition"
                                      :class="editDonasi.status === 'rejected' ? 'scale-100 opacity-100' : 'scale-0 opacity-0'"></span>
                            </span>
                            <div class="min-w-0">
                                <span class="block text-xs font-bold text-foreground">Ditolak</span>
                                <span class="block text-[11px] text-muted-foreground-1 mt-0.5">Bukti tidak valid atau dana tidak masuk; otomatis mengurangi nominal kampanye.</span>
                            </div>
                        </label>
                    </div>

                    <div class="pt-3 border-t border-border-line-1 flex items-center justify-end gap-2">
                        <button type="button"
                                data-hs-overlay="#modal-edit-status-donasi"
                                class="btn btn-secondary btn-sm rounded-xl cursor-pointer">
                            Batal
                        </button>
                        <button type="submit"
                                class="btn btn-primary btn-sm rounded-xl cursor-pointer">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL: KONFIRMASI VERIFIKASI / TOLAK DONASI (Preline Overlay) --}}
    {{-- ============================================================ --}}
    <div id="modal-konfirmasi-aksi"
         class="hs-overlay hidden size-full fixed top-0 inset-s-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none"
         role="dialog"
         tabindex="-1"
         aria-labelledby="modal-konfirmasi-aksi-label">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-md sm:w-full m-3 sm:mx-auto">
            <div class="relative bg-overlay rounded-2xl shadow-2xl border border-overlay-line overflow-hidden w-full pointer-events-auto">
                <div class="px-5 py-4 border-b border-border-line-1 flex items-center justify-between">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <span class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
                              :class="confirmModal.type === 'tolak' ? 'bg-danger-soft text-danger' : 'bg-brand-100 text-brand-700'">
                            <svg x-show="confirmModal.type !== 'tolak'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <svg x-show="confirmModal.type === 'tolak'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </span>
                        <h3 id="modal-konfirmasi-aksi-label" class="font-bold text-foreground text-sm sm:text-base truncate" x-text="confirmModal.title"></h3>
                    </div>
                    <button type="button"
                            data-hs-overlay="#modal-konfirmasi-aksi"
                            class="size-8 inline-flex justify-center items-center rounded-full bg-surface border border-surface-line text-surface-foreground hover:bg-surface-hover transition shrink-0 cursor-pointer"
                            aria-label="Tutup">
                        <span class="sr-only">Tutup</span>
                        <svg class="shrink-0 size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form method="POST" :action="confirmModal.actionUrl" class="p-5 space-y-4">
                    @csrf

                    <!-- Ringkasan Transaksi -->
                    <div class="p-3.5 rounded-xl bg-muted/60 border border-border-line-1 space-y-1.5">
                        <div class="flex items-center justify-between text-xs gap-2">
                            <span class="badge"
                                  :class="confirmModal.type === 'tolak' ? 'badge-danger' : 'badge-success'"
                                  x-text="confirmModal.type === 'tolak' ? 'Penolakan Donasi' : 'Verifikasi Donasi'"></span>
                            <span class="font-mono font-bold text-brand-800" x-text="confirmModal.id"></span>
                        </div>
                        <div class="flex items-baseline justify-between gap-2 pt-0.5">
                            <p class="text-sm font-bold text-foreground truncate" x-text="confirmModal.nama"></p>
                            <span class="text-sm font-extrabold text-brand-900 tabular shrink-0" x-text="confirmModal.nominal"></span>
                        </div>
                    </div>

                    <!-- Penjelasan Konfirmasi (.alert) -->
                    <div class="alert text-xs leading-relaxed"
                         :class="confirmModal.type === 'tolak' ? 'alert-danger' : 'alert-info'">
                        <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                        <p x-text="confirmModal.message"></p>
                    </div>

                    <div class="pt-2 border-t border-border-line-1 flex items-center justify-end gap-2">
                        <button type="button"
                                data-hs-overlay="#modal-konfirmasi-aksi"
                                class="btn btn-secondary btn-sm rounded-xl cursor-pointer">
                            Batal
                        </button>
                        <button type="submit"
                                :class="confirmModal.type === 'tolak' ? 'btn btn-danger btn-sm rounded-xl cursor-pointer' : 'btn btn-primary btn-sm rounded-xl cursor-pointer'"
                                x-text="confirmModal.confirmText">
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL: CRUD PENYALURAN DANA & KLASTER ASNAF (Preline Overlay) --}}
    {{-- ============================================================ --}}
    <div id="modal-penyaluran"
         class="hs-overlay hidden size-full fixed top-0 inset-s-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none"
         role="dialog"
         tabindex="-1"
         aria-labelledby="penyaluran-modal-title">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-3xl sm:w-full m-3 sm:mx-auto">
            <div class="relative bg-overlay rounded-2xl shadow-xl w-full border border-overlay-line overflow-hidden pointer-events-auto">
                <div class="flex items-center justify-between px-5 py-4 border-b border-border">
                    <div>
                        <h3 id="penyaluran-modal-title" class="text-sm font-bold text-foreground">Kelola Penyaluran Dana &amp; Klaster Asnaf</h3>
                        <p class="text-xs text-muted-foreground-2 mt-0.5">Tambah, perbarui, atau hapus realisasi penyaluran program per klaster asnaf.</p>
                    </div>
                    <button type="button" data-hs-overlay="#modal-penyaluran" class="text-muted-foreground hover:text-foreground transition text-xl leading-none cursor-pointer">&times;</button>
                </div>

                <div class="p-5 space-y-5 max-h-[75vh] overflow-y-auto custom-scrollbar">
                    <form id="form-penyaluran" method="POST" action="{{ route('admin.penyaluran-dana.store') }}" novalidate onsubmit="return validatePenyaluranForm(event)" class="p-4 rounded-xl border border-border bg-muted/40 space-y-3">
                        @csrf
                        <input type="hidden" name="_method" id="penyaluran-method" value="POST">

                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <span id="penyaluran-form-label" class="text-xs font-bold uppercase tracking-wider text-brand-800">Tambah Penyaluran Baru</span>
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-semibold text-brand-800 bg-brand-50 border border-brand-200 px-2.5 py-0.5 rounded-lg tabular">
                                    Sisa Saldo Kas: Rp {{ number_format(max(0, $metrics['saldo_kas']), 0, ',', '.') }}
                                </span>
                                <button type="button" id="btn-reset-penyaluran" onclick="resetPenyaluranForm()" class="hidden text-xs font-semibold text-muted-foreground hover:text-foreground cursor-pointer">
                                    Batal Edit
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label for="penyaluran-klaster" class="form-label text-xs font-semibold mb-1">Klaster Asnaf <span class="text-danger">*</span></label>
                                <x-preline-select
                                    id="penyaluran-klaster"
                                    name="klaster_asnaf"
                                    placeholder="Pilih klaster asnaf"
                                    toggle-classes="hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2 ps-3.5 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-layer border border-border rounded-xl text-start text-xs sm:text-sm text-foreground shadow-2xs focus:outline-hidden focus:border-primary focus:ring-2 focus:ring-primary/20"
                                    dropdown-classes="mt-2 z-[90] w-full max-h-60 p-1 space-y-0.5 bg-dropdown border border-dropdown-line rounded-xl shadow-lg overflow-hidden overflow-y-auto">
                                    @foreach($klasterLabels as $key => $label)
                                        <option value="{{ $key }}" @selected($loop->first)>{{ $label }}</option>
                                    @endforeach
                                </x-preline-select>
                                <p id="err-penyaluran-klaster" class="form-error hidden"></p>
                            </div>
                            <div>
                                <label for="penyaluran-nominal" class="form-label text-xs font-semibold mb-1">Nominal (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="nominal" id="penyaluran-nominal" min="1000" max="{{ max(0, (int) $metrics['saldo_kas']) }}" step="1000" class="form-input py-2 text-xs sm:text-sm tabular" placeholder="Contoh: 500000">
                                <p class="form-hint text-[11px]">Min. Rp 1.000 &bull; Maks. sesuai sisa saldo kas</p>
                                <p id="err-penyaluran-nominal" class="form-error hidden"></p>
                            </div>
                            <div>
                                <label for="penyaluran-tanggal" class="form-label text-xs font-semibold mb-1">Tanggal Penyaluran <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_penyaluran" id="penyaluran-tanggal" value="{{ now()->format('Y-m-d') }}" class="form-input py-2 text-xs sm:text-sm">
                                <p id="err-penyaluran-tanggal" class="form-error hidden"></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label for="penyaluran-keterangan" class="form-label text-xs font-semibold mb-1">Keterangan Program <span class="text-danger">*</span></label>
                                <input type="text" name="keterangan" id="penyaluran-keterangan" placeholder="Contoh: Beasiswa semester ganjil" class="form-input py-2 text-xs sm:text-sm">
                                <p id="err-penyaluran-keterangan" class="form-error hidden"></p>
                            </div>
                            <div>
                                <label for="penyaluran-penerima" class="form-label text-xs font-semibold mb-1">Penerima Manfaat</label>
                                <input type="text" name="penerima_manfaat" id="penyaluran-penerima" placeholder="Contoh: 25 Anak Asuh" class="form-input py-2 text-xs sm:text-sm">
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" id="penyaluran-submit-btn" class="btn btn-primary btn-sm rounded-xl cursor-pointer">
                                Simpan Penyaluran
                            </button>
                        </div>
                    </form>

                    <div class="space-y-2">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-muted-foreground-1">Riwayat Penyaluran Dana ({{ $penyaluranList->count() }})</h4>
                        <div class="rounded-xl border border-border divide-y divide-border-line-1 overflow-hidden">
                            @forelse($penyaluranList as $item)
                                <div x-data="{ confirmDelete: false }" class="p-3 bg-card hover:bg-muted/40 transition text-xs">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2">
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-brand-100 text-brand-900">
                                                    {{ $klasterLabels[$item->klaster_asnaf] ?? ucfirst($item->klaster_asnaf) }}
                                                </span>
                                                <span class="text-muted-foreground">{{ $item->tanggal_penyaluran?->format('d M Y') }}</span>
                                            </div>
                                            <p class="font-bold text-foreground mt-1">{{ $item->keterangan }}</p>
                                            @if($item->penerima_manfaat)
                                                <p class="text-muted-foreground-2 text-[11px]">Penerima: {{ $item->penerima_manfaat }}</p>
                                            @endif
                                        </div>
                                        <div class="flex items-center justify-between sm:justify-end gap-3 shrink-0">
                                            <span class="font-extrabold text-sm text-foreground tabular">Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
                                            <div class="inline-flex items-center gap-1">
                                                <button type="button"
                                                        onclick="editPenyaluran(@js($item->id), @js($item->klaster_asnaf), @js($item->keterangan), @js((float) $item->nominal), @js($item->tanggal_penyaluran?->format('Y-m-d')), @js($item->penerima_manfaat ?? ''))"
                                                        class="p-1.5 rounded-lg text-muted-foreground-2 hover:text-brand-700 hover:bg-brand-50 transition cursor-pointer"
                                                        title="Edit">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" />
                                                    </svg>
                                                </button>
                                                <button type="button"
                                                        @click="confirmDelete = true"
                                                        class="p-1.5 rounded-lg text-muted-foreground-2 hover:text-danger hover:bg-danger-soft transition cursor-pointer"
                                                        title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div x-show="confirmDelete" x-cloak class="mt-2.5 pt-2.5 border-t border-danger/20 bg-danger-soft/50 rounded-xl px-3 py-2 flex flex-wrap items-center justify-between gap-2">
                                        <span class="text-xs font-semibold text-danger">Yakin ingin menghapus data penyaluran ini?</span>
                                        <div class="flex items-center gap-2">
                                            <button type="button" @click="confirmDelete = false" class="btn btn-secondary btn-sm cursor-pointer">Batal</button>
                                            <form method="POST" action="{{ route('admin.penyaluran-dana.destroy', $item->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm cursor-pointer">Ya, Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center text-xs text-muted-foreground">Belum ada riwayat penyaluran dana.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL: CRUD PENGELUARAN OPERASIONAL (Preline Overlay)         --}}
    {{-- ============================================================ --}}
    <div id="modal-operasional"
         class="hs-overlay hidden size-full fixed top-0 inset-s-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none"
         role="dialog"
         tabindex="-1"
         aria-labelledby="operasional-modal-title">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-3xl sm:w-full m-3 sm:mx-auto">
            <div class="relative bg-overlay rounded-2xl shadow-xl w-full border border-overlay-line overflow-hidden pointer-events-auto">
                <div class="flex items-center justify-between px-5 py-4 border-b border-border">
                    <div>
                        <h3 id="operasional-modal-title" class="text-sm font-bold text-foreground">Kelola Pengeluaran Operasional</h3>
                        <p class="text-xs text-muted-foreground-2 mt-0.5">Tambah, perbarui, atau hapus data pengeluaran operasional yayasan.</p>
                    </div>
                    <button type="button" data-hs-overlay="#modal-operasional" class="text-muted-foreground hover:text-foreground transition text-xl leading-none cursor-pointer">&times;</button>
                </div>

                <div class="p-5 space-y-5 max-h-[75vh] overflow-y-auto custom-scrollbar">
                    <form id="form-operasional" method="POST" action="{{ route('admin.pengeluaran-operasional.store') }}" novalidate onsubmit="return validateOperasionalForm(event)" class="p-4 rounded-xl border border-border bg-muted/40 space-y-3">
                        @csrf
                        <input type="hidden" name="_method" id="operasional-method" value="POST">

                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <span id="operasional-form-label" class="text-xs font-bold uppercase tracking-wider text-brand-800">Tambah Pengeluaran Operasional</span>
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-semibold text-brand-800 bg-brand-50 border border-brand-200 px-2.5 py-0.5 rounded-lg tabular">
                                    Sisa Saldo Kas: Rp {{ number_format(max(0, $metrics['saldo_kas']), 0, ',', '.') }}
                                </span>
                                <button type="button" id="btn-reset-operasional" onclick="resetOperasionalForm()" class="hidden text-xs font-semibold text-muted-foreground hover:text-foreground cursor-pointer">
                                    Batal Edit
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label for="operasional-kategori" class="form-label text-xs font-semibold mb-1">Kategori <span class="text-danger">*</span></label>
                                <input type="text" name="kategori" id="operasional-kategori" placeholder="Contoh: Utilitas / Administrasi" class="form-input py-2 text-xs sm:text-sm">
                                <p id="err-operasional-kategori" class="form-error hidden"></p>
                            </div>
                            <div>
                                <label for="operasional-nominal" class="form-label text-xs font-semibold mb-1">Nominal (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="nominal" id="operasional-nominal" min="1000" max="{{ max(0, (int) $metrics['saldo_kas']) }}" step="1000" class="form-input py-2 text-xs sm:text-sm tabular" placeholder="Contoh: 500000">
                                <p class="form-hint text-[11px]">Min. Rp 1.000 &bull; Maks. sesuai sisa saldo kas</p>
                                <p id="err-operasional-nominal" class="form-error hidden"></p>
                            </div>
                            <div>
                                <label for="operasional-tanggal" class="form-label text-xs font-semibold mb-1">Tanggal Pengeluaran <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_pengeluaran" id="operasional-tanggal" value="{{ now()->format('Y-m-d') }}" class="form-input py-2 text-xs sm:text-sm">
                                <p id="err-operasional-tanggal" class="form-error hidden"></p>
                            </div>
                        </div>

                        <div>
                            <label for="operasional-keterangan" class="form-label text-xs font-semibold mb-1">Keterangan <span class="text-danger">*</span></label>
                            <input type="text" name="keterangan" id="operasional-keterangan" placeholder="Contoh: Pembayaran listrik & internet asrama" class="form-input py-2 text-xs sm:text-sm">
                            <p id="err-operasional-keterangan" class="form-error hidden"></p>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" id="operasional-submit-btn" class="btn btn-primary btn-sm rounded-xl cursor-pointer">
                                Simpan Operasional
                            </button>
                        </div>
                    </form>

                    <div class="space-y-2">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-muted-foreground-1">Riwayat Pengeluaran Operasional ({{ $operasionalList->count() }})</h4>
                        <div class="rounded-xl border border-border divide-y divide-border-line-1 overflow-hidden">
                            @forelse($operasionalList as $op)
                                <div x-data="{ confirmDelete: false }" class="p-3 bg-card hover:bg-muted/40 transition text-xs">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2">
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-muted text-foreground">
                                                    {{ $op->kategori }}
                                                </span>
                                                <span class="text-muted-foreground">{{ $op->tanggal_pengeluaran?->format('d M Y') }}</span>
                                            </div>
                                            <p class="font-bold text-foreground mt-1">{{ $op->keterangan }}</p>
                                        </div>
                                        <div class="flex items-center justify-between sm:justify-end gap-3 shrink-0">
                                            <span class="font-extrabold text-sm text-foreground tabular">Rp {{ number_format($op->nominal, 0, ',', '.') }}</span>
                                            <div class="inline-flex items-center gap-1">
                                                <button type="button"
                                                        onclick="editOperasional(@js($op->id), @js($op->kategori), @js($op->keterangan), @js((float) $op->nominal), @js($op->tanggal_pengeluaran?->format('Y-m-d')))"
                                                        class="p-1.5 rounded-lg text-muted-foreground-2 hover:text-brand-700 hover:bg-brand-50 transition cursor-pointer"
                                                        title="Edit">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" />
                                                    </svg>
                                                </button>
                                                <button type="button"
                                                        @click="confirmDelete = true"
                                                        class="p-1.5 rounded-lg text-muted-foreground-2 hover:text-danger hover:bg-danger-soft transition cursor-pointer"
                                                        title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div x-show="confirmDelete" x-cloak class="mt-2.5 pt-2.5 border-t border-danger/20 bg-danger-soft/50 rounded-xl px-3 py-2 flex flex-wrap items-center justify-between gap-2">
                                        <span class="text-xs font-semibold text-danger">Yakin ingin menghapus data pengeluaran ini?</span>
                                        <div class="flex items-center gap-2">
                                            <button type="button" @click="confirmDelete = false" class="btn btn-secondary btn-sm cursor-pointer">Batal</button>
                                            <form method="POST" action="{{ route('admin.pengeluaran-operasional.destroy', $op->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm cursor-pointer">Ya, Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center text-xs text-muted-foreground">Belum ada riwayat pengeluaran operasional.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const currentSaldoKas = {{ max(0, (float) $metrics['saldo_kas']) }};
    let currentMaxPenyaluran = Math.floor(currentSaldoKas);
    let currentMaxOperasional = Math.floor(currentSaldoKas);

    function setFieldError(inputEl, errorElId, message) {
        const errEl = document.getElementById(errorElId);
        if (errEl) {
            errEl.textContent = message;
            errEl.classList.remove('hidden');
        }
        if (inputEl) {
            inputEl.classList.add('border-danger', 'focus:border-danger', 'focus:ring-danger/20');
            const prelineToggle = window.HSSelect?.getInstance(inputEl, true)?.element?.toggle;
            if (prelineToggle) {
                prelineToggle.classList.add('border-danger');
            }
        }
    }

    function clearFieldErrors(pairs) {
        pairs.forEach(function (pair) {
            const errEl = document.getElementById(pair.errId);
            if (errEl) {
                errEl.textContent = '';
                errEl.classList.add('hidden');
            }
            const inputEl = document.getElementById(pair.inputId);
            if (inputEl) {
                inputEl.classList.remove('border-danger', 'focus:border-danger', 'focus:ring-danger/20');
                const prelineToggle = window.HSSelect?.getInstance(inputEl, true)?.element?.toggle;
                if (prelineToggle) {
                    prelineToggle.classList.remove('border-danger');
                }
            }
        });
    }

    function clearPenyaluranErrors() {
        clearFieldErrors([
            { inputId: 'penyaluran-klaster', errId: 'err-penyaluran-klaster' },
            { inputId: 'penyaluran-nominal', errId: 'err-penyaluran-nominal' },
            { inputId: 'penyaluran-tanggal', errId: 'err-penyaluran-tanggal' },
            { inputId: 'penyaluran-keterangan', errId: 'err-penyaluran-keterangan' },
        ]);
    }

    function validatePenyaluranForm(event) {
        clearPenyaluranErrors();

        const klasterEl = document.getElementById('penyaluran-klaster');
        const nominalEl = document.getElementById('penyaluran-nominal');
        const tanggalEl = document.getElementById('penyaluran-tanggal');
        const keteranganEl = document.getElementById('penyaluran-keterangan');
        let firstInvalid = null;
        let isPrelineSelectInvalid = false;

        if (!klasterEl || !klasterEl.value) {
            setFieldError(klasterEl, 'err-penyaluran-klaster', 'Klaster asnaf wajib dipilih.');
            if (!firstInvalid) {
                firstInvalid = klasterEl;
                isPrelineSelectInvalid = true;
            }
        }

        const nominalRaw = (nominalEl?.value || '').trim();
        const nominalVal = Number(nominalRaw);
        if (!nominalRaw) {
            setFieldError(nominalEl, 'err-penyaluran-nominal', 'Nominal penyaluran wajib diisi.');
            if (!firstInvalid) firstInvalid = nominalEl;
        } else if (isNaN(nominalVal) || nominalVal < 1000) {
            setFieldError(nominalEl, 'err-penyaluran-nominal', 'Nominal penyaluran minimal Rp 1.000.');
            if (!firstInvalid) firstInvalid = nominalEl;
        } else if (nominalVal > currentMaxPenyaluran) {
            setFieldError(
                nominalEl,
                'err-penyaluran-nominal',
                'Nominal melebihi sisa Saldo Kas & Cadangan (Rp ' + new Intl.NumberFormat('id-ID').format(currentMaxPenyaluran) + ').'
            );
            if (!firstInvalid) firstInvalid = nominalEl;
        }

        if (!tanggalEl || !tanggalEl.value) {
            setFieldError(tanggalEl, 'err-penyaluran-tanggal', 'Tanggal penyaluran wajib diisi.');
            if (!firstInvalid) firstInvalid = tanggalEl;
        }

        if (!keteranganEl || !keteranganEl.value.trim()) {
            setFieldError(keteranganEl, 'err-penyaluran-keterangan', 'Keterangan program wajib diisi.');
            if (!firstInvalid) firstInvalid = keteranganEl;
        }

        if (firstInvalid) {
            if (event) event.preventDefault();
            if (isPrelineSelectInvalid && typeof window.focusPrelineSelect === 'function') {
                window.focusPrelineSelect('#penyaluran-klaster');
            } else if (typeof firstInvalid.focus === 'function') {
                firstInvalid.focus();
            }
            return false;
        }

        return true;
    }

    function clearOperasionalErrors() {
        clearFieldErrors([
            { inputId: 'operasional-kategori', errId: 'err-operasional-kategori' },
            { inputId: 'operasional-nominal', errId: 'err-operasional-nominal' },
            { inputId: 'operasional-tanggal', errId: 'err-operasional-tanggal' },
            { inputId: 'operasional-keterangan', errId: 'err-operasional-keterangan' },
        ]);
    }

    function validateOperasionalForm(event) {
        clearOperasionalErrors();

        const kategoriEl = document.getElementById('operasional-kategori');
        const nominalEl = document.getElementById('operasional-nominal');
        const tanggalEl = document.getElementById('operasional-tanggal');
        const keteranganEl = document.getElementById('operasional-keterangan');
        let firstInvalid = null;

        if (!kategoriEl || !kategoriEl.value.trim()) {
            setFieldError(kategoriEl, 'err-operasional-kategori', 'Kategori pengeluaran wajib diisi.');
            if (!firstInvalid) firstInvalid = kategoriEl;
        }

        const nominalRaw = (nominalEl?.value || '').trim();
        const nominalVal = Number(nominalRaw);
        if (!nominalRaw) {
            setFieldError(nominalEl, 'err-operasional-nominal', 'Nominal pengeluaran wajib diisi.');
            if (!firstInvalid) firstInvalid = nominalEl;
        } else if (isNaN(nominalVal) || nominalVal < 1000) {
            setFieldError(nominalEl, 'err-operasional-nominal', 'Nominal pengeluaran minimal Rp 1.000.');
            if (!firstInvalid) firstInvalid = nominalEl;
        } else if (nominalVal > currentMaxOperasional) {
            setFieldError(
                nominalEl,
                'err-operasional-nominal',
                'Nominal melebihi sisa Saldo Kas & Cadangan (Rp ' + new Intl.NumberFormat('id-ID').format(currentMaxOperasional) + ').'
            );
            if (!firstInvalid) firstInvalid = nominalEl;
        }

        if (!tanggalEl || !tanggalEl.value) {
            setFieldError(tanggalEl, 'err-operasional-tanggal', 'Tanggal pengeluaran wajib diisi.');
            if (!firstInvalid) firstInvalid = tanggalEl;
        }

        if (!keteranganEl || !keteranganEl.value.trim()) {
            setFieldError(keteranganEl, 'err-operasional-keterangan', 'Keterangan pengeluaran wajib diisi.');
            if (!firstInvalid) firstInvalid = keteranganEl;
        }

        if (firstInvalid) {
            if (event) event.preventDefault();
            if (typeof firstInvalid.focus === 'function') {
                firstInvalid.focus();
            }
            return false;
        }

        return true;
    }

    function editPenyaluran(id, klaster, keterangan, nominal, tanggal, penerima) {
        clearPenyaluranErrors();
        document.getElementById('form-penyaluran').action = '/admin/penyaluran-dana/' + id;
        document.getElementById('penyaluran-method').value = 'PUT';
        document.getElementById('penyaluran-form-label').textContent = 'Edit Penyaluran Dana';
        document.getElementById('penyaluran-submit-btn').textContent = 'Perbarui Penyaluran';
        document.getElementById('btn-reset-penyaluran').classList.remove('hidden');

        const klasterEl = document.getElementById('penyaluran-klaster');
        klasterEl.value = klaster;
        if (typeof window.syncPrelineSelect === 'function') {
            window.syncPrelineSelect('#penyaluran-klaster');
        }
        document.getElementById('penyaluran-keterangan').value = keterangan;
        const nominalInput = document.getElementById('penyaluran-nominal');
        nominalInput.value = nominal;
        currentMaxPenyaluran = Math.floor(currentSaldoKas + Number(nominal || 0));
        nominalInput.max = currentMaxPenyaluran;
        document.getElementById('penyaluran-tanggal').value = tanggal;
        document.getElementById('penyaluran-penerima').value = penerima;
    }

    function resetPenyaluranForm() {
        clearPenyaluranErrors();
        document.getElementById('form-penyaluran').action = '{{ route('admin.penyaluran-dana.store') }}';
        document.getElementById('penyaluran-method').value = 'POST';
        document.getElementById('penyaluran-form-label').textContent = 'Tambah Penyaluran Baru';
        document.getElementById('penyaluran-submit-btn').textContent = 'Simpan Penyaluran';
        document.getElementById('btn-reset-penyaluran').classList.add('hidden');

        const klasterEl = document.getElementById('penyaluran-klaster');
        klasterEl.value = 'pendidikan';
        if (typeof window.syncPrelineSelect === 'function') {
            window.syncPrelineSelect('#penyaluran-klaster');
        }
        document.getElementById('penyaluran-keterangan').value = '';
        const nominalInput = document.getElementById('penyaluran-nominal');
        nominalInput.value = '';
        currentMaxPenyaluran = Math.floor(currentSaldoKas);
        nominalInput.max = currentMaxPenyaluran;
        document.getElementById('penyaluran-penerima').value = '';
    }

    function editOperasional(id, kategori, keterangan, nominal, tanggal) {
        clearOperasionalErrors();
        document.getElementById('form-operasional').action = '/admin/pengeluaran-operasional/' + id;
        document.getElementById('operasional-method').value = 'PUT';
        document.getElementById('operasional-form-label').textContent = 'Edit Pengeluaran Operasional';
        document.getElementById('operasional-submit-btn').textContent = 'Perbarui Operasional';
        document.getElementById('btn-reset-operasional').classList.remove('hidden');

        document.getElementById('operasional-kategori').value = kategori;
        document.getElementById('operasional-keterangan').value = keterangan;
        const nominalInput = document.getElementById('operasional-nominal');
        nominalInput.value = nominal;
        currentMaxOperasional = Math.floor(currentSaldoKas + Number(nominal || 0));
        nominalInput.max = currentMaxOperasional;
        document.getElementById('operasional-tanggal').value = tanggal;
    }

    function resetOperasionalForm() {
        clearOperasionalErrors();
        document.getElementById('form-operasional').action = '{{ route('admin.pengeluaran-operasional.store') }}';
        document.getElementById('operasional-method').value = 'POST';
        document.getElementById('operasional-form-label').textContent = 'Tambah Pengeluaran Operasional';
        document.getElementById('operasional-submit-btn').textContent = 'Simpan Operasional';
        document.getElementById('btn-reset-operasional').classList.add('hidden');

        document.getElementById('operasional-kategori').value = '';
        document.getElementById('operasional-keterangan').value = '';
        const nominalInput = document.getElementById('operasional-nominal');
        nominalInput.value = '';
        currentMaxOperasional = Math.floor(currentSaldoKas);
        nominalInput.max = currentMaxOperasional;
    }
</script>
@endsection
