@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5 pb-16 w-full max-w-full min-w-0" x-data="{
    status: 'tayang',
    ringkasan: '',
    isiBerita: '',
    isEmpty: true,
    isBold: false,
    isItalic: false,
    isUnderline: false,
    isAlignLeft: false,
    isAlignCenter: false,
    isAlignRight: false,
    isJustify: false,
    isBulletList: false,
    isNumberedList: false,
    currentBlockLabel: 'Paragraf',
    openBlock: false,
    tags: [],
    tagInput: '',
    recommendedTags: [
        'Donasi',
        'Pendidikan',
        'Kegiatan',
        'Anak Asuh',
        'Santunan',
        'Beasiswa',
        'Dakwah',
        'Kesehatan',
        'Sosial',
        'Gizi & Sembako'
    ],
    hasTag(tag) {
        return this.tags.some(t => t.toLowerCase() === tag.trim().toLowerCase());
    },
    toggleTag(tag) {
        const trimmed = tag.trim();
        const existingIndex = this.tags.findIndex(t => t.toLowerCase() === trimmed.toLowerCase());
        if (existingIndex > -1) {
            this.tags.splice(existingIndex, 1);
        } else {
            this.tags.push(trimmed);
        }
    },
    imagePreview: null,
    isDragging: false,
    get wordCount() {
        if (!this.isiBerita) return 0;
        const text = this.isiBerita.replace(/<[^>]*>/g, ' ').replace(/&nbsp;/g, ' ').trim();
        if (!text) return 0;
        return text.split(/\s+/).filter(Boolean).length;
    },
    updateContent() {
        const editor = this.$refs.editor;
        if (!editor) return;
        const text = editor.innerText.trim();
        const hasImg = editor.querySelector('img') !== null;
        this.isEmpty = text === '' && !hasImg;
        this.isiBerita = this.isEmpty ? '' : editor.innerHTML;
        this.checkActiveStates();
    },
    formatDoc(cmd, value = null) {
        if (!this.$refs.editor) return;
        this.$refs.editor.focus();
        document.execCommand(cmd, false, value);
        this.updateContent();
    },
    formatBlock(tag, label) {
        this.formatDoc('formatBlock', tag);
        this.currentBlockLabel = label;
        this.openBlock = false;
    },
    checkActiveStates() {
        try {
            this.isBold = document.queryCommandState('bold');
            this.isItalic = document.queryCommandState('italic');
            this.isUnderline = document.queryCommandState('underline');
            this.isAlignLeft = document.queryCommandState('justifyLeft');
            this.isAlignCenter = document.queryCommandState('justifyCenter');
            this.isAlignRight = document.queryCommandState('justifyRight');
            this.isJustify = document.queryCommandState('justifyFull');
            this.isBulletList = document.queryCommandState('insertUnorderedList');
            this.isNumberedList = document.queryCommandState('insertOrderedList');

            const block = (document.queryCommandValue('formatBlock') || '').toLowerCase();
            if (block.includes('h1') || block.includes('h2')) {
                this.currentBlockLabel = 'Heading 2';
            } else if (block.includes('h3')) {
                this.currentBlockLabel = 'Heading 3';
            } else if (block.includes('blockquote')) {
                this.currentBlockLabel = 'Kutipan';
            } else {
                this.currentBlockLabel = 'Paragraf';
            }
        } catch (e) {
            // Ignore when there is no active selection
        }
    },
    insertLink() {
        if (!this.$refs.editor) return;
        this.$refs.editor.focus();
        const selection = window.getSelection();
        const selectedText = selection ? selection.toString() : '';
        const url = prompt('Masukkan tautan URL (contoh: https://himatunayat.org):', 'https://');
        if (url && url.trim() && url.trim() !== 'https://') {
            if (!selectedText) {
                document.execCommand('insertHTML', false, `<a href='${url.trim()}' target='_blank' rel='noopener noreferrer' class='text-brand-700 underline font-medium'>${url.trim()}</a>`);
            } else {
                document.execCommand('createLink', false, url.trim());
            }
            this.updateContent();
        }
    },
    insertImagePrompt() {
        const input = document.getElementById('editor_image_file');
        if (input) {
            input.click();
        }
    },
    handleEditorImageUpload(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                if (this.$refs.editor) {
                    this.$refs.editor.focus();
                    document.execCommand('insertHTML', false, `<p><img src='${event.target.result}' alt='Gambar Berita' class='rounded-xl max-w-full my-3 border border-border shadow-xs' /></p>`);
                    this.updateContent();
                }
            };
            reader.readAsDataURL(file);
        }
        e.target.value = '';
    },
    addTag() {
        const trimmed = this.tagInput.trim();
        if (trimmed && !this.hasTag(trimmed)) {
            const matchedRec = this.recommendedTags.find(r => r.toLowerCase() === trimmed.toLowerCase());
            this.tags.push(matchedRec || trimmed);
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
    },
    handleDrop(e) {
        this.isDragging = false;
        if (e.dataTransfer.files && e.dataTransfer.files[0]) {
            const file = e.dataTransfer.files[0];
            const dt = new DataTransfer();
            dt.items.add(file);
            const input = document.getElementById('gambar_input');
            if (input) input.files = dt.files;
            
            const reader = new FileReader();
            reader.onload = (event) => {
                this.imagePreview = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    },
    removeImage() {
        this.imagePreview = null;
        const input = document.getElementById('gambar_input');
        if (input) input.value = '';
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

                <!-- Hidden Input File -->
                <input type="file" 
                       id="gambar_input" 
                       name="gambar"
                       @change="handleImageUpload($event)" 
                       accept="image/png, image/jpeg, image/webp" 
                       class="hidden">

                <!-- 1. Kondisi Kosong (Belum ada gambar yang diunggah) -->
                <div x-show="!imagePreview">
                    <label for="gambar_input" 
                           @dragover.prevent="isDragging = true"
                           @dragleave.prevent="isDragging = false"
                           @drop.prevent="handleDrop($event)"
                           :class="isDragging ? 'border-brand-700 bg-brand-50/40 ring-2 ring-brand-700/20' : 'border-border-line-3 hover:border-brand-700 hover:bg-brand-50/20'"
                           class="border-2 border-dashed rounded-2xl p-8 sm:p-10 flex flex-col items-center justify-center text-center transition-all duration-200 cursor-pointer bg-muted group block w-full">
                        
                        <!-- Upload Cloud Icon -->
                        <div class="w-14 h-14 rounded-full border border-border bg-card shadow-xs flex items-center justify-center text-muted-foreground-2 mb-3.5 group-hover:scale-110 group-hover:text-brand-700 group-hover:border-brand-300 transition-all duration-200">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-foreground group-hover:text-brand-700 transition">Klik untuk mengunggah gambar utama</p>
                        <p class="text-xs text-muted-foreground-1 mt-1">atau tarik &amp; lepas (drag &amp; drop) gambar di sini</p>
                        <div class="inline-flex items-center gap-2 mt-3.5 px-3 py-1 rounded-full bg-card border border-border text-[11px] text-muted-foreground font-medium shadow-2xs">
                            <svg class="w-3.5 h-3.5 text-muted-foreground-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                            </svg>
                            <span>Format: JPG, PNG, WEBP (maks. 2MB, rasio 16:9 disarankan)</span>
                        </div>
                    </label>
                </div>

                <!-- 2. Kondisi Terunggah (In-Place Preview Bersatu dengan Tombol Ganti Gambar) -->
                <div x-show="imagePreview" style="display: none;" class="space-y-2">
                    <div class="relative w-full rounded-2xl overflow-hidden border border-border bg-slate-900 shadow-xs group">
                        <!-- Preview Image Container -->
                        <div class="relative w-full aspect-video sm:max-h-96 flex items-center justify-center bg-slate-950/40">
                            <img :src="imagePreview" alt="Preview Gambar Utama Berita" class="w-full h-full object-cover">

                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/40 pointer-events-none"></div>

                            <!-- Floating Action Button di Sudut Kanan Atas -->
                            <div class="absolute top-3 right-3 sm:top-4 sm:right-4 z-10">
                                <!-- Tombol Ganti Gambar -->
                                <label for="gambar_input" 
                                       title="Ganti Gambar Lain"
                                       class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white/95 hover:bg-white text-slate-800 text-xs font-bold shadow-lg backdrop-blur-md transition-all cursor-pointer hover:scale-105 active:scale-95">
                                    <svg class="w-4 h-4 text-brand-700" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                    </svg>
                                    <span>Ganti Gambar</span>
                                </label>
                            </div>

                            <!-- Bottom Status Info Badge -->
                            <div class="absolute bottom-3 left-3 sm:bottom-4 sm:left-4 z-10 flex items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-black/60 backdrop-blur-md text-white text-xs font-medium">
                                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                    <span>Gambar utama berita siap ditampilkan</span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <p class="text-[11px] text-muted-foreground-1 font-medium flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-muted-foreground-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                        <span>Gambar ini akan menjadi thumbnail di daftar berita dan banner utama di halaman detail berita.</span>
                    </p>
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

            <!-- 4. Isi Berita (Rich Text Editor) -->
            <div class="space-y-3">
                <div class="flex items-center gap-2 text-foreground font-bold text-base border-b border-border-line-1 pb-3">
                    <svg class="w-5 h-5 text-muted-foreground-2" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    <span>Isi Berita</span>
                    <span class="text-xs text-danger font-bold">*</span>
                </div>

                <!-- Rich Editor Frame -->
                <div class="border border-border-line-3 rounded-2xl overflow-hidden focus-within:ring-2 focus-within:ring-brand-700 focus-within:border-brand-700 bg-card transition shadow-2xs">
                    <!-- Toolbar -->
                    <div class="flex items-center flex-wrap gap-1 px-3 py-2 bg-muted border-b border-border text-muted-foreground-2 text-xs select-none">
                        <!-- Paragraph Dropdown -->
                        <div class="relative" @click.outside="openBlock = false">
                            <button type="button" 
                                    @mousedown.prevent
                                    @click="openBlock = !openBlock"
                                    class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-card border border-border hover:bg-surface-hover text-foreground font-medium text-xs cursor-pointer transition shadow-2xs">
                                <span x-text="currentBlockLabel">Paragraf</span>
                                <svg class="w-3.5 h-3.5 text-muted-foreground transition-transform" :class="openBlock ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <!-- Dropdown Menu -->
                            <div x-show="openBlock" 
                                 x-transition
                                 style="display: none;"
                                 class="absolute left-0 top-full mt-1.5 w-44 bg-card border border-border rounded-xl shadow-lg py-1 z-30 text-xs">
                                <button type="button" 
                                        @mousedown.prevent
                                        @click="formatBlock('<p>', 'Paragraf')" 
                                        class="w-full text-left px-3 py-2 hover:bg-muted font-normal text-foreground flex items-center justify-between cursor-pointer">
                                    <span>Paragraf Normal</span>
                                    <span class="text-[10px] text-muted-foreground">&lt;p&gt;</span>
                                </button>
                                <button type="button" 
                                        @mousedown.prevent
                                        @click="formatBlock('<h2>', 'Heading 2')" 
                                        class="w-full text-left px-3 py-2 hover:bg-muted font-bold text-foreground flex items-center justify-between text-sm cursor-pointer">
                                    <span>Sub Judul (H2)</span>
                                    <span class="text-[10px] text-muted-foreground font-normal">&lt;h2&gt;</span>
                                </button>
                                <button type="button" 
                                        @mousedown.prevent
                                        @click="formatBlock('<h3>', 'Heading 3')" 
                                        class="w-full text-left px-3 py-2 hover:bg-muted font-semibold text-foreground flex items-center justify-between text-xs cursor-pointer">
                                    <span>Sub Judul (H3)</span>
                                    <span class="text-[10px] text-muted-foreground font-normal">&lt;h3&gt;</span>
                                </button>
                                <button type="button" 
                                        @mousedown.prevent
                                        @click="formatBlock('<blockquote>', 'Kutipan')" 
                                        class="w-full text-left px-3 py-2 hover:bg-muted italic text-foreground border-t border-border mt-1 pt-1.5 flex items-center justify-between cursor-pointer">
                                    <span>Kutipan Blok</span>
                                    <span class="text-[10px] text-muted-foreground not-italic">&lt;quote&gt;</span>
                                </button>
                            </div>
                        </div>

                        <div class="h-4 w-px bg-border-line-3 mx-1"></div>

                        <!-- Bold, Italic, Underline -->
                        <button type="button" 
                                @mousedown.prevent
                                @click="formatDoc('bold')" 
                                title="Tebal (Ctrl/Cmd + B)"
                                :class="isBold ? 'bg-brand-700 text-white shadow-2xs font-extrabold' : 'text-foreground hover:bg-card hover:text-brand-700'"
                                class="w-7 h-7 flex items-center justify-center rounded-lg font-bold text-xs transition cursor-pointer">
                            B
                        </button>
                        <button type="button" 
                                @mousedown.prevent
                                @click="formatDoc('italic')" 
                                title="Miring (Ctrl/Cmd + I)"
                                :class="isItalic ? 'bg-brand-700 text-white shadow-2xs' : 'text-foreground hover:bg-card hover:text-brand-700'"
                                class="w-7 h-7 flex items-center justify-center rounded-lg italic font-serif text-xs transition cursor-pointer">
                            I
                        </button>
                        <button type="button" 
                                @mousedown.prevent
                                @click="formatDoc('underline')" 
                                title="Garis Bawah (Ctrl/Cmd + U)"
                                :class="isUnderline ? 'bg-brand-700 text-white shadow-2xs' : 'text-foreground hover:bg-card hover:text-brand-700'"
                                class="w-7 h-7 flex items-center justify-center rounded-lg underline text-xs transition cursor-pointer">
                            U
                        </button>

                        <div class="h-4 w-px bg-border-line-3 mx-1"></div>

                        <!-- Alignments (Left, Center, Right, Justify) -->
                        <button type="button" 
                                @mousedown.prevent
                                @click="formatDoc('justifyLeft')" 
                                title="Rata Kiri"
                                :class="isAlignLeft ? 'bg-brand-700 text-white shadow-2xs' : 'text-muted-foreground-2 hover:bg-card hover:text-foreground'"
                                class="w-7 h-7 flex items-center justify-center rounded-lg text-xs transition cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h10.5m-10.5 5.25h16.5" />
                            </svg>
                        </button>
                        <button type="button" 
                                @mousedown.prevent
                                @click="formatDoc('justifyCenter')" 
                                title="Rata Tengah"
                                :class="isAlignCenter ? 'bg-brand-700 text-white shadow-2xs' : 'text-muted-foreground-2 hover:bg-card hover:text-foreground'"
                                class="w-7 h-7 flex items-center justify-center rounded-lg text-xs transition cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M6.75 12h10.5m-13.5 5.25h16.5" />
                            </svg>
                        </button>
                        <button type="button" 
                                @mousedown.prevent
                                @click="formatDoc('justifyRight')" 
                                title="Rata Kanan"
                                :class="isAlignRight ? 'bg-brand-700 text-white shadow-2xs' : 'text-muted-foreground-2 hover:bg-card hover:text-foreground'"
                                class="w-7 h-7 flex items-center justify-center rounded-lg text-xs transition cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M9.75 12h10.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>
                        <button type="button" 
                                @mousedown.prevent
                                @click="formatDoc('justifyFull')" 
                                title="Rata Kanan-Kiri (Justify)"
                                :class="isJustify ? 'bg-brand-700 text-white shadow-2xs' : 'text-muted-foreground-2 hover:bg-card hover:text-foreground'"
                                class="w-7 h-7 flex items-center justify-center rounded-lg text-xs transition cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>

                        <div class="h-4 w-px bg-border-line-3 mx-1"></div>

                        <!-- Lists (Bullet & Numbered) -->
                        <button type="button" 
                                @mousedown.prevent
                                @click="formatDoc('insertUnorderedList')" 
                                title="Daftar Poin (Bullet List)"
                                :class="isBulletList ? 'bg-brand-700 text-white shadow-2xs' : 'text-muted-foreground-2 hover:bg-card hover:text-foreground'"
                                class="w-7 h-7 flex items-center justify-center rounded-lg text-xs transition cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 17.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                        </button>
                        <button type="button" 
                                @mousedown.prevent
                                @click="formatDoc('insertOrderedList')" 
                                title="Daftar Nomor (Numbered List)"
                                :class="isNumberedList ? 'bg-brand-700 text-white shadow-2xs' : 'text-muted-foreground-2 hover:bg-card hover:text-foreground'"
                                class="w-7 h-7 flex items-center justify-center rounded-lg text-xs transition cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M4.5 7.5V4.5l-1.5 1m1.5 8a1.5 1.5 0 10-1.5-1.5m0 5.5h1.5a1 1 0 001-1v-.5a1 1 0 00-1-1h-.5" />
                            </svg>
                        </button>

                        <div class="h-4 w-px bg-border-line-3 mx-1"></div>

                        <!-- Link & Media -->
                        <button type="button" 
                                @mousedown.prevent
                                @click="insertLink()" 
                                title="Sisipkan Tautan (Link)"
                                class="w-7 h-7 flex items-center justify-center rounded-lg text-muted-foreground-2 hover:bg-card hover:text-foreground text-xs transition cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                            </svg>
                        </button>
                        <button type="button" 
                                @mousedown.prevent
                                @click="insertImagePrompt()" 
                                title="Sisipkan Gambar ke Isi Berita"
                                class="w-7 h-7 flex items-center justify-center rounded-lg text-muted-foreground-2 hover:bg-card hover:text-foreground text-xs transition cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                        </button>
                        <input type="file" 
                               id="editor_image_file" 
                               accept="image/*" 
                               class="hidden" 
                               @change="handleEditorImageUpload($event)">
                    </div>

                    <!-- Editable Content Area Container -->
                    <div class="relative min-h-[220px] bg-card">
                        <!-- Placeholder Overlay -->
                        <div x-show="isEmpty" 
                             @click="$refs.editor.focus()"
                             class="absolute top-4 left-4 text-sm text-muted-foreground pointer-events-none select-none">
                            Tulis isi berita di sini...
                        </div>

                        <!-- Contenteditable Surface -->
                        <div x-ref="editor"
                             contenteditable="true"
                             @input="updateContent()"
                             @blur="updateContent()"
                             @keyup="checkActiveStates()"
                             @mouseup="checkActiveStates()"
                             @paste="setTimeout(() => updateContent(), 10)"
                             class="w-full p-4 text-sm text-foreground focus:outline-hidden min-h-[220px] max-h-[500px] overflow-y-auto leading-relaxed [&_p]:mb-2.5 [&_h1]:text-2xl [&_h1]:font-bold [&_h1]:mb-2 [&_h1]:mt-3 [&_h2]:text-xl [&_h2]:font-bold [&_h2]:mb-2 [&_h2]:mt-3 [&_h3]:text-lg [&_h3]:font-semibold [&_h3]:mb-1.5 [&_h3]:mt-2.5 [&_ul]:list-disc [&_ul]:pl-6 [&_ul]:mb-2.5 [&_ol]:list-decimal [&_ol]:pl-6 [&_ol]:mb-2.5 [&_blockquote]:border-l-4 [&_blockquote]:border-brand-600 [&_blockquote]:pl-4 [&_blockquote]:italic [&_blockquote]:my-3 [&_blockquote]:text-muted-foreground-1 [&_a]:text-brand-700 [&_a]:underline [&_img]:max-w-full [&_img]:rounded-xl [&_img]:my-3"></div>

                        <!-- Hidden Input for Form Submission -->
                        <input type="hidden" name="konten" :value="isiBerita">
                    </div>

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

                <!-- Rekomendasi Tag / Pilihan Cepat 1 Klik -->
                <div class="space-y-1.5 pt-1">
                    <div class="flex items-center gap-1.5 text-xs text-muted-foreground font-medium">
                        <svg class="w-3.5 h-3.5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                        </svg>
                        <span>Rekomendasi label (1 klik untuk memilih):</span>
                    </div>

                    <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                        <template x-for="recTag in recommendedTags" :key="recTag">
                            <button type="button" 
                                    @click="toggleTag(recTag)"
                                    :class="hasTag(recTag) 
                                        ? 'bg-brand-50 text-brand-800 border-brand-300 ring-1 ring-brand-400 font-semibold shadow-2xs' 
                                        : 'bg-card text-muted-foreground-2 hover:text-foreground hover:bg-muted border-border-line-3'"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium border transition-all cursor-pointer shadow-2xs hover:border-brand-400">
                                <span x-show="!hasTag(recTag)" class="text-muted-foreground font-bold text-sm leading-none">+</span>
                                <svg x-show="hasTag(recTag)" style="display: none;" class="w-3.5 h-3.5 text-brand-700" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                <span x-text="recTag"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Tag Terpilih / Badges Display -->
                <div x-show="tags.length > 0" style="display: none;" class="space-y-2 pt-2 border-t border-border-line-1">
                    <div class="flex items-center justify-between text-xs text-muted-foreground font-medium">
                        <span>Tag Terpilih (<span x-text="tags.length"></span>):</span>
                        <button type="button" 
                                @click="tags = []" 
                                class="text-[11px] text-muted-foreground hover:text-danger cursor-pointer transition">
                            Hapus Semua
                        </button>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="(tag, index) in tags" :key="index">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-brand-50 text-brand-800 border border-brand-200 shadow-2xs">
                                <span x-text="tag"></span>
                                <button type="button" @click="removeTag(index)" title="Hapus tag" class="hover:text-danger cursor-pointer transition">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </span>
                        </template>
                    </div>
                </div>

                <!-- Hidden Input untuk Kirim Data Form -->
                <template x-for="(tag, index) in tags" :key="'tag-input-'+index">
                    <input type="hidden" name="tags[]" :value="tag">
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
