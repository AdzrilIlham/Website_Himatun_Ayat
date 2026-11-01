@extends('layouts.admin')

@section('content')
<div class="space-y-5 pb-16 w-full max-w-full min-w-0" 
     x-data="{
        tab: 'informasi-dasar',
        judul: {{ json_encode($kampanye->judul) }},
        kategori: {{ json_encode($kampanye->kategori) }},
        deskripsi: {{ json_encode($kampanye->deskripsi) }},
        target_dana: {{ $kampanye->target_dana }},
        tanggal_mulai: {{ json_encode($kampanye->tanggal_mulai ? $kampanye->tanggal_mulai->format('Y-m-d') : '') }},
        tenggat_waktu: {{ json_encode($kampanye->tenggat_waktu ? $kampanye->tenggat_waktu->format('Y-m-d') : '') }},
        status: {{ json_encode($kampanye->status) }},
        isDirty: false,
        bannerPreview: {{ json_encode($kampanye->banner_path ? app(\App\Services\CloudflareR2Service::class)->getPublicUrl($kampanye->banner_path) : null) }},
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
        <div class="p-4 rounded-2xl bg-danger-soft border border-danger/30 text-danger text-xs sm:text-sm space-y-1">
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
        <!-- Breadcrumb Navigasi -->
        <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs font-medium text-muted-foreground">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-muted-foreground-2 transition">Dashboard</a>
            <svg class="w-3.5 h-3.5 text-muted-foreground/60 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
            <a href="{{ route('admin.kelola-kampanye.index') }}" class="hover:text-muted-foreground-2 transition">Kelola Kampanye</a>
            <svg class="w-3.5 h-3.5 text-muted-foreground/60 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
            <span class="text-muted-foreground-2 font-semibold truncate max-w-xs">Edit: {{ $kampanye->judul }}</span>
        </nav>

        <!-- Header Bar dengan Tombol Back Taktil & Ramah Fitts's Law -->
        <div class="flex items-center gap-3.5 pt-0.5">
            <a href="{{ route('admin.kelola-kampanye.index') }}"
               @click="confirmLeave($event)"
               title="Kembali ke Kelola Kampanye"
               aria-label="Kembali ke halaman Kelola Kampanye"
               class="group inline-flex items-center justify-center w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-card border border-border/90 text-muted-foreground-2 hover:text-brand-700 hover:border-brand-300 hover:bg-brand-50/40 active:scale-95 shadow-2xs hover:shadow-xs transition-all duration-200 cursor-pointer shrink-0 focus:outline-hidden focus:ring-2 focus:ring-brand-700/20">
                <svg class="w-5 h-5 transition-transform duration-200 ease-out group-hover:-translate-x-1" 
                     fill="none" viewBox="0 0 24 24" stroke-width="2.25" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>

            <div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-brand-900">Edit Kampanye</h1>
                <p class="text-xs sm:text-sm text-muted-foreground-1 mt-0.5 font-medium max-w-2xl">
                    Perbarui informasi kampanye, target donasi, atau jadwal periode penggalangan dana.
                </p>
            </div>
        </div>
    </div>

    <!-- 2 Columns Grid: Form (Kiri) & Live Preview (Kanan) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- KOLOM KIRI: Form Input dengan 3 Tab -->
        <div class="lg:col-span-7 bg-card rounded-2xl border border-border shadow-2xs overflow-hidden">
            <!-- Header Kartu -->
            <div class="p-5 sm:p-6 border-b border-border-line-1 flex items-center gap-3 bg-muted/40">
                <div class="w-10 h-10 rounded-xl bg-brand-700 text-white flex items-center justify-center shrink-0 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-foreground text-base leading-snug">Edit Informasi Kampanye</h2>
                    <p class="text-xs text-muted-foreground-1 font-medium">Ubah data kampanye {{ $kampanye->judul }}</p>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="border-b border-border px-5 sm:px-6 flex items-center gap-6 overflow-x-auto text-xs sm:text-sm font-semibold">
                <button type="button" 
                        @click="tab = 'informasi-dasar'"
                        :class="tab === 'informasi-dasar' ? 'border-brand-700 text-brand-700' : 'border-transparent text-muted-foreground-1 hover:text-foreground'"
                        class="py-3.5 border-b-2 transition shrink-0 cursor-pointer">
                    Informasi Dasar
                </button>
                <button type="button" 
                        @click="tab = 'detail-kampanye'"
                        :class="tab === 'detail-kampanye' ? 'border-brand-700 text-brand-700' : 'border-transparent text-muted-foreground-1 hover:text-foreground'"
                        class="py-3.5 border-b-2 transition shrink-0 cursor-pointer">
                    Detail Kampanye
                </button>
                <button type="button" 
                        @click="tab = 'media-konten'"
                        :class="tab === 'media-konten' ? 'border-brand-700 text-brand-700' : 'border-transparent text-muted-foreground-1 hover:text-foreground'"
                        class="py-3.5 border-b-2 transition shrink-0 cursor-pointer">
                    Media & Konten
                </button>
            </div>

            <!-- Form Wrapper -->
            <form action="{{ route('admin.kelola-kampanye.update', $kampanye->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="p-5 sm:p-6 space-y-4">
                    <!-- ================= TAB 1: INFORMASI DASAR ================= -->
                    <div x-show="tab === 'informasi-dasar'" class="space-y-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-foreground mb-1.5">
                                Judul Kampanye <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="judul" 
                                   x-model="judul"
                                   required 
                                   class="w-full rounded-xl border border-border-line-3 bg-card px-4 py-2.5 text-xs sm:text-sm text-foreground focus:border-brand-700 focus:ring-1 focus:ring-brand-700 focus:outline-hidden transition shadow-2xs">
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-foreground mb-1.5">
                                Kategori <span class="text-danger">*</span>
                            </label>
                            <x-preline-select name="kategori" 
                                    x-model="kategori"
                                    required 
                                    placeholder="Pilih kategori kampanye"
                                    toggle-classes="hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative px-4 py-2.5 pe-10 flex gap-x-2 text-nowrap w-full cursor-pointer border border-border-line-3 bg-card rounded-xl text-start text-xs sm:text-sm text-foreground shadow-2xs focus:outline-hidden focus:border-brand-700 focus:ring-1 focus:ring-brand-700 transition">
                                @foreach($kategoriList as $kat)
                                    <option value="{{ $kat }}" @selected($kampanye->kategori === $kat)>{{ $kat }}</option>
                                @endforeach
                            </x-preline-select>
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-foreground mb-1.5">
                                Deskripsi <span class="text-danger">*</span>
                            </label>
                            <textarea name="deskripsi" 
                                      x-model="deskripsi"
                                      rows="5"
                                      required 
                                      class="w-full rounded-xl border border-border-line-3 bg-card px-4 py-2.5 text-xs sm:text-sm text-foreground focus:border-brand-700 focus:ring-1 focus:ring-brand-700 focus:outline-hidden transition shadow-2xs"></textarea>
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button type="button" 
                                    @click="tab = 'detail-kampanye'" 
                                    class="px-5 py-2.5 rounded-xl bg-brand-700 text-white text-xs sm:text-sm font-semibold hover:bg-brand-800 transition shadow-xs cursor-pointer">
                                Lanjut: Detail Kampanye &rarr;
                            </button>
                        </div>
                    </div>

                    <!-- ================= TAB 2: DETAIL KAMPANYE ================= -->
                    <div x-show="tab === 'detail-kampanye'" class="space-y-4" style="display: none;">
                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-foreground mb-1.5">
                                Target Donasi (Rp) <span class="text-danger">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-xs sm:text-sm font-bold text-muted-foreground-1">Rp</span>
                                <input type="number" 
                                       name="target_dana" 
                                       x-model="target_dana"
                                       min="1000"
                                       step="1000"
                                       required 
                                       class="w-full rounded-xl border border-border-line-3 bg-card pl-12 pr-4 py-2.5 text-xs sm:text-sm text-foreground focus:border-brand-700 focus:ring-1 focus:ring-brand-700 focus:outline-hidden transition shadow-2xs">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs sm:text-sm font-bold text-foreground mb-1.5">
                                    Tanggal Mulai <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       name="tanggal_mulai" 
                                       x-model="tanggal_mulai"
                                       class="w-full rounded-xl border border-border-line-3 bg-card px-4 py-2.5 text-xs sm:text-sm text-foreground focus:border-brand-700 focus:outline-hidden transition shadow-2xs">
                            </div>
                            <div>
                                <label class="block text-xs sm:text-sm font-bold text-foreground mb-1.5">
                                    Tenggat Waktu Selesai <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       name="tenggat_waktu" 
                                       x-model="tenggat_waktu"
                                       class="w-full rounded-xl border border-border-line-3 bg-card px-4 py-2.5 text-xs sm:text-sm text-foreground focus:border-brand-700 focus:outline-hidden transition shadow-2xs">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-foreground mb-1.5">
                                Status Kampanye <span class="text-danger">*</span>
                            </label>
                            <div class="grid grid-cols-3 gap-2">
                                <label class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition text-xs"
                                       :class="status === 'aktif' ? 'border-brand-700 bg-brand-50/50' : 'border-border bg-card'">
                                    <input type="radio" name="status" value="aktif" x-model="status" class="text-brand-700">
                                    <span class="font-bold text-foreground">Aktif</span>
                                </label>
                                <label class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition text-xs"
                                       :class="status === 'draft' ? 'border-border-line-8 bg-muted' : 'border-border bg-card'">
                                    <input type="radio" name="status" value="draft" x-model="status" class="text-muted-foreground-2">
                                    <span class="font-bold text-foreground">Draft</span>
                                </label>
                                <label class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition text-xs"
                                       :class="status === 'selesai' ? 'border-info bg-info-soft/50' : 'border-border bg-card'">
                                    <input type="radio" name="status" value="selesai" x-model="status" class="text-info">
                                    <span class="font-bold text-foreground">Selesai</span>
                                </label>
                            </div>
                        </div>

                        <div class="pt-2 flex justify-between">
                            <button type="button" 
                                    @click="tab = 'informasi-dasar'" 
                                    class="px-4 py-2.5 rounded-xl border border-border-line-3 text-muted-foreground-2 text-xs sm:text-sm font-semibold hover:bg-muted transition cursor-pointer">
                                &larr; Kembali
                            </button>
                            <button type="button" 
                                    @click="tab = 'media-konten'" 
                                    class="px-5 py-2.5 rounded-xl bg-brand-700 text-white text-xs sm:text-sm font-semibold hover:bg-brand-800 transition shadow-xs cursor-pointer">
                                Lanjut: Media & Konten &rarr;
                            </button>
                        </div>
                    </div>

                    <!-- ================= TAB 3: MEDIA & KONTEN ================= -->
                    <div x-show="tab === 'media-konten'" class="space-y-4" style="display: none;">
                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-foreground mb-1.5">
                                Ganti Foto Banner Kampanye
                            </label>
                            <div class="border-2 border-dashed border-border-line-3 hover:border-brand-700 rounded-2xl p-6 text-center transition cursor-pointer bg-muted relative">
                                <input type="file" 
                                       name="banner" 
                                       accept="image/*"
                                       @change="handleImageUpload($event)"
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <div class="flex flex-col items-center justify-center pointer-events-none">
                                    <svg class="w-8 h-8 text-muted-foreground mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h1.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                    <p class="text-xs sm:text-sm font-semibold text-muted-foreground-2">Unggah Banner Baru (Opsional)</p>
                                    <p class="text-[11px] text-muted-foreground mt-1">Kosongkan bila tidak ingin mengubah foto</p>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-border-line-1 flex items-center justify-between gap-3">
                            <button type="button" 
                                    @click="tab = 'detail-kampanye'" 
                                    class="px-4 py-2.5 rounded-xl border border-border-line-3 text-muted-foreground-2 text-xs sm:text-sm font-semibold hover:bg-muted transition cursor-pointer">
                                &larr; Kembali
                            </button>

                            <button type="submit" 
                                    class="px-5 py-2.5 rounded-xl bg-brand-700 text-white text-xs sm:text-sm font-semibold hover:bg-brand-800 transition shadow-xs cursor-pointer">
                                Perbarui Kampanye
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- KOLOM KANAN: Live Preview Kampanye -->
        <div class="lg:col-span-5 sticky top-6">
            <div class="bg-card rounded-2xl border border-border shadow-2xs overflow-hidden">
                <div class="px-5 py-3.5 border-b border-border-line-1 flex items-center gap-2 text-muted-foreground-2 text-xs sm:text-sm font-bold bg-muted">
                    <svg class="w-4 h-4 text-muted-foreground-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                    </svg>
                    <span>Preview Kampanye</span>
                </div>

                <div class="p-5 space-y-4">
                    <div class="relative w-full aspect-16/9 rounded-2xl bg-surface-hover overflow-hidden flex items-center justify-center">
                        <template x-if="bannerPreview">
                            <img :src="bannerPreview" alt="Banner Kampanye" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!bannerPreview">
                            <div class="text-center text-muted-foreground p-4">
                                <span class="text-xs font-medium">Foto Banner Kampanye</span>
                            </div>
                        </template>
                    </div>

                    <div class="flex justify-end">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold capitalize transition"
                              :class="status === 'aktif' ? 'bg-brand-100 dark:bg-brand-900/50 text-brand-700 dark:text-brand-200' : (status === 'selesai' ? 'bg-info-soft text-info' : 'bg-surface text-muted-foreground-2')"
                              x-text="status">
                        </span>
                    </div>

                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-foreground leading-snug" 
                            x-text="judul || 'Judul Kampanye Anda'"></h3>
                        <p class="text-xs text-muted-foreground-1 mt-1 line-clamp-2" 
                           x-text="deskripsi || ''"></p>
                    </div>

                    <div>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-brand-100 dark:bg-brand-900/50 text-brand-700 dark:text-brand-200 border border-brand-200 dark:border-brand-800"
                              x-text="kategori || 'Pendidikan & Tahfidz'"></span>
                    </div>

                    <div class="grid grid-cols-3 gap-2 pt-2 border-t border-border-line-1 text-xs">
                        <div>
                            <span class="block text-[10px] text-muted-foreground font-bold uppercase tracking-wider">Target</span>
                            <span class="font-extrabold text-foreground truncate block" x-text="formatRupiah(target_dana)"></span>
                        </div>
                        <div>
                            <span class="block text-[10px] text-muted-foreground font-bold uppercase tracking-wider">Terkumpul</span>
                            <span class="font-extrabold text-foreground truncate block">Rp {{ number_format($kampanye->total_terkumpul, 0, ',', '.') }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] text-muted-foreground font-bold uppercase tracking-wider">Progres</span>
                            <span class="font-extrabold text-foreground truncate block">{{ $kampanye->persentase_tercapai }}%</span>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-border-line-1 space-y-2 text-xs">
                        <h4 class="font-bold text-foreground text-xs">Informasi Kampanye</h4>

                        <div class="flex items-start gap-2 text-muted-foreground-2">
                            <svg class="w-4 h-4 text-muted-foreground-1 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <span class="block text-[10px] text-muted-foreground font-medium">Periode</span>
                                <span class="font-semibold text-foreground" 
                                      x-text="formatDateDisplay(tanggal_mulai) + ' - ' + formatDateDisplay(tenggat_waktu)"></span>
                            </div>
                        </div>

                        <div class="flex items-start gap-2 text-muted-foreground-2 pt-1">
                            <svg class="w-4 h-4 text-muted-foreground-1 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>
                            <div class="flex items-center gap-4">
                                <div>
                                    <span class="block text-[10px] text-muted-foreground font-medium">Jumlah Donatur</span>
                                    <span class="font-semibold text-foreground">{{ $kampanye->donasi()->count() }} Donatur</span>
                                </div>
                                <div>
                                    <span class="block text-[10px] text-muted-foreground font-medium">Status</span>
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold capitalize"
                                          :class="status === 'aktif' ? 'bg-brand-100 dark:bg-brand-900/50 text-brand-700 dark:text-brand-200' : 'bg-surface text-muted-foreground-2'"
                                          x-text="status"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-border-line-1 text-xs space-y-1">
                        <h4 class="font-bold text-foreground text-xs">Deskripsi Lengkap</h4>
                        <p class="text-muted-foreground-2 text-[11px] leading-relaxed whitespace-pre-line" 
                           x-text="deskripsi || ''"></p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
