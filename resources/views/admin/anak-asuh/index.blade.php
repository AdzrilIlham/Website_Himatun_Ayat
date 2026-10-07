@extends('layouts.admin')

@section('content')
<div class="space-y-5 pb-12 w-full max-w-full min-w-0" 
     x-data="{ 
        createModalOpen: false, 
        editModalOpen: false, 
        detailModalOpen: false,
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
            this.detailModalOpen = true;
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
            this.editModalOpen = true;
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
        <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm">
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
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-[#2D6A4F]">Data Anak Asuh</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola data dan informasi anak asuh Panti Asuhan Himmatun Ayat Bandung.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
            <!-- Ekspor PDF -->
            <a href="{{ route('admin.anak-asuh.export.pdf', request()->only(['search', 'status'])) }}" 
               class="inline-flex items-center gap-1.5 px-3.5 sm:px-4 py-1.5 sm:py-2 rounded-full border border-slate-700 bg-white text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-900 transition shadow-2xs">
                <svg class="w-3.5 h-3.5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                <span>Ekspor PDF</span>
            </a>

            <!-- Ekspor Excel -->
            <a href="{{ route('admin.anak-asuh.export.excel', request()->only(['search', 'status'])) }}" 
               class="inline-flex items-center gap-1.5 px-3.5 sm:px-4 py-1.5 sm:py-2 rounded-full border border-slate-700 bg-white text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-900 transition shadow-2xs">
                <svg class="w-3.5 h-3.5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625c.621 0 1.125-.504 1.125-1.125V5.625m-1.125 13.875h-7.5c-.621 0-1.125-.504-1.125-1.125m9.75 0v-1.5c0-.621-.504-1.125-1.125-1.125m0 0h-7.5m7.5 0V5.625m-19.5 0A1.125 1.125 0 014.5 4.5h15a1.125 1.125 0 011.125 1.125v10.5m-16.125-10.5h16.125" />
                </svg>
                <span>Ekspor Excel</span>
            </a>

            <!-- + Tambah Anak Asuh -->
            <button type="button" 
                    @click="createModalOpen = true" 
                    class="inline-flex items-center gap-1.5 px-4 sm:px-5 py-1.5 sm:py-2 rounded-full bg-[#2D6A4F] text-xs font-semibold text-white hover:bg-[#1B4332] transition shadow-xs cursor-pointer">
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
        <div class="bg-[#F1F5F9]/80 border border-slate-200/60 rounded-2xl p-4 flex flex-col justify-between">
            <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-500">TOTAL ANAK BINAAN</span>
            <div class="mt-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">
                    {{ number_format($stats['total_aktif'], 0, ',', '.') }}
                </span>
                <span class="text-xs sm:text-sm font-semibold text-[#2D6A4F]">Jiwa Aktif</span>
            </div>
        </div>

        <!-- Card 2: TINGKAT SD / MI -->
        <div class="bg-[#F1F5F9]/80 border border-slate-200/60 rounded-2xl p-4 flex flex-col justify-between">
            <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-500">TINGKAT SD / MI</span>
            <div class="mt-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">
                    {{ number_format($stats['sd']['count'], 0, ',', '.') }}
                </span>
                <span class="text-xs sm:text-sm font-semibold text-slate-600">Santri ({{ $stats['sd']['persen'] }}%)</span>
            </div>
        </div>

        <!-- Card 3: SMP / MTS -->
        <div class="bg-[#F1F5F9]/80 border border-slate-200/60 rounded-2xl p-4 flex flex-col justify-between">
            <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-500">SMP / MTS</span>
            <div class="mt-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">
                    {{ number_format($stats['smp']['count'], 0, ',', '.') }}
                </span>
                <span class="text-xs sm:text-sm font-semibold text-slate-600">Santri ({{ $stats['smp']['persen'] }}%)</span>
            </div>
        </div>

        <!-- Card 4: SMA / SMK / MA -->
        <div class="bg-[#F1F5F9]/80 border border-slate-200/60 rounded-2xl p-4 flex flex-col justify-between">
            <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-500">SMA / SMK / MA</span>
            <div class="mt-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">
                    {{ number_format($stats['sma']['count'], 0, ',', '.') }}
                </span>
                <span class="text-xs sm:text-sm font-semibold text-slate-600">Santri ({{ $stats['sma']['persen'] }}%)</span>
            </div>
        </div>

        <!-- Card 5: PERGURUAN TINGGI -->
        <div class="bg-[#F1F5F9]/80 border border-slate-200/60 rounded-2xl p-4 flex flex-col justify-between">
            <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-500">PERGURUAN TINGGI</span>
            <div class="mt-2 flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">
                    {{ number_format($stats['pt']['count'], 0, ',', '.') }}
                </span>
                <span class="text-xs sm:text-sm font-semibold text-slate-600">Mahasiswa ({{ $stats['pt']['persen'] }}%)</span>
            </div>
        </div>
    </div>

    <!-- Main Container: Filter Bar Hijau Gelap & Tabel Data (Sesuai Desain Tangkapan Layar) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden w-full max-w-full min-w-0">
        
        <!-- Filter Bar Hijau Gelap (#2D6A4F) -->
        <div class="bg-[#2D6A4F] px-4 sm:px-6 py-4">
            <form method="GET" action="{{ route('admin.anak-asuh.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                <!-- Search Box (Cari nama...) -->
                <div class="relative flex-1 max-w-md">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input type="text" 
                           name="search" 
                           value="{{ $search }}" 
                           placeholder="Cari nama..." 
                           @input.debounce.500ms="$el.form.submit()" 
                           class="w-full rounded-xl bg-white pl-10 pr-9 py-2.5 text-xs sm:text-sm text-slate-800 placeholder:text-slate-400 focus:outline-hidden shadow-2xs">
                    @if($search)
                        <a href="{{ route('admin.anak-asuh.index', array_filter(['status' => ($status && $status !== 'Semua Status' && $status !== 'all') ? $status : null])) }}" 
                           title="Hapus pencarian" 
                           class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 transition">
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
                           class="inline-flex items-center gap-1 px-2.5 py-2 text-xs font-medium text-emerald-200 hover:text-white hover:bg-emerald-800/60 rounded-xl transition"
                           title="Reset semua filter">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            <span>Reset</span>
                        </a>
                    @endif

                    <span class="text-xs font-medium text-emerald-100 shrink-0">Filter:</span>
                    <div class="relative">
                        <select name="status" 
                                onchange="this.form.submit()" 
                                class="appearance-none bg-emerald-800/60 hover:bg-emerald-800/80 text-white border border-emerald-600/50 rounded-xl px-3.5 pr-8 py-2 text-xs sm:text-sm font-medium focus:outline-hidden cursor-pointer transition">
                            <option value="Semua Status" {{ (!$status || $status === 'Semua Status' || $status === 'all') ? 'selected' : '' }} class="text-slate-800 bg-white">Semua Status</option>
                            <option value="SD" {{ $status === 'SD' ? 'selected' : '' }} class="text-slate-800 bg-white">SD</option>
                            <option value="SMP" {{ $status === 'SMP' ? 'selected' : '' }} class="text-slate-800 bg-white">SMP</option>
                            <option value="SMA" {{ $status === 'SMA' ? 'selected' : '' }} class="text-slate-800 bg-white">SMA</option>
                            <option value="Perguruan Tinggi" {{ $status === 'Perguruan Tinggi' ? 'selected' : '' }} class="text-slate-800 bg-white">Perguruan Tinggi</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5 text-white">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- 1. Tampilan Desktop & Tablet (md ke atas) -->
        <div class="hidden md:block overflow-x-auto overflow-y-auto max-h-[calc(100vh-420px)] min-h-[360px] w-full divide-y divide-slate-100">
            <table class="w-full text-left text-sm table-fixed min-w-[750px]">
                <colgroup>
                    <col class="w-[30%]">
                    <col class="w-[18%]">
                    <col class="w-[20%]">
                    <col class="w-[20%]">
                    <col class="w-[12%]">
                </colgroup>
                <thead class="sticky top-0 z-10 bg-slate-100 shadow-2xs">
                    <tr class="border-b border-slate-200/80 text-xs font-bold text-slate-700">
                        <th class="py-3 px-6 text-left bg-slate-100">Nama</th>
                        <th class="py-3 px-6 text-left bg-slate-100">Usia</th>
                        <th class="py-3 px-6 text-center bg-slate-100">Status Pendidikan</th>
                        <th class="py-3 px-6 text-left bg-slate-100">Tanggal Bergabung</th>
                        <th class="py-3 px-6 text-center bg-slate-100">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($anakAsuhList as $anak)
                        <tr class="hover:bg-slate-50/70 transition">
                            <!-- Kolom Nama (Avatar & Nama Lengkap) -->
                            <td class="py-3.5 px-6 align-middle">
                                <div class="flex items-center gap-3 min-w-0">
                                    @if($anak->foto_path && file_exists(public_path($anak->foto_path)))
                                        <img src="{{ asset($anak->foto_path) }}" 
                                             alt="{{ $anak->nama_lengkap }}" 
                                             class="w-10 h-10 rounded-full object-cover shrink-0 border border-slate-200">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 text-slate-800 font-bold text-sm flex items-center justify-center shrink-0">
                                            {{ $anak->inisial }}
                                        </div>
                                    @endif
                                    <div class="min-w-0 truncate">
                                        <p class="font-bold text-slate-900 leading-tight truncate">{{ $anak->nama_lengkap }}</p>
                                        @if($anak->nama_panggilan)
                                            <p class="text-xs text-slate-400 mt-0.5 truncate">({{ $anak->nama_panggilan }})</p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Kolom Usia -->
                            <td class="py-3.5 px-6 align-middle text-slate-700 font-medium">
                                {{ $anak->usia_formatted }}
                            </td>

                            <!-- Kolom Status Pendidikan (Badge Sesuai Desain) -->
                            <td class="py-3.5 px-6 align-middle text-center">
                                @php
                                    $p = strtoupper($anak->pendidikan_terakhir ?? 'SD');
                                @endphp
                                @if(str_contains($p, 'SD') || str_contains($p, 'MI'))
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-[#D1FAE5] text-[#065F46]">
                                        SD
                                    </span>
                                @elseif(str_contains($p, 'SMP') || str_contains($p, 'MTS'))
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-[#C7E9D6] text-[#1B4332]">
                                        SMP
                                    </span>
                                @elseif(str_contains($p, 'SMA') || str_contains($p, 'SMK') || str_contains($p, 'MA'))
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-slate-200 text-slate-700">
                                        SMA
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                        PT
                                    </span>
                                @endif
                            </td>

                            <!-- Kolom Tanggal Bergabung -->
                            <td class="py-3.5 px-6 align-middle text-slate-600 text-sm">
                                {{ $anak->created_at->translatedFormat('d M Y') }}
                            </td>

                            <!-- Kolom Aksi (Ikon Mata & Pensil Sesuai Desain) -->
                            <td class="py-3.5 px-6 align-middle text-center">
                                <div class="inline-flex items-center justify-center gap-2">
                                    <!-- Detail (Mata) -->
                                    <button type="button" 
                                            @click="openDetail({{ json_encode($anak) }})"
                                            title="Lihat Detail" 
                                            class="p-1.5 text-slate-400 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>

                                    <!-- Edit (Pensil) -->
                                    <button type="button" 
                                            @click="openEdit({{ json_encode($anak) }})"
                                            title="Ubah Data" 
                                            class="p-1.5 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition cursor-pointer">
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
                                                class="p-1.5 text-slate-300 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition cursor-pointer">
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
                            <td colspan="5" class="py-12 text-center text-slate-400 text-xs sm:text-sm">
                                Tidak ada data anak asuh yang sesuai dengan pencarian atau filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 2. Tampilan Khusus Mobile (< md) Card Per Transaksi -->
        <div class="md:hidden space-y-3 p-3 bg-slate-50/60 overflow-y-auto max-h-[calc(100vh-420px)] min-h-[300px]">
            @forelse($anakAsuhList as $anak)
                <div class="bg-white rounded-xl border border-slate-200 shadow-2xs p-4 space-y-3 transition">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-2.5 min-w-0">
                            @if($anak->foto_path && file_exists(public_path($anak->foto_path)))
                                <img src="{{ asset($anak->foto_path) }}" alt="{{ $anak->nama_lengkap }}" class="w-10 h-10 rounded-full object-cover shrink-0 border border-slate-200">
                            @else
                                <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 text-slate-800 font-bold text-sm flex items-center justify-center shrink-0">
                                    {{ $anak->inisial }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="font-bold text-slate-900 leading-tight truncate">{{ $anak->nama_lengkap }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">Usia: {{ $anak->usia_formatted }}</p>
                            </div>
                        </div>

                        <!-- Badge Pendidikan -->
                        @php $p = strtoupper($anak->pendidikan_terakhir ?? 'SD'); @endphp
                        @if(str_contains($p, 'SD') || str_contains($p, 'MI'))
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[#D1FAE5] text-[#065F46]">SD</span>
                        @elseif(str_contains($p, 'SMP') || str_contains($p, 'MTS'))
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[#C7E9D6] text-[#1B4332]">SMP</span>
                        @elseif(str_contains($p, 'SMA') || str_contains($p, 'SMK') || str_contains($p, 'MA'))
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-200 text-slate-700">SMA</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-blue-100 text-blue-800">PT</span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between text-xs text-slate-500 pt-2 border-t border-slate-100">
                        <span>Bergabung: {{ $anak->created_at->translatedFormat('d M Y') }}</span>
                        <div class="inline-flex items-center gap-1.5">
                            <button type="button" @click="openDetail({{ json_encode($anak) }})" title="Lihat Detail" class="p-1.5 text-slate-400 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                            <button type="button" @click="openEdit({{ json_encode($anak) }})" title="Ubah Data" class="p-1.5 text-slate-400 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </button>
                            <form method="POST" action="{{ route('admin.anak-asuh.destroy', $anak->id) }}" onsubmit="return confirm('Hapus data anak asuh {{ $anak->nama_lengkap }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Hapus Data" class="p-1.5 text-slate-300 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl border border-slate-200 py-10 text-center text-slate-400 text-xs">
                    Tidak ada data anak asuh yang sesuai dengan pencarian atau filter.
                </div>
            @endforelse
        </div>

        <!-- Table Footer: Counter & Pagination (Sesuai Desain Tangkapan Layar Gambar 2) -->
        <div class="px-6 py-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white">
            <div class="text-sm text-slate-600 font-normal text-center sm:text-left">
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
                        <span class="text-slate-400 cursor-not-allowed p-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.25" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                            </svg>
                        </span>
                    @else
                        <a href="{{ $anakAsuhList->previousPageUrl() }}" 
                           class="text-slate-600 hover:text-slate-900 p-1 transition"
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
                                <span class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs text-white bg-[#2D6A4F] shadow-xs leading-none">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $anakAsuhList->url($page) }}" 
                                   class="w-8 h-8 rounded-lg flex items-center justify-center font-normal text-xs text-slate-700 hover:text-slate-900 hover:bg-slate-100 transition leading-none">
                                    {{ $page }}
                                </a>
                            @endif
                        @endfor
                    </div>

                    {{-- Next Page Link --}}
                    @if ($anakAsuhList->hasMorePages())
                        <a href="{{ $anakAsuhList->nextPageUrl() }}" 
                           class="text-slate-600 hover:text-slate-900 p-1 transition"
                           title="Halaman Selanjutnya">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.25" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </a>
                    @else
                        <span class="text-slate-400 cursor-not-allowed p-1">
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
    <div x-show="createModalOpen" 
         x-cloak 
         @keydown.escape.window="createModalOpen = false" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" 
         style="display: none;">
        <div @click="createModalOpen = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-2xs transition-opacity"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden max-w-lg w-full z-10 flex flex-col max-h-[90vh]">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
                <h3 class="font-bold text-base text-slate-900">Tambah Data Anak Asuh</h3>
                <button type="button" @click="createModalOpen = false" class="text-slate-400 hover:text-slate-700 text-lg">&times;</button>
            </div>
            <form method="POST" action="{{ route('admin.anak-asuh.store') }}" enctype="multipart/form-data" class="overflow-y-auto p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap *</label>
                    <input type="text" name="nama_lengkap" required placeholder="Contoh: Muhammad Fajar Pratama" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:border-emerald-600 focus:outline-hidden">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Panggilan</label>
                        <input type="text" name="nama_panggilan" placeholder="Contoh: Fajar" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:border-emerald-600 focus:outline-hidden">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Kelamin *</label>
                        <select name="jenis_kelamin" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:border-emerald-600 focus:outline-hidden">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:border-emerald-600 focus:outline-hidden">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Pendidikan Terakhir *</label>
                        <select name="pendidikan_terakhir" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:border-emerald-600 focus:outline-hidden">
                            <option value="SD">SD / MI</option>
                            <option value="SMP">SMP / MTS</option>
                            <option value="SMA">SMA / SMK / MA</option>
                            <option value="Perguruan Tinggi">Perguruan Tinggi</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Status Asuhan *</label>
                    <select name="status_asuhan" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:border-emerald-600 focus:outline-hidden">
                        <option value="Aktif">Aktif</option>
                        <option value="Alumni">Alumni</option>
                        <option value="Non-Aktif">Non-Aktif</option>
                    </select>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-semibold text-slate-700">Foto Profil (Opsional)</label>
                        <span class="text-[11px] text-slate-400">Maks. 2MB (JPG, PNG, WebP)</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <template x-if="createPhotoPreview">
                            <div class="relative shrink-0">
                                <img :src="createPhotoPreview" alt="Preview Foto" class="w-12 h-12 rounded-full object-cover border border-slate-200">
                                <button type="button" 
                                        @click="clearCreatePhoto" 
                                        title="Hapus foto" 
                                        class="absolute -top-1 -right-1 bg-rose-500 text-white rounded-full w-4 h-4 flex items-center justify-center text-[10px] shadow-xs hover:bg-rose-600 transition">
                                    &times;
                                </button>
                            </div>
                        </template>
                        <input type="file" 
                               id="create_foto_input"
                               name="foto" 
                               accept="image/*" 
                               @change="handleCreatePhoto" 
                               class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Keterangan / Catatan</label>
                    <textarea name="keterangan" rows="2" placeholder="Catatan tambahan mengenai santri..." class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:border-emerald-600 focus:outline-hidden"></textarea>
                </div>
                <div class="pt-2 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" @click="createModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-semibold bg-[#2D6A4F] text-white hover:bg-[#1B4332] transition">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODAL EDIT ANAK ASUH ================= -->
    <div x-show="editModalOpen" 
         x-cloak 
         @keydown.escape.window="editModalOpen = false" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" 
         style="display: none;">
        <div @click="editModalOpen = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-2xs transition-opacity"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden max-w-lg w-full z-10 flex flex-col max-h-[90vh]">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
                <h3 class="font-bold text-base text-slate-900">Ubah Data Anak Asuh</h3>
                <button type="button" @click="editModalOpen = false" class="text-slate-400 hover:text-slate-700 text-lg">&times;</button>
            </div>
            <form :action="'/admin/anak-asuh/' + editData.id" method="POST" enctype="multipart/form-data" class="overflow-y-auto p-5 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap *</label>
                    <input type="text" name="nama_lengkap" x-model="editData.nama_lengkap" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:border-emerald-600 focus:outline-hidden">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Panggilan</label>
                        <input type="text" name="nama_panggilan" x-model="editData.nama_panggilan" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:border-emerald-600 focus:outline-hidden">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Kelamin *</label>
                        <select name="jenis_kelamin" x-model="editData.jenis_kelamin" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:border-emerald-600 focus:outline-hidden">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" x-model="editData.tanggal_lahir" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:border-emerald-600 focus:outline-hidden">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Pendidikan Terakhir *</label>
                        <select name="pendidikan_terakhir" x-model="editData.pendidikan_terakhir" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:border-emerald-600 focus:outline-hidden">
                            <option value="SD">SD / MI</option>
                            <option value="SMP">SMP / MTS</option>
                            <option value="SMA">SMA / SMK / MA</option>
                            <option value="Perguruan Tinggi">Perguruan Tinggi</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Status Asuhan *</label>
                    <select name="status_asuhan" x-model="editData.status_asuhan" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:border-emerald-600 focus:outline-hidden">
                        <option value="Aktif">Aktif</option>
                        <option value="Alumni">Alumni</option>
                        <option value="Non-Aktif">Non-Aktif</option>
                    </select>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-semibold text-slate-700">Ubah Foto (Opsional)</label>
                        <span class="text-[11px] text-slate-400">Maks. 2MB (JPG, PNG, WebP)</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <template x-if="editPhotoPreview">
                            <div class="relative shrink-0">
                                <img :src="editPhotoPreview" alt="Preview Baru" class="w-12 h-12 rounded-full object-cover border border-slate-200">
                                <button type="button" 
                                        @click="clearEditPhoto" 
                                        title="Batalkan foto baru" 
                                        class="absolute -top-1 -right-1 bg-rose-500 text-white rounded-full w-4 h-4 flex items-center justify-center text-[10px] shadow-xs hover:bg-rose-600 transition">
                                    &times;
                                </button>
                            </div>
                        </template>
                        <template x-if="!editPhotoPreview && editData.foto_path">
                            <img :src="'/' + editData.foto_path" alt="Foto Saat Ini" class="w-12 h-12 rounded-full object-cover shrink-0 border border-slate-200">
                        </template>
                        <input type="file" 
                               id="edit_foto_input"
                               name="foto" 
                               accept="image/*" 
                               @change="handleEditPhoto" 
                               class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Keterangan / Catatan</label>
                    <textarea name="keterangan" x-model="editData.keterangan" rows="2" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:border-emerald-600 focus:outline-hidden"></textarea>
                </div>
                <div class="pt-2 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" @click="editModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-semibold bg-[#2D6A4F] text-white hover:bg-[#1B4332] transition">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODAL DETAIL ANAK ASUH ================= -->
    <div x-show="detailModalOpen" 
         x-cloak 
         @keydown.escape.window="detailModalOpen = false" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" 
         style="display: none;">
        <div @click="detailModalOpen = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-2xs transition-opacity"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden max-w-md w-full z-10 flex flex-col max-h-[90vh]">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
                <h3 class="font-bold text-base text-slate-900">Detail Anak Asuh</h3>
                <button type="button" @click="detailModalOpen = false" class="text-slate-400 hover:text-slate-700 text-lg">&times;</button>
            </div>
            <div class="p-5 overflow-y-auto space-y-4" x-if="selectedAnak">
                <div class="flex items-center gap-4">
                    <template x-if="selectedAnak && selectedAnak.foto_path">
                        <img :src="'/' + selectedAnak.foto_path" 
                             :alt="selectedAnak.nama_lengkap" 
                             class="w-16 h-16 rounded-full object-cover shrink-0 border border-slate-200 shadow-2xs">
                    </template>
                    <template x-if="!selectedAnak || !selectedAnak.foto_path">
                        <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-800 font-bold text-xl flex items-center justify-center shrink-0 border border-slate-200">
                            <span x-text="selectedAnak ? selectedAnak.nama_lengkap.substring(0, 1).toUpperCase() : 'A'"></span>
                        </div>
                    </template>
                    <div>
                        <h4 class="font-bold text-base text-slate-900" x-text="selectedAnak ? selectedAnak.nama_lengkap : ''"></h4>
                        <p class="text-xs text-slate-500" x-text="selectedAnak && selectedAnak.nama_panggilan ? 'Panggilan: ' + selectedAnak.nama_panggilan : '-'"></p>
                        <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800" x-text="selectedAnak ? selectedAnak.status_asuhan : ''"></span>
                    </div>
                </div>

                <div class="bg-slate-50 rounded-xl p-3.5 space-y-2 text-xs">
                    <div class="flex justify-between py-1 border-b border-slate-200/50">
                        <span class="text-slate-500 font-medium">Jenis Kelamin</span>
                        <span class="font-semibold text-slate-800" x-text="selectedAnak && selectedAnak.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'"></span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-200/50">
                        <span class="text-slate-500 font-medium">Pendidikan Terakhir</span>
                        <span class="font-semibold text-slate-800" x-text="selectedAnak ? selectedAnak.pendidikan_terakhir : '-'"></span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-200/50">
                        <span class="text-slate-500 font-medium">Tanggal Lahir</span>
                        <span class="font-semibold text-slate-800" x-text="selectedAnak && selectedAnak.tanggal_lahir ? selectedAnak.tanggal_lahir.substring(0, 10) : '-'"></span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-200/50">
                        <span class="text-slate-500 font-medium">Tanggal Bergabung</span>
                        <span class="font-semibold text-slate-800" x-text="selectedAnak && selectedAnak.created_at ? selectedAnak.created_at.substring(0, 10) : '-'"></span>
                    </div>
                    <div class="py-1">
                        <span class="text-slate-500 font-medium block mb-1">Keterangan:</span>
                        <p class="text-slate-700 italic" x-text="selectedAnak && selectedAnak.keterangan ? selectedAnak.keterangan : 'Tidak ada catatan.'"></p>
                    </div>
                </div>
            </div>
            <div class="px-5 py-3 border-t border-slate-100 flex justify-end bg-white">
                <button type="button" @click="detailModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection
