@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5 pb-12 w-full max-w-full min-w-0" x-data="{
    searchQuery: '',
    selectedSort: 'status',
    beritaList: {{ Js::from($daftarBerita) }},
    get filteredBerita() {
        if (!this.searchQuery) return this.beritaList;
        return this.beritaList.filter(item => 
            item.judul.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
            item.kategori.toLowerCase().includes(this.searchQuery.toLowerCase())
        );
    }
}">
    <!-- Flash Notifications -->
    @if(session('success'))
        <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 text-lg leading-none cursor-pointer">&times;</button>
        </div>
    @endif

    <!-- Header Page: Title + Tambah Berita Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-[#1B4332] tracking-tight">CMS Berita</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5 font-medium">Kelola dan Publikasikan berita terbaru yayasan</p>
        </div>
        <div>
            <a href="{{ route('admin.berita.create') }}" 
               class="inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl bg-[#24533C] hover:bg-[#1B4332] text-white font-semibold text-xs sm:text-sm shadow-xs transition cursor-pointer">
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
        <div class="relative bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 p-5 shadow-xs overflow-hidden flex flex-col justify-between">
            <!-- Decorative circle accent -->
            <div class="w-28 h-28 rounded-full bg-[#B7D5C4]/35 absolute -bottom-8 -right-8 pointer-events-none"></div>

            <div class="flex items-start justify-between relative z-10">
                <span class="text-[11px] font-bold text-slate-500 tracking-wider uppercase">TOTAL BERITA</span>
                <div class="w-9 h-9 rounded-xl bg-[#E8F5E9] text-[#2D6A4F] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </div>
            </div>

            <div class="mt-4 relative z-10">
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">{{ $stats['total_berita'] }}</span>
                    <span class="text-sm font-semibold text-slate-700">Berita</span>
                </div>
                <div class="flex items-center gap-1.5 mt-3">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                        {{ $stats['reguler'] }} Tayang
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#FDE8E8] text-[#E02424]">
                        {{ $stats['mendesak'] }} Draft
                    </span>
                </div>
            </div>
        </div>

        <!-- Card 2: BERITA DI PUBLIKASIKAN -->
        <div class="relative bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 p-5 shadow-xs overflow-hidden flex flex-col justify-between">
            <!-- Decorative circle accent -->
            <div class="w-28 h-28 rounded-full bg-[#F5E6B7]/50 absolute -bottom-8 -right-8 pointer-events-none"></div>

            <div class="flex items-start justify-between relative z-10">
                <span class="text-[11px] font-bold text-slate-500 tracking-wider uppercase leading-tight">
                    BERITA<br>DI PUBLIKASIKAN
                </span>
                <div class="w-9 h-9 rounded-xl bg-[#FFF9E6] text-amber-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </div>
            </div>

            <div class="mt-4 relative z-10">
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">{{ $stats['dipublikasikan'] }}</span>
                    <span class="text-sm font-semibold text-slate-700">Rilis Berita</span>
                </div>
                <div class="flex items-center gap-1.5 mt-3 text-xs text-slate-600 font-medium">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>100% Disertai Bukti Dokumentasi</span>
                </div>
            </div>
        </div>

        <!-- Card 3: DRAFT -->
        <div class="relative bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 p-5 shadow-xs overflow-hidden flex flex-col justify-between">
            <!-- Decorative circle accent -->
            <div class="w-28 h-28 rounded-full bg-rose-100/40 absolute -bottom-8 -right-8 pointer-events-none"></div>

            <div class="flex items-start justify-between relative z-10">
                <span class="text-[11px] font-bold text-rose-600 tracking-wider uppercase">DRAFT</span>
                <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div class="mt-4 relative z-10">
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl sm:text-4xl font-black text-[#D9381E] tracking-tight">{{ $stats['draft'] }}</span>
                    <span class="text-sm font-semibold text-slate-700">Draft Berita</span>
                </div>
                <div class="flex items-center gap-1.5 mt-3 text-xs text-slate-600 font-medium">
                    <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                    <span>Perlu Peninjauan & Verifikasi</span>
                </div>
            </div>
        </div>

        <!-- Card 4: ARSIP -->
        <div class="relative bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 p-5 shadow-xs overflow-hidden flex flex-col justify-between">
            <!-- Decorative circle accent -->
            <div class="w-28 h-28 rounded-full bg-rose-100/40 absolute -bottom-8 -right-8 pointer-events-none"></div>

            <div class="flex items-start justify-between relative z-10">
                <span class="text-[11px] font-bold text-rose-600 tracking-wider uppercase">ARSIP</span>
                <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                </div>
            </div>

            <div class="mt-4 relative z-10">
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl sm:text-4xl font-black text-[#D9381E] tracking-tight">{{ $stats['arsip'] }}</span>
                    <span class="text-sm font-semibold text-slate-700">Arsip Berita</span>
                </div>
                <div class="flex items-center gap-1.5 mt-3 text-xs text-slate-600 font-medium">
                    <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                    <span>Tersimpan di Direktori Arsip</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Bar & Filters -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pt-2">
        <!-- Input Pencarian -->
        <div class="relative flex-1 max-w-xl">
            <input type="text" 
                   x-model="searchQuery" 
                   placeholder="Cari Berita yayasan..." 
                   class="w-full bg-white border border-slate-300 rounded-full px-5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-[#2D6A4F] focus:border-[#2D6A4F] transition shadow-xs">
        </div>

        <!-- Filter Urutan -->
        <div class="flex items-center gap-2 self-end md:self-auto shrink-0">
            <span class="text-sm text-slate-600 font-medium mr-1">Urutkan:</span>
            <button type="button" 
                    @click="selectedSort = 'status'"
                    :class="selectedSort === 'status' 
                        ? 'bg-[#2D6A4F] text-white shadow-xs' 
                        : 'bg-white border border-slate-300 text-slate-700 hover:bg-slate-50'"
                    class="px-4 py-1.5 rounded-full text-xs font-semibold transition cursor-pointer">
                Status
            </button>
            <button type="button" 
                    @click="selectedSort = 'kategori'"
                    :class="selectedSort === 'kategori' 
                        ? 'bg-[#2D6A4F] text-white shadow-xs' 
                        : 'bg-white border border-slate-300 text-slate-700 hover:bg-slate-50'"
                    class="px-4 py-1.5 rounded-full text-xs font-semibold transition cursor-pointer">
                Kategori
            </button>
        </div>
    </div>

    <!-- Table Daftar Berita (Sesuai Visual Mockup) -->
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <!-- Header Tabel dengan Latar Lembut Sage/Green -->
                <thead>
                    <tr class="bg-[#E4ECE7] text-slate-700 text-xs font-bold uppercase tracking-wider border-b border-[#D5E1D9]">
                        <th scope="col" class="py-3.5 px-4 text-center w-16">No</th>
                        <th scope="col" class="py-3.5 px-6">JUDUL BERITA</th>
                        <th scope="col" class="py-3.5 px-4 text-center">KATEGORI</th>
                        <th scope="col" class="py-3.5 px-4 text-center">STATUS</th>
                        <th scope="col" class="py-3.5 px-6 text-center">TANGGAL</th>
                        <th scope="col" class="py-3.5 px-6 text-center w-28">AKSI</th>
                    </tr>
                </thead>

                <!-- Body Tabel -->
                <tbody class="divide-y divide-slate-100 text-sm">
                    <template x-for="(item, index) in filteredBerita" :key="item.id">
                        <tr class="hover:bg-slate-50/80 transition">
                            <!-- No -->
                            <td class="py-4 px-4 text-center text-slate-700 font-medium" x-text="index + 1"></td>

                            <!-- Judul Berita + Thumbnail -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <!-- Thumbnail Placeholder Box -->
                                    <div class="w-12 h-12 rounded-lg bg-slate-300/80 shrink-0 flex items-center justify-center text-slate-400">
                                        <svg class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                        </svg>
                                    </div>
                                    <span class="font-bold text-slate-900 leading-snug" x-text="item.judul"></span>
                                </div>
                            </td>

                            <!-- Kategori Badge -->
                            <td class="py-4 px-4 text-center">
                                <span class="inline-block px-3.5 py-1 rounded-full text-xs font-semibold"
                                      :class="item.kategori.includes('Dakwah') 
                                          ? 'bg-[#F3E8FF] text-[#8B5CF6]' 
                                          : 'bg-[#FFEDD5] text-[#EA580C]'"
                                      x-text="item.kategori">
                                </span>
                            </td>

                            <!-- Status Badge -->
                            <td class="py-4 px-4 text-center">
                                <span class="inline-block px-3.5 py-1 rounded-full text-xs font-semibold"
                                      :class="item.status === 'Selesai' 
                                          ? 'bg-[#EBF5FF] text-[#3F83F8]' 
                                          : 'bg-[#DEF7EC] text-[#0E9F6E]'"
                                      x-text="item.status">
                                </span>
                            </td>

                            <!-- Tanggal (Rentang Waktu) -->
                            <td class="py-4 px-6 text-center text-xs text-slate-600 font-medium leading-tight">
                                <div>01 Feb 2025</div>
                                <div class="text-slate-400 mt-0.5">- 28 Feb 2025</div>
                            </td>

                            <!-- Aksi (Edit & Delete) -->
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <!-- Edit Button -->
                                    <a href="{{ route('admin.berita.create') }}" 
                                       title="Edit Berita"
                                       class="text-slate-600 hover:text-[#2D6A4F] transition p-1 rounded-md hover:bg-slate-100">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </a>

                                    <!-- Delete Button -->
                                    <button type="button" 
                                            @click="if(confirm('Apakah Anda yakin ingin menghapus berita ini?')) { beritaList = beritaList.filter(b => b.id !== item.id) }"
                                            title="Hapus Berita"
                                            class="text-rose-500 hover:text-rose-700 transition p-1 rounded-md hover:bg-rose-50 cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>

                    <!-- Empty State jika pencarian tidak menemukan hasil -->
                    <tr x-show="filteredBerita.length === 0" style="display: none;">
                        <td colspan="6" class="py-12 text-center text-slate-500 text-sm">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-10 h-10 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                                <p class="font-medium text-slate-600">Tidak ada berita yang sesuai dengan kata kunci pencarian.</p>
                                <button type="button" @click="searchQuery = ''" class="mt-2 text-xs text-[#2D6A4F] hover:underline font-semibold cursor-pointer">
                                    Reset Pencarian
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
