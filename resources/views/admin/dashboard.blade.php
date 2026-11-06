@extends('layouts.admin')

@section('content')
<div class="space-y-4 pb-12" x-data="{ previewSrc: '', previewDonatur: '', previewNominal: '' }">
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

    <!-- Top Header & Export Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-brand-900">Ringkasan Dashboard</h1>
            <p class="text-xs text-muted-foreground-1 mt-0.5">Selamat datang kembali, Admin. Berikut ringkasan aktivitas hari ini.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
            <a href="{{ route('admin.dashboard.export.pdf', request()->only(['search', 'metode', 'tanggal'])) }}" 
               class="btn btn-secondary btn-sm rounded-full">
                <svg class="w-3.5 h-3.5 text-muted-foreground-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                <span>Ekspor PDF</span>
            </a>
            <a href="{{ route('admin.dashboard.export.excel', request()->only(['search', 'metode', 'tanggal'])) }}" 
               class="btn btn-secondary btn-sm rounded-full">
                <svg class="w-3.5 h-3.5 text-muted-foreground-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625c.621 0 1.125-.504 1.125-1.125V5.625m-1.125 13.875h-7.5c-.621 0-1.125-.504-1.125-1.125m9.75 0v-1.5c0-.621-.504-1.125-1.125-1.125m0 0h-7.5m7.5 0V5.625m-19.5 0A1.125 1.125 0 014.5 4.5h15a1.125 1.125 0 011.125 1.125v10.5m-16.125-10.5h16.125" />
                </svg>
                <span>Ekspor Excel</span>
            </a>
        </div>
    </div>

    <!-- 3 Stat Cards (Sesuai Desain Tangkapan Layar: Badge Ikon Tumpang Tindih di Kanan Atas) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5 pt-6">
        <!-- Card 1: TOTAL DONASI BULAN INI -->
        <div class="relative bg-card rounded-2xl p-4 sm:p-5 border border-brand-100 dark:border-brand-900 shadow-2xs flex flex-col justify-between overflow-hidden">
            <!-- Overlapping Icon Badge (Lingkaran Besar sesuai Gambar 2) -->
            <div class="absolute -top-4 -right-4 w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-brand-100 dark:bg-brand-900 flex items-center justify-center shadow-xs">
                <!-- Ikon Dompet (Wallet) -->
                <svg class="w-8 h-8 sm:w-9 sm:h-9 text-brand-900 dark:text-brand-200" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" />
                </svg>
            </div>
            <div>
                <span class="block pr-16 sm:pr-20 text-2xs font-bold uppercase tracking-wider text-muted-foreground-1 leading-tight">TOTAL DONASI BULAN INI</span>
                <div class="mt-1.5 sm:mt-2">
                    <span class="text-2xl sm:text-3xl font-extrabold tracking-tight text-foreground tabular">
                        Rp{{ number_format($summary['total_donasi_bulan_ini'], 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Card 2: TOTAL ANAK ASUH AKTIF -->
        <div class="relative bg-card rounded-2xl p-4 sm:p-5 border border-brand-100 dark:border-brand-900 shadow-2xs flex flex-col justify-between overflow-hidden">
            <!-- Overlapping Icon Badge (Lingkaran Besar sesuai Gambar 2) -->
            <div class="absolute -top-4 -right-4 w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-brand-100 dark:bg-brand-900 flex items-center justify-center shadow-xs">
                <svg class="w-8 h-8 sm:w-9 sm:h-9 text-brand-900 dark:text-brand-200" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-5.25 6.557c0 1.455.517 2.822 1.407 3.896" />
                </svg>
            </div>
            <div>
                <span class="block pr-16 sm:pr-20 text-2xs font-bold uppercase tracking-wider text-muted-foreground-1 leading-tight">TOTAL ANAK ASUH AKTIF</span>
                <div class="mt-1.5 sm:mt-2 flex items-baseline gap-1.5">
                    <span class="text-2xl sm:text-3xl font-extrabold tracking-tight text-foreground tabular">
                        {{ number_format($summary['total_anak_asuh_aktif'], 0, ',', '.') }}
                    </span>
                    <span class="text-xs sm:text-sm font-semibold text-muted-foreground-2">Santri</span>
                </div>
            </div>
        </div>

        <!-- Card 3: KAMPANYE AKTIF -->
        <div class="relative bg-card rounded-2xl p-4 sm:p-5 border border-brand-100 dark:border-brand-900 shadow-2xs flex flex-col justify-between overflow-hidden">
            <!-- Overlapping Icon Badge (Lingkaran Besar sesuai Gambar 2) -->
            <div class="absolute -top-4 -right-4 w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-brand-100 dark:bg-brand-900 flex items-center justify-center shadow-xs">
                <!-- Ikon Tangan Memegang Hati (sesuai Gambar 2) -->
                <svg class="w-8 h-8 sm:w-9 sm:h-9 text-brand-900 dark:text-brand-200" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 14h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 16" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="m7 20 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2 15 6 6" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.5c.7-.7 1.5-1.6 1.5-2.7A2.73 2.73 0 0 0 16 4a2.78 2.78 0 0 0-5 1.8c0 1.2.8 2 1.5 2.8L16 12Z" />
                </svg>
            </div>
            <div>
                <span class="block pr-16 sm:pr-20 text-2xs font-bold uppercase tracking-wider text-muted-foreground-1 leading-tight">KAMPANYE AKTIF</span>
                <div class="mt-1.5 sm:mt-2 flex items-baseline gap-1.5">
                    <span class="text-2xl sm:text-3xl font-extrabold tracking-tight text-foreground tabular">
                        {{ $summary['kampanye_aktif'] }}
                    </span>
                    <span class="text-xs sm:text-sm font-semibold text-muted-foreground-2">Program</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar Section -->
    <div class="bg-transparent flex flex-col lg:flex-row lg:items-center justify-between gap-3">
        <!-- Search Input -->
        <form method="GET" action="{{ route('admin.dashboard') }}" class="flex-1 max-w-md">
            <input type="hidden" name="metode" value="{{ $metode }}">
            @if($tanggal)
                <input type="hidden" name="tanggal" value="{{ $tanggal }}">
            @endif
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="w-4 h-4 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Cari ID Transaksi, Nama Donatur, atau Kampanye..." 
                       class="form-input pl-10 pr-4 py-2">
            </div>
        </form>

        <!-- Method Chips & Date Filter -->
        <div class="flex flex-wrap items-center gap-2">
            <!-- Chip: Semua -->
            <a href="{{ route('admin.dashboard', array_merge(request()->only(['search', 'tanggal']), ['metode' => 'all'])) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ $metode === 'all' || !$metode ? 'bg-brand-700 text-white shadow-2xs' : 'bg-surface text-muted-foreground-2 hover:bg-surface-hover' }}">
                Semua ({{ $counts['semua'] }})
            </a>

            <!-- Chip: Transfer Bank -->
            <a href="{{ route('admin.dashboard', array_merge(request()->only(['search', 'tanggal']), ['metode' => 'transfer_bank'])) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ $metode === 'transfer_bank' ? 'bg-brand-700 text-white shadow-2xs' : 'bg-surface text-muted-foreground-2 hover:bg-surface-hover' }}">
                Transfer Bank ({{ $counts['transfer_bank'] }})
            </a>

            <!-- Chip: QRIS Manual -->
            <a href="{{ route('admin.dashboard', array_merge(request()->only(['search', 'tanggal']), ['metode' => 'qris_manual'])) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ $metode === 'qris_manual' ? 'bg-brand-700 text-white shadow-2xs' : 'bg-surface text-muted-foreground-2 hover:bg-surface-hover' }}">
                QRIS Manual ({{ $counts['qris_manual'] }})
            </a>

            <!-- Date Filter Picker (Functional) -->
            <form method="GET" action="{{ route('admin.dashboard') }}" class="inline-flex items-center">
                <input type="hidden" name="search" value="{{ $search }}">
                <input type="hidden" name="metode" value="{{ $metode }}">
                <div class="relative inline-flex items-center">
                    <label for="tanggal_filter" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-surface text-muted-foreground-2 hover:bg-surface-hover cursor-pointer transition">
                        <svg class="w-3.5 h-3.5 text-muted-foreground-1" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.253M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                        <span>{{ $tanggal ? 'Tanggal: ' . \Carbon\Carbon::parse($tanggal)->translatedFormat('d M Y') : 'Semua Tanggal' }}</span>
                    </label>
                    <input type="date" 
                           id="tanggal_filter" 
                           name="tanggal" 
                           value="{{ $tanggal }}" 
                           onchange="this.form.submit()" 
                           class="absolute inset-0 opacity-0 cursor-pointer w-full">
                </div>
                @if($tanggal)
                    <a href="{{ route('admin.dashboard', request()->only(['search', 'metode'])) }}" 
                       title="Hapus filter tanggal" 
                       class="ml-1 text-muted-foreground hover:text-muted-foreground-2 text-xs px-1.5 py-1">
                        &times;
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Info Banner (Sesuai Desain Tangkapan Layar) -->
    <div class="rounded-xl bg-muted border border-border px-3.5 py-2.5 flex items-center justify-between text-xs text-muted-foreground-2">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-muted-foreground-1 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
            </svg>
            <span class="font-medium text-xs leading-relaxed">Menampilkan antrian konfirmasi donasi langsung rekening operasional BSI, BCA & Mandiri.</span>
        </div>
        <span class="text-muted-foreground font-normal hidden sm:inline text-xs shrink-0">Pembaruan otomatis tiap 60 detik</span>
    </div>

    <!-- Container Antrian Validasi Donasi (Tabel di Desktop md+, Kartu Nyaman di Mobile <md) -->
    <div class="bg-card rounded-2xl border border-border shadow-2xs overflow-hidden">
        
        <!-- 1. Tampilan Tabel Khusus Desktop & Tablet (md ke atas) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-sm table-fixed min-w-[800px]">
                <colgroup>
                    <col class="w-[22%]">
                    <col class="w-[15%]">
                    <col class="w-[23%]">
                    <col class="w-[13%]">
                    <col class="w-[15%]">
                    <col class="w-[12%]">
                </colgroup>
                <thead>
                    <tr class="border-b border-border-line-1 text-2xs font-bold text-muted-foreground uppercase tracking-wider">
                        <th class="w-[22%] py-2.5 sm:py-3 px-4 sm:px-6 font-semibold text-left">DONATUR</th>
                        <th class="w-[15%] py-2.5 sm:py-3 px-4 sm:px-6 font-semibold text-center">NOMINAL</th>
                        <th class="w-[23%] py-2.5 sm:py-3 px-4 sm:px-6 font-semibold text-center">DOA/HARAPAN</th>
                        <th class="w-[13%] py-2.5 sm:py-3 px-4 sm:px-6 font-semibold text-center">BUKTI TRANSFER</th>
                        <th class="w-[15%] py-2.5 sm:py-3 px-4 sm:px-6 font-semibold text-center">STATUS</th>
                        <th class="w-[12%] py-2.5 sm:py-3 px-4 sm:px-6 font-semibold text-center">AKSI VALIDASI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-line-1">
                    @forelse($donasiPending as $donasi)
                        <tr class="hover:bg-muted/60 transition">
                            <!-- DONATUR (Rata Kiri) -->
                            <td class="py-2.5 sm:py-3 px-4 sm:px-6 align-middle text-left">
                                <div class="flex flex-col items-start justify-center text-left max-w-full">
                                    <p class="font-bold text-foreground leading-tight text-left truncate max-w-full">{{ $donasi->nama_donatur }}</p>
                                    @if($donasi->no_whatsapp)
                                        <p class="text-xs text-muted-foreground-1 font-medium mt-0.5 flex items-center justify-start gap-1.5 truncate max-w-full">
                                            <svg class="w-3.5 h-3.5 text-brand-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                                            </svg>
                                            <span>{{ $donasi->no_whatsapp }}</span>
                                        </p>
                                    @endif
                                    @if($donasi->kampanye)
                                        <p class="text-2xs text-brand-700 dark:text-brand-300 font-medium mt-0.5 text-left truncate max-w-full">{{ $donasi->kampanye->judul }}</p>
                                    @endif
                                </div>
                            </td>

                            <!-- NOMINAL (Rata Tengah) -->
                            <td class="py-2.5 sm:py-3 px-4 sm:px-6 align-middle text-center">
                                <span class="font-extrabold text-sm sm:text-base text-brand-900 dark:text-brand-200 block text-center truncate">
                                    Rp{{ number_format($donasi->nominal, 0, ',', '.') }}
                                </span>
                            </td>

                            <!-- DOA/HARAPAN (Rata Tengah) -->
                            <td class="py-2.5 sm:py-3 px-4 sm:px-6 align-middle text-center">
                                <p class="text-xs text-muted-foreground-2 line-clamp-2 text-center mx-auto break-words">
                                    {{ $donasi->pesan_doa ?: '-' }}
                                </p>
                            </td>

                            <!-- BUKTI TRANSFER THUMBNAIL (Rata Tengah & Buka Overlay Modal) -->
                            <td class="py-2.5 sm:py-3 px-4 sm:px-6 align-middle text-center">
                                @php
                                    $proofUrl = $donasi->bukti_transfer_path ? asset($donasi->bukti_transfer_path) : null;
                                @endphp
                                @if($proofUrl)
                                    <div class="flex justify-center">
                                        <button type="button" 
                                                data-hs-overlay="#proof-preview-modal"
                                                aria-haspopup="dialog"
                                                aria-controls="proof-preview-modal"
                                                @click="previewSrc = '{{ $proofUrl }}'; previewDonatur = '{{ addslashes($donasi->nama_donatur) }}'; previewNominal = 'Rp{{ number_format($donasi->nominal, 0, ',', '.') }}'"
                                                aria-label="Lihat bukti transfer {{ $donasi->nama_donatur }}"
                                                class="group block w-9 h-12 rounded-lg overflow-hidden border border-border shadow-2xs hover:border-brand-600 transition bg-muted cursor-pointer">
                                            <img src="{{ $proofUrl }}" alt="Bukti Transfer" class="w-full h-full object-cover group-hover:scale-105 transition">
                                        </button>
                                    </div>
                                @else
                                    <span class="text-xs text-muted-foreground italic">Tidak ada foto</span>
                                @endif
                            </td>

                            <!-- STATUS (Rata Tengah) -->
                            <td class="py-2.5 sm:py-3 px-4 sm:px-6 align-middle text-center">
                                <div class="flex justify-center">
                                    @if($donasi->status === 'pending')
                                        <span class="badge badge-warning gap-1.5">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>Menunggu Verifikasi</span>
                                        </span>
                                    @elseif($donasi->status === 'verified')
                                        <span class="badge badge-success gap-1.5">
                                            ✓ Terverifikasi
                                        </span>
                                    @else
                                        <span class="badge badge-danger gap-1.5">
                                            ✕ Ditolak
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- AKSI VALIDASI (Rata Tengah) -->
                            <td class="py-2.5 sm:py-3 px-4 sm:px-6 align-middle text-center">
                                <div class="inline-flex items-center justify-center gap-1.5">
                                    <!-- Tombol Verif -->
                                    <form method="POST" action="{{ route('admin.donasi.verifikasi', $donasi->id) }}">
                                        @csrf
                                        <button type="submit" 
                                                onclick="return confirm('Verifikasi donasi sebesar Rp{{ number_format($donasi->nominal, 0, ',', '.') }} dari {{ $donasi->nama_donatur }}?')"
                                                class="px-3 py-1 rounded-lg text-xs font-semibold border border-success/40 bg-success-soft text-success hover:bg-success hover:text-white transition">
                                            Verif
                                        </button>
                                    </form>

                                    <!-- Tombol Tolak -->
                                    <form method="POST" action="{{ route('admin.donasi.tolak', $donasi->id) }}">
                                        @csrf
                                        <button type="submit" 
                                                onclick="return confirm('Tolak donasi ini?')"
                                                class="px-3 py-1 rounded-lg text-xs font-semibold border border-danger/40 bg-danger-soft text-danger hover:bg-danger hover:text-white transition">
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-muted-foreground text-xs sm:text-sm">
                                Tidak ada transaksi yang menunggu verifikasi sesuai filter yang dipilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 2. Tampilan Kartu Khusus Mobile (< md): Nyaman, Lega, Tanpa Scroll Horizontal -->
        <div class="md:hidden space-y-3 p-3 bg-muted/60">
            @forelse($donasiPending as $donasi)
                <div class="bg-card rounded-xl border border-border shadow-2xs p-4 space-y-3 transition hover:border-brand-500">
                    <!-- Baris 1: Donatur & Badge Status -->
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0 flex-1">
                            <p class="font-bold text-foreground leading-snug truncate">{{ $donasi->nama_donatur }}</p>
                            @if($donasi->no_whatsapp)
                                <p class="text-xs text-muted-foreground-1 font-medium mt-0.5 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-brand-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                                    </svg>
                                    <span>{{ $donasi->no_whatsapp }}</span>
                                </p>
                            @endif
                        </div>
                        <div class="shrink-0">
                            @if($donasi->status === 'pending')
                                <span class="badge badge-warning gap-1">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Menunggu</span>
                                </span>
                            @elseif($donasi->status === 'verified')
                                <span class="badge badge-success gap-1">
                                    ✓ Terverifikasi
                                </span>
                            @else
                                <span class="badge badge-danger gap-1">
                                    ✕ Ditolak
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Baris 2: Nominal & Program Kampanye -->
                    <div class="flex items-center justify-between gap-2 pt-1 border-t border-border-line-1">
                        <span class="font-extrabold text-base text-brand-900 dark:text-brand-200">
                            Rp{{ number_format($donasi->nominal, 0, ',', '.') }}
                        </span>
                        @if($donasi->kampanye)
                            <span class="text-2xs font-medium text-brand-700 dark:text-brand-300 bg-brand-50 dark:bg-brand-900/50 px-2.5 py-0.5 rounded-md truncate max-w-[180px]">
                                {{ $donasi->kampanye->judul }}
                            </span>
                        @endif
                    </div>

                    <!-- Baris 3: Doa / Harapan Donatur -->
                    @if($donasi->pesan_doa)
                        <div class="p-2.5 rounded-xl bg-muted border border-border-line-1 text-xs text-muted-foreground-2 italic">
                            &ldquo;{{ $donasi->pesan_doa }}&rdquo;
                        </div>
                    @endif

                    <!-- Baris 4: Bukti Transfer & Tombol Aksi -->
                    <div class="flex items-center justify-between gap-3 pt-1">
                        @php
                            $proofUrlMobile = $donasi->bukti_transfer_path ? asset($donasi->bukti_transfer_path) : null;
                        @endphp
                        <div>
                            @if($proofUrlMobile)
                                <button type="button" 
                                        data-hs-overlay="#proof-preview-modal"
                                        aria-haspopup="dialog"
                                        aria-controls="proof-preview-modal"
                                        @click="previewSrc = '{{ $proofUrlMobile }}'; previewDonatur = '{{ addslashes($donasi->nama_donatur) }}'; previewNominal = 'Rp{{ number_format($donasi->nominal, 0, ',', '.') }}'"
                                        class="inline-flex items-center gap-2 px-2.5 py-1.5 rounded-lg border border-border bg-muted text-xs font-medium text-muted-foreground-2 hover:border-brand-600 transition cursor-pointer">
                                    <img src="{{ $proofUrlMobile }}" alt="Struk" class="w-6 h-7 rounded object-cover">
                                    <span>Bukti Struk</span>
                                </button>
                            @else
                                <span class="text-xs text-muted-foreground italic">Tanpa bukti</span>
                            @endif
                        </div>

                        <!-- Tombol Verif & Tolak (Touch-Friendly di Mobile) -->
                        <div class="inline-flex items-center gap-2">
                            <form method="POST" action="{{ route('admin.donasi.verifikasi', $donasi->id) }}">
                                @csrf
                                <button type="submit" 
                                        onclick="return confirm('Verifikasi donasi sebesar Rp{{ number_format($donasi->nominal, 0, ',', '.') }} dari {{ $donasi->nama_donatur }}?')"
                                        class="px-3.5 py-1.5 rounded-lg text-xs font-semibold border border-success/40 bg-success-soft text-success hover:bg-success hover:text-white transition shadow-2xs">
                                    Verif
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.donasi.tolak', $donasi->id) }}">
                                @csrf
                                <button type="submit" 
                                        onclick="return confirm('Tolak donasi ini?')"
                                        class="px-3.5 py-1.5 rounded-lg text-xs font-semibold border border-danger/40 bg-danger-soft text-danger hover:bg-danger hover:text-white transition shadow-2xs">
                                    Tolak
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-card rounded-xl border border-border py-10 text-center text-muted-foreground text-xs">
                    Tidak ada transaksi yang menunggu verifikasi sesuai filter yang dipilih.
                </div>
            @endforelse
        </div>

        <!-- Table Footer: Counter & Pagination (Rapi & Simetris di Mobile & Desktop) -->
        <div class="px-5 py-3.5 border-t border-border-line-1 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-muted-foreground-1">
            <div class="text-xs text-muted-foreground-1 font-medium flex items-center justify-center sm:justify-start text-center sm:text-left">
                Menampilkan <span class="font-semibold text-foreground mx-1">{{ $donasiPending->count() }}</span> dari <span class="font-semibold text-foreground mx-1">{{ $donasiPending->total() }}</span> transaksi pending verifikasi
            </div>

            <!-- Custom Pagination Pills matching Screenshot (Rapi & Sejajar) -->
            @if ($donasiPending->hasPages())
                <nav class="flex items-center justify-center sm:justify-end gap-1.5">
                    {{-- Previous Page Link --}}
                    @if ($donasiPending->onFirstPage())
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center text-muted-foreground/60 bg-muted cursor-not-allowed">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                            </svg>
                        </span>
                    @else
                        <a href="{{ $donasiPending->previousPageUrl() }}" 
                           class="w-8 h-8 rounded-lg flex items-center justify-center text-muted-foreground-2 hover:text-foreground hover:bg-muted border border-transparent hover:border-border transition"
                           title="Halaman Sebelumnya">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                            </svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($donasiPending->getUrlRange(1, $donasiPending->lastPage()) as $page => $url)
                        @if ($page == $donasiPending->currentPage())
                            <span class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs text-white bg-brand-700 shadow-xs leading-none">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="w-8 h-8 rounded-lg flex items-center justify-center font-semibold text-xs text-muted-foreground-2 hover:text-foreground hover:bg-muted border border-transparent hover:border-border transition leading-none">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($donasiPending->hasMorePages())
                        <a href="{{ $donasiPending->nextPageUrl() }}" 
                           class="w-8 h-8 rounded-lg flex items-center justify-center text-muted-foreground-2 hover:text-foreground hover:bg-muted border border-transparent hover:border-border transition"
                           title="Halaman Selanjutnya">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </a>
                    @else
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center text-muted-foreground/60 bg-muted cursor-not-allowed">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </span>
                    @endif
                </nav>
            @endif
        </div>
    </div>

    <!-- Modal Preview Bukti Transfer (Preline Modal) -->
    <div id="proof-preview-modal"
         class="hs-overlay hidden size-full fixed top-0 inset-s-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none"
         role="dialog"
         tabindex="-1"
         aria-labelledby="proof-preview-modal-label">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-md sm:w-full m-3 sm:mx-auto">
            <div class="relative bg-overlay rounded-2xl shadow-2xl border border-overlay-line overflow-hidden w-full flex flex-col max-h-[90vh] pointer-events-auto">
                <!-- Header Modal -->
                <div class="px-5 py-3.5 border-b border-border-line-1 flex items-center justify-between bg-muted/60">
                    <div class="min-w-0 pr-3">
                        <h3 id="proof-preview-modal-label" class="font-bold text-sm text-foreground leading-tight">Bukti Transfer</h3>
                        <p class="text-xs text-muted-foreground-1 font-medium mt-0.5 truncate">
                            <span x-text="previewDonatur" class="font-semibold text-foreground"></span> &bull; <span x-text="previewNominal" class="font-bold text-brand-700 dark:text-brand-300"></span>
                        </p>
                    </div>
                    <button type="button" 
                            data-hs-overlay="#proof-preview-modal"
                            class="size-8 inline-flex justify-center items-center rounded-full bg-surface border border-surface-line text-surface-foreground hover:bg-surface-hover transition shrink-0"
                            aria-label="Tutup">
                        <span class="sr-only">Tutup</span>
                        <svg class="shrink-0 size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Isi Gambar Struk (Scrollable jika layar kecil) -->
                <div class="p-4 overflow-y-auto flex items-center justify-center bg-muted/60">
                    <img :src="previewSrc" 
                         alt="Bukti Transfer Donasi" 
                         class="max-h-[60vh] sm:max-h-[65vh] w-auto rounded-xl border border-border shadow-xs object-contain bg-card">
                </div>

                <!-- Footer Modal -->
                <div class="px-5 py-3 border-t border-border-line-1 flex items-center justify-between text-xs bg-overlay">
                    <span class="text-muted-foreground text-2xs hidden sm:inline">Tekan ESC atau klik luar untuk menutup</span>
                    <span class="text-muted-foreground text-2xs sm:hidden">Ketuk luar untuk menutup</span>
                    <button type="button" 
                            data-hs-overlay="#proof-preview-modal"
                            class="btn btn-ghost btn-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Auto-refresh Script: hanya me-refresh jika tab aktif dan pengguna tidak sedang mengetik/fokus -->
<script>
    setInterval(function() {
        if (document.visibilityState === 'visible') {
            const activeEl = document.activeElement;
            const isTyping = activeEl && (
                activeEl.tagName === 'INPUT' || 
                activeEl.tagName === 'TEXTAREA' || 
                activeEl.isContentEditable
            );
            if (!isTyping) {
                window.location.reload();
            }
        }
    }, 60000);
</script>
@endsection
