@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5 pb-16 w-full max-w-full min-w-0" x-data="{
    jenisLaporan: '{{ $jenisLaporan }}',
    bulan: '{{ $bulan }}',
    tahun: '{{ $tahun }}',
    kategori: '{{ $kategori }}',
    previewLoading: false,
    showHistoryModal: false,
    triggerPreview() {
        this.previewLoading = true;
        setTimeout(() => {
            this.previewLoading = false;
        }, 500);
    }
}">
    <!-- Header Page: Title, Subtitle & Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-[#1B4332] tracking-tight">Laporan & Ekspor</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5 font-medium">Filter laporan sesuai kebutuhan, preview data sebelum diunduh</p>
        </div>
        <div class="w-full sm:w-auto">
            <button type="button" 
                    @click="showHistoryModal = true"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs sm:text-sm font-semibold shadow-2xs transition cursor-pointer">
                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                <span>Riwayat Ekspor</span>
            </button>
        </div>
    </div>

    <!-- 1. Card Filter: Pilih Jenis Laporan -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-2xs space-y-4">
        <div class="flex items-center gap-2 text-slate-900 font-bold text-sm sm:text-base">
            <svg class="w-4.5 h-4.5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>
            <span>Pilih Jenis Laporan</span>
        </div>

        <form action="{{ route('admin.laporan-ekspor.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Jenis Laporan -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-700">Jenis Laporan</label>
                <select name="jenis_laporan" 
                        x-model="jenisLaporan"
                        class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-hidden focus:ring-2 focus:ring-[#2D6A4F] focus:border-[#2D6A4F] transition cursor-pointer">
                    <option value="Data Anak Asuh">Data Anak Asuh</option>
                    <option value="Kelola Donasi">Kelola Donasi</option>
                    <option value="Kelola Kampanye">Kelola Kampanye</option>
                </select>
            </div>

            <!-- Bulan -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-700">Bulan</label>
                <select name="bulan" 
                        x-model="bulan"
                        class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-hidden focus:ring-2 focus:ring-[#2D6A4F] focus:border-[#2D6A4F] transition cursor-pointer">
                    @foreach($daftarBulan as $b)
                        <option value="{{ $b }}">{{ $b }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tahun -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-700">Tahun</label>
                <select name="tahun" 
                        x-model="tahun"
                        class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-hidden focus:ring-2 focus:ring-[#2D6A4F] focus:border-[#2D6A4F] transition cursor-pointer">
                    @foreach($daftarTahun as $t)
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Kategori -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-700">Kategori</label>
                <select name="kategori" 
                        x-model="kategori"
                        class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-hidden focus:ring-2 focus:ring-[#2D6A4F] focus:border-[#2D6A4F] transition cursor-pointer">
                    @foreach($daftarKategori as $k)
                        <option value="{{ $k }}">{{ $k }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <!-- 2. Stat Cards (4 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4">
        <!-- Card 1: Total Anak Asuh -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-2xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-[#E8F5E9] text-[#2D6A4F] flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium">Total Anak Asuh</p>
                <p class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mt-0.5">{{ $stats['total'] }}</p>
                <p class="text-[11px] text-slate-400 mt-0.5 font-medium">Data sesuai filter</p>
            </div>
        </div>

        <!-- Card 2: Aktif Pendidikan -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-2xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-[#E8F5E9] text-[#2D6A4F] flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-5.25 6.557c.725 0 1.436-.033 2.138-.098" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium">Aktif Pendidikan</p>
                <p class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mt-0.5">{{ $stats['aktif_pendidikan'] }}</p>
                <p class="text-[11px] text-slate-400 mt-0.5 font-medium">{{ $stats['aktif_pendidikan_persen'] }} dari total</p>
            </div>
        </div>

        <!-- Card 3: Aktif di Asrama -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-2xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-[#E8F5E9] text-[#2D6A4F] flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium">Aktif di Asrama</p>
                <p class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mt-0.5">{{ $stats['aktif_asrama'] }}</p>
                <p class="text-[11px] text-slate-400 mt-0.5 font-medium">{{ $stats['aktif_asrama_persen'] }} dari total</p>
            </div>
        </div>

        <!-- Card 4: Perlu Perhatian -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-2xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium">Perlu Perhatian</p>
                <p class="text-2xl sm:text-3xl font-black text-amber-500 tracking-tight mt-0.5">{{ $stats['perlu_perhatian'] }}</p>
                <p class="text-[11px] text-slate-400 mt-0.5 font-medium">{{ $stats['perlu_perhatian_persen'] }} dari total</p>
            </div>
        </div>
    </div>

    <!-- 3. Grid 2 Kolom: Preview Laporan (Kiri) & Ekspor Laporan + Catatan (Kanan) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-5">
        <!-- Kolom Kiri: Preview Laporan (8 cols) -->
        <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-5 sm:p-6 space-y-4">
            <!-- Header Preview -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                <div class="flex items-start gap-2.5">
                    <svg class="w-5 h-5 text-slate-700 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    <div>
                        <h2 class="font-bold text-slate-900 text-sm sm:text-base">Preview Laporan</h2>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">Berikut adalah data yang sesuai dengan filter yang Anda pilih.</p>
                    </div>
                </div>

                <div class="w-full sm:w-auto">
                    <button type="button" 
                            @click="triggerPreview()"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-[#1B4332] hover:bg-[#143326] text-white text-xs sm:text-sm font-semibold shadow-xs transition cursor-pointer">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Preview Laporan</span>
                    </button>
                </div>
            </div>

            <!-- 1. Tampilan Desktop & Tablet (md ke atas): Tabel Lengkap Sesuai Mockup -->
            <div class="hidden md:block overflow-x-auto w-full rounded-xl">
                <table class="w-full text-left border-collapse text-xs sm:text-sm min-w-[650px]">
                    <thead>
                        <tr class="text-slate-600 font-bold border-b border-slate-200 bg-transparent">
                            <th scope="col" class="py-3 px-3 w-12">No</th>
                            <th scope="col" class="py-3 px-3">ID Anak</th>
                            <th scope="col" class="py-3 px-4">Nama Anak Asuh</th>
                            <th scope="col" class="py-3 px-3">Kategori</th>
                            <th scope="col" class="py-3 px-3">Status</th>
                            <th scope="col" class="py-3 px-4">Tanggal Bergabung</th>
                            <th scope="col" class="py-3 px-2 text-center w-10"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($previewData as $item)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-3 text-slate-500 font-medium">{{ $item['no'] }}</td>
                                <td class="py-3.5 px-3 font-bold text-slate-900">{{ $item['id_anak'] }}</td>
                                <td class="py-3.5 px-4 font-semibold text-slate-800">{{ $item['nama'] }}</td>
                                <td class="py-3.5 px-3">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $item['kategori'] === 'Pendidikan' ? 'bg-[#E8F5E9] text-[#2E7D32]' : 'bg-[#E3F2FD] text-[#1976D2]' }}">
                                        {{ $item['kategori'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-3">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $item['status'] === 'Aktif' ? 'bg-[#E8F5E9] text-[#2E7D32]' : 'bg-[#FFF3E0] text-[#E65100]' }}">
                                        {{ $item['status'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-slate-500 font-medium">{{ $item['tanggal'] }}</td>
                                <td class="py-3.5 px-2 text-center text-slate-400">
                                    <button type="button" class="p-1 hover:text-slate-700 rounded-md hover:bg-slate-100 transition cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- 2. Tampilan Khusus Mobile (< md): Kartu Responsif -->
            <div class="md:hidden space-y-3">
                @foreach($previewData as $item)
                    <div class="bg-slate-50/70 border border-slate-200/80 rounded-xl p-3.5 space-y-2.5">
                        <!-- Header Kartu: No, ID Anak, & Status Badge -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-slate-400">#{{ $item['no'] }}</span>
                                <span class="px-2 py-0.5 rounded-md bg-white border border-slate-200 text-xs font-black text-slate-800 shadow-2xs">
                                    {{ $item['id_anak'] }}
                                </span>
                            </div>
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-semibold {{ $item['status'] === 'Aktif' ? 'bg-[#E8F5E9] text-[#2E7D32]' : 'bg-[#FFF3E0] text-[#E65100]' }}">
                                {{ $item['status'] }}
                            </span>
                        </div>

                        <!-- Nama Anak Asuh -->
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm leading-snug">{{ $item['nama'] }}</h4>
                        </div>

                        <!-- Footer: Kategori & Tanggal Bergabung -->
                        <div class="flex items-center justify-between text-xs pt-2 border-t border-slate-200/60">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-semibold {{ $item['kategori'] === 'Pendidikan' ? 'bg-[#E8F5E9] text-[#2E7D32]' : 'bg-[#E3F2FD] text-[#1976D2]' }}">
                                {{ $item['kategori'] }}
                            </span>
                            <span class="text-slate-400 font-medium flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5" />
                                </svg>
                                {{ $item['tanggal'] }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Footer Paginasi -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-3 border-t border-slate-100 text-xs text-slate-500 font-medium">
                <div class="text-center sm:text-left">
                    Menampilkan <span class="font-bold text-slate-800">1 - 5</span> dari <span class="font-bold text-slate-800">128</span> data
                </div>
                <div class="flex items-center justify-center gap-1 self-center sm:self-auto">
                    <button type="button" class="w-8 h-8 rounded-lg border border-slate-200 bg-white text-slate-400 hover:bg-slate-50 flex items-center justify-center transition cursor-pointer">
                        &lt;
                    </button>
                    <button type="button" class="w-8 h-8 rounded-lg bg-[#1B4332] text-white font-bold flex items-center justify-center shadow-2xs">
                        1
                    </button>
                    <button type="button" class="w-8 h-8 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 font-medium flex items-center justify-center transition cursor-pointer">
                        2
                    </button>
                    <button type="button" class="w-8 h-8 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 font-medium flex items-center justify-center transition cursor-pointer">
                        3
                    </button>
                    <button type="button" class="w-8 h-8 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 font-medium flex items-center justify-center transition cursor-pointer">
                        4
                    </button>
                    <button type="button" class="w-8 h-8 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 font-medium flex items-center justify-center transition cursor-pointer">
                        5
                    </button>
                    <button type="button" class="w-8 h-8 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 flex items-center justify-center transition cursor-pointer">
                        &gt;
                    </button>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Ekspor Laporan & Catatan (4 cols) -->
        <div class="lg:col-span-4 space-y-4">
            <!-- Card Ekspor Laporan -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-5 sm:p-6 space-y-4">
                <div class="flex items-start gap-2.5">
                    <svg class="w-5 h-5 text-slate-700 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    <div>
                        <h2 class="font-bold text-slate-900 text-sm sm:text-base">Ekspor Laporan</h2>
                        <p class="text-xs text-slate-500 font-medium mt-0.5 leading-relaxed">Pilih format file dan unduh laporan setelah preview.</p>
                    </div>
                </div>

                <div class="space-y-3 pt-2">
                    <!-- Tombol Ekspor Excel -->
                    <a href="{{ route('admin.laporan-ekspor.export.excel', ['jenis_laporan' => $jenisLaporan, 'bulan' => $bulan, 'tahun' => $tahun]) }}" 
                       class="w-full flex items-center justify-between p-3.5 rounded-xl border border-slate-200 hover:border-emerald-500 bg-white hover:bg-emerald-50/20 transition group shadow-2xs cursor-pointer">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                            </div>
                            <span class="font-bold text-xs sm:text-sm text-slate-800 group-hover:text-emerald-800 transition">Ekspor Excel</span>
                        </div>
                        <svg class="w-4.5 h-4.5 text-slate-400 group-hover:text-emerald-700 transition" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                    </a>

                    <!-- Tombol Ekspor PDF -->
                    <a href="{{ route('admin.laporan-ekspor.export.pdf', ['jenis_laporan' => $jenisLaporan, 'bulan' => $bulan, 'tahun' => $tahun]) }}" 
                       class="w-full flex items-center justify-between p-3.5 rounded-xl border border-slate-200 hover:border-rose-500 bg-white hover:bg-rose-50/20 transition group shadow-2xs cursor-pointer">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                            </div>
                            <span class="font-bold text-xs sm:text-sm text-slate-800 group-hover:text-rose-700 transition">Ekspor PDF</span>
                        </div>
                        <svg class="w-4.5 h-4.5 text-slate-400 group-hover:text-rose-600 transition" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Card Catatan -->
            <div class="bg-[#F0FDF4] border border-[#DCFCE7] rounded-2xl p-4 sm:p-5 flex items-start gap-3">
                <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.007v.008H12v-.008zM12 3a9 9 0 100 18 9 9 0 000-18z" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-emerald-950 text-xs sm:text-sm">Catatan</h4>
                    <p class="text-xs text-emerald-800 font-medium mt-1 leading-relaxed">
                        Pastikan Anda melakukan preview laporan terlebih dahulu sebelum mengunduh.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Riwayat Ekspor (Sederhana & Elegan) -->
    <div x-show="showHistoryModal" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <!-- Backdrop -->
            <div @click="showHistoryModal = false" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity"></div>

            <!-- Modal Content -->
            <div class="relative transform overflow-hidden rounded-2xl bg-white p-6 text-left shadow-xl transition-all sm:my-8 w-full max-w-lg space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-900">Riwayat Unduhan Laporan</h3>
                    <button type="button" @click="showHistoryModal = false" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-2.5 text-xs">
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                        <div>
                            <p class="font-bold text-slate-800">Laporan Anak Asuh - September 2025</p>
                            <p class="text-slate-400 mt-0.5">Format: Excel (.xlsx) &bull; Diunduh 2 jam yang lalu</p>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800">Selesai</span>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                        <div>
                            <p class="font-bold text-slate-800">Laporan Donasi - Agustus 2025</p>
                            <p class="text-slate-400 mt-0.5">Format: PDF (.pdf) &bull; Diunduh 1 hari yang lalu</p>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800">Selesai</span>
                    </div>
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="button" @click="showHistoryModal = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 text-xs font-semibold cursor-pointer">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
