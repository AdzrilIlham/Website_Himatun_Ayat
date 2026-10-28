@extends('layouts.admin')

@section('content')
<div class="space-y-5 pb-12 w-full max-w-full min-w-0" 
     x-data="{ 
        selectedAnak: null,
        createPhotoPreview: null,
        editPhotoPreview: null,
        editData: {
            id: '',
            nama_lengkap: '',
            nama_panggilan: '',
            jenis_kelamin: 'L',
            tanggal_lahir: '',
            pendidikan_terakhir: 'SD',
            status_asuhan: 'Aktif',
            keterangan: '',
            foto_path: ''
        },
        openDetail(anak) {
            this.selectedAnak = anak;
        },
        openEdit(anak) {
            this.editPhotoPreview = null;
            this.editData = {
                id: anak.id,
                nama_lengkap: anak.nama_lengkap,
                nama_panggilan: anak.nama_panggilan || '',
                jenis_kelamin: anak.jenis_kelamin,
                tanggal_lahir: anak.tanggal_lahir ? anak.tanggal_lahir.substring(0, 10) : '',
                pendidikan_terakhir: anak.pendidikan_terakhir || 'SD',
                status_asuhan: anak.status_asuhan,
                keterangan: anak.keterangan || '',
                foto_path: anak.foto_path || ''
            };
            this.$nextTick(() => {
                const form = this.$root.querySelector('#edit-anak-form');
                ['jenis_kelamin', 'pendidikan_terakhir', 'status_asuhan'].forEach((name) => {
                    window.syncPrelineSelect(form.querySelector('[name=' + name + ']'));
                });
            });
        },
        handleCreatePhoto(e) {
            const file = e.target.files[0];
            if (file) {
                this.createPhotoPreview = URL.createObjectURL(file);
            } else {
                this.createPhotoPreview = null;
            }
        },
        clearCreatePhoto() {
            this.createPhotoPreview = null;
            const input = document.getElementById('create_foto_input');
            if (input) input.value = '';
        },
        handleEditPhoto(e) {
            const file = e.target.files[0];
            if (file) {
                this.editPhotoPreview = URL.createObjectURL(file);
            } else {
                this.editPhotoPreview = null;
            }
        },
        clearEditPhoto() {
            this.editPhotoPreview = null;
            const input = document.getElementById('edit_foto_input');
            if (input) input.value = '';
        }
     }">

    <!-- Flash Notifications -->
    @if(session('success'))
        <div class="p-3.5 rounded-xl bg-brand-50 border border-brand-200 text-brand-800 text-xs sm:text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-brand-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-brand-600 hover:text-brand-900">&times;</button>
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

    <!-- Top Header & Action Buttons (Sesuai Desain Tangkapan Layar & Konsisten dengan Dashboard) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-brand-700">Data Anak Asuh</h1>
            <p class="text-xs text-muted-foreground-1 mt-0.5">Kelola data dan informasi anak asuh Panti Asuhan Himmatun Ayat Bandung.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
            <!-- Ekspor PDF -->
            <a href="{{ route('admin.anak-asuh.export.pdf', request()->only(['search', 'status'])) }}" 
               class="inline-flex items-center gap-1.5 px-3.5 sm:px-4 py-1.5 sm:py-2 rounded-full border border-border-line-7 bg-card text-xs font-semibold text-muted-foreground-2 hover:bg-muted hover:border-border-line-8 transition shadow-2xs">
                <svg class="w-3.5 h-3.5 text-muted-foreground-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                <span>Ekspor PDF</span>
            </a>

            <!-- Ekspor Excel -->
            <a href="{{ route('admin.anak-asuh.export.excel', request()->only(['search', 'status'])) }}" 
               class="inline-flex items-center gap-1.5 px-3.5 sm:px-4 py-1.5 sm:py-2 rounded-full border border-border-line-7 bg-card text-xs font-semibold text-muted-foreground-2 hover:bg-muted hover:border-border-line-8 transition shadow-2xs">
                <svg class="w-3.5 h-3.5 text-muted-foreground-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625c.621 0 1.125-.504 1.125-1.125V5.625m-1.125 13.875h-7.5c-.621 0-1.125-.504-1.125-1.125m9.75 0v-1.5c0-.621-.504-1.125-1.125-1.125m0 0h-7.5m7.5 0V5.625m-19.5 0A1.125 1.125 0 014.5 4.5h15a1.125 1.125 0 011.125 1.125v10.5m-16.125-10.5h16.125" />
                </svg>
                <span>Ekspor Excel</span>
            </a>

            <!-- + Tambah Anak Asuh -->
            <button type="button" 
                    data-hs-overlay="#create-anak-modal"
                    aria-haspopup="dialog"
                    aria-controls="create-anak-modal"
                    class="inline-flex items-center gap-1.5 px-4 sm:px-5 py-1.5 sm:py-2 rounded-full bg-brand-700 text-xs font-semibold text-white hover:bg-brand-800 transition shadow-xs cursor-pointer">
                <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Tambah Anak Asuh</span>
            </button>
        </div>
    </div>

    <!-- 5 Stat Summary Cards (Sesuai Desain Tangkapan Layar & Konsisten dengan Dashboard) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-5 gap-3 sm:gap-4 w-full">
        <!-- Card 1: TOTAL ANAK BINAAN -->
        <div class="bg-muted/80 border border-border rounded-2xl p-4 flex flex-col justify-between">
            <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-muted-foreground-1">TOTAL ANAK BINAAN</span>
            <div class="mt-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-extrabold tracking-tight text-foreground">
                    {{ number_format($stats['total_aktif'], 0, ',', '.') }}
                </span>
                <span class="text-xs sm:text-sm font-semibold text-brand-700">Jiwa Aktif</span>
            </div>
        </div>

        <!-- Card 2: TINGKAT SD / MI -->
        <div class="bg-muted/80 border border-border rounded-2xl p-4 flex flex-col justify-between">
            <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-muted-foreground-1">TINGKAT SD / MI</span>
            <div class="mt-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-extrabold tracking-tight text-foreground">
                    {{ number_format($stats['sd']['count'], 0, ',', '.') }}
                </span>
                <span class="text-xs sm:text-sm font-semibold text-muted-foreground-2">Santri ({{ $stats['sd']['persen'] }}%)</span>
            </div>
        </div>

        <!-- Card 3: SMP / MTS -->
        <div class="bg-muted/80 border border-border rounded-2xl p-4 flex flex-col justify-between">
            <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-muted-foreground-1">SMP / MTS</span>
            <div class="mt-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-extrabold tracking-tight text-foreground">
                    {{ number_format($stats['smp']['count'], 0, ',', '.') }}
                </span>
                <span class="text-xs sm:text-sm font-semibold text-muted-foreground-2">Santri ({{ $stats['smp']['persen'] }}%)</span>
            </div>
        </div>

        <!-- Card 4: SMA / SMK / MA -->
        <div class="bg-muted/80 border border-border rounded-2xl p-4 flex flex-col justify-between">
            <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-muted-foreground-1">SMA / SMK / MA</span>
            <div class="mt-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-extrabold tracking-tight text-foreground">
                    {{ number_format($stats['sma']['count'], 0, ',', '.') }}
                </span>
                <span class="text-xs sm:text-sm font-semibold text-muted-foreground-2">Santri ({{ $stats['sma']['persen'] }}%)</span>
            </div>
        </div>

        <!-- Card 5: PERGURUAN TINGGI -->
        <div class="bg-muted/80 border border-border rounded-2xl p-4 flex flex-col justify-between">
            <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-muted-foreground-1">PERGURUAN TINGGI</span>
            <div class="mt-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-extrabold tracking-tight text-foreground">
                    {{ number_format($stats['pt']['count'], 0, ',', '.') }}
                </span>
                <span class="text-xs sm:text-sm font-semibold text-muted-foreground-2">Mahasiswa ({{ $stats['pt']['persen'] }}%)</span>
            </div>
        </div>
    </div>

    <!-- Main Container: Filter Bar Hijau Gelap & Tabel Data (Sesuai Desain Tangkapan Layar) -->
    <div class="bg-card rounded-2xl border border-border shadow-2xs overflow-hidden w-full max-w-full min-w-0">
        
        <!-- Filter Bar Hijau Gelap (#2D6A4F) -->
        <div class="bg-brand-700 px-4 sm:px-6 py-4">
            <form method="GET" action="{{ route('admin.anak-asuh.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                <!-- Search Box (Cari nama...) -->
                <div class="relative flex-1 max-w-md">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                        <svg class="w-4 h-4 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input type="text" 
                           name="search" 
                           value="{{ $search }}" 
                           placeholder="Cari nama..." 
                           @input.debounce.500ms="$el.form.submit()" 
                           class="w-full rounded-xl bg-card pl-10 pr-9 py-2.5 text-xs sm:text-sm text-foreground placeholder:text-muted-foreground focus:outline-hidden shadow-2xs">
                    @if($search)
                        <a href="{{ route('admin.anak-asuh.index', array_filter(['status' => ($status && $status !== 'Semua Status' && $status !== 'all') ? $status : null])) }}" 
                           title="Hapus pencarian" 
                           class="absolute inset-y-0 right-0 flex items-center pr-3 text-muted-foreground hover:text-muted-foreground-2 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>

                <!-- Filter Status Dropdown & Reset Button -->
                <div class="flex items-center gap-2 justify-end">
                    @if($search || ($status && $status !== 'Semua Status' && $status !== 'all'))
                        <a href="{{ route('admin.anak-asuh.index') }}" 
                           class="inline-flex items-center gap-1 px-2.5 py-2 text-xs font-medium text-brand-200 hover:text-white hover:bg-brand-800/60 rounded-xl transition"
                           title="Reset semua filter">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            <span>Reset</span>
                        </a>
                    @endif

                    <span class="text-xs font-medium text-brand-100 shrink-0">Filter:</span>
                    <x-preline-select
                        name="status"
                        onchange="this.form.submit()"
                        placeholder="Semua Status"
                        wrapper-classes="relative w-44"
                        toggle-classes="hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2 ps-3.5 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-brand-800/60 hover:bg-brand-800/80 border border-brand-600/50 rounded-xl text-start text-xs sm:text-sm font-medium text-white focus:outline-hidden transition"
                        dropdown-classes="mt-2 z-50 w-full max-h-72 p-1 space-y-0.5 bg-card border border-border rounded-xl shadow-lg overflow-hidden overflow-y-auto"
                        option-classes="py-2 px-3 w-full text-xs sm:text-sm text-foreground cursor-pointer hover:bg-brand-50 rounded-lg focus:outline-hidden focus:bg-brand-50 hs-selected:bg-brand-50 hs-selected:text-brand-800">
                        <option value="Semua Status" @selected(!$status || $status === 'Semua Status' || $status === 'all')>Semua Status</option>
                        <option value="SD" @selected($status === 'SD')>SD</option>
                        <option value="SMP" @selected($status === 'SMP')>SMP</option>
                        <option value="SMA" @selected($status === 'SMA')>SMA</option>
                        <option value="Perguruan Tinggi" @selected($status === 'Perguruan Tinggi')>Perguruan Tinggi</option>
                    </x-preline-select>
                </div>
            </form>
        </div>

        <!-- 1. Tampilan Desktop & Tablet (md ke atas) -->
        <div class="hidden md:block overflow-x-auto w-full">
            <table class="w-full text-left text-sm table-fixed min-w-[750px]">
                <colgroup>
                    <col class="w-[30%]">
                    <col class="w-[18%]">
                    <col class="w-[20%]">
                    <col class="w-[20%]">
                    <col class="w-[12%]">
                </colgroup>
                <thead class="bg-surface text-xs font-bold text-muted-foreground-2">
                    <tr class="border-b border-border">
                        <th class="py-3 px-6 text-left">Nama</th>
                        <th class="py-3 px-6 text-left">Usia</th>
                        <th class="py-3 px-6 text-center">Status Pendidikan</th>
                        <th class="py-3 px-6 text-left">Tanggal Bergabung</th>
                        <th class="py-3 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-line-1">
                    @forelse($anakAsuhList as $anak)
                        <tr class="hover:bg-muted transition">
                            <!-- Kolom Nama (Avatar & Nama Lengkap) -->
                            <td class="py-3.5 px-6 align-middle">
                                <div class="flex items-center gap-3 min-w-0">
                                    @if($anak->foto_path && file_exists(public_path($anak->foto_path)))
                                        <img src="{{ asset($anak->foto_path) }}" 
                                             alt="{{ $anak->nama_lengkap }}" 
                                             class="w-10 h-10 rounded-full object-cover shrink-0 border border-border">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-surface border border-border text-foreground font-bold text-sm flex items-center justify-center shrink-0">
                                            {{ $anak->inisial }}
                                        </div>
                                    @endif
                                    <div class="min-w-0 truncate">
                                        <p class="font-bold text-foreground leading-tight truncate">{{ $anak->nama_lengkap }}</p>
                                        @if($anak->nama_panggilan)
                                            <p class="text-xs text-muted-foreground mt-0.5 truncate">({{ $anak->nama_panggilan }})</p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Kolom Usia -->
                            <td class="py-3.5 px-6 align-middle text-muted-foreground-2 font-medium">
                                {{ $anak->usia_formatted }}
                            </td>

                            <!-- Kolom Status Pendidikan (Badge Sesuai Desain) -->
                            <td class="py-3.5 px-6 align-middle text-center">
                                @php
                                    $p = strtoupper($anak->pendidikan_terakhir ?? 'SD');
                                @endphp
                                @if(str_contains($p, 'SD') || str_contains($p, 'MI'))
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-brand-100 dark:bg-brand-900/50 text-brand-700 dark:text-brand-200">
                                        SD
                                    </span>
                                @elseif(str_contains($p, 'SMP') || str_contains($p, 'MTS'))
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-brand-200 text-brand-900">
                                        SMP
                                    </span>
                                @elseif(str_contains($p, 'SMA') || str_contains($p, 'SMK') || str_contains($p, 'MA'))
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-surface-hover text-muted-foreground-2">
                                        SMA
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-info-soft text-info">
                                        PT
                                    </span>
                                @endif
                            </td>

                            <!-- Kolom Tanggal Bergabung -->
                            <td class="py-3.5 px-6 align-middle text-muted-foreground-2 text-sm">
                                {{ $anak->created_at->translatedFormat('d M Y') }}
                            </td>

                            <!-- Kolom Aksi (Ikon Mata & Pensil Sesuai Desain) -->
                            <td class="py-3.5 px-6 align-middle text-center">
                                <div class="inline-flex items-center justify-center gap-2">
                                    <!-- Detail (Mata) -->
                                    <button type="button" 
                                            data-hs-overlay="#detail-anak-modal"
                                            aria-haspopup="dialog"
                                            aria-controls="detail-anak-modal"
                                            @click="openDetail({{ json_encode($anak) }})"
                                            title="Lihat Detail" 
                                            class="p-1.5 text-muted-foreground hover:text-brand-700 hover:bg-brand-50 rounded-lg transition cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>

                                    <!-- Edit (Pensil) -->
                                    <button type="button" 
                                            data-hs-overlay="#edit-anak-modal"
                                            aria-haspopup="dialog"
                                            aria-controls="edit-anak-modal"
                                            @click="openEdit({{ json_encode($anak) }})"
                                            title="Ubah Data" 
                                            class="p-1.5 text-muted-foreground hover:text-muted-foreground-2 hover:bg-muted rounded-lg transition cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </button>

                                    <!-- Hapus (Sampah) -->
                                    <form method="POST" action="{{ route('admin.anak-asuh.destroy', $anak->id) }}" onsubmit="return confirm('Hapus data anak asuh {{ $anak->nama_lengkap }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Hapus" 
                                                class="p-1.5 text-muted-foreground/60 hover:text-danger hover:bg-danger-soft rounded-lg transition cursor-pointer">
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
                            <td colspan="5" class="py-12 text-center text-muted-foreground text-xs sm:text-sm">
                                Tidak ada data anak asuh yang sesuai dengan pencarian atau filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 2. Tampilan Khusus Mobile (< md) Card Per Transaksi -->
        <div class="md:hidden space-y-3 p-3 bg-muted">
            @forelse($anakAsuhList as $anak)
                <div class="bg-card rounded-xl border border-border shadow-2xs p-4 space-y-3 transition">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-2.5 min-w-0">
                            @if($anak->foto_path && file_exists(public_path($anak->foto_path)))
                                <img src="{{ asset($anak->foto_path) }}" alt="{{ $anak->nama_lengkap }}" class="w-10 h-10 rounded-full object-cover shrink-0 border border-border">
                            @else
                                <div class="w-10 h-10 rounded-full bg-surface border border-border text-foreground font-bold text-sm flex items-center justify-center shrink-0">
                                    {{ $anak->inisial }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="font-bold text-foreground leading-tight truncate">{{ $anak->nama_lengkap }}</p>
                                <p class="text-xs text-muted-foreground-1 mt-0.5">Usia: {{ $anak->usia_formatted }}</p>
                            </div>
                        </div>

                        <!-- Badge Pendidikan -->
                        @php $p = strtoupper($anak->pendidikan_terakhir ?? 'SD'); @endphp
                        @if(str_contains($p, 'SD') || str_contains($p, 'MI'))
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-brand-100 dark:bg-brand-900/50 text-brand-700 dark:text-brand-200">SD</span>
                        @elseif(str_contains($p, 'SMP') || str_contains($p, 'MTS'))
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-brand-200 text-brand-900">SMP</span>
                        @elseif(str_contains($p, 'SMA') || str_contains($p, 'SMK') || str_contains($p, 'MA'))
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-surface-hover text-muted-foreground-2">SMA</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-info-soft text-info">PT</span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between text-xs text-muted-foreground-1 pt-2 border-t border-border-line-1">
                        <span>Bergabung: {{ $anak->created_at->translatedFormat('d M Y') }}</span>
                        <div class="inline-flex items-center gap-1.5">
                            <button type="button" data-hs-overlay="#detail-anak-modal" aria-haspopup="dialog" aria-controls="detail-anak-modal" @click="openDetail({{ json_encode($anak) }})" title="Lihat Detail" class="p-1.5 text-muted-foreground hover:text-brand-700 hover:bg-brand-50 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                            <button type="button" data-hs-overlay="#edit-anak-modal" aria-haspopup="dialog" aria-controls="edit-anak-modal" @click="openEdit({{ json_encode($anak) }})" title="Ubah Data" class="p-1.5 text-muted-foreground hover:text-foreground hover:bg-muted rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </button>
                            <form method="POST" action="{{ route('admin.anak-asuh.destroy', $anak->id) }}" onsubmit="return confirm('Hapus data anak asuh {{ $anak->nama_lengkap }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Hapus Data" class="p-1.5 text-muted-foreground/60 hover:text-danger hover:bg-danger-soft rounded-lg transition cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-card rounded-xl border border-border py-10 text-center text-muted-foreground text-xs">
                    Tidak ada data anak asuh yang sesuai dengan pencarian atau filter.
                </div>
            @endforelse
        </div>

        <!-- Table Footer: Counter & Pagination (Sesuai Desain Tangkapan Layar Gambar 2) -->
        <div class="px-6 py-4 border-t border-border-line-1 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-card">
            <div class="text-sm text-muted-foreground-2 font-normal text-center sm:text-left">
                @if($anakAsuhList->total() > 0)
                    Menampilkan {{ $anakAsuhList->firstItem() }}-{{ $anakAsuhList->lastItem() }} dari {{ $anakAsuhList->total() }} anak
                @else
                    Menampilkan 0 anak
                @endif
            </div>

            <!-- Styled Pagination matching Screenshot 2 (< [1] 2 3 >) -->
            @if ($anakAsuhList->hasPages())
                @php
                    $current = $anakAsuhList->currentPage();
                    $last = $anakAsuhList->lastPage();
                    $start = max(1, $current - 1);
                    $end = min($last, $current + 1);
                    if ($current == 1) {
                        $end = min($last, 3);
                    } elseif ($current == $last) {
                        $start = max(1, $last - 2);
                    }
                @endphp
                <nav class="flex items-center justify-center sm:justify-end gap-3 text-sm">
                    {{-- Previous Page Link --}}
                    @if ($anakAsuhList->onFirstPage())
                        <span class="text-muted-foreground cursor-not-allowed p-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.25" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                            </svg>
                        </span>
                    @else
                        <a href="{{ $anakAsuhList->previousPageUrl() }}" 
                           class="text-muted-foreground-2 hover:text-foreground p-1 transition"
                           title="Halaman Sebelumnya">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.25" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                            </svg>
                        </a>
                    @endif

                    {{-- Page Numbers (Window 3 halaman persis seperti di Gambar 2) --}}
                    <div class="flex items-center gap-2">
                        @for ($page = $start; $page <= $end; $page++)
                            @if ($page == $current)
                                <span class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs text-white bg-brand-700 shadow-xs leading-none">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $anakAsuhList->url($page) }}" 
                                   class="w-8 h-8 rounded-lg flex items-center justify-center font-normal text-xs text-muted-foreground-2 hover:text-foreground hover:bg-muted transition leading-none">
                                    {{ $page }}
                                </a>
                            @endif
                        @endfor
                    </div>

                    {{-- Next Page Link --}}
                    @if ($anakAsuhList->hasMorePages())
                        <a href="{{ $anakAsuhList->nextPageUrl() }}" 
                           class="text-muted-foreground-2 hover:text-foreground p-1 transition"
                           title="Halaman Selanjutnya">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.25" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </a>
                    @else
                        <span class="text-muted-foreground cursor-not-allowed p-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.25" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </span>
                    @endif
                </nav>
            @endif
        </div>
    </div>

    <!-- ================= MODAL TAMBAH ANAK ASUH ================= -->
    <div id="create-anak-modal"
         class="hs-overlay hidden size-full fixed top-0 inset-s-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none"
         role="dialog"
         tabindex="-1"
         aria-labelledby="create-anak-modal-label">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
            <div class="relative bg-overlay rounded-2xl shadow-2xl border border-overlay-line overflow-hidden w-full flex flex-col max-h-[90vh] pointer-events-auto">
                <div class="px-5 py-4 border-b border-border-line-1 flex items-center justify-between bg-muted">
                    <h3 id="create-anak-modal-label" class="font-bold text-base text-foreground">Tambah Data Anak Asuh</h3>
                    <button type="button"
                            data-hs-overlay="#create-anak-modal"
                            class="size-8 inline-flex justify-center items-center rounded-full bg-surface border border-surface-line text-surface-foreground hover:bg-surface-hover transition"
                            aria-label="Tutup">
                        <span class="sr-only">Tutup</span>
                        <svg class="shrink-0 size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.anak-asuh.store') }}" enctype="multipart/form-data" class="overflow-y-auto p-5 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-muted-foreground-2 mb-1">Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" required placeholder="Contoh: Muhammad Fajar Pratama" class="w-full rounded-xl border border-border px-3.5 py-2 text-sm focus:border-primary focus:outline-hidden">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-muted-foreground-2 mb-1">Nama Panggilan</label>
                            <input type="text" name="nama_panggilan" placeholder="Contoh: Fajar" class="w-full rounded-xl border border-border px-3.5 py-2 text-sm focus:border-primary focus:outline-hidden">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-muted-foreground-2 mb-1">Jenis Kelamin *</label>
                            <x-preline-select name="jenis_kelamin" required placeholder="Pilih jenis kelamin" toggle-classes="hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2 ps-3.5 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-layer border border-border rounded-xl text-start text-sm text-foreground focus:outline-hidden focus:border-primary focus:ring-2 focus:ring-brand-600/20">
                                <option value="L" selected>Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </x-preline-select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-muted-foreground-2 mb-1">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="w-full rounded-xl border border-border px-3.5 py-2 text-sm focus:border-primary focus:outline-hidden">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-muted-foreground-2 mb-1">Pendidikan Terakhir *</label>
                            <x-preline-select name="pendidikan_terakhir" placeholder="Pilih pendidikan" toggle-classes="hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2 ps-3.5 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-layer border border-border rounded-xl text-start text-sm text-foreground focus:outline-hidden focus:border-primary focus:ring-2 focus:ring-brand-600/20">
                                <option value="SD" selected>SD / MI</option>
                                <option value="SMP">SMP / MTS</option>
                                <option value="SMA">SMA / SMK / MA</option>
                                <option value="Perguruan Tinggi">Perguruan Tinggi</option>
                            </x-preline-select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-muted-foreground-2 mb-1">Status Asuhan *</label>
                        <x-preline-select name="status_asuhan" required placeholder="Pilih status asuhan" toggle-classes="hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2 ps-3.5 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-layer border border-border rounded-xl text-start text-sm text-foreground focus:outline-hidden focus:border-primary focus:ring-2 focus:ring-brand-600/20">
                            <option value="Aktif" selected>Aktif</option>
                            <option value="Alumni">Alumni</option>
                            <option value="Non-Aktif">Non-Aktif</option>
                        </x-preline-select>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-semibold text-muted-foreground-2">Foto Profil (Opsional)</label>
                            <span class="text-[11px] text-muted-foreground">Maks. 2MB (JPG, PNG, WebP)</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <template x-if="createPhotoPreview">
                                <div class="relative shrink-0">
                                    <img :src="createPhotoPreview" alt="Preview Foto" class="w-12 h-12 rounded-full object-cover border border-border">
                                    <button type="button" 
                                            @click="clearCreatePhoto" 
                                            title="Hapus foto" 
                                            class="absolute -top-1 -right-1 bg-danger text-white rounded-full w-4 h-4 flex items-center justify-center text-[10px] shadow-xs hover:bg-danger transition">
                                        &times;
                                    </button>
                                </div>
                            </template>
                            <input type="file" 
                                   id="create_foto_input"
                                   name="foto" 
                                   accept="image/*" 
                                   @change="handleCreatePhoto" 
                                   class="w-full text-xs text-muted-foreground-1 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-muted-foreground-2 mb-1">Keterangan / Catatan</label>
                        <textarea name="keterangan" rows="2" placeholder="Catatan tambahan mengenai santri..." class="w-full rounded-xl border border-border px-3.5 py-2 text-sm focus:border-primary focus:outline-hidden"></textarea>
                    </div>
                    <div class="pt-2 border-t border-border-line-1 flex items-center justify-end gap-2">
                        <button type="button" data-hs-overlay="#create-anak-modal" class="px-4 py-2 rounded-xl text-xs font-semibold text-muted-foreground-2 hover:bg-muted transition">Batal</button>
                        <button type="submit" class="px-5 py-2 rounded-xl text-xs font-semibold bg-brand-700 text-white hover:bg-brand-800 transition">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ================= MODAL EDIT ANAK ASUH ================= -->
    <div id="edit-anak-modal"
         class="hs-overlay hidden size-full fixed top-0 inset-s-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none"
         role="dialog"
         tabindex="-1"
         aria-labelledby="edit-anak-modal-label">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
            <div class="relative bg-overlay rounded-2xl shadow-2xl border border-overlay-line overflow-hidden w-full flex flex-col max-h-[90vh] pointer-events-auto">
                <div class="px-5 py-4 border-b border-border-line-1 flex items-center justify-between bg-muted">
                    <h3 id="edit-anak-modal-label" class="font-bold text-base text-foreground">Ubah Data Anak Asuh</h3>
                    <button type="button"
                            data-hs-overlay="#edit-anak-modal"
                            class="size-8 inline-flex justify-center items-center rounded-full bg-surface border border-surface-line text-surface-foreground hover:bg-surface-hover transition"
                            aria-label="Tutup">
                        <span class="sr-only">Tutup</span>
                        <svg class="shrink-0 size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form id="edit-anak-form" :action="'/admin/anak-asuh/' + editData.id" method="POST" enctype="multipart/form-data" class="overflow-y-auto p-5 space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-semibold text-muted-foreground-2 mb-1">Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" x-model="editData.nama_lengkap" required class="w-full rounded-xl border border-border px-3.5 py-2 text-sm focus:border-primary focus:outline-hidden">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-muted-foreground-2 mb-1">Nama Panggilan</label>
                            <input type="text" name="nama_panggilan" x-model="editData.nama_panggilan" class="w-full rounded-xl border border-border px-3.5 py-2 text-sm focus:border-primary focus:outline-hidden">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-muted-foreground-2 mb-1">Jenis Kelamin *</label>
                            <x-preline-select name="jenis_kelamin" x-model="editData.jenis_kelamin" required placeholder="Pilih jenis kelamin" toggle-classes="hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2 ps-3.5 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-layer border border-border rounded-xl text-start text-sm text-foreground focus:outline-hidden focus:border-primary focus:ring-2 focus:ring-brand-600/20">
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </x-preline-select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-muted-foreground-2 mb-1">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" x-model="editData.tanggal_lahir" class="w-full rounded-xl border border-border px-3.5 py-2 text-sm focus:border-primary focus:outline-hidden">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-muted-foreground-2 mb-1">Pendidikan Terakhir *</label>
                            <x-preline-select name="pendidikan_terakhir" x-model="editData.pendidikan_terakhir" placeholder="Pilih pendidikan" toggle-classes="hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2 ps-3.5 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-layer border border-border rounded-xl text-start text-sm text-foreground focus:outline-hidden focus:border-primary focus:ring-2 focus:ring-brand-600/20">
                                <option value="SD">SD / MI</option>
                                <option value="SMP">SMP / MTS</option>
                                <option value="SMA">SMA / SMK / MA</option>
                                <option value="Perguruan Tinggi">Perguruan Tinggi</option>
                            </x-preline-select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-muted-foreground-2 mb-1">Status Asuhan *</label>
                        <x-preline-select name="status_asuhan" x-model="editData.status_asuhan" required placeholder="Pilih status asuhan" toggle-classes="hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2 ps-3.5 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-layer border border-border rounded-xl text-start text-sm text-foreground focus:outline-hidden focus:border-primary focus:ring-2 focus:ring-brand-600/20">
                            <option value="Aktif">Aktif</option>
                            <option value="Alumni">Alumni</option>
                            <option value="Non-Aktif">Non-Aktif</option>
                        </x-preline-select>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-semibold text-muted-foreground-2">Ubah Foto (Opsional)</label>
                            <span class="text-[11px] text-muted-foreground">Maks. 2MB (JPG, PNG, WebP)</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <template x-if="editPhotoPreview">
                                <div class="relative shrink-0">
                                    <img :src="editPhotoPreview" alt="Preview Baru" class="w-12 h-12 rounded-full object-cover border border-border">
                                    <button type="button" 
                                            @click="clearEditPhoto" 
                                            title="Batalkan foto baru" 
                                            class="absolute -top-1 -right-1 bg-danger text-white rounded-full w-4 h-4 flex items-center justify-center text-[10px] shadow-xs hover:bg-danger transition">
                                        &times;
                                    </button>
                                </div>
                            </template>
                            <template x-if="!editPhotoPreview && editData.foto_path">
                                <img :src="'/' + editData.foto_path" alt="Foto Saat Ini" class="w-12 h-12 rounded-full object-cover shrink-0 border border-border">
                            </template>
                            <input type="file" 
                                   id="edit_foto_input"
                                   name="foto" 
                                   accept="image/*" 
                                   @change="handleEditPhoto" 
                                   class="w-full text-xs text-muted-foreground-1 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-muted-foreground-2 mb-1">Keterangan / Catatan</label>
                        <textarea name="keterangan" x-model="editData.keterangan" rows="2" class="w-full rounded-xl border border-border px-3.5 py-2 text-sm focus:border-primary focus:outline-hidden"></textarea>
                    </div>
                    <div class="pt-2 border-t border-border-line-1 flex items-center justify-end gap-2">
                        <button type="button" data-hs-overlay="#edit-anak-modal" class="px-4 py-2 rounded-xl text-xs font-semibold text-muted-foreground-2 hover:bg-muted transition">Batal</button>
                        <button type="submit" class="px-5 py-2 rounded-xl text-xs font-semibold bg-brand-700 text-white hover:bg-brand-800 transition">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ================= MODAL DETAIL ANAK ASUH ================= -->
    <div id="detail-anak-modal"
         class="hs-overlay hidden size-full fixed top-0 inset-s-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none"
         role="dialog"
         tabindex="-1"
         aria-labelledby="detail-anak-modal-label">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-md sm:w-full m-3 sm:mx-auto">
            <div class="relative bg-overlay rounded-2xl shadow-2xl border border-overlay-line overflow-hidden w-full flex flex-col max-h-[90vh] pointer-events-auto">
                <div class="px-5 py-4 border-b border-border-line-1 flex items-center justify-between bg-muted">
                    <h3 id="detail-anak-modal-label" class="font-bold text-base text-foreground">Detail Anak Asuh</h3>
                    <button type="button"
                            data-hs-overlay="#detail-anak-modal"
                            class="size-8 inline-flex justify-center items-center rounded-full bg-surface border border-surface-line text-surface-foreground hover:bg-surface-hover transition"
                            aria-label="Tutup">
                        <span class="sr-only">Tutup</span>
                        <svg class="shrink-0 size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-5 overflow-y-auto space-y-4" x-show="selectedAnak">
                    <div class="flex items-center gap-4">
                        <template x-if="selectedAnak && selectedAnak.foto_path">
                            <img :src="'/' + selectedAnak.foto_path" 
                                 :alt="selectedAnak.nama_lengkap" 
                                 class="w-16 h-16 rounded-full object-cover shrink-0 border border-border shadow-2xs">
                        </template>
                        <template x-if="!selectedAnak || !selectedAnak.foto_path">
                            <div class="w-16 h-16 rounded-full bg-surface text-foreground font-bold text-xl flex items-center justify-center shrink-0 border border-border">
                                <span x-text="selectedAnak ? selectedAnak.nama_lengkap.substring(0, 1).toUpperCase() : 'A'"></span>
                            </div>
                        </template>
                        <div>
                            <h4 class="font-bold text-base text-foreground" x-text="selectedAnak ? selectedAnak.nama_lengkap : ''"></h4>
                            <p class="text-xs text-muted-foreground-1" x-text="selectedAnak && selectedAnak.nama_panggilan ? 'Panggilan: ' + selectedAnak.nama_panggilan : '-'"></p>
                            <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-brand-100 dark:bg-brand-900/50 text-brand-800 dark:text-brand-200" x-text="selectedAnak ? selectedAnak.status_asuhan : ''"></span>
                        </div>
                    </div>

                    <div class="bg-muted rounded-xl p-3.5 space-y-2 text-xs">
                        <div class="flex justify-between py-1 border-b border-border/50">
                            <span class="text-muted-foreground-1 font-medium">Jenis Kelamin</span>
                            <span class="font-semibold text-foreground" x-text="selectedAnak && selectedAnak.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'"></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-border/50">
                            <span class="text-muted-foreground-1 font-medium">Pendidikan Terakhir</span>
                            <span class="font-semibold text-foreground" x-text="selectedAnak ? selectedAnak.pendidikan_terakhir : '-'"></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-border/50">
                            <span class="text-muted-foreground-1 font-medium">Tanggal Lahir</span>
                            <span class="font-semibold text-foreground" x-text="selectedAnak && selectedAnak.tanggal_lahir ? selectedAnak.tanggal_lahir.substring(0, 10) : '-'"></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-border/50">
                            <span class="text-muted-foreground-1 font-medium">Tanggal Bergabung</span>
                            <span class="font-semibold text-foreground" x-text="selectedAnak && selectedAnak.created_at ? selectedAnak.created_at.substring(0, 10) : '-'"></span>
                        </div>
                        <div class="py-1">
                            <span class="text-muted-foreground-1 font-medium block mb-1">Keterangan:</span>
                            <p class="text-muted-foreground-2 italic" x-text="selectedAnak && selectedAnak.keterangan ? selectedAnak.keterangan : 'Tidak ada catatan.'"></p>
                        </div>
                    </div>
                </div>
                <div class="px-5 py-3 border-t border-border-line-1 flex justify-end bg-overlay">
                    <button type="button" data-hs-overlay="#detail-anak-modal" class="px-4 py-2 rounded-xl text-xs font-semibold bg-surface hover:bg-surface-hover text-muted-foreground-2 transition">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
