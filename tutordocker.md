# Panduan Menjalankan Project dengan Docker di Windows

Panduan ini ditujukan untuk pengguna **Windows** yang baru pertama kali menjalankan project **Website Himatun Ayat** menggunakan Docker.

---

## 1. Prasyarat (Instalasi Pertama Kali)

1. **Install Docker Desktop for Windows**
   - Unduh dan install dari: https://www.docker.com/products/docker-desktop/
   - Saat instalasi, pastikan opsi **Use WSL 2 instead of Hyper-V** dicentang (direkomendasikan).
   - Jika diminta mengaktifkan/update WSL 2, buka **PowerShell** (Run as Administrator) dan jalankan:
     ```powershell
     wsl --install
     ```
     Lalu restart komputer jika diminta.
2. **Jalankan Docker Desktop**
   - Buka aplikasi **Docker Desktop** dan tunggu sampai status di pojok kiri bawah bertuliskan **Engine running** (ikon paus berwarna hijau).
3. **Pastikan Git sudah terpasang** (atau gunakan Git Bash / PowerShell / Windows Terminal).

> **Tips Performa:** Jika menggunakan WSL 2, sebaiknya clone project di dalam filesystem WSL (misalnya `~/projects/...`) agar proses `composer`, `npm`, dan hot-reload Vite berjalan jauh lebih cepat dibandingkan di drive `C:\`. Namun, menjalankan langsung dari folder Windows (`C:\...`) juga tetap bisa.

---

## 2. Clone Repository

Buka **PowerShell** atau **Git Bash**, lalu jalankan:

```powershell
git clone <url-repository>
cd Website_Himatun_Ayat
```

---

## 3. Siapkan File `.env`

Salin file `.env.example` menjadi `.env`:

- **Di PowerShell:**
  ```powershell
  Copy-Item .env.example .env
  ```
- **Di Command Prompt (CMD):**
  ```cmd
  copy .env.example .env
  ```

Buka file `.env` dan sesuaikan bagian database agar cocok dengan konfigurasi `compose.yaml`:

```env
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=website_himattun
DB_USERNAME=root
DB_PASSWORD=root
```

### Jika Port Bentrok (Misal Ada XAMPP / Laragon / MySQL Lokal)
Jika port default (`8000`, `5173`, `3306`, atau `8080`) sudah dipakai aplikasi lain di Windows, buka `.env` lalu hapus tanda `#` di bagian paling bawah dan ubah port-nya, misalnya:

```env
APP_PORT=8001
VITE_PORT=5174
FORWARD_DB_PORT=3307
FORWARD_PHPMYADMIN_PORT=8081
```

---

## 4. Build & Jalankan Container (Pertama Kali)

Pastikan Docker Desktop sudah menyala, lalu jalankan di terminal:

```powershell
docker compose up -d --build
```

> **Catatan:** Proses pertama kali akan memakan waktu beberapa menit karena Docker harus mengunduh image dan menginstall dependensi.

Secara otomatis, container akan menjalankan:
1. Build image PHP 8.4 + ekstensi (`Dockerfile`).
2. Menarik image `mysql:8.0`, `node:22-bookworm-slim`, `phpmyadmin`, dan `mailpit`.
3. Di container `app` (`docker-entrypoint.sh`):
   - `composer install`
   - `php artisan key:generate` (jika `APP_KEY` masih kosong)
   - `php artisan storage:link`
   - Membuat database `website_himattun` dan `testing`
   - `php artisan migrate --force`
   - `php artisan db:seed --force` (jika tabel `users` masih kosong)
4. Di container `vite`:
   - `npm install` dan menjalankan server dev `npm run dev`.

---

## 5. Pantau Proses Startup (Opsional)

Untuk melihat progres instalasi `composer`, migrasi database, dan `npm`:

```powershell
docker compose logs -f app vite
```

Tunggu sampai muncul pesan `[entrypoint] Starting application server...` dan Vite selesai dijalankan. Tekan `Ctrl + C` untuk keluar dari tampilan log (container tetap berjalan di background).

---

## 6. Cek Status & Akses Aplikasi

Pastikan semua container berstatus **Up** / **Healthy**:

```powershell
docker compose ps
```

Jika sudah siap, buka di browser:

| Layanan | URL Default | Keterangan |
| :--- | :--- | :--- |
| **Website Laravel** | http://localhost:8000 | Aplikasi utama (sesuaikan jika `APP_PORT` diubah) |
| **phpMyAdmin** | http://localhost:8080 | User: `root`, Password: `root` |
| **Mailpit** | http://localhost:8025 | Dashboard untuk melihat email testing |

---

## 7. Perintah Sehari-hari

- **Menjalankan perintah Artisan / Composer / Test:**
  ```powershell
  docker compose exec app php artisan migrate
  docker compose exec app php artisan test
  docker compose exec app composer install
  ```
- **Menghentikan container (data database tetap aman):**
  ```powershell
  docker compose down
  ```
- **Menyalakan lagi di hari berikutnya (tanpa build ulang):**
  ```powershell
  docker compose up -d
  ```
- **Reset total dari nol (menghapus volume database, `vendor`, dan `node_modules`):**
  ```powershell
  docker compose down -v
  ```

---

## 8. Troubleshooting Umum di Windows

1. **Error `error during connect` / `open //./pipe/dockerDesktopLinuxEngine`:**
   - Docker Desktop belum dijalankan. Buka Docker Desktop terlebih dahulu dan tunggu sampai statusnya **Engine running**.
2. **Error `Bind for 0.0.0.0:3306 failed: port is already allocated` (atau port `8000` / `8080`):**
   - Port sedang dipakai aplikasi lain (misalnya MySQL dari Laragon/XAMPP). Matikan aplikasi tersebut atau ubah port di `.env` (lihat langkah 3), lalu jalankan `docker compose up -d`.
3. **Vite HMR tidak update otomatis saat edit file di Windows:**
   - Konfigurasi `compose.yaml` sudah mengaktifkan `VITE_USE_POLLING=true` dan `CHOKIDAR_USEPOLLING="true"`, jadi perubahan file seharusnya tetap terdeteksi. Jika terasa lambat, pertimbangkan memindahkan folder project ke filesystem WSL 2.
