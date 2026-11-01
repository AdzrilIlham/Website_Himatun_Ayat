@extends('layouts.admin')

@section('content')
<div class="space-y-5 pb-12 w-full max-w-full min-w-0"
     x-data="{
        photoPreview: null,
        handlePhoto(e) {
            const file = e.target.files[0];
            if (file) {
                this.photoPreview = URL.createObjectURL(file);
            } else {
                this.photoPreview = null;
            }
        },
        clearPhoto() {
            this.photoPreview = null;
            const input = document.getElementById('foto_input');
            if (input) input.value = '';
        }
     }">

    {{-- Flash Error --}}
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

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <div class="flex items-center gap-2 mb-0.5">
                <a href="{{ route('admin.anak-asuh.index') }}"
                   class="inline-flex items-center gap-1 text-xs font-medium text-slate-500 hover:text-[#2D6A4F] transition">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                    Data Anak Asuh
                </a>
                <span class="text-slate-300 text-xs">/</span>
                <span class="text-xs font-medium text-slate-700">Tambah Baru</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-[#2D6A4F]">Tambah Anak Asuh</h1>
            <p class="text-xs text-slate-500 mt-0.5">Isi formulir di bawah untuk mendaftarkan anak asuh baru.</p>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden w-full max-w-2xl">

        {{-- Card Header --}}
        <div class="bg-[#2D6A4F] px-5 sm:px-6 py-4 flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-white/15 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
            </div>
            <div>
                <h2 class="text-sm font-bold text-white">Data Anak Asuh Baru</h2>
                <p class="text-[11px] text-emerald-200">Lengkapi semua field yang bertanda bintang (*)</p>
            </div>
        </div>

        {{-- Form Body --}}
        <form method="POST" action="{{ route('admin.anak-asuh.store') }}" enctype="multipart/form-data" class="p-5 sm:p-6 space-y-5">
            @csrf

            {{-- Foto Profil --}}
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-semibold text-slate-700">Foto Profil <span class="text-slate-400 font-normal">(Opsional)</span></label>
                    <span class="text-[11px] text-slate-400">Maks. 2MB · JPG, PNG, WebP</span>
                </div>
                <div class="flex items-center gap-4">
                    {{-- Preview Avatar --}}
                    <div class="shrink-0">
                        <template x-if="photoPreview">
                            <div class="relative">
                                <img :src="photoPreview"
                                     alt="Preview Foto"
                                     class="w-16 h-16 rounded-full object-cover border-2 border-emerald-200 shadow-xs">
                                <button type="button"
                                        @click="clearPhoto"
                                        title="Hapus foto"
                                        class="absolute -top-1 -right-1 bg-rose-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-[11px] shadow-xs hover:bg-rose-600 transition">
                                    &times;
                                </button>
                            </div>
                        </template>
                        <template x-if="!photoPreview">
                            <div class="w-16 h-16 rounded-full bg-slate-100 border-2 border-dashed border-slate-300 flex items-center justify-center text-slate-400">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </div>
                        </template>
                    </div>
                    {{-- File Input --}}
                    <div class="flex-1">
                        <input type="file"
                               id="foto_input"
                               name="foto"
                               accept="image/*"
                               @change="handlePhoto"
                               class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                        @error('foto')
                            <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-100"></div>

            {{-- Nama Lengkap --}}
            <div>
                <label for="nama_lengkap" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       id="nama_lengkap"
                       name="nama_lengkap"
                       value="{{ old('nama_lengkap') }}"
                       required
                       placeholder="Contoh: Muhammad Fajar Pratama"
                       class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition @error('nama_lengkap') border-rose-400 bg-rose-50 @enderror">
                @error('nama_lengkap')
                    <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nama Panggilan & Jenis Kelamin --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="nama_panggilan" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Nama Panggilan
                    </label>
                    <input type="text"
                           id="nama_panggilan"
                           name="nama_panggilan"
                           value="{{ old('nama_panggilan') }}"
                           placeholder="Contoh: Fajar"
                           class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition @error('nama_panggilan') border-rose-400 bg-rose-50 @enderror">
                    @error('nama_panggilan')
                        <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="jenis_kelamin" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Jenis Kelamin <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <select id="jenis_kelamin"
                                name="jenis_kelamin"
                                required
                                class="appearance-none w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition cursor-pointer @error('jenis_kelamin') border-rose-400 bg-rose-50 @enderror">
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </div>
                    </div>
                    @error('jenis_kelamin')
                        <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tanggal Lahir & Pendidikan --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="tanggal_lahir" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Tanggal Lahir
                    </label>
                    <input type="date"
                           id="tanggal_lahir"
                           name="tanggal_lahir"
                           value="{{ old('tanggal_lahir') }}"
                           class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition @error('tanggal_lahir') border-rose-400 bg-rose-50 @enderror">
                    @error('tanggal_lahir')
                        <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="pendidikan_terakhir" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Pendidikan Terakhir
                    </label>
                    <div class="relative">
                        <select id="pendidikan_terakhir"
                                name="pendidikan_terakhir"
                                class="appearance-none w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition cursor-pointer @error('pendidikan_terakhir') border-rose-400 bg-rose-50 @enderror">
                            <option value="SD"              {{ old('pendidikan_terakhir') == 'SD' ? 'selected' : '' }}>SD / MI</option>
                            <option value="SMP"             {{ old('pendidikan_terakhir') == 'SMP' ? 'selected' : '' }}>SMP / MTS</option>
                            <option value="SMA"             {{ old('pendidikan_terakhir') == 'SMA' ? 'selected' : '' }}>SMA / SMK / MA</option>
                            <option value="Perguruan Tinggi" {{ old('pendidikan_terakhir') == 'Perguruan Tinggi' ? 'selected' : '' }}>Perguruan Tinggi</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </div>
                    </div>
                    @error('pendidikan_terakhir')
                        <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Status Asuhan --}}
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-2">
                    Status Asuhan <span class="text-rose-500">*</span>
                </label>
                <div class="flex flex-wrap gap-2.5">
                    @foreach(['Aktif' => ['color' => 'emerald', 'desc' => 'Sedang dalam pengasuhan'], 'Alumni' => ['color' => 'blue', 'desc' => 'Telah lulus / wisuda'], 'Non-Aktif' => ['color' => 'slate', 'desc' => 'Tidak aktif sementara']] as $val => $opt)
                        <label class="flex-1 min-w-[130px] cursor-pointer">
                            <input type="radio"
                                   name="status_asuhan"
                                   value="{{ $val }}"
                                   {{ old('status_asuhan', 'Aktif') == $val ? 'checked' : '' }}
                                   class="peer sr-only">
                            <div class="flex items-center gap-2.5 rounded-xl border-2 border-slate-200 px-4 py-3 transition
                                        peer-checked:border-[#2D6A4F] peer-checked:bg-[#2D6A4F]/5
                                        hover:border-slate-300 hover:bg-slate-50">
                                <div class="w-3.5 h-3.5 rounded-full border-2 border-slate-300 peer-checked:border-[#2D6A4F] flex items-center justify-center shrink-0
                                            transition [input:checked+div>&]:border-[#2D6A4F]">
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-800">{{ $val }}</p>
                                    <p class="text-[10px] text-slate-500">{{ $opt['desc'] }}</p>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('status_asuhan')
                    <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Keterangan --}}
            <div>
                <label for="keterangan" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Keterangan / Catatan
                </label>
                <textarea id="keterangan"
                          name="keterangan"
                          rows="3"
                          placeholder="Catatan tambahan mengenai santri, kondisi keluarga, dsb..."
                          class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition resize-none @error('keterangan') border-rose-400 bg-rose-50 @enderror">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Form Footer --}}
            <div class="pt-2 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                <a href="{{ route('admin.anak-asuh.index') }}"
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:border-slate-300 transition">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Batal
                </a>
                <button type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl bg-[#2D6A4F] text-xs font-semibold text-white hover:bg-[#1B4332] transition shadow-xs cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Simpan Data Anak Asuh
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
