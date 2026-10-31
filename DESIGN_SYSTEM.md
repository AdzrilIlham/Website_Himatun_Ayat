# Design System — Himmatun Ayat

Fondasi tampilan untuk website & admin panel Himmatun Ayat. Semua warna, tipografi, dan
komponen dasar bersumber dari dua file:

| File | Isi |
| --- | --- |
| `resources/css/theme.css` | Palet warna brand, token semantik (light & dark), ukuran teks tambahan |
| `resources/css/app.css` | Import Preline, base style, dan komponen dasar (`.btn`, `.card`, `.form-input`, `.badge`, `.alert`) |

Preline UI sudah terpasang (`preline@4`) dan di-import lewat `resources/js/app.js`.

---

## 1. Palet warna

### Brand — Nature Green

| Token | Hex | Penggunaan |
| --- | --- | --- |
| `brand-50` | `#F2F9F4` | Latar paling lembut |
| `brand-100` | `#D8F3DC` | Latar highlight / icon box |
| `brand-200` | `#B7E4C7` | Border highlight |
| `brand-300` | `#95D5B2` | Aksen ringan |
| `brand-400` | `#74C69D` | Aksen |
| `brand-500` | `#52B788` | Aksen kuat |
| `brand-600` | `#40916C` | Hover di dark mode |
| `brand-700` | `#2D6A4F` | **Warna utama** (tombol, nav aktif, link) |
| `brand-800` | `#24533C` | Hover tombol utama |
| `brand-900` | `#1B4332` | Heading / teks tegas |
| `brand-950` | `#081C15` | Teks paling gelap |

### Status

| Token | Nilai | Penggunaan |
| --- | --- | --- |
| `success` / `success-soft` | `#2D6A4F` / `#D8F3DC` | Donasi terverifikasi, status aktif |
| `warning` / `warning-soft` | `#B45309` / `#FEF3C7` | Menunggu verifikasi |
| `danger` / `danger-soft` | `#DC2626` / `#FEE2E2` | Ditolak, hapus, error |
| `info` / `info-soft` | `#0369A1` / `#E0F2FE` | Informasi netral |

### Netral

Menggunakan **Slate** bawaan Tailwind (`slate-50` … `slate-950`). Tidak ada palet abu-abu
khusus agar konsisten dengan halaman yang sudah ada.

---

## 2. Token semantik

Token ini dipetakan ke utility Tailwind oleh Preline, jadi bisa langsung dipakai:

```html
<body class="bg-background text-foreground">
    <div class="bg-card border-border text-muted-foreground">...</div>
    <button class="bg-primary text-primary-foreground hover:bg-primary-hover">Simpan</button>
</body>
```

| Kelompok | Contoh utility |
| --- | --- |
| Permukaan | `bg-background`, `bg-background-1`, `bg-layer`, `bg-surface` |
| Teks | `text-foreground`, `text-muted-foreground`, `text-primary` |
| Border | `border-border`, `border-border-line-2` |
| Aksi | `bg-primary`, `bg-primary-hover`, `bg-secondary`, `bg-destructive` |
| Komponen Preline | `bg-card`, `bg-navbar`, `bg-sidebar`, `bg-dropdown`, `bg-overlay`, `bg-tooltip` |

**Aturan pakai:** untuk warna brand sehari-hari pakai `brand-*` (mis. `bg-brand-700`).
Untuk komponen yang dibuat dengan Preline, pakai token semantik (`bg-primary`, `bg-card`, dst)
supaya otomatis ikut tema.

---

## 3. Tipografi

- Font: **Plus Jakarta Sans** (di-load lewat `laravel-vite-plugin/fonts` di `vite.config.js`
  dan `<link>` Bunny Fonts di setiap layout).
- Utility `text-2xs` (`11px`) disediakan untuk label tabel, badge, dan metadata.
- Gunakan `tabular` pada nominal uang agar angka rata:

```html
<span class="tabular font-bold">Rp 1.250.000</span>
```

Skala yang dipakai: `text-xs` (12), `text-sm` (14), `text-base` (16), `text-lg` (18),
`text-xl` (20), `text-2xl` (24), `text-3xl` (30).

---

## 4. Komponen dasar

Komponen ini opsional. Utility Tailwind tetap boleh dipakai langsung.

```html
<!-- Tombol -->
<button class="btn btn-primary">Donasi Sekarang</button>
<button class="btn btn-secondary">Batal</button>
<button class="btn btn-ghost btn-sm">Reset</button>
<button class="btn btn-danger">Hapus</button>

<!-- Card -->
<div class="card">
    <div class="card-header"><h2 class="font-semibold">Total Donasi</h2></div>
    <div class="card-body">...</div>
    <div class="card-footer">...</div>
</div>

<!-- Form -->
<label class="form-label" for="nama">Nama</label>
<input id="nama" class="form-input" type="text" placeholder="Masukkan nama">
<p class="form-hint">Sesuai KTP.</p>
<p class="form-error">Nama wajib diisi.</p>

<!-- Badge -->
<span class="badge badge-success">Terverifikasi</span>
<span class="badge badge-warning">Menunggu</span>
<span class="badge badge-danger">Ditolak</span>
<span class="badge badge-neutral">Arsip</span>

<!-- Alert -->
<div class="alert alert-success">Data berhasil disimpan.</div>
```

---

## 5. Preline UI

Setup yang sudah dilakukan:

1. `resources/js/app.js` → import Preline + `HSStaticMethods.autoInit()` setelah DOM siap.
2. `resources/css/app.css` → import `preline/variants.css` + `preline/css/themes/theme.css`
3. Token brand di-override di `resources/css/theme.css`.

Artinya komponen dari [dokumentasi Preline](https://preline.co/docs/) bisa langsung
di-copy dan warnanya otomatis mengikuti brand. Contoh:

```html
<!-- Accordion -->
<div class="hs-accordion-group">
    <div class="hs-accordion active" id="faq-1">
        <button class="hs-accordion-toggle" aria-expanded="true" aria-controls="faq-1-content">
            Pertanyaan
        </button>
        <div id="faq-1-content" class="hs-accordion-content overflow-hidden transition-[height] duration-300">
            Jawaban.
        </div>
    </div>
</div>

<!-- Dropdown -->
<div class="hs-dropdown relative inline-flex">
    <button class="hs-dropdown-toggle btn btn-secondary" aria-expanded="false">Aksi</button>
    <div class="hs-dropdown-menu hidden" role="menu">
        <a class="block px-4 py-2 hover:bg-dropdown-item-hover" href="#">Edit</a>
    </div>
</div>
```

Catatan Preline v4:

- Preline v4 tidak auto-init saat di-import. `resources/js/app.js` memanggil
  `HSStaticMethods.autoInit()` setelah DOM siap.
- Kalau menambah markup Preline secara dinamis (fetch/Alpine/partial), panggil
  `window.HSStaticMethods.autoInit()` lagi.
- Gunakan atribut `hs-*` (bukan `data-hs-*` lama) untuk toggle/menu.
- Styling state memakai variant Preline, mis. `hs-dropdown-open:opacity-100`,
  `hs-accordion-active:text-primary`.

### 5.1 Select (`x-preline-select`)

Semua `<select>` memakai komponen Blade ini agar tampilan & perilakunya seragam:

```blade
<x-preline-select name="kategori" x-model="kategori" required placeholder="Pilih kategori">
    <option value="" selected>Pilih kategori</option>
    @foreach($kategoriList as $kat)
        <option value="{{ $kat }}">{{ $kat }}</option>
    @endforeach
</x-preline-select>
```

Props yang tersedia:

| Prop | Default | Keterangan |
| --- | --- | --- |
| `placeholder` | `Pilih opsi...` | Teks saat belum ada nilai |
| `wrapperClasses` | `relative w-full` | Class wrapper `.hs-select` |
| `toggleClasses` | gaya form standar | Class tombol toggle |
| `dropdownClasses` | gaya dropdown standar | Class panel opsi |
| `optionClasses` | gaya opsi standar | Class tiap opsi |
| `mode` | `null` | Diisi `tags` untuk mode tag (multi) |

Hal penting:

- Komponen selalu mengirim `optionAllowEmptyOption: true`. Karena itu, opsi
  placeholder **wajib** memakai `<option value="" selected>` dan **jangan**
  `disabled` — opsi `disabled` membuat browser memilih opsi pertama yang aktif
  sehingga placeholder tidak tampil dan nilai form terisi tanpa disengaja.
- Select yang nilai awalnya diisi server cukup pakai `@selected(...)`
  (contoh: halaman edit kampanye).
- Untuk select yang dipakai dengan `x-model`, gunakan `window.syncPrelineSelect(el)`
  setelah nilai diubah secara programatik (mis. saat mengisi form edit) agar teks
  toggle ikut berubah. Helper ini juga mengembalikan nilai native yang di-reset
  oleh `setValue()` Preline.
- Untuk memfokuskan select dari validasi Alpine, pakai
  `window.focusPrelineSelect(el)` (elemen `<select>` aslinya disembunyikan Preline).
- Select yang memicu submit (`onchange="this.form.submit()"`) tetap berfungsi;
  event `change` dari Preline diteruskan ke `<select>` asli.

### 5.2 Modal / Offcanvas

Modal memakai overlay Preline, bukan `x-show`:

```blade
<button type="button" data-hs-overlay="#contoh-modal" aria-haspopup="dialog"
        aria-controls="contoh-modal">Buka</button>

<div id="contoh-modal"
     class="hs-overlay hidden size-full fixed top-0 inset-s-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none"
     role="dialog" tabindex="-1" aria-labelledby="contoh-modal-label">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <div class="relative bg-overlay rounded-2xl shadow-2xl border border-overlay-line overflow-hidden pointer-events-auto">
            <h3 id="contoh-modal-label">Judul</h3>
            ...
            <button type="button" data-hs-overlay="#contoh-modal">Tutup</button>
        </div>
    </div>
</div>
```

- Tombol buka/tutup memakai `data-hs-overlay="#id"` yang sama.
- State Alpine tetap boleh menempel di tombol yang sama
  (`@click="openEdit(...)"`); Preline tidak menghentikan propagasi, jadi keduanya
  berjalan. Pola ini dipakai di halaman Anak Asuh dan Dashboard.
- Isi modal dinamis (mis. data baris tabel) tetap dikendalikan Alpine
  (`selectedAnak`, `editData`, `previewSrc`).
- Jangan memakai template literal (backtick + `${}`) di dalam atribut `x-data`
  atau atribut Alpine lain — tanda kutip ganda di dalamnya memotong atribut HTML.
  Pakai konkatenasi string, mis. `'[name=' + name + ']'`.

### 5.3 Batasan yang disengaja

- **Wizard & tab form** di `kelola-kampanye/create` dan `edit` tetap memakai
  Alpine (`x-show`) karena ada gating validasi per langkah dan state `isDirty`.
  Preline Tabs tidak dipakai di sana agar validasi tidak bisa dilewati.
- **Tooltip** hanya dipakai di halaman `/design-system`. Tombol aksi ikon di
  tabel padat tetap memakai atribut `title` native agar tidak membuat ratusan
  instance tooltip.
- **Sidebar admin** memakai Preline Offcanvas (`#admin-sidebar`) untuk mobile,
  sementara di desktop sidebar tetap statis.


---

## 6. Dark mode (opsional)

Dark mode berbasis class, bukan media query. Aktifkan dengan menambah `class="dark"`
pada `<html>`:

```html
<html class="dark">
```

Token dark sudah disiapkan di `theme.css`. Class `dark:` juga tersedia untuk utility,
contoh `dark:bg-slate-900`.

Untuk badge/pill yang memakai brand pastel, tambahkan pasangan dark-nya agar tetap
kontras di kedua tema:

```html
<span class="bg-brand-100 dark:bg-brand-900/50 text-brand-700 dark:text-brand-200 border border-brand-200 dark:border-brand-800">
    Pendidikan
</span>
```

Pola yang sama dipakai untuk header tabel (`dark:bg-brand-900/40`) dan tombol
sekunder brand (`dark:hover:bg-brand-800`).

---

## 7. Migrasi dari hardcode

Halaman lama masih memakai hex langsung. Saat menyentuh file tersebut, ganti dengan token:

| Hardcode | Token |
| --- | --- |
| `bg-[#2D6A4F]` | `bg-brand-700` |
| `hover:bg-[#24533C]` | `hover:bg-brand-800` |
| `text-[#1B4332]` | `text-brand-900` |
| `bg-[#E8F5E9]` | `bg-brand-50` |
| `text-emerald-700`, `bg-emerald-700` | `text-brand-700`, `bg-brand-700` |
| `bg-[#F8FAFC]` | `bg-background` |
| `text-slate-800` (body) | `text-foreground` |

Hindari menambah hex baru. Kalau butuh warna baru, tambahkan token di
`resources/css/theme.css`, bukan langsung di Blade.

---

## 8. Preview

Halaman preview design system tersedia di:

```
http://localhost:8000/design-system
```

Hanya aktif saat `APP_ENV=local` (lihat `routes/web.php`).
