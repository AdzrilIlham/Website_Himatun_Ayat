@extends('layouts.admin')

@section('content')
<div class="space-y-5 pb-16 w-full max-w-full min-w-0" 
     x-data="{
        tab: 'informasi-dasar',
        isDirty: false,
        judul: '',
        kategori: '',
        deskripsi: '',
        target_dana: '',
        tanggal_mulai: '',
        tenggat_waktu: '',
        status: 'aktif',
        bannerPreview: null,
        errorMessage: '',
        
        step1Errors: { judul: false, kategori: false, deskripsi: false },
        step2Errors: { target_dana: false, tanggal_mulai: false, tenggat_waktu: false },
        step3Errors: { banner: false },

        isStep1Valid() {
            return (this.judul || '').trim().length > 0 &&
                   (this.kategori || '').trim().length > 0 &&
                   (this.deskripsi || '').trim().length > 0;
        },

        validateAndGoToStep2() {
            this.errorMessage = '';
            this.step1Errors.judul = !(this.judul || '').trim();
            this.step1Errors.kategori = !(this.kategori || '').trim();
            this.step1Errors.deskripsi = !(this.deskripsi || '').trim();

            if (!this.isStep1Valid()) {
                this.errorMessage = 'Mohon lengkapi semua kolom wajib pada Langkah 1 (Informasi Dasar) sebelum melanjutkan.';
                if (this.step1Errors.judul && this.$refs.judulInput) {
                    this.$refs.judulInput.focus();
                } else if (this.step1Errors.kategori && this.$refs.kategoriInput) {
                    this.$refs.kategoriInput.focus();
                } else if (this.step1Errors.deskripsi && this.$refs.deskripsiInput) {
                    this.$refs.deskripsiInput.focus();
                }
                return false;
            }

            this.tab = 'detail-kampanye';
            window.scrollTo({ top: 0, behavior: 'smooth' });
            return true;
        },

        isStep2Valid() {
            const target = Number(this.target_dana);
            const validTarget = !isNaN(target) && target >= 1000;
            const validStartDate = Boolean(this.tanggal_mulai);
            const validEndDate = Boolean(this.tenggat_waktu);
            const validRange = validStartDate && validEndDate && (this.tenggat_waktu >= this.tanggal_mulai);

            return this.isStep1Valid() && validTarget && validRange;
        },

        validateAndGoToStep3() {
            this.errorMessage = '';
            const target = Number(this.target_dana);
            this.step2Errors.target_dana = isNaN(target) || target < 1000;
            this.step2Errors.tanggal_mulai = !this.tanggal_mulai;
            this.step2Errors.tenggat_waktu = !this.tenggat_waktu || (this.tanggal_mulai && this.tenggat_waktu < this.tanggal_mulai);

            if (!this.isStep2Valid()) {
                this.errorMessage = 'Mohon lengkapi target donasi dan periode tanggal yang valid pada Langkah 2 (Detail Kampanye).';
                if (this.step2Errors.target_dana && this.$refs.targetInput) {
                    this.$refs.targetInput.focus();
                } else if (this.step2Errors.tanggal_mulai && this.$refs.mulaiInput) {
                    this.$refs.mulaiInput.focus();
                } else if (this.step2Errors.tenggat_waktu && this.$refs.tenggatInput) {
                    this.$refs.tenggatInput.focus();
                }
                return false;
            }

            this.tab = 'media-konten';
            window.scrollTo({ top: 0, behavior: 'smooth' });
            return true;
        },

        isStep3Valid() {
            return this.isStep2Valid() && Boolean(this.bannerPreview);
        },

        validateStep3() {
            this.errorMessage = '';
            this.step3Errors.banner = !this.bannerPreview;
            if (!this.bannerPreview) {
                this.errorMessage = 'Foto banner utama kampanye wajib diunggah sebelum menyimpan kampanye.';
                return false;
            }
            return true;
        },

        handleFormSubmit(e, statusOverride = null) {
            if (statusOverride) {
                this.status = statusOverride;
            }

            if (!this.isStep1Valid()) {
                e.preventDefault();
                this.tab = 'informasi-dasar';
                this.validateAndGoToStep2();
                return false;
            }

            if (!this.isStep2Valid()) {
                e.preventDefault();
                this.tab = 'detail-kampanye';
                this.validateAndGoToStep3();
                return false;
            }

            if (!this.validateStep3()) {
                e.preventDefault();
                return false;
            }
        },

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
            this.step3Errors.banner = false;
            this.errorMessage = '';
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

    <!-- Flash Errors Backend -->
    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm space-y-1 shadow-2xs">
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

    <!-- 2 Columns Grid: Form Wizard (Kiri) & Live Preview (Kanan) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- KOLOM KIRI: Form Input dengan Multi-Step Wizard Gating -->
        <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
            <!-- Header Kartu -->
            <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between gap-3 bg-slate-50/40">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#24533C] text-white flex items-center justify-center shrink-0 shadow-2xs">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-bold text-slate-900 text-base leading-snug">Formulir Kampanye Baru</h2>
                        <p class="text-xs text-slate-500 font-medium">Lengkapi setiap tahapan formulir secara berurutan.</p>
                    </div>
                </div>

                <!-- Step Tracker Badge -->
                <div class="shrink-0 text-xs font-bold text-emerald-800 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-full shadow-2xs">
                    <span x-text="tab === 'informasi-dasar' ? 'Langkah 1 dari 3' : (tab === 'detail-kampanye' ? 'Langkah 2 dari 3' : 'Langkah 3 dari 3')"></span>
                </div>
            </div>

            <!-- STEP INDICATOR PROGRESS BAR (Non-clickable: Admin TIDAK bisa klik lompat langsung di atas) -->
            <div class="border-b border-slate-200 bg-slate-50/60 px-5 sm:px-6 py-3.5 select-none" aria-label="Progress Tahapan">
                <div class="grid grid-cols-3 gap-2 sm:gap-3 text-xs">
                    <!-- Step 1 Indicator -->
                    <div class="flex items-center gap-2 transition"
                         :class="tab === 'informasi-dasar' ? 'text-[#24533C] font-bold' : (isStep1Valid() ? 'text-emerald-700 font-semibold' : 'text-slate-400 font-medium')">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center text-[11px] font-bold shrink-0 transition"
                              :class="isStep1Valid() && tab !== 'informasi-dasar' ? 'bg-emerald-600 text-white' : (tab === 'informasi-dasar' ? 'bg-[#24533C] text-white ring-2 ring-emerald-200' : 'bg-slate-200 text-slate-600')">
                            <template x-if="isStep1Valid() && tab !== 'informasi-dasar'">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </template>
                            <template x-if="!isStep1Valid() || tab === 'informasi-dasar'">
                                <span>1</span>
                            </template>
                        </span>
                        <div class="min-w-0">
                            <p class="truncate text-[11px] sm:text-xs">Informasi Dasar</p>
                        </div>
                    </div>

                    <!-- Step 2 Indicator -->
                    <div class="flex items-center gap-2 transition"
                         :class="tab === 'detail-kampanye' ? 'text-[#24533C] font-bold' : (isStep2Valid() ? 'text-emerald-700 font-semibold' : 'text-slate-400 font-medium')">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center text-[11px] font-bold shrink-0 transition"
                              :class="isStep2Valid() && tab !== 'detail-kampanye' ? 'bg-emerald-600 text-white' : (tab === 'detail-kampanye' ? 'bg-[#24533C] text-white ring-2 ring-emerald-200' : 'bg-slate-200 text-slate-400')">
                            <template x-if="isStep2Valid() && tab !== 'detail-kampanye'">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </template>
                            <template x-if="!isStep2Valid() || tab === 'detail-kampanye'">
                                <span>2</span>
                            </template>
                        </span>
                        <div class="min-w-0 flex items-center gap-1">
                            <p class="truncate text-[11px] sm:text-xs">Detail Kampanye</p>
                            <template x-if="!isStep1Valid()">
                                <svg class="w-3 h-3 text-slate-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" title="Langkah terkunci">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </template>
                        </div>
                    </div>

                    <!-- Step 3 Indicator -->
                    <div class="flex items-center gap-2 transition"
                         :class="tab === 'media-konten' ? 'text-[#24533C] font-bold' : (isStep3Valid() ? 'text-emerald-700 font-semibold' : 'text-slate-400 font-medium')">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center text-[11px] font-bold shrink-0 transition"
                              :class="isStep3Valid() ? 'bg-emerald-600 text-white' : (tab === 'media-konten' ? 'bg-[#24533C] text-white ring-2 ring-emerald-200' : 'bg-slate-200 text-slate-400')">
                            <template x-if="isStep3Valid()">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </template>
                            <template x-if="!isStep3Valid()">
                                <span>3</span>
                            </template>
                        </span>
                        <div class="min-w-0 flex items-center gap-1">
                            <p class="truncate text-[11px] sm:text-xs">Media & Konten</p>
                            <template x-if="!isStep2Valid()">
                                <svg class="w-3 h-3 text-slate-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" title="Langkah terkunci">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Baris Keterangan Informasi IMK -->
                <div class="mt-2.5 pt-2 border-t border-slate-200/60 flex items-center justify-between text-[11px] text-slate-500">
                    <span class="inline-flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                        <span>Selesaikan pengisian halaman saat ini untuk membuka halaman selanjutnya.</span>
                    </span>
                    <span class="hidden sm:inline text-slate-400 font-medium">Navigasi terkunci berurutan</span>
                </div>
            </div>

            <!-- Form Wrapper -->
            <form action="{{ route('admin.kelola-kampanye.store') }}" 
                  method="POST" 
                  enctype="multipart/form-data"
                  @submit="handleFormSubmit($event)">
                @csrf

                <div class="p-5 sm:p-6 space-y-4">
                    <!-- Alert Error Banner Real-time -->
                    <div x-show="errorMessage" 
                         x-transition
                         class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium flex items-center gap-2.5 shadow-2xs"
                         style="display: none;">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.25" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                        <span x-text="errorMessage"></span>
                    </div>

                    <!-- ================= TAB 1: INFORMASI DASAR ================= -->
                    <div x-show="tab === 'informasi-dasar'" class="space-y-4">
                        <!-- Judul Kampanye * -->
                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-slate-800 mb-1.5">
                                Judul Kampanye <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   name="judul" 
                                   x-ref="judulInput"
                                   x-model="judul"
                                   @input="isDirty = true; step1Errors.judul = false; errorMessage = ''"
                                   required 
                                   placeholder="Contoh: Beasiswa Pendidikan Untuk Anak Yatim"
                                   :class="step1Errors.judul ? 'border-rose-400 ring-2 ring-rose-100 bg-rose-50/20' : 'border-slate-300 focus:border-[#24533C] focus:ring-1 focus:ring-[#24533C] bg-white'"
                                   class="w-full rounded-xl border px-4 py-2.5 text-xs sm:text-sm text-slate-800 placeholder:text-slate-400 focus:outline-hidden transition shadow-2xs">
                            <p x-show="step1Errors.judul" class="text-[11px] font-semibold text-rose-500 mt-1 flex items-center gap-1" style="display: none;">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                                <span>Judul kampanye wajib diisi sebelum lanjut ke langkah berikutnya.</span>
                            </p>
                        </div>

                        <!-- Kategori * -->
                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-slate-800 mb-1.5">
                                Kategori <span class="text-rose-500">*</span>
                            </label>
                            <select name="kategori" 
                                    x-ref="kategoriInput"
                                    x-model="kategori"
                                    @change="isDirty = true; step1Errors.kategori = false; errorMessage = ''"
                                    required 
                                    :class="step1Errors.kategori ? 'border-rose-400 ring-2 ring-rose-100 bg-rose-50/20' : 'border-slate-300 focus:border-[#24533C] focus:ring-1 focus:ring-[#24533C] bg-white'"
                                    class="w-full rounded-xl border px-4 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-hidden transition shadow-2xs">
                                <option value="" disabled selected>Pilih kategori kampanye</option>
                                @foreach($kategoriList as $kat)
                                    <option value="{{ $kat }}">{{ $kat }}</option>
                                @endforeach
                            </select>
                            <p x-show="step1Errors.kategori" class="text-[11px] font-semibold text-rose-500 mt-1 flex items-center gap-1" style="display: none;">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                                <span>Silakan pilih kategori program kampanye.</span>
                            </p>
                        </div>

                        <!-- Deskripsi * -->
                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-slate-800 mb-1.5">
                                Deskripsi <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="deskripsi" 
                                      x-ref="deskripsiInput"
                                      x-model="deskripsi"
                                      @input="isDirty = true; step1Errors.deskripsi = false; errorMessage = ''"
                                      rows="5"
                                      required 
                                      placeholder="Tuliskan deskripsi kampanye, detail program, tujuan, dan manfaat bagi penerima bantuan..."
                                      :class="step1Errors.deskripsi ? 'border-rose-400 ring-2 ring-rose-100 bg-rose-50/20' : 'border-slate-300 focus:border-[#24533C] focus:ring-1 focus:ring-[#24533C] bg-white'"
                                      class="w-full rounded-xl border px-4 py-2.5 text-xs sm:text-sm text-slate-800 placeholder:text-slate-400 focus:outline-hidden transition shadow-2xs"></textarea>
                            <p x-show="step1Errors.deskripsi" class="text-[11px] font-semibold text-rose-500 mt-1 flex items-center gap-1" style="display: none;">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                                <span>Deskripsi kampanye wajib diisi sebelum lanjut.</span>
                            </p>
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[11px] text-slate-400 font-medium">Langkah 1: Informasi Dasar</span>
                            <button type="button" 
                                    @click="validateAndGoToStep2()" 
                                    class="px-5 py-2.5 rounded-xl bg-[#24533C] text-white text-xs sm:text-sm font-semibold hover:bg-[#1B4332] active:scale-95 transition-all shadow-xs cursor-pointer inline-flex items-center gap-2">
                                <span>Lanjut: Detail Kampanye</span>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.25" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
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
                                       x-ref="targetInput"
                                       x-model="target_dana"
                                       @input="isDirty = true; step2Errors.target_dana = false; errorMessage = ''"
                                       min="1000"
                                       step="1000"
                                       required 
                                       placeholder="Contoh: 118500000"
                                       :class="step2Errors.target_dana ? 'border-rose-400 ring-2 ring-rose-100 bg-rose-50/20' : 'border-slate-300 focus:border-[#24533C] focus:ring-1 focus:ring-[#24533C] bg-white'"
                                       class="w-full rounded-xl border pl-12 pr-4 py-2.5 text-xs sm:text-sm text-slate-800 placeholder:text-slate-400 focus:outline-hidden transition shadow-2xs">
                            </div>
                            <p x-show="step2Errors.target_dana" class="text-[11px] font-semibold text-rose-500 mt-1 flex items-center gap-1" style="display: none;">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                                <span>Target donasi wajib diisi minimal Rp 1.000.</span>
                            </p>
                        </div>

                        <!-- Grid Tanggal Mulai & Tenggat Waktu -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs sm:text-sm font-bold text-slate-800 mb-1.5">
                                    Tanggal Mulai <span class="text-rose-500">*</span>
                                </label>
                                <input type="date" 
                                       name="tanggal_mulai" 
                                       x-ref="mulaiInput"
                                       x-model="tanggal_mulai"
                                       @change="isDirty = true; step2Errors.tanggal_mulai = false; errorMessage = ''"
                                       required
                                       :class="step2Errors.tanggal_mulai ? 'border-rose-400 ring-2 ring-rose-100 bg-rose-50/20' : 'border-slate-300 focus:border-[#24533C] bg-white'"
                                       class="w-full rounded-xl border px-4 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-hidden transition shadow-2xs">
                                <p x-show="step2Errors.tanggal_mulai" class="text-[11px] font-semibold text-rose-500 mt-1" style="display: none;">
                                    Tanggal mulai kampanye wajib diisi.
                                </p>
                            </div>
                            <div>
                                <label class="block text-xs sm:text-sm font-bold text-slate-800 mb-1.5">
                                    Tenggat Waktu Selesai <span class="text-rose-500">*</span>
                                </label>
                                <input type="date" 
                                       name="tenggat_waktu" 
                                       x-ref="tenggatInput"
                                       x-model="tenggat_waktu"
                                       @change="isDirty = true; step2Errors.tenggat_waktu = false; errorMessage = ''"
                                       required
                                       :class="step2Errors.tenggat_waktu ? 'border-rose-400 ring-2 ring-rose-100 bg-rose-50/20' : 'border-slate-300 focus:border-[#24533C] bg-white'"
                                       class="w-full rounded-xl border px-4 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-hidden transition shadow-2xs">
                                <p x-show="step2Errors.tenggat_waktu" class="text-[11px] font-semibold text-rose-500 mt-1" style="display: none;">
                                    Tenggat waktu harus sama atau setelah tanggal mulai.
                                </p>
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

                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                            <button type="button" 
                                    @click="tab = 'informasi-dasar'; errorMessage = ''; window.scrollTo({ top: 0, behavior: 'smooth' })" 
                                    class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs sm:text-sm font-semibold hover:bg-slate-50 active:scale-95 transition-all cursor-pointer inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                                </svg>
                                <span>Kembali ke Informasi Dasar</span>
                            </button>
                            <button type="button" 
                                    @click="validateAndGoToStep3()" 
                                    class="px-5 py-2.5 rounded-xl bg-[#24533C] text-white text-xs sm:text-sm font-semibold hover:bg-[#1B4332] active:scale-95 transition-all shadow-xs cursor-pointer inline-flex items-center gap-2">
                                <span>Lanjut: Media & Konten</span>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.25" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- ================= TAB 3: MEDIA & KONTEN ================= -->
                    <div x-show="tab === 'media-konten'" class="space-y-4" style="display: none;">
                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-slate-800 mb-1.5">
                                Foto Banner Utama Kampanye <span class="text-rose-500">*</span>
                            </label>
                            <div class="border-2 border-dashed rounded-2xl p-6 text-center transition cursor-pointer bg-slate-50/50 relative"
                                 :class="step3Errors.banner ? 'border-rose-400 ring-2 ring-rose-100 bg-rose-50/30' : 'border-slate-300 hover:border-[#24533C]'">
                                <input type="file" 
                                       name="banner" 
                                       accept="image/*"
                                       required
                                       @change="handleImageUpload($event)"
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <div class="flex flex-col items-center justify-center pointer-events-none">
                                    <template x-if="bannerPreview">
                                        <div class="space-y-2">
                                            <div class="w-36 h-22 rounded-xl overflow-hidden mx-auto border border-emerald-300 shadow-2xs">
                                                <img :src="bannerPreview" alt="Preview Banner" class="w-full h-full object-cover">
                                            </div>
                                            <p class="text-xs font-bold text-emerald-800">Banner terpilih &amp; siap diunggah</p>
                                            <p class="text-[11px] text-slate-400">Klik untuk mengganti foto banner lain</p>
                                        </div>
                                    </template>
                                    <template x-if="!bannerPreview">
                                        <div>
                                            <svg class="w-9 h-9 text-slate-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h1.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                            </svg>
                                            <p class="text-xs sm:text-sm font-semibold text-slate-700">Pilih atau unggah file foto banner <span class="text-rose-500">*</span></p>
                                            <p class="text-[11px] text-slate-400 mt-1">Format: JPG, PNG, WEBP (Maksimal 5MB, rasio 16:9 disarankan)</p>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <p x-show="step3Errors.banner" class="text-[11px] font-semibold text-rose-500 mt-1.5 text-center flex items-center justify-center gap-1" style="display: none;">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                                <span>Foto banner utama kampanye wajib diunggah sebelum menyimpan.</span>
                            </p>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
                            <button type="button" 
                                    @click="tab = 'detail-kampanye'; errorMessage = ''; window.scrollTo({ top: 0, behavior: 'smooth' })" 
                                    class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs sm:text-sm font-semibold hover:bg-slate-50 active:scale-95 transition-all cursor-pointer inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                                </svg>
                                <span>Kembali ke Detail Kampanye</span>
                            </button>

                            <div class="flex items-center gap-2">
                                <button type="submit" 
                                        name="status_override" 
                                        value="draft"
                                        @click="handleFormSubmit($event, 'draft')"
                                        class="px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-700 text-xs sm:text-sm font-semibold hover:bg-slate-50 active:scale-95 transition-all shadow-2xs cursor-pointer">
                                    Simpan Draft
                                </button>
                                <button type="submit" 
                                        @click="handleFormSubmit($event)"
                                        class="px-5 py-2.5 rounded-xl bg-[#24533C] text-white text-xs sm:text-sm font-semibold hover:bg-[#1B4332] active:scale-95 transition-all shadow-xs cursor-pointer">
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
                    <span>Preview Kampanye Real-Time</span>
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
                              x-text="kategori || 'Pilih Kategori'"></span>
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
                                      x-text="(tanggal_mulai && tenggat_waktu) ? (formatDateDisplay(tanggal_mulai) + ' - ' + formatDateDisplay(tenggat_waktu)) : 'Periode belum ditentukan'"></span>
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
