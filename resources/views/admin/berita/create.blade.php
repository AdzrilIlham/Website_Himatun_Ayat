@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5 pb-16 w-full max-w-full min-w-0" x-data="{
    status: 'tayang',
    ringkasan: '',
    isiBerita: '',
    tags: [],
    tagInput: '',
    imagePreview: null,
    get wordCount() {
        if (!this.isiBerita.trim()) return 0;
        return this.isiBerita.trim().split(/\s+/).length;
    },
    addTag() {
        if (this.tagInput.trim() && !this.tags.includes(this.tagInput.trim())) {
            this.tags.push(this.tagInput.trim());
            this.tagInput = '';
        }
    },
    removeTag(index) {
        this.tags.splice(index, 1);
    },
    handleImageUpload(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                this.imagePreview = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
}">
    <!-- Header Page: Title + Kembali Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-brand-900 tracking-tight">Tambah Berita</h1>
            <p class="text-xs sm:text-sm text-muted-foreground-1 mt-0.5 font-medium">Buat berita atau pengumuman terbaru untuk yayasan</p>
        </div>
        <div>
            <a href="{{ route('admin.berita.index') }}" 
               class="inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl bg-brand-700 hover:bg-brand-800 text-white font-semibold text-xs sm:text-sm shadow-xs transition cursor-pointer">
                <span>Kembali ke Daftar</span>
            </a>
        </div>
    </div>

    <!-- Main Card Container Form -->
    <form action="{{ route('admin.berita.index') }}" method="GET" class="space-y-6">
        <div class="bg-card rounded-2xl sm:rounded-3xl border border-border/90 p-6 sm:p-8 space-y-8 shadow-xs">
            
            <!-- 1. Informasi Utama -->
            <div class="space-y-5">
                <div class="flex items-center gap-2 text-foreground font-bold text-base border-b border-border-line-1 pb-3">
                    <svg class="w-5 h-5 text-muted-foreground-2" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    <span>Informasi Utama</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Judul Berita -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-foreground">
                            Judul Berita <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="judul" 
                               placeholder="Masukkan judul berita..." 
                               class="w-full bg-card border border-border-line-3 rounded-xl px-4 py-2.5 text-sm text-foreground placeholder:text-muted-foreground focus:outline-hidden focus:ring-2 focus:ring-brand-700 focus:border-brand-700 transition">
                    </div>

                    <!-- Kategori -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-foreground">
                            Kategori <span class="text-danger">*</span>
                        </label>
                        <x-preline-select name="kategori" 
                                placeholder="Pilih kategori"
                                toggle-classes="hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative px-4 py-2.5 pe-10 flex gap-x-2 text-nowrap w-full cursor-pointer bg-card border border-border-line-3 rounded-xl text-start text-sm text-foreground shadow-2xs focus:outline-hidden focus:border-brand-700 focus:ring-2 focus:ring-brand-700/20 transition">
                            <option value="" selected>Pilih kategori</option>
                            @foreach($kategoriList as $kategori)
                                <option value="{{ $kategori }}">{{ $kategori }}</option>
                            @endforeach
                        </x-preline-select>
                    </div>

                    <!-- Tanggal Publikasi -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-foreground">
                            Tanggal Publikasi <span class="text-danger">*</span>
                        </label>
                        <div class="relative flex items-center">
                            <input type="date" 
                                   name="tanggal_publikasi" 
                                   value="2025-10-09"
                                   class="w-full bg-card border border-border-line-3 rounded-xl px-4 py-2.5 text-sm text-foreground focus:outline-hidden focus:ring-2 focus:ring-brand-700 focus:border-brand-700 transition">
                        </div>
                    </div>

                    <!-- Status Segmented Control -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-foreground">
                            Status
                        </label>
                        <div class="p-1 bg-surface rounded-xl flex items-center gap-1 border border-border">
                            <!-- Tayang Button -->
                            <button type="button" 
                                    @click="status = 'tayang'"
                                    :class="status === 'tayang' 
                                        ? 'bg-brand-700 text-white shadow-xs' 
                                        : 'text-muted-foreground-2 hover:text-foreground'"
                                    class="flex-1 py-1.5 px-3 rounded-lg text-xs font-medium flex items-center justify-center gap-1.5 transition cursor-pointer">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Tayang</span>
                            </button>

                            <!-- Draft Button -->
                            <button type="button" 
                                    @click="status = 'draft'"
                                    :class="status === 'draft' 
                                        ? 'bg-brand-700 text-white shadow-xs' 
                                        : 'text-muted-foreground-2 hover:text-foreground'"
                                    class="flex-1 py-1.5 px-3 rounded-lg text-xs font-medium flex items-center justify-center gap-1.5 transition cursor-pointer">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                <span>Draft</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Gambar Utama -->
            <div class="space-y-4">
                <div class="flex items-center gap-2 text-foreground font-bold text-base border-b border-border-line-1 pb-3">
                    <svg class="w-5 h-5 text-muted-foreground-2" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 017.5 0z" />
                    </svg>
                    <span>Gambar Utama</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-5 gap-5">
                    <!-- Dropzone Area Upload (3 cols) -->
                    <label class="md:col-span-3 border-2 border-dashed border-border-line-3 rounded-2xl p-6 sm:p-8 flex flex-col items-center justify-center text-center hover:border-brand-700 hover:bg-brand-50/20 transition cursor-pointer bg-muted">
                        <input type="file" @change="handleImageUpload($event)" accept="image/png, image/jpeg" class="hidden">
                        
                        <!-- Upload Cloud Icon -->
                        <div class="w-12 h-12 rounded-full border border-border bg-card shadow-xs flex items-center justify-center text-muted-foreground-2 mb-3">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-foreground">Klik untuk mengunggah gambar</p>
                        <p class="text-xs text-muted-foreground-1 mt-0.5">atau drag & drop di sini</p>
                        <p class="text-[11px] text-muted-foreground mt-2 font-medium">Format: JPG, PNG (maks. 2MB)</p>
                    </label>

                    <!-- Preview Gambar Box (2 cols) -->
                    <div class="md:col-span-2 border border-border rounded-2xl p-6 flex items-center gap-4 bg-card">
                        <div class="w-20 h-20 rounded-xl border border-border bg-muted flex items-center justify-center text-muted-foreground shrink-0 overflow-hidden">
                            <template x-if="imagePreview">
                                <img :src="imagePreview" alt="Preview Gambar" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!imagePreview">
                                <svg class="w-8 h-8 text-muted-foreground/60" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>
                            </template>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-foreground">Preview Gambar</h4>
                            <p class="text-xs text-muted-foreground-1 mt-1 leading-relaxed">
                                Gambar akan ditampilkan di halaman daftar berita dan halaman detail.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Ringkasan Berita -->
            <div class="space-y-3">
                <div class="flex items-center gap-2 text-foreground font-bold text-base border-b border-border-line-1 pb-3">
                    <svg class="w-5 h-5 text-muted-foreground-2" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
                    </svg>
                    <span>Ringkasan Berita</span>
                </div>

                <div class="space-y-1">
                    <textarea x-model="ringkasan" 
                              maxlength="300"
                              rows="3" 
                              placeholder="Tulis ringkasan singkat tentang berita ini..." 
                              class="w-full bg-card border border-border-line-3 rounded-2xl p-4 text-sm text-foreground placeholder:text-muted-foreground focus:outline-hidden focus:ring-2 focus:ring-brand-700 focus:border-brand-700 transition"></textarea>
                    <div class="text-right text-xs text-muted-foreground font-medium">
                        <span x-text="ringkasan.length"></span>/300
                    </div>
                </div>
            </div>

            <!-- 4. Isi Berita (Rich Text Editor Mockup) -->
            <div class="space-y-3">
                <div class="flex items-center gap-2 text-foreground font-bold text-base border-b border-border-line-1 pb-3">
                    <svg class="w-5 h-5 text-muted-foreground-2" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    <span>Isi Berita</span>
                </div>

                <!-- Rich Editor Frame -->
                <div class="border border-border-line-3 rounded-2xl overflow-hidden focus-within:ring-2 focus-within:ring-brand-700 focus-within:border-brand-700 bg-card transition">
                    <!-- Toolbar Slicing -->
                    <div class="flex items-center flex-wrap gap-1 px-3 py-2 bg-muted border-b border-border text-muted-foreground-2 text-xs">
                        <!-- Paragraph Dropdown -->
                        <div class="flex items-center gap-1 px-2 py-1 rounded-md bg-card border border-border font-medium cursor-pointer">
                            <span>Paragraph</span>
                            <svg class="w-3.5 h-3.5 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </div>

                        <div class="h-4 w-px bg-border-line-3 mx-1.5"></div>

                        <!-- Bold, Italic, Underline -->
                        <button type="button" class="w-7 h-7 flex items-center justify-center rounded-md hover:bg-surface-hover font-bold text-foreground">B</button>
                        <button type="button" class="w-7 h-7 flex items-center justify-center rounded-md hover:bg-surface-hover italic font-serif text-foreground">I</button>
                        <button type="button" class="w-7 h-7 flex items-center justify-center rounded-md hover:bg-surface-hover underline text-foreground">U</button>

                        <div class="h-4 w-px bg-border-line-3 mx-1.5"></div>

                        <!-- Alignments -->
                        <button type="button" class="p-1 rounded-md hover:bg-surface-hover text-muted-foreground-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h10.5m-10.5 5.25h16.5" />
                            </svg>
                        </button>
                        <button type="button" class="p-1 rounded-md hover:bg-surface-hover text-muted-foreground-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M6.75 12h10.5m-13.5 5.25h16.5" />
                            </svg>
                        </button>
                        <button type="button" class="p-1 rounded-md hover:bg-surface-hover text-muted-foreground-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M9.75 12h10.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>

                        <div class="h-4 w-px bg-border-line-3 mx-1.5"></div>

                        <!-- Bullet List -->
                        <button type="button" class="p-1 rounded-md hover:bg-surface-hover text-muted-foreground-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 017.5 0zM3.75 17.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                        </button>

                        <div class="h-4 w-px bg-border-line-3 mx-1.5"></div>

                        <!-- Link & Media -->
                        <button type="button" class="p-1 rounded-md hover:bg-surface-hover text-muted-foreground-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                            </svg>
                        </button>
                        <button type="button" class="p-1 rounded-md hover:bg-surface-hover text-muted-foreground-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                        </button>
                    </div>

                    <!-- Text Input Area -->
                    <textarea x-model="isiBerita" 
                              rows="8" 
                              placeholder="Tulis isi berita di sini..." 
                              class="w-full p-4 text-sm text-foreground placeholder:text-muted-foreground focus:outline-hidden min-h-[220px] resize-y"></textarea>

                    <!-- Footer Word Counter -->
                    <div class="px-4 py-2 border-t border-border-line-1 flex justify-end bg-card">
                        <span class="text-xs text-muted-foreground font-medium">
                            <span x-text="wordCount"></span> kata
                        </span>
                    </div>
                </div>
            </div>

            <!-- 5. Tag / Label (Opsional) -->
            <div class="space-y-3">
                <div class="flex items-center gap-2 text-foreground font-bold text-base border-b border-border-line-1 pb-3">
                    <svg class="w-5 h-5 text-muted-foreground-2" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                    </svg>
                    <span>Tag / Label <span class="text-xs font-normal text-muted-foreground">(Opsional)</span></span>
                </div>

                <div class="relative flex items-center">
                    <input type="text" 
                           x-model="tagInput"
                           @keydown.enter.prevent="addTag()"
                           placeholder="Contoh: donasi, pendidikan, kegiatan, dll..." 
                           class="w-full bg-card border border-border-line-3 rounded-xl px-4 py-2.5 pr-12 text-sm text-foreground placeholder:text-muted-foreground focus:outline-hidden focus:ring-2 focus:ring-brand-700 focus:border-brand-700 transition">
                    
                    <button type="button" 
                            @click="addTag()"
                            title="Tambahkan tag"
                            class="absolute right-2.5 w-7 h-7 flex items-center justify-center text-muted-foreground hover:text-muted-foreground-2 hover:bg-muted rounded-lg transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </button>
                </div>

                <!-- Tag Badges Display -->
                <template x-if="tags.length > 0">
                    <div class="flex flex-wrap gap-2 pt-1">
                        <template x-for="(tag, index) in tags" :key="index">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-brand-50 text-brand-800 border border-brand-200">
                                <span x-text="tag"></span>
                                <button type="button" @click="removeTag(index)" class="hover:text-danger cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </span>
                        </template>
                    </div>
                </template>
            </div>
        </div>

        <!-- Tombol Aksi Bawah: Batal & Simpan Berita -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.berita.index') }}" 
               class="px-6 py-2.5 rounded-xl border border-border-line-3 bg-card hover:bg-muted text-muted-foreground-2 text-sm font-semibold transition shadow-2xs">
                Batal
            </a>

            <button type="submit" 
                    class="px-6 py-2.5 rounded-xl bg-brand-700 hover:bg-brand-800 text-white text-sm font-semibold flex items-center gap-2 shadow-xs transition hover:shadow-md cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                <span>Simpan Berita</span>
            </button>
        </div>
    </form>
</div>
@endsection
