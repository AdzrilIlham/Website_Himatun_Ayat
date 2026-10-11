@extends('layouts.admin')

@section('content')
<div class="space-y-5 pb-16 w-full max-w-full min-w-0" 
     x-data="{
        tab: 'informasi-dasar',
        isDirty: false,
        judul: 'Beasiswa Pendidikan Untuk Anak Yatim',
        kategori: 'Pendidikan & Tahfidz',
        deskripsi: 'Dukung pendidikan mereka untuk masa depan yang lebih baik. Kampanye ini bertujuan untuk memberikan beasiswa pendidikan kepada anak-anak yatim dan dhuafa agar mereka dapat melanjutkan pendidikan ke jenjang yang lebih tinggi. Dengan dukungan Anda, kami yakin mereka bisa meraih masa depan yang lebih baik dan bermanfaat bagi umat.',
        target_dana: 118500000,
        tanggal_mulai: '{{ date('Y-m-d') }}',
        tenggat_waktu: '{{ date('Y-m-d', strtotime('+30 days')) }}',
        status: 'aktif',
        bannerPreview: null,
        confirmLeave(e) {
            if (this.isDirty) {
                if (!confirm('Perubahan formulir belum disimpan. Yakin ingin kembali ke halaman Kelola Kampanye?')) {
                    e.preventDefault();
                }
            }
        },
        formatRupiah(val) {
            if (!val || isNaN(val)) return 'Rp 0';
            return 'Rp ' + Number(val).toLocaleString('id-ID');
        },
        handleImageUpload(e) {
            this.isDirty = true;
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    this.bannerPreview = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        formatDateDisplay(d) {
            if (!d) return '-';
            const date = new Date(d);
            if (isNaN(date)) return d;
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        }
     }">

    <!-- Flash Errors -->
    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm space-y-1">
            <p class="font-bold">Mohon periksa kesalahan input berikut:</p>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Navigasi Ergonomis & Breadcrumbs (Prinsip Interaksi Manusia & Komputer / IMK) -->
    <div class="flex flex-col gap-2.5">
        <!-- Breadcrumb Hierarki Navigasi -->
        <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs font-medium text-slate-400">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 transition">Dashboard</a>
            <svg class="w-3.5 h-3.5 text-slate-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
            <a href="{{ route('admin.kelola-kampanye.index') }}" class="hover:text-slate-700 transition">Kelola Kampanye</a>
            <svg class="w-3.5 h-3.5 text-slate-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
            <span class="text-slate-600 font-semibold">Buat Kampanye Baru</span>
        </nav>

        <!-- Header Bar dengan Tombol Back Taktil & Ramah Fitts's Law -->
        <div class="flex items-center gap-3.5 pt-0.5">
            <!-- Tombol Kembali Ber-affordance Kuat -->
            <a href="{{ route('admin.kelola-kampanye.index') }}"
               @click="confirmLeave($event)"
               title="Kembali ke Kelola Kampanye"
               aria-label="Kembali ke halaman Kelola Kampanye"
               class="group inline-flex items-center justify-center w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-white border border-slate-200/90 text-slate-600 hover:text-[#24533C] hover:border-emerald-300 hover:bg-emerald-50/40 active:scale-95 shadow-2xs hover:shadow-xs transition-all duration-200 cursor-pointer shrink-0 focus:outline-hidden focus:ring-2 focus:ring-[#24533C]/20">
                <svg class="w-5 h-5 transition-transform duration-200 ease-out group-hover:-translate-x-1" 
                     fill="none" viewBox="0 0 24 24" stroke-width="2.25" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>

            <div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-[#1B4332]">Buat Kampanye Baru</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5 font-medium max-w-2xl">
                    Mulai dengan mengisi informasi kampanye. Pastikan semua data sudah lengkap agar kampanye Anda dapat menjangkau lebih banyak donatur.
                </p>
            </div>
        </div>
    </div>

    <!-- 2 Columns Grid: Form (Kiri) & Live Preview (Kanan) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- KOLOM KIRI: Form Input dengan 3 Tab (Persis Screenshot 2) -->
        <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
            <!-- Header Kartu -->
            <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center gap-3 bg-slate-50/40">
                <div class="w-10 h-10 rounded-xl bg-[#24533C] text-white flex items-center justify-center shrink-0 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-slate-900 text-base leading-snug">Buat Kampanye Baru</h2>
                    <p class="text-xs text-slate-500 font-medium">Isi detail kampanye untuk menggalang dana dari donatur.</p>
                </div>
            </div>

            <!-- Tab Navigation: Informasi Dasar | Detail Kampanye | Media & Konten -->
            <div class="border-b border-slate-200 px-5 sm:px-6 flex items-center gap-6 overflow-x-auto text-xs sm:text-sm font-semibold">
                <button type="button" 
                        @click="tab = 'informasi-dasar'"
                        :class="tab === 'informasi-dasar' ? 'border-[#24533C] text-[#24533C]' : 'border-transparent text-slate-500 hover:text-slate-800'"
                        class="py-3.5 border-b-2 transition shrink-0 cursor-pointer">
                    Informasi Dasar
                </button>
                <button type="button" 
                        @click="tab = 'detail-kampanye'"
                        :class="tab === 'detail-kampanye' ? 'border-[#24533C] text-[#24533C]' : 'border-transparent text-slate-500 hover:text-slate-800'"
                        class="py-3.5 border-b-2 transition shrink-0 cursor-pointer">
                    Detail Kampanye
                </button>
                <button type="button" 
                        @click="tab = 'media-konten'"
                        :class="tab === 'media-konten' ? 'border-[#24533C] text-[#24533C]' : 'border-transparent text-slate-500 hover:text-slate-800'"
                        class="py-3.5 border-b-2 transition shrink-0 cursor-pointer">
                    Media & Konten
                </button>
            </div>

            <!-- Form Wrapper -->
            <form action="{{ route('admin.kelola-kampanye.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="p-5 sm:p-6 space-y-4">
                    <!-- ================= TAB 1: INFORMASI DASAR ================= -->
                    <div x-show="tab === 'informasi-dasar'" class="space-y-4">
                        <!-- Judul Kampanye * -->
                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-slate-800 mb-1.5">
                                Judul Kampanye <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   name="judul" 
                                   x-model="judul"
                                   @input="isDirty = true"
                                   required 
                                   placeholder="Contoh: Beasiswa Pendidikan Untuk Anak Yatim"
                                   class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs sm:text-sm text-slate-800 placeholder:text-slate-400 focus:border-[#24533C] focus:ring-1 focus:ring-[#24533C] focus:outline-hidden transition shadow-2xs">
                        </div>

                        <!-- Kategori * -->
                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-slate-800 mb-1.5">
                                Kategori <span class="text-rose-500">*</span>
                            </label>
                            <select name="kategori" 
                                    x-model="kategori"
                                    @change="isDirty = true"
                                    required 
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs sm:text-sm text-slate-800 focus:border-[#24533C] focus:ring-1 focus:ring-[#24533C] focus:outline-hidden transition shadow-2xs">
                                <option value="" disabled>Pilih kategori</option>
                                @foreach($kategoriList as $kat)
                                    <option value="{{ $kat }}">{{ $kat }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Deskripsi * -->
                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-slate-800 mb-1.5">
                                Deskripsi <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="deskripsi" 
                                      x-model="deskripsi"
                                      @input="isDirty = true"
                                      rows="5"
                                      required 
                                      placeholder="Tuliskan Deskripsi kampanye, Tuliskan detail program, tujuan, dan manfaat kampanye..."
                                      class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs sm:text-sm text-slate-800 placeholder:text-slate-400 focus:border-[#24533C] focus:ring-1 focus:ring-[#24533C] focus:outline-hidden transition shadow-2xs"></textarea>
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button type="button" 
                                    @click="tab = 'detail-kampanye'" 
                                    class="px-5 py-2.5 rounded-xl bg-[#24533C] text-white text-xs sm:text-sm font-semibold hover:bg-[#1B4332] transition shadow-xs cursor-pointer">
                                Lanjut: Detail Kampanye &rarr;
                            </button>
                        </div>
                    </div>

                    <!-- ================= TAB 2: DETAIL KAMPANYE ================= -->
                    <div x-show="tab === 'detail-kampanye'" class="space-y-4" style="display: none;">
                        <!-- Target Donasi (Rp) * -->
                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-slate-800 mb-1.5">
                                Target Donasi (Rp) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-xs sm:text-sm font-bold text-slate-500">Rp</span>
                                <input type="number" 
                                       name="target_dana" 
                                       x-model="target_dana"
                                       min="1000"
                                       step="1000"
                                       required 
                                       placeholder="118500000"
                                       class="w-full rounded-xl border border-slate-300 bg-white pl-12 pr-4 py-2.5 text-xs sm:text-sm text-slate-800 placeholder:text-slate-400 focus:border-[#24533C] focus:ring-1 focus:ring-[#24533C] focus:outline-hidden transition shadow-2xs">
                            </div>
                        </div>

                        <!-- Grid Tanggal Mulai & Tenggat Waktu -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs sm:text-sm font-bold text-slate-800 mb-1.5">
                                    Tanggal Mulai <span class="text-rose-500">*</span>
                                </label>
                                <input type="date" 
                                       name="tanggal_mulai" 
                                       x-model="tanggal_mulai"
                                       class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs sm:text-sm text-slate-800 focus:border-[#24533C] focus:outline-hidden transition shadow-2xs">
                            </div>
                            <div>
                                <label class="block text-xs sm:text-sm font-bold text-slate-800 mb-1.5">
                                    Tenggat Waktu Selesai <span class="text-rose-500">*</span>
                                </label>
                                <input type="date" 
                                       name="tenggat_waktu" 
                                       x-model="tenggat_waktu"
                                       class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs sm:text-sm text-slate-800 focus:border-[#24533C] focus:outline-hidden transition shadow-2xs">
                            </div>
                        </div>

                        <!-- Status Publikasi -->
                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-slate-800 mb-1.5">
                                Status Kampanye <span class="text-rose-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="flex items-center gap-2.5 p-3 rounded-xl border cursor-pointer transition"
                                       :class="status === 'aktif' ? 'border-[#24533C] bg-emerald-50/50' : 'border-slate-200 bg-white'">
                                    <input type="radio" name="status" value="aktif" x-model="status" class="text-[#24533C] focus:ring-[#24533C]">
                                    <div class="text-xs">
                                        <p class="font-bold text-slate-800">Aktif</p>
                                        <p class="text-slate-500 text-[11px]">Siap terima donasi</p>
                                    </div>
                                </label>
                                <label class="flex items-center gap-2.5 p-3 rounded-xl border cursor-pointer transition"
                                       :class="status === 'draft' ? 'border-slate-800 bg-slate-50' : 'border-slate-200 bg-white'">
                                    <input type="radio" name="status" value="draft" x-model="status" class="text-slate-700 focus:ring-slate-700">
                                    <div class="text-xs">
                                        <p class="font-bold text-slate-800">Draft</p>
                                        <p class="text-slate-500 text-[11px]">Simpan sementara</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="pt-2 flex justify-between">
                            <button type="button" 
                                    @click="tab = 'informasi-dasar'" 
                                    class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs sm:text-sm font-semibold hover:bg-slate-50 transition cursor-pointer">
                                &larr; Kembali
                            </button>
                            <button type="button" 
                                    @click="tab = 'media-konten'" 
                                    class="px-5 py-2.5 rounded-xl bg-[#24533C] text-white text-xs sm:text-sm font-semibold hover:bg-[#1B4332] transition shadow-xs cursor-pointer">
                                Lanjut: Media & Konten &rarr;
                            </button>
                        </div>
                    </div>

                    <!-- ================= TAB 3: MEDIA & KONTEN ================= -->
                    <div x-show="tab === 'media-konten'" class="space-y-4" style="display: none;">
                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-slate-800 mb-1.5">
                                Foto Banner Utama Kampanye
                            </label>
                            <div class="border-2 border-dashed border-slate-300 hover:border-[#24533C] rounded-2xl p-6 text-center transition cursor-pointer bg-slate-50/50 relative">
                                <input type="file" 
                                       name="banner" 
                                       accept="image/*"
                                       @change="handleImageUpload($event)"
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <div class="flex flex-col items-center justify-center pointer-events-none">
                                    <svg class="w-8 h-8 text-slate-400 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h1.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                    <p class="text-xs sm:text-sm font-semibold text-slate-700">Klik atau geser file foto banner ke sini</p>
                                    <p class="text-[11px] text-slate-400 mt-1">Format: JPG, PNG, WEBP (Maksimal 5MB, rasio 16:9 disarankan)</p>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
                            <button type="button" 
                                    @click="tab = 'detail-kampanye'" 
                                    class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs sm:text-sm font-semibold hover:bg-slate-50 transition cursor-pointer">
                                &larr; Kembali
                            </button>

                            <div class="flex items-center gap-2">
                                <button type="submit" 
                                        name="status_override" 
                                        value="draft"
                                        @click="status = 'draft'"
                                        class="px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-700 text-xs sm:text-sm font-semibold hover:bg-slate-50 transition shadow-2xs cursor-pointer">
                                    Simpan Draft
                                </button>
                                <button type="submit" 
                                        class="px-5 py-2.5 rounded-xl bg-[#24533C] text-white text-xs sm:text-sm font-semibold hover:bg-[#1B4332] transition shadow-xs cursor-pointer">
                                    Simpan &amp; Terbitkan Kampanye
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- KOLOM KANAN: Live Preview Kampanye (Persis Screenshot 2) -->
        <div class="lg:col-span-5 sticky top-6">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
                <!-- Preview Top Header -->
                <div class="px-5 py-3.5 border-b border-slate-100 flex items-center gap-2 text-slate-700 text-xs sm:text-sm font-bold bg-slate-50/50">
                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                    </svg>
                    <span>Preview Kampanye</span>
                </div>

                <div class="p-5 space-y-4">
                    <!-- Banner Placeholder / Uploaded Image -->
                    <div class="relative w-full aspect-16/9 rounded-2xl bg-slate-200 overflow-hidden flex items-center justify-center">
                        <template x-if="bannerPreview">
                            <img :src="bannerPreview" alt="Banner Kampanye" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!bannerPreview">
                            <div class="text-center text-slate-400 p-4">
                                <svg class="w-10 h-10 mx-auto mb-1 opacity-50" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>
                                <span class="text-xs font-medium">Foto Banner Kampanye</span>
                            </div>
                        </template>
                    </div>

                    <!-- Badge Status Aktif / Draft (Persis Screenshot 2) -->
                    <div class="flex justify-end">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold capitalize transition"
                              :class="status === 'aktif' ? 'bg-[#DCFCE7] text-[#15803D]' : 'bg-slate-100 text-slate-600'"
                              x-text="status === 'aktif' ? 'Aktif' : 'Draft'">
                        </span>
                    </div>

                    <!-- Judul & Deskripsi Singkat Preview -->
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 leading-snug" 
                            x-text="judul || 'Judul Kampanye Anda'"></h3>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-2" 
                           x-text="deskripsi || 'Dukung pendidikan mereka untuk masa depan yang lebih baik.'"></p>
                    </div>

                    <!-- Kategori Pill (Persis Screenshot 2: Hijau Pastel) -->
                    <div>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-[#D1FAE5] text-[#047857] border border-emerald-200"
                              x-text="kategori || 'Pendidikan & Tahfidz'"></span>
                    </div>

                    <!-- 3 Row Metrics: Target | Terkumpul | Progres -->
                    <div class="grid grid-cols-3 gap-2 pt-2 border-t border-slate-100 text-xs">
                        <div>
                            <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider">Target</span>
                            <span class="font-extrabold text-slate-900 truncate block" x-text="formatRupiah(target_dana)"></span>
                        </div>
                        <div>
                            <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider">Terkumpul</span>
                            <span class="font-extrabold text-slate-900 truncate block">Rp 0</span>
                        </div>
                        <div>
                            <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider">Progres</span>
                            <span class="font-extrabold text-slate-900 truncate block">0%</span>
                        </div>
                    </div>

                    <!-- Section: Informasi Kampanye (Persis Screenshot 2) -->
                    <div class="pt-3 border-t border-slate-100 space-y-2 text-xs">
                        <h4 class="font-bold text-slate-800 text-xs">Informasi Kampanye</h4>

                        <!-- Periode Tanggal -->
                        <div class="flex items-start gap-2 text-slate-600">
                            <svg class="w-4 h-4 text-slate-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <span class="block text-[10px] text-slate-400 font-medium">Periode</span>
                                <span class="font-semibold text-slate-800" 
                                      x-text="formatDateDisplay(tanggal_mulai) + ' - ' + formatDateDisplay(tenggat_waktu)"></span>
                            </div>
                        </div>

                        <!-- Jumlah Donatur -->
                        <div class="flex items-start gap-2 text-slate-600 pt-1">
                            <svg class="w-4 h-4 text-slate-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>
                            <div class="flex items-center gap-4">
                                <div>
                                    <span class="block text-[10px] text-slate-400 font-medium">Jumlah Donatur</span>
                                    <span class="font-semibold text-slate-800">0 Donatur</span>
                                </div>
                                <div>
                                    <span class="block text-[10px] text-slate-400 font-medium">Status</span>
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#DCFCE7] text-[#15803D]"
                                          x-text="status === 'aktif' ? 'Aktif' : 'Draft'"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Deskripsi Lengkap Preview -->
                    <div class="pt-3 border-t border-slate-100 text-xs space-y-1">
                        <h4 class="font-bold text-slate-800 text-xs">Deskripsi Lengkap</h4>
                        <p class="text-slate-600 text-[11px] leading-relaxed whitespace-pre-line" 
                           x-text="deskripsi || 'Deskripsi lengkap kampanye akan tampil di sini.'"></p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
