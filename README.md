# Sistem Informasi dan Portal Web Yayasan Himatun Ayat Bandung

Sistem Informasi dan Portal Web Yayasan Himatun Ayat Bandung adalah aplikasi berbasis web terintegrasi yang dirancang untuk memperkuat transparansi operasional panti asuhan, mempublikasikan profil dan kegiatan anak asuh, serta memfasilitasi penghimpunan donasi secara digital dan terstruktur.

Aplikasi ini menghubungkan masyarakat/calon donatur dengan pengelola yayasan melalui dua antarmuka utama:
1. **Front-End Publik (Donatur / Pengguna)**: Portal informasi profil, visi-misi, dokumen legalitas resmi, transparansi kegiatan anak asuh, peta lokasi Google Maps, kontak interaktif WhatsApp, serta alur donasi manual dengan upload bukti transfer.
2. **Back-End CMS (Admin / Pengurus Yayasan)**: Panel manajemen terpusat (*low technical barrier*) yang ramah pengguna untuk mengelola biodata anak asuh, verifikasi bukti donasi masuk, kampanye donasi, CMS berita, dan ekspor laporan keuangan.

---

## Informasi Yayasan
- **Nama Yayasan**: Yayasan Himatun Ayat Bandung
- **Alamat**: Jl. Cibiru Indah 7 RT/RW 04/14 Des. Cibiru Wetan, Kec. Cileunyi, Kab. Bandung
- **Integrasi Eksternal**: Google Maps API, WhatsApp Direct API, Cloudflare R2 Storage

---

## Tech Stack & Arsitektur Sistem

| Komponen | Teknologi | Peran & Keterangan |
| :--- | :--- | :--- |
| **Framework Backend** | **Laravel 13 (PHP 8.4+)** | Routing, Controller MVC, Eloquent ORM, migrasi database, autentikasi session admin. |
| **UI Components & Styling** | **Preline UI & Tailwind CSS v4** | Komponen UI modern & responsif (sidebar offcanvas drawer, modal popups, tabel responsif, stat cards, dropdown). |
| **Interaktivitas Client-side** | **Preline JS & Alpine.js** | Interaksi drawer/modal bebas reload, instant photo preview, dan manipulasi form yang ringan dan cepat. |
| **Build Tools** | **Vite** | Bundler aset modern berkecepatan tinggi dengan integrasi `@tailwindcss/vite`. |
| **Cloud Storage** | **Cloudflare R2** | *Object storage* dengan *zero egress fee* untuk penyimpanan aset gambar & dokumen. |
| **Laporan & Ekspor** | **DomPDF & Maatwebsite Excel** | *Engine* pembuatan laporan rekap donasi format PDF dan berkas spreadsheet Excel. |
| **Database** | **MySQL** | Basis data relasional untuk anak asuh, kampanye, transaksi donasi, dan berita. |

---

## Keamanan Penyimpanan Data (Cloudflare R2)

Sistem menerapkan prinsip pemisahan bucket untuk menjamin keamanan dan privasi donatur:

```text
Cloudflare R2 Storage
├── r2_public (Bucket Publik)
│   ├── berita/        # Cover dan dokumentasi artikel
│   ├── anak-asuh/     # Foto profil anak asuh
│   └──> Diakses langsung melalui URL CDN publik.
│
└── r2_private (Bucket Tertutup / Privat)
    └── bukti-transfer/ # Foto struk / slip transfer donatur
    └──> Akses tertutup total dari publik.
    └──> Hanya dapat diakses oleh Admin via Temporary Pre-Signed URL (kedaluwarsa dalam 10 menit).
```

---

## Arsitektur Struktur Folder

```text
Website_Himattun/
├── app/
│   ├── Exports/
│   │   ├── AnakAsuhExport.php             # Logika ekspor data santri ke Excel
│   │   └── DonasiExport.php               # Logika ekspor rekap donasi ke Excel
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Controller.php             # Base controller
│   │       └── Admin/                     # Controller modul manajemen yayasan
│   │           ├── DashboardController.php# Ringkasan statistik & validasi donasi
│   │           └── AnakAsuhController.php # CRUD data anak asuh & ekspor
│   ├── Models/
│   │   ├── User.php                       # Akun pengurus admin yayasan
│   │   ├── AnakAsuh.php                   # Model tabel anak_asuh
│   │   ├── Kampanye.php                   # Model tabel kampanye
│   │   ├── Donasi.php                     # Model tabel donasi & bukti transfer
│   │   └── Berita.php                     # Model tabel berita & kegiatan
│   ├── Providers/
│   │   └── AppServiceProvider.php         # Provider konfigurasi pagination & app
│   └── Services/
│       ├── CloudflareR2Service.php        # Helper upload & signed URL Cloudflare R2
│       └── LaporanService.php             # Kalkulasi rumus & rekapitulasi data donasi
├── config/
│   └── filesystems.php                    # Konfigurasi disk r2_public & r2_private
├── database/
│   ├── migrations/                        # Skema database inti (anak asuh, donasi, dll.)
│   └── seeders/                           # Seeder data demo & akun pengurus
└── resources/
    ├── css/
    │   └── app.css                        # Tailwind CSS v4 & varian Preline UI
    ├── js/
    │   └── app.js                         # Bundler JS (Preline UI & Alpine.js)
    └── views/
        ├── layouts/
        │   ├── app.blade.php              # Layout publik (Navbar, Footer, Preline scripts)
        │   └── admin.blade.php            # Layout CMS (Sidebar responsif Preline Offcanvas)
        ├── admin/                         # View modul manajemen yayasan
        │   ├── dashboard.blade.php        # Ringkasan & antrean validasi donasi
        │   ├── anak-asuh/                 # View data santri (tabel, modal & cetak PDF)
        │   └── laporan/                   # Template cetak PDF rekapitulasi donasi
        └── welcome.blade.php              # Halaman awal publik
```

---

## Arsitektur Percabangan Git (Branching Strategy)

Repositori ini menerapkan alur kerja berbasis fitur (*Feature-Branch Workflow*) dengan percabangan sebagai berikut:

```text
production (Branch rilis stabil / live server)
  └── develop (Branch integrasi & pengujian)
       │
       ├── [BAGIAN PENGGUNA / DONATUR]
       │    ├── feature/user-landingpage
       │    ├── feature/user-profile
       │    ├── feature/user-kampanye-donasi
       │    └── feature/user-berita-kegiatan
       │
       └── [BAGIAN ADMIN / PENGURUS]
            ├── feature/admin-auth
            ├── feature/admin-dashboard
            ├── feature/admin-data-anak-asuh
            ├── feature/admin-kelola-donasi
            ├── feature/admin-kelola-kampanye
            ├── feature/admin-cms-berita
            └── feature/admin-laporan-ekspor
```

---

## Rincian Modul Per Fitur

### 1. Branch Utama
| Branch | Fungsi | Keterangan |
| :--- | :--- | :--- |
| `production` | Production Live | Branch stabil yang dideploy ke server produksi yayasan. Hanya menerima merge dari `develop` atau `hotfix`. |
| `develop` | Integration & Staging | Branch integrasi utama tempat penggabungan seluruh branch fitur untuk pengujian bersama sebelum rilis. |

---

### 2. Sisi Donatur / Pengguna Publik (`feature/user-*`)
| Nama Branch | Acuan SRS | Deskripsi Fitur |
| :--- | :--- | :--- |
| `feature/user-landingpage` | KF-02 | Beranda utama, Hero banner, integrasi Peta Presisi Google Maps (Jl. Cibiru Indah 7), dan tombol chat interaktif WhatsApp Direct API. |
| `feature/user-profile` | KF-01, KF-03 | Profil lengkap yayasan, visi & misi, sejarah, bagan struktur kepengurusan, dan berkas transparansi dokumen legalitas resmi. |
| `feature/user-kampanye-donasi` | KF-05, PD-02, PD-03 | Katalog program binaan yang membutuhkan dana serta formulir pembayaran donasi online (QRIS, Transfer Bank, & upload bukti). |
| `feature/user-berita-kegiatan` | KF-04, PD-01 | Publikasi artikel berita operasional yayasan dan galeri foto dokumentasi kegiatan anak asuh. |

---

### 3. Sisi Admin / Pengurus Yayasan (`feature/admin-*`)
| Nama Branch | Menu Sidebar UI | Deskripsi Fitur |
| :--- | :--- | :--- |
| `feature/admin-auth` | *Otentikasi* | Sistem login khusus pengurus yayasan, manajemen sesi Laravel, proteksi route admin, dan pencegahan SQL Injection (KF-06, KNF-03, KNF-08). |
| `feature/admin-dashboard` | **Dashboard** | Ringkasan metrik yayasan (total dana donasi terverifikasi, jumlah anak asuh aktif, kampanye aktif, dan aktivitas terkini). |
| `feature/admin-data-anak-asuh` | **Data Anak Asuh** | Modul CRUD pendataan biodata anak asuh binaan (nama, usia, jenjang pendidikan, status asuhan). |
| `feature/admin-kelola-donasi` | **Kelola Donasi** | Pencatatan donasi masuk, verifikasi berkas bukti transfer, persetujuan status pembayaran, dan riwayat pesan donatur (KF-08, PY-03). |
| `feature/admin-kelola-kampanye` | **Kelola Kampanye** | Pengelolaan (CRUD) program kampanye donasi, target nominal dana, deskripsi program, dan status masa berlaku kampanye (KF-07). |
| `feature/admin-cms-berita` | **CMS Berita** | Editor penulisan artikel/kegiatan yayasan dan manajemen upload foto galeri kegiatan anak asuh ke Cloudflare R2 (KF-07, PY-01, PY-02). |
| `feature/admin-laporan-ekspor` | **Laporan & Ekspor** | Rekapitulasi laporan keuangan donasi terpusat dan fasilitas ekspor data ke format PDF/Excel untuk arsip dan transparansi (KNF-02, PY-03). |

---

## Panduan Instalasi & Menjalankan Proyek

### 1. Kloning Repositori
```bash
git clone https://github.com/AdzrilIlham/Website_Himattun.git
cd Website_Himattun
git checkout develop
```

### 2. Instalasi Dependency
```bash
# Instal dependency PHP via Composer
composer install

# Instal dependency Node & Tailwind via NPM
npm install
```

### 3. Konfigurasi Environment
Salin file environment dan generate application key:
```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan konfigurasi database dan kredensial Cloudflare R2 di `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=website_himattun
DB_USERNAME=root
DB_PASSWORD=

# Cloudflare R2 Storage
CLOUDFLARE_R2_ACCOUNT_ID=your-account-id
CLOUDFLARE_R2_ACCESS_KEY_ID=your-access-key-id
CLOUDFLARE_R2_SECRET_ACCESS_KEY=your-secret-access-key
CLOUDFLARE_R2_ENDPOINT=https://${CLOUDFLARE_R2_ACCOUNT_ID}.r2.cloudflarestorage.com
CLOUDFLARE_R2_BUCKET_PUBLIC=yayasan-public
CLOUDFLARE_R2_PUBLIC_URL=https://pub-xxxxxx.r2.dev
CLOUDFLARE_R2_BUCKET_PRIVATE=yayasan-private
```

### 4. Migrasi Database
```bash
php artisan migrate
```

### 5. Menjalankan Server Pengembangan
Jalankan frontend compiler dan server backend secara paralel:
```bash
# Terminal 1: Vite & Tailwind Compiler
npm run dev

# Terminal 2: Laravel Server
php artisan serve
```
Akses portal publik melalui peramban: `http://localhost:8000`

---

## Panduan Alur Kerja Git (Git Workflow)

1. **Memulai Fitur**:
   ```bash
   git checkout develop
   git pull origin develop
   git checkout <nama-branch-fitur>
   ```

2. **Format Commit (Conventional Commits)**:
   ```bash
   git add .
   git commit -m "feat: tambahkan integrasi peta google maps pada landing page (KF-02)"
   ```

3. **Mengirim Fitur**:
   ```bash
   git push -u origin <nama-branch-fitur>
   ```
   Buat *Pull Request* (PR) di GitHub dari `<nama-branch-fitur>` menuju ke branch `develop`.

---

## Lisensi & Hak Cipta
Hak Cipta © 2026 Yayasan Himatun Ayat Bandung. Seluruh hak cipta dilindungi undang-undang.
