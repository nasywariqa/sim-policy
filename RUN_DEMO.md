# Menjalankan Demo PLC (Local Development)

## Prasyarat

- PHP 8.3+, Composer, Node.js, npm, dan MySQL (Laragon boleh digunakan).
- Project berada pada folder `D:\RPL\sim-ukm-plc` (atau folder lain sesuai mesin).
- Buka **Laragon → Start All** dan pastikan MySQL Server menerima koneksi.
- Jangan gunakan `DemoSeeder` pada production: akun demo memiliki password yang diketahui.

## 1. Setelah patch Git diterapkan

Jalankan dari root project pada PowerShell:

```powershell
composer install
npm install
if (-not (Test-Path .env)) {
    Copy-Item .env.example .env
    php artisan key:generate
}
```

Jika `.env` sudah ada, **jangan timpa**. Atur nilai berikut di `.env` sesuai MySQL lokal:

```dotenv
APP_NAME="PLC Management"
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sim_ukm_plc
DB_USERNAME=root
DB_PASSWORD=
SAW_SERVICE_URL=http://127.0.0.1:8010
SAW_SERVICE_TOKEN=ganti-dengan-token-lokal-yang-panjang
```

> Password database Laragon bisa berbeda. `SAW_SERVICE_TOKEN` harus sama di `.env` Laravel dan terminal Python.

Buat database satu kali melalui MySQL (gunakan kredensial lokal yang sesuai):

```powershell
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS sim_ukm_plc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

Jika root tidak mempunyai password, gunakan `mysql -u root -e "CREATE DATABASE ..."` tanpa `-p`. Jangan memasukkan password ke source code.

```powershell
php artisan config:clear
php artisan migrate
php artisan db:seed --class=DemoSeeder
```

**Data demo (hanya untuk lokal):**

| Role | Email | Password awal |
|---|---|---|
| Super Admin | `admin@plc.test` | `Demo12345!` |
| Ketua Umum (Pejabat Teras + permission SAW) | `ketum@plc.test` | `Demo12345!` |
| Pejabat Teras | `teras@plc.test` | `Demo12345!` |
| Ketua Bidang | `ketua@plc.test` | `Demo12345!` |
| Pengurus Bidang | `pengurus@plc.test` | `Demo12345!` |

## 2. Jalankan tiga terminal

**Terminal A — Laravel:**

```powershell
php artisan serve --host=127.0.0.1 --port=8000
```

**Terminal B — Vite:**

```powershell
npm run dev
```

**Terminal C — Python SAW:**

```powershell
$env:SAW_SERVICE_TOKEN="ganti-dengan-token-lokal-yang-panjang"
python services/saw-python/server.py
```

Buka **http://127.0.0.1:8000** (gunakan `127.0.0.1` secara konsisten agar cookie Sanctum bekerja). Jangan gunakan port Vite sebagai URL utama aplikasi.

## 3. Pemeriksaan minimum

```powershell
php artisan route:list
php artisan migrate:status
php artisan test
npm run typecheck
npm run build
python -m unittest discover -s services/saw-python -p "test_*.py" -v
```

Catatan pengujian: `phpunit.xml` menggunakan SQLite in-memory untuk test.
Jika PHP lokal belum mengaktifkan ekstensi `pdo_sqlite`, `php artisan test`
akan gagal sebelum test berjalan. Ini tidak memengaruhi aplikasi demo yang
menggunakan MySQL; aktifkan `pdo_sqlite` di konfigurasi PHP Laragon jika
ingin menjalankan suite tersebut. Jangan mengarahkan test ke database
demo `sim_ukm_plc` atau menjalankan reset database pada data demo.

**Alur demo:** Landing → Login sebagai Ketua Bidang → buat/ajukan program → login Pejabat Teras → evaluasi/penilaian → Ketua Umum atur bobot → hitung ranking (Python harus hidup) → ambil keputusan → login Ketua Bidang dan lihat status.

## 4. Batasan V1 dan status sementara

Paket ini adalah **MVP demo**, bukan penyelesaian final seluruh TBD. Untuk memudahkan demonstrasi, asumsi operasional sementara yang digunakan:
- Satu periode aktif pada satu waktu.
- PIC dibatasi satu bidang dan periode pada V1.
- Lima kriteria SAW memakai konfigurasi awal **usulan** dari PRD: bobot 25/20/15/25/15, benefit/cost sesuai rancangan awal, skala 1–5 kecuali anggaran memakai nilai rupiah.
- Skor kriteria kualitatif diagregasi sebagai rata-rata penilaian yang sudah masuk. Anggaran diambil dari nilai anggaran program kerja.
- Anggaran nol dihitung valid; untuk cost dengan nilai minimum nol, alternatif nol mendapatkan normalisasi 1 dan yang positif 0.
- Ranking seri dipisahkan dengan urutan ID stabil. Ini bersifat provisional.
- Catatan evaluasi hanya dapat ditambah, bukan diedit/dihapus melalui UI/API yang disediakan sekarang; kebijakan final masih TBD.
- Riwayat keputusan dan hasil SAW disimpan; tidak dihapus.
- Ketua Bidang dapat menandai program yang disetujui sebagai selesai untuk keperluan demo; aturan aktor final masih TBD.
- Pejabat Teras berhak melihat ranking; role lain belum diberikan akses.
- Tidak ada self-registration, export, email notification, atau halaman kosong.

**Minta konfirmasi dan perbarui PRD/SRS sebelum menganggap asumsi tersebut sebagai keputusan produk final.**

## 5. Catatan operasional

- Jika `php artisan route:list` gagal karena Sanctum belum terpasang, jalankan `composer require laravel/sanctum` terlebih dahulu.
- Jangan jalankan `migrate:fresh` pada database yang memiliki data penting.
- Paket ZIP berisi patch kode, bukan dependency `vendor/` atau `node_modules/`.
- Bila `npm install` menghasilkan perubahan lockfile, simpan lockfile tersebut pada commit berikutnya.
- Jangan menaruh `.env` atau password nyata dalam ZIP yang diunggah.
