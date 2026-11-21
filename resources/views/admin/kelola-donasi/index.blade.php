@extends('layouts.admin')

@section('title', 'Kelola Donasi - Admin Panel Himmatun Ayat')
@section('breadcrumb', 'Kelola Donasi')

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
            <button type="button" onclick="this.parentElement.remove()" class="text-brand-600 hover:text-brand-900 text-lg leading-none">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="p-3.5 rounded-xl bg-danger-soft border border-danger/30 text-danger text-xs sm:text-sm">
            <p class="font-bold mb-1">Terjadi kesalahan pada input data:</p>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Top Header: Title, Count, & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-brand-900">Kelola Donasi</h1>
            <p class="text-xs sm:text-sm text-muted-foreground-1 mt-0.5 font-medium">{{ $totalDonaturTerkini }} donatur terdaftar &bull; {{ $donasiList->total() }} transaksi</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <!-- Tombol Kelola Penyaluran -->
            <button type="button"
                    onclick="openPenyaluranModal()"
                    class="inline-flex items-center gap-1.5 px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl border border-border bg-card text-foreground text-xs sm:text-sm font-semibold hover:bg-muted transition shadow-2xs cursor-pointer">
                <svg class="w-4 h-4 text-warning shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Penyaluran Dana</span>
            </button>

            <!-- Tombol Kelola Operasional -->
            <button type="button"
                    onclick="openOperasionalModal()"
                    class="inline-flex items-center gap-1.5 px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl border border-border bg-card text-foreground text-xs sm:text-sm font-semibold hover:bg-muted transition shadow-2xs cursor-pointer">
                <svg class="w-4 h-4 text-brand-700 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Operasional</span>
            </button>

            <!-- Tombol ↓ Export -->
            <a href="{{ route('admin.kelola-donasi.export') }}"
               class="inline-flex items-center gap-2 px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl bg-brand-700 text-white text-xs sm:text-sm font-semibold hover:bg-brand-800 transition shadow-xs cursor-pointer">
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
                    <span class="text-xl sm:text-2xl font-extrabold tracking-tight text-foreground">
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
                    <button type="button" onclick="openPenyaluranModal()" title="Kelola Penyaluran Dana" class="w-8 h-8 rounded-lg bg-warning-soft text-warning hover:opacity-80 transition flex items-center justify-center shrink-0 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-5.25 6.557c0 1.455.517 2.822 1.407 3.896" />
                        </svg>
                    </button>
                </div>
                <div class="mt-2">
                    <span class="text-xl sm:text-2xl font-extrabold tracking-tight text-foreground">
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
                    <button type="button" onclick="openOperasionalModal()" title="Kelola Pengeluaran Operasional" class="w-8 h-8 rounded-lg bg-muted text-muted-foreground-2 hover:bg-surface-hover transition flex items-center justify-center shrink-0 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0012 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52l2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 01-2.031.352 5.988 5.988 0 01-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.97zm-13.5 0C4.24 5.113 3.24 5.287 2.25 5.49m3-.52L2.63 15.696c-.122.499.106 1.028.589 1.202a5.989 5.989 0 002.031.352 5.988 5.988 0 002.031-.352c.483-.174.711-.703.59-1.202L5.25 4.97z" />
                        </svg>
                    </button>
                </div>
                <div class="mt-2">
                    <span class="text-xl sm:text-2xl font-extrabold tracking-tight text-foreground">
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
                    <span class="text-xl sm:text-2xl font-extrabold tracking-tight text-foreground">
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
    <div class="bg-brand-100 dark:bg-brand-900/40 rounded-2xl p-4 sm:p-5 border border-brand-900/5 dark:border-brand-800 shadow-2xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h2 class="text-sm sm:text-base font-bold text-foreground">Alokasi Penyaluran Program per Klaster Asnaf</h2>
                <p class="text-xs text-muted-foreground-2 mt-0.5">Distribusi realisasi pemanfaatan dana sedekah dan zakat secara terperinci.</p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <div class="text-xs text-muted-foreground-2 sm:text-right">
                    Total Realisasi: <span class="font-bold text-foreground">Rp {{ number_format($asnaf['total_realisasi'], 0, ',', '.') }}</span>
                </div>
                <button type="button"
                        onclick="openPenyaluranModal()"
                        class="px-3 py-1.5 rounded-xl bg-brand-700 text-white text-xs font-semibold hover:bg-brand-800 transition cursor-pointer">
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
                            <p class="text-muted-foreground-2 text-[11px] mt-0.5 font-medium">
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

    <!-- Toolbar: Search Bar & Segmented Sorting Pills -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3.5 pt-1">
        <!-- Search Input -->
        <form method="GET" action="{{ route('admin.kelola-donasi.index') }}" class="w-full md:w-96">
            <input type="hidden" name="sort" value="{{ $sort }}">
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="w-4 h-4 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Cari nama atau email donatur..."
                       class="w-full rounded-xl border border-border-line-3 bg-card pl-10 pr-9 py-2.5 text-xs sm:text-sm text-foreground placeholder:text-muted-foreground focus:border-brand-700 focus:ring-1 focus:ring-brand-700 focus:outline-hidden transition shadow-2xs">
                @if($search)
                    <a href="{{ route('admin.kelola-donasi.index', array_filter(['sort' => $sort])) }}"
                       title="Hapus pencarian"
                       class="absolute inset-y-0 right-0 flex items-center pr-3 text-muted-foreground hover:text-muted-foreground-2 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif
            </div>
        </form>

        <!-- Segmented Sorting Pills -->
        <div class="flex items-center gap-2.5 overflow-x-auto pb-1 md:pb-0">
            <span class="text-xs sm:text-sm font-medium text-muted-foreground-1 shrink-0">Urutkan:</span>
            <div class="inline-flex rounded-xl bg-surface p-1 border border-border gap-1 shrink-0">
                <a href="{{ route('admin.kelola-donasi.index', ['search' => $search, 'sort' => 'total_donasi']) }}"
                   class="px-3.5 sm:px-4 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-semibold transition cursor-pointer {{ $sort === 'total_donasi' || !$sort ? 'bg-brand-700 text-white shadow-xs' : 'text-muted-foreground-2 hover:text-foreground hover:bg-surface-hover/70' }}">
                    Total Donasi
                </a>
                <a href="{{ route('admin.kelola-donasi.index', ['search' => $search, 'sort' => 'frekuensi']) }}"
                   class="px-3.5 sm:px-4 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-semibold transition cursor-pointer {{ $sort === 'frekuensi' ? 'bg-brand-700 text-white shadow-xs' : 'text-muted-foreground-2 hover:text-foreground hover:bg-surface-hover/70' }}">
                    Frekuensi
                </a>
                <a href="{{ route('admin.kelola-donasi.index', ['search' => $search, 'sort' => 'nama']) }}"
                   class="px-3.5 sm:px-4 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-semibold transition cursor-pointer {{ $sort === 'nama' ? 'bg-brand-700 text-white shadow-xs' : 'text-muted-foreground-2 hover:text-foreground hover:bg-surface-hover/70' }}">
                    Nama
                </a>
            </div>
        </div>
    </div>

    <!-- ================= DATA CONTAINER ================= -->

    <!-- 1. Tampilan Desktop & Tablet (md ke atas) -->
    <div class="hidden md:block overflow-x-auto w-full pb-1">
        <div class="min-w-[950px] space-y-2">
            <!-- Table Header -->
            <div class="bg-brand-100 dark:bg-brand-900/40 text-muted-foreground-2 font-bold uppercase text-[11px] tracking-wider rounded-2xl px-6 py-3 flex items-center shadow-2xs">
                <div class="w-[24%] text-left">DONATUR</div>
                <div class="w-[24%] text-left">KONTAK</div>
                <div class="w-[17%] text-left">KAMPANYE</div>
                <div class="w-[15%] text-left">TOTAL DONASI</div>
                <div class="w-[8%] text-center">BUKTI</div>
                <div class="w-[12%] text-center">AKSI</div>
            </div>

            <!-- Table Rows -->
            <div class="bg-card rounded-2xl border border-border/70 shadow-2xs divide-y divide-border-line-1 overflow-hidden">
                @forelse($donasiList as $donasi)
                    @php
                        $proofUrl = $donasi->bukti_transfer_path ? route('admin.donasi.bukti-transfer.show', $donasi) : null;
                    @endphp
                    <div class="px-6 py-4 flex items-center hover:bg-muted transition">
                        <!-- DONATUR -->
                        <div class="w-[24%] flex items-center gap-3 pr-3 min-w-0">
                            <div class="w-9 h-9 rounded-full bg-brand-900 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-2xs">
                                {{ strtoupper(substr($donasi->nama_donatur, 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <span class="font-bold text-foreground text-sm truncate block">{{ $donasi->nama_donatur }}</span>
                                <span class="text-[11px] text-muted-foreground font-medium">
                                    {{ $donasi->status === 'verified' ? '✓ Terverifikasi' : ($donasi->status === 'rejected' ? '✗ Ditolak' : '⏳ Menunggu') }}
                                </span>
                            </div>
                        </div>

                        <!-- KONTAK -->
                        <div class="w-[24%] text-xs min-w-0 pr-3">
                            <p class="text-muted-foreground-2 font-medium truncate">{{ $donasi->email ?: '-' }}</p>
                            <p class="text-muted-foreground text-[11px] mt-0.5 truncate">{{ $donasi->no_whatsapp ?: '-' }}</p>
                        </div>

                        <!-- KAMPANYE -->
                        <div class="w-[17%] pr-3">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-brand-100 dark:bg-brand-900/50 text-brand-900 dark:text-brand-200 truncate max-w-full">
                                {{ $donasi->kampanye ? $donasi->kampanye->judul : 'Umum' }}
                            </span>
                        </div>

                        <!-- TOTAL DONASI -->
                        <div class="w-[15%]">
                            <span class="font-bold text-sm text-foreground">
                                Rp {{ number_format($donasi->nominal, 0, ',', '.') }}
                            </span>
                        </div>

                        <!-- BUKTI TRANSFER -->
                        <div class="w-[8%] flex justify-center">
                            @if($proofUrl)
                                <button type="button"
                                        onclick="showBuktiModal(@js($donasi->id), @js($donasi->nama_donatur), @js($proofUrl))"
                                        class="p-1.5 rounded-lg text-brand-700 hover:bg-brand-50 transition cursor-pointer"
                                        title="Lihat Bukti Transfer">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>
                            @else
                                <span class="text-[11px] text-muted-foreground">-</span>
                            @endif
                        </div>

                        <!-- AKSI -->
                        <div class="w-[12%] flex items-center justify-center gap-1.5 shrink-0">
                            <!-- Edit -->
                            <button type="button"
                                    onclick="openEditModal(@js($donasi->id), @js($donasi->nama_donatur), @js($donasi->email ?? ''), @js($donasi->no_whatsapp ?? ''), @js($donasi->kampanye_id), @js((float) $donasi->nominal), @js($donasi->metode_pembayaran ?? ''), @js($donasi->pesan_doa ?? ''), @js($donasi->status))"
                                    class="p-1.5 rounded-lg text-muted-foreground-2 hover:text-brand-700 hover:bg-brand-50 transition cursor-pointer"
                                    title="Edit Donasi">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" />
                                </svg>
                            </button>

                            <!-- Terima -->
                            @if($donasi->status !== 'verified')
                                <form method="POST" action="{{ route('admin.kelola-donasi.terima', $donasi->id) }}">
                                    @csrf
                                    <button type="submit"
                                            class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-brand-100 dark:bg-brand-900/50 text-brand-900 dark:text-brand-200 hover:bg-brand-200 dark:hover:bg-brand-800 transition shadow-2xs cursor-pointer"
                                            title="Verifikasi Donasi">
                                        Terima
                                    </button>
                                </form>
                            @endif

                            <!-- Hapus -->
                            <form method="POST" action="{{ route('admin.kelola-donasi.destroy', $donasi->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Hapus donasi ini?')"
                                        class="p-1.5 rounded-lg text-muted-foreground-2 hover:text-danger hover:bg-danger-soft transition cursor-pointer"
                                        title="Hapus Donasi">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-muted-foreground text-sm">
                        Tidak ada data donasi yang sesuai dengan pencarian.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 2. Tampilan Mobile (< md) -->
    <div class="md:hidden space-y-3.5">
        @forelse($donasiList as $donasi)
            @php
                $proofUrl = $donasi->bukti_transfer_path ? route('admin.donasi.bukti-transfer.show', $donasi) : null;
            @endphp
            <div class="bg-card rounded-2xl border border-border shadow-2xs p-4 space-y-3 transition hover:border-brand-700">
                <!-- Baris 1: Avatar, Nama Donatur, & Badge Status -->
                <div class="flex items-start justify-between gap-2.5">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-full bg-brand-900 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-2xs">
                            {{ strtoupper(substr($donasi->nama_donatur, 0, 2)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-foreground text-sm truncate leading-snug">{{ $donasi->nama_donatur }}</p>
                            <p class="text-xs text-muted-foreground-1 font-medium truncate mt-0.5">{{ $donasi->email ?: '-' }}</p>
                        </div>
                    </div>
                    <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-semibold bg-brand-100 dark:bg-brand-900/50 text-brand-900 dark:text-brand-200 shrink-0">
                        {{ $donasi->kampanye ? $donasi->kampanye->judul : 'Umum' }}
                    </span>
                </div>

                <!-- Baris 2: Kontak WhatsApp -->
                <div class="flex items-center gap-2 text-xs text-muted-foreground-2 bg-muted px-3 py-2 rounded-xl border border-border-line-1">
                    <svg class="w-3.5 h-3.5 text-brand-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                    </svg>
                    <span class="truncate">{{ $donasi->no_whatsapp ?: '-' }}</span>
                </div>

                <!-- Baris 3: Total Donasi & Tombol Aksi -->
                <div class="flex items-center justify-between gap-2 pt-2 border-t border-border-line-1">
                    <div>
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-muted-foreground">TOTAL DONASI</span>
                        <span class="font-extrabold text-base text-brand-900 leading-tight">
                            Rp {{ number_format($donasi->nominal, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="inline-flex items-center gap-1.5 shrink-0">
                        <!-- Lihat Bukti -->
                        @if($proofUrl)
                            <button type="button"
                                    onclick="showBuktiModal(@js($donasi->id), @js($donasi->nama_donatur), @js($proofUrl))"
                                    class="p-1.5 rounded-lg text-brand-700 hover:bg-brand-50 transition cursor-pointer"
                                    title="Lihat Bukti">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                        @endif

                        <!-- Edit -->
                        <button type="button"
                                onclick="openEditModal(@js($donasi->id), @js($donasi->nama_donatur), @js($donasi->email ?? ''), @js($donasi->no_whatsapp ?? ''), @js($donasi->kampanye_id), @js((float) $donasi->nominal), @js($donasi->metode_pembayaran ?? ''), @js($donasi->pesan_doa ?? ''), @js($donasi->status))"
                                class="p-1.5 rounded-lg text-muted-foreground-2 hover:text-brand-700 hover:bg-brand-50 transition cursor-pointer"
                                title="Edit">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" />
                            </svg>
                        </button>

                        <!-- Terima -->
                        @if($donasi->status !== 'verified')
                            <form method="POST" action="{{ route('admin.kelola-donasi.terima', $donasi->id) }}">
                                @csrf
                                <button type="submit"
                                        class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-brand-100 dark:bg-brand-900/50 text-brand-900 dark:text-brand-200 hover:bg-brand-200 dark:hover:bg-brand-800 transition shadow-2xs cursor-pointer">
                                    Terima
                                </button>
                            </form>
                        @endif

                        <!-- Hapus -->
                        <form method="POST" action="{{ route('admin.kelola-donasi.destroy', $donasi->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Hapus donasi ini?')"
                                    class="p-1.5 rounded-lg text-muted-foreground-2 hover:text-danger hover:bg-danger-soft transition cursor-pointer">
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
                Tidak ada data donasi yang sesuai dengan pencarian.
            </div>
        @endforelse
    </div>

    <!-- Card Footer: Pagination -->
    <div class="bg-card rounded-2xl border border-border/70 p-4 sm:p-4.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs sm:text-sm text-muted-foreground-1 shadow-2xs">
        <div class="font-medium text-center sm:text-left text-muted-foreground-2">
            Menampilkan {{ $donasiList->firstItem() ?? 1 }}-{{ $donasiList->lastItem() ?? 10 }} dari {{ $donasiList->total() }} Data Donasi
        </div>

        <nav class="flex items-center justify-center sm:justify-end gap-1.5">
            @if ($donasiList->onFirstPage())
                <span class="w-8 h-8 rounded-lg flex items-center justify-center text-muted-foreground cursor-not-allowed">&lt;</span>
            @else
                <a href="{{ $donasiList->previousPageUrl() }}"
                   class="w-8 h-8 rounded-lg flex items-center justify-center text-muted-foreground-2 hover:bg-muted hover:text-foreground transition">&lt;</a>
            @endif

            @for ($p = 1; $p <= min(3, $donasiList->lastPage()); $p++)
                @if ($p == $donasiList->currentPage())
                    <span class="w-8 h-8 rounded-lg bg-brand-700 text-white flex items-center justify-center font-bold text-xs sm:text-sm shadow-2xs">{{ $p }}</span>
                @else
                    <a href="{{ $donasiList->url($p) }}"
                       class="w-8 h-8 rounded-lg text-muted-foreground-2 hover:bg-muted flex items-center justify-center font-medium text-xs sm:text-sm transition">{{ $p }}</a>
                @endif
            @endfor

            @if ($donasiList->hasMorePages())
                <a href="{{ $donasiList->nextPageUrl() }}"
                   class="w-8 h-8 rounded-lg flex items-center justify-center text-muted-foreground-2 hover:bg-muted hover:text-foreground transition">&gt;</a>
            @else
                <span class="w-8 h-8 rounded-lg flex items-center justify-center text-muted-foreground cursor-not-allowed">&gt;</span>
            @endif
        </nav>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL: LIHAT BUKTI TRANSFER                                   --}}
{{-- ============================================================ --}}
<div id="modal-bukti" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
    <div class="bg-card rounded-2xl shadow-xl w-full max-w-lg border border-border overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-border">
            <div>
                <h3 class="text-sm font-bold text-foreground">Bukti Transfer</h3>
                <p id="bukti-nama" class="text-xs text-muted-foreground-2 mt-0.5"></p>
            </div>
            <button type="button" onclick="closeBuktiModal()" class="text-muted-foreground hover:text-foreground transition text-xl leading-none cursor-pointer">&times;</button>
        </div>
        <div class="p-5 flex items-center justify-center bg-muted/40">
            <img id="bukti-img" src="" alt="Bukti Transfer"
                 class="w-full rounded-xl border border-border object-contain max-h-[60vh]">
        </div>
        <div class="px-5 py-4 border-t border-border flex justify-end gap-2">
            <button type="button" onclick="closeBuktiModal()"
                    class="px-4 py-2 rounded-xl text-xs font-semibold text-muted-foreground-2 hover:bg-muted transition cursor-pointer">
                Tutup
            </button>
            <a id="bukti-download" href="#" target="_blank" rel="noopener"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-700 text-white text-xs font-semibold hover:bg-brand-800 transition">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Buka / Unduh
            </a>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL: EDIT DONASI                                            --}}
{{-- ============================================================ --}}
<div id="modal-edit" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
    <div class="bg-card rounded-2xl shadow-xl w-full max-w-xl border border-border overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-border">
            <h3 class="text-sm font-bold text-foreground">Edit Data Donasi</h3>
            <button type="button" onclick="closeEditModal()" class="text-muted-foreground hover:text-foreground transition text-xl leading-none cursor-pointer">&times;</button>
        </div>

        <form id="form-edit" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="p-5 space-y-4 max-h-[70vh] overflow-y-auto">

                <!-- Nama Donatur -->
                <div>
                    <label class="block text-xs font-semibold text-foreground mb-1.5">Nama Donatur <span class="text-danger">*</span></label>
                    <input type="text" name="nama_donatur" id="edit-nama"
                           class="w-full rounded-xl border border-border-line-3 bg-surface px-3.5 py-2.5 text-sm text-foreground focus:border-brand-700 focus:ring-1 focus:ring-brand-700 focus:outline-hidden transition"
                           required>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-semibold text-foreground mb-1.5">Email</label>
                        <input type="email" name="email" id="edit-email"
                               class="w-full rounded-xl border border-border-line-3 bg-surface px-3.5 py-2.5 text-sm text-foreground focus:border-brand-700 focus:ring-1 focus:ring-brand-700 focus:outline-hidden transition">
                    </div>
                    <!-- WhatsApp -->
                    <div>
                        <label class="block text-xs font-semibold text-foreground mb-1.5">No. WhatsApp</label>
                        <input type="text" name="no_whatsapp" id="edit-wa"
                               class="w-full rounded-xl border border-border-line-3 bg-surface px-3.5 py-2.5 text-sm text-foreground focus:border-brand-700 focus:ring-1 focus:ring-brand-700 focus:outline-hidden transition">
                    </div>
                </div>

                <!-- Kampanye -->
                <div>
                    <label class="block text-xs font-semibold text-foreground mb-1.5">Kampanye</label>
                    <select name="kampanye_id" id="edit-kampanye"
                            class="w-full rounded-xl border border-border-line-3 bg-surface px-3.5 py-2.5 text-sm text-foreground focus:border-brand-700 focus:ring-1 focus:ring-brand-700 focus:outline-hidden transition">
                        <option value="">— Donasi Umum —</option>
                        @foreach($kampanyeList as $kampanye)
                            <option value="{{ $kampanye->id }}">{{ $kampanye->judul }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- Nominal -->
                    <div>
                        <label class="block text-xs font-semibold text-foreground mb-1.5">Nominal (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="nominal" id="edit-nominal" min="1000" step="1000"
                               class="w-full rounded-xl border border-border-line-3 bg-surface px-3.5 py-2.5 text-sm text-foreground focus:border-brand-700 focus:ring-1 focus:ring-brand-700 focus:outline-hidden transition"
                               required>
                    </div>
                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-semibold text-foreground mb-1.5">Status <span class="text-danger">*</span></label>
                        <select name="status" id="edit-status"
                                class="w-full rounded-xl border border-border-line-3 bg-surface px-3.5 py-2.5 text-sm text-foreground focus:border-brand-700 focus:ring-1 focus:ring-brand-700 focus:outline-hidden transition">
                            <option value="pending">Menunggu</option>
                            <option value="verified">Terverifikasi</option>
                            <option value="rejected">Ditolak</option>
                        </select>
                    </div>
                </div>

                <!-- Metode Pembayaran -->
                <div>
                    <label class="block text-xs font-semibold text-foreground mb-1.5">Metode Pembayaran</label>
                    <input type="text" name="metode_pembayaran" id="edit-metode"
                           class="w-full rounded-xl border border-border-line-3 bg-surface px-3.5 py-2.5 text-sm text-foreground focus:border-brand-700 focus:ring-1 focus:ring-brand-700 focus:outline-hidden transition">
                </div>

                <!-- Pesan / Doa -->
                <div>
                    <label class="block text-xs font-semibold text-foreground mb-1.5">Pesan / Doa</label>
                    <textarea name="pesan_doa" id="edit-pesan" rows="3"
                              class="w-full rounded-xl border border-border-line-3 bg-surface px-3.5 py-2.5 text-sm text-foreground focus:border-brand-700 focus:ring-1 focus:ring-brand-700 focus:outline-hidden transition resize-none"></textarea>
                </div>
            </div>

            <div class="px-5 py-4 border-t border-border flex items-center justify-end gap-2">
                <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 rounded-xl text-xs font-semibold text-muted-foreground-2 hover:bg-muted transition cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2 rounded-xl bg-brand-700 text-white text-xs font-semibold hover:bg-brand-800 transition shadow-xs cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL: CRUD PENYALURAN DANA & KLASTER ASNAF                   --}}
{{-- ============================================================ --}}
<div id="modal-penyaluran" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
    <div class="bg-card rounded-2xl shadow-xl w-full max-w-3xl border border-border overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-border">
            <div>
                <h3 id="penyaluran-modal-title" class="text-sm font-bold text-foreground">Kelola Penyaluran Dana &amp; Klaster Asnaf</h3>
                <p class="text-xs text-muted-foreground-2 mt-0.5">Tambah, perbarui, atau hapus realisasi penyaluran program per klaster asnaf.</p>
            </div>
            <button type="button" onclick="closePenyaluranModal()" class="text-muted-foreground hover:text-foreground transition text-xl leading-none cursor-pointer">&times;</button>
        </div>

        <div class="p-5 space-y-5 max-h-[75vh] overflow-y-auto">
            <!-- Form Tambah / Edit Penyaluran -->
            <form id="form-penyaluran" method="POST" action="{{ route('admin.penyaluran-dana.store') }}" class="p-4 rounded-xl border border-border bg-muted/40 space-y-3">
                @csrf
                <input type="hidden" name="_method" id="penyaluran-method" value="POST">

                <div class="flex items-center justify-between">
                    <span id="penyaluran-form-label" class="text-xs font-bold uppercase tracking-wider text-brand-800">Tambah Penyaluran Baru</span>
                    <button type="button" id="btn-reset-penyaluran" onclick="resetPenyaluranForm()" class="hidden text-xs font-semibold text-muted-foreground hover:text-foreground cursor-pointer">
                        Batal Edit
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-foreground mb-1">Klaster Asnaf <span class="text-danger">*</span></label>
                        <select name="klaster_asnaf" id="penyaluran-klaster" required
                                class="w-full rounded-xl border border-border-line-3 bg-surface px-3 py-2 text-xs sm:text-sm text-foreground focus:border-brand-700 focus:outline-hidden">
                            @foreach($klasterLabels as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-foreground mb-1">Nominal (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="nominal" id="penyaluran-nominal" min="1000" step="1000" required
                               class="w-full rounded-xl border border-border-line-3 bg-surface px-3 py-2 text-xs sm:text-sm text-foreground focus:border-brand-700 focus:outline-hidden">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-foreground mb-1">Tanggal Penyaluran <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_penyaluran" id="penyaluran-tanggal" value="{{ now()->format('Y-m-d') }}" required
                               class="w-full rounded-xl border border-border-line-3 bg-surface px-3 py-2 text-xs sm:text-sm text-foreground focus:border-brand-700 focus:outline-hidden">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-foreground mb-1">Keterangan Program <span class="text-danger">*</span></label>
                        <input type="text" name="keterangan" id="penyaluran-keterangan" placeholder="Contoh: Beasiswa semester ganjil" required
                               class="w-full rounded-xl border border-border-line-3 bg-surface px-3 py-2 text-xs sm:text-sm text-foreground focus:border-brand-700 focus:outline-hidden">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-foreground mb-1">Penerima Manfaat</label>
                        <input type="text" name="penerima_manfaat" id="penyaluran-penerima" placeholder="Contoh: 25 Anak Asuh"
                               class="w-full rounded-xl border border-border-line-3 bg-surface px-3 py-2 text-xs sm:text-sm text-foreground focus:border-brand-700 focus:outline-hidden">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" id="penyaluran-submit-btn"
                            class="px-4 py-2 rounded-xl bg-brand-700 text-white text-xs font-semibold hover:bg-brand-800 transition cursor-pointer">
                        Simpan Penyaluran
                    </button>
                </div>
            </form>

            <!-- Daftar Riwayat Penyaluran -->
            <div class="space-y-2">
                <h4 class="text-xs font-bold uppercase tracking-wider text-muted-foreground-1">Riwayat Penyaluran Dana ({{ $penyaluranList->count() }})</h4>
                <div class="rounded-xl border border-border divide-y divide-border-line-1 overflow-hidden">
                    @forelse($penyaluranList as $item)
                        <div class="p-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2 bg-card hover:bg-muted/40 transition text-xs">
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
                                <span class="font-extrabold text-sm text-foreground">Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
                                <div class="inline-flex items-center gap-1">
                                    <button type="button"
                                            onclick="editPenyaluran(@js($item->id), @js($item->klaster_asnaf), @js($item->keterangan), @js((float) $item->nominal), @js($item->tanggal_penyaluran?->format('Y-m-d')), @js($item->penerima_manfaat ?? ''))"
                                            class="p-1.5 rounded-lg text-muted-foreground-2 hover:text-brand-700 hover:bg-brand-50 transition cursor-pointer"
                                            title="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" />
                                        </svg>
                                    </button>
                                    <form method="POST" action="{{ route('admin.penyaluran-dana.destroy', $item->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus data penyaluran ini?')"
                                                class="p-1.5 rounded-lg text-muted-foreground-2 hover:text-danger hover:bg-danger-soft transition cursor-pointer"
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
                        <div class="p-6 text-center text-xs text-muted-foreground">Belum ada riwayat penyaluran dana.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL: CRUD PENGELUARAN OPERASIONAL                           --}}
{{-- ============================================================ --}}
<div id="modal-operasional" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
    <div class="bg-card rounded-2xl shadow-xl w-full max-w-3xl border border-border overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-border">
            <div>
                <h3 class="text-sm font-bold text-foreground">Kelola Pengeluaran Operasional</h3>
                <p class="text-xs text-muted-foreground-2 mt-0.5">Tambah, perbarui, atau hapus data pengeluaran operasional yayasan.</p>
            </div>
            <button type="button" onclick="closeOperasionalModal()" class="text-muted-foreground hover:text-foreground transition text-xl leading-none cursor-pointer">&times;</button>
        </div>

        <div class="p-5 space-y-5 max-h-[75vh] overflow-y-auto">
            <!-- Form Tambah / Edit Operasional -->
            <form id="form-operasional" method="POST" action="{{ route('admin.pengeluaran-operasional.store') }}" class="p-4 rounded-xl border border-border bg-muted/40 space-y-3">
                @csrf
                <input type="hidden" name="_method" id="operasional-method" value="POST">

                <div class="flex items-center justify-between">
                    <span id="operasional-form-label" class="text-xs font-bold uppercase tracking-wider text-brand-800">Tambah Pengeluaran Operasional</span>
                    <button type="button" id="btn-reset-operasional" onclick="resetOperasionalForm()" class="hidden text-xs font-semibold text-muted-foreground hover:text-foreground cursor-pointer">
                        Batal Edit
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-foreground mb-1">Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="kategori" id="operasional-kategori" placeholder="Contoh: Utilitas / Administrasi" required
                               class="w-full rounded-xl border border-border-line-3 bg-surface px-3 py-2 text-xs sm:text-sm text-foreground focus:border-brand-700 focus:outline-hidden">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-foreground mb-1">Nominal (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="nominal" id="operasional-nominal" min="1000" step="1000" required
                               class="w-full rounded-xl border border-border-line-3 bg-surface px-3 py-2 text-xs sm:text-sm text-foreground focus:border-brand-700 focus:outline-hidden">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-foreground mb-1">Tanggal Pengeluaran <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_pengeluaran" id="operasional-tanggal" value="{{ now()->format('Y-m-d') }}" required
                               class="w-full rounded-xl border border-border-line-3 bg-surface px-3 py-2 text-xs sm:text-sm text-foreground focus:border-brand-700 focus:outline-hidden">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-foreground mb-1">Keterangan <span class="text-danger">*</span></label>
                    <input type="text" name="keterangan" id="operasional-keterangan" placeholder="Contoh: Pembayaran listrik & internet asrama" required
                           class="w-full rounded-xl border border-border-line-3 bg-surface px-3 py-2 text-xs sm:text-sm text-foreground focus:border-brand-700 focus:outline-hidden">
                </div>

                <div class="flex justify-end">
                    <button type="submit" id="operasional-submit-btn"
                            class="px-4 py-2 rounded-xl bg-brand-700 text-white text-xs font-semibold hover:bg-brand-800 transition cursor-pointer">
                        Simpan Operasional
                    </button>
                </div>
            </form>

            <!-- Daftar Riwayat Operasional -->
            <div class="space-y-2">
                <h4 class="text-xs font-bold uppercase tracking-wider text-muted-foreground-1">Riwayat Pengeluaran Operasional ({{ $operasionalList->count() }})</h4>
                <div class="rounded-xl border border-border divide-y divide-border-line-1 overflow-hidden">
                    @forelse($operasionalList as $op)
                        <div class="p-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2 bg-card hover:bg-muted/40 transition text-xs">
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
                                <span class="font-extrabold text-sm text-foreground">Rp {{ number_format($op->nominal, 0, ',', '.') }}</span>
                                <div class="inline-flex items-center gap-1">
                                    <button type="button"
                                            onclick="editOperasional(@js($op->id), @js($op->kategori), @js($op->keterangan), @js((float) $op->nominal), @js($op->tanggal_pengeluaran?->format('Y-m-d')))"
                                            class="p-1.5 rounded-lg text-muted-foreground-2 hover:text-brand-700 hover:bg-brand-50 transition cursor-pointer"
                                            title="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" />
                                        </svg>
                                    </button>
                                    <form method="POST" action="{{ route('admin.pengeluaran-operasional.destroy', $op->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus data operasional ini?')"
                                                class="p-1.5 rounded-lg text-muted-foreground-2 hover:text-danger hover:bg-danger-soft transition cursor-pointer"
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
                        <div class="p-6 text-center text-xs text-muted-foreground">Belum ada riwayat pengeluaran operasional.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // --- Modal Bukti Transfer ---
    function showBuktiModal(id, nama, imgUrl) {
        document.getElementById('bukti-nama').textContent = nama;
        document.getElementById('bukti-img').src = imgUrl;
        document.getElementById('bukti-download').href = imgUrl;
        const modal = document.getElementById('modal-bukti');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeBuktiModal() {
        const modal = document.getElementById('modal-bukti');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.getElementById('bukti-img').src = '';
    }

    // --- Modal Edit Donasi ---
    function openEditModal(id, nama, email, wa, kampanyeId, nominal, metode, pesan, status) {
        document.getElementById('form-edit').action = '/admin/kelola-donasi/' + id;
        document.getElementById('edit-nama').value    = nama;
        document.getElementById('edit-email').value   = email;
        document.getElementById('edit-wa').value      = wa;
        document.getElementById('edit-nominal').value = nominal;
        document.getElementById('edit-metode').value  = metode;
        document.getElementById('edit-pesan').value   = pesan;
        document.getElementById('edit-status').value  = status;

        const kampanyeSelect = document.getElementById('edit-kampanye');
        kampanyeSelect.value = kampanyeId !== null ? kampanyeId : '';

        const modal = document.getElementById('modal-edit');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeEditModal() {
        const modal = document.getElementById('modal-edit');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // --- Modal Penyaluran Dana ---
    function openPenyaluranModal() {
        resetPenyaluranForm();
        const modal = document.getElementById('modal-penyaluran');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closePenyaluranModal() {
        const modal = document.getElementById('modal-penyaluran');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function editPenyaluran(id, klaster, keterangan, nominal, tanggal, penerima) {
        document.getElementById('form-penyaluran').action = '/admin/penyaluran-dana/' + id;
        document.getElementById('penyaluran-method').value = 'PUT';
        document.getElementById('penyaluran-form-label').textContent = 'Edit Penyaluran Dana';
        document.getElementById('penyaluran-submit-btn').textContent = 'Perbarui Penyaluran';
        document.getElementById('btn-reset-penyaluran').classList.remove('hidden');

        document.getElementById('penyaluran-klaster').value = klaster;
        document.getElementById('penyaluran-keterangan').value = keterangan;
        document.getElementById('penyaluran-nominal').value = nominal;
        document.getElementById('penyaluran-tanggal').value = tanggal;
        document.getElementById('penyaluran-penerima').value = penerima;
    }

    function resetPenyaluranForm() {
        document.getElementById('form-penyaluran').action = '{{ route('admin.penyaluran-dana.store') }}';
        document.getElementById('penyaluran-method').value = 'POST';
        document.getElementById('penyaluran-form-label').textContent = 'Tambah Penyaluran Baru';
        document.getElementById('penyaluran-submit-btn').textContent = 'Simpan Penyaluran';
        document.getElementById('btn-reset-penyaluran').classList.add('hidden');

        document.getElementById('penyaluran-klaster').value = 'pendidikan';
        document.getElementById('penyaluran-keterangan').value = '';
        document.getElementById('penyaluran-nominal').value = '';
        document.getElementById('penyaluran-penerima').value = '';
    }

    // --- Modal Pengeluaran Operasional ---
    function openOperasionalModal() {
        resetOperasionalForm();
        const modal = document.getElementById('modal-operasional');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeOperasionalModal() {
        const modal = document.getElementById('modal-operasional');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function editOperasional(id, kategori, keterangan, nominal, tanggal) {
        document.getElementById('form-operasional').action = '/admin/pengeluaran-operasional/' + id;
        document.getElementById('operasional-method').value = 'PUT';
        document.getElementById('operasional-form-label').textContent = 'Edit Pengeluaran Operasional';
        document.getElementById('operasional-submit-btn').textContent = 'Perbarui Operasional';
        document.getElementById('btn-reset-operasional').classList.remove('hidden');

        document.getElementById('operasional-kategori').value = kategori;
        document.getElementById('operasional-keterangan').value = keterangan;
        document.getElementById('operasional-nominal').value = nominal;
        document.getElementById('operasional-tanggal').value = tanggal;
    }

    function resetOperasionalForm() {
        document.getElementById('form-operasional').action = '{{ route('admin.pengeluaran-operasional.store') }}';
        document.getElementById('operasional-method').value = 'POST';
        document.getElementById('operasional-form-label').textContent = 'Tambah Pengeluaran Operasional';
        document.getElementById('operasional-submit-btn').textContent = 'Simpan Operasional';
        document.getElementById('btn-reset-operasional').classList.add('hidden');

        document.getElementById('operasional-kategori').value = '';
        document.getElementById('operasional-keterangan').value = '';
        document.getElementById('operasional-nominal').value = '';
    }

    // Tutup modal saat klik backdrop
    ['modal-bukti', 'modal-edit', 'modal-penyaluran', 'modal-operasional'].forEach(function(id) {
        document.getElementById(id).addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
                this.classList.remove('flex');
            }
        });
    });
</script>
@endsection
