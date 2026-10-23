# EduPay SPP

Aplikasi pembayaran SPP sekolah berbasis Laravel. Admin mengelola tahun ajaran, kelas, tarif, siswa, tagihan, pembayaran, dan laporan. Siswa dapat melihat tunggakan, membayar tagihan secara online melalui Midtrans, serta mengunduh/mencetak kwitansi.

## Fitur

### Admin
- Dashboard ringkasan siswa aktif, tagihan bulan berjalan, penerimaan, tunggakan, grafik 6 bulan, dan persentase kelunasan per kelas.
- Pengelolaan tahun ajaran dengan satu tahun ajaran aktif.
- Pengelolaan kelas dan tarif SPP per tingkat (X, XI, XII).
- Pengelolaan siswa beserta akun login siswa.
- Pembuatan tagihan manual dan generate tagihan massal per bulan.
- Generate tagihan aman diulang karena tagihan yang sudah ada dilewati.
- Pencatatan pembayaran tunai/transfer yang langsung melunasi tagihan.
- Pembayaran online melalui Midtrans Snap.
- Kwitansi digital siap cetak.
- Laporan pembayaran dengan filter tahun ajaran, bulan, tahun, kelas, status, dan pencarian.
- Ekspor laporan ke CSV.
- Pengaturan identitas sekolah, bendahara, kepala sekolah, dan catatan kwitansi.

### Siswa
- Login menggunakan email atau NIS.
- Dashboard tunggakan dan status pembayaran.
- Daftar tagihan per bulan.
- Pembayaran online beberapa tagihan sekaligus.
- Riwayat pembayaran dan kwitansi.

## Teknologi

- PHP 8.3+ (diuji pada PHP 8.4)
- Laravel 13
- SQLite (default) atau database lain yang didukung Laravel
- Tailwind CSS 4
- Vite 8
- PHPUnit 12
- Midtrans Snap

## Persyaratan

- PHP 8.3 atau lebih baru dengan ekstensi `pdo_sqlite`
- Composer 2
- Node.js 20.19+ atau 22.12+
- npm
- Git

## Instalasi

```bash
git clone <URL_REPOSITORY> "Sistem Pembayaran Spp Sekolah"
cd "Sistem Pembayaran Spp Sekolah"

composer install

# Windows
copy .env.example .env

# Linux/macOS
cp .env.example .env

php artisan key:generate

# Windows PowerShell
New-Item -ItemType File -Path database\database.sqlite -Force

# Linux/macOS
touch database/database.sqlite

php artisan migrate --seed

npm install
npm run build
```

Sesuaikan `APP_URL` di `.env` jika aplikasi tidak berjalan di `http://localhost:8000`.

## Menjalankan Aplikasi

Jalankan server Laravel dan Vite di dua terminal:

```bash
php artisan serve
```

```bash
npm run dev
```

Atau gunakan:

```bash
composer run dev
```

Buka `http://localhost:8000`. Jika port 8000 sudah dipakai, gunakan port lain dan sesuaikan `APP_URL`:

```bash
php artisan serve --port=8001
```

## Akun Demo

Data demo dibuat oleh `DatabaseSeeder`.

| Peran | Login | Kata sandi |
| --- | --- | --- |
| Admin | `admin@edupay.test` | `password` |
| Siswa | `20261001` | `password` |

Siswa demo `20261001` memiliki tagihan Juli-Oktober 2026 dengan Juli dan Agustus sudah lunas, sedangkan September dan Oktober belum dibayar. Seluruh siswa demo menggunakan kata sandi `password`.

Data demo mencakup 24 siswa, 6 kelas, tarif SPP X/XI/XII, tagihan Juli-Oktober 2026, dan sebagian pembayaran.

## Konfigurasi Midtrans

Tambahkan kredensial Midtrans pada `.env`:

```env
MIDTRANS_SERVER_KEY=your-server-key
MIDTRANS_CLIENT_KEY=your-client-key
MIDTRANS_IS_PRODUCTION=false
```

- `MIDTRANS_IS_PRODUCTION=false` menggunakan environment sandbox.
- Jika `MIDTRANS_SERVER_KEY` kosong, aplikasi berjalan dalam mode simulasi: pembayaran online langsung dianggap berhasil tanpa memanggil Midtrans.
- Atur URL notifikasi Midtrans ke `{APP_URL}/payments/callback`.
- Endpoint callback memvalidasi signature SHA512 dan hanya memproses invoice yang valid.

Untuk production, gunakan URL HTTPS yang dapat diakses publik, set `APP_DEBUG=false`, dan isi kredensial Midtrans production.

## Alur Sistem

1. Admin mengatur tahun ajaran aktif, tarif SPP per tingkat, kelas, dan siswa.
2. Admin membuat tagihan manual atau generate tagihan bulanan untuk seluruh siswa.
3. Siswa membayar melalui portal, atau admin mencatat pembayaran tunai/transfer.
4. `PaymentService` membuat invoice dan `payment_items`, lalu memperbarui `paid_amount` serta status tagihan menjadi `unpaid`, `partial`, atau `paid`.
5. Status pembayaran diperbarui melalui Midtrans callback atau pengecekan status di halaman finish.
6. Siswa dan admin dapat melihat riwayat serta mencetak kwitansi.
7. Admin memantau dashboard dan mengekspor laporan pembayaran ke CSV.

Status tagihan: `unpaid`, `partial`, `paid`.

Status pembayaran: `pending`, `success`, `failed`.

## Struktur Proyek

```text
app/
  Enums/                     Status tagihan, pembayaran, dan peran pengguna
  Http/
    Controllers/
      Admin/                 Dashboard, master data, tagihan, pembayaran, laporan
      Auth/                  Login dan logout
      Portal/                Dashboard, tagihan, dan pembayaran siswa
      PaymentCallbackController.php
  Models/                    AcademicYear, SchoolClass, Student, Fee, Bill, Payment, PaymentItem, Setting, User
  Services/
    MidtransService.php      Integrasi Midtrans Snap dan validasi callback
    PaymentService.php       Pembuatan pembayaran dan pembaruan status tagihan
  Support/helpers.php        Helper rupiah() dan school_setting()

database/
  migrations/                Skema database
  seeders/DatabaseSeeder.php Data demo

resources/
  css/app.css                Tailwind dan token warna
  views/                     Halaman admin, portal, auth, dan kwitansi

routes/web.php               Seluruh route aplikasi
tests/Feature/               Tes autentikasi, tagihan, pembayaran, akses, dan laporan
```

## Pengujian

```bash
php artisan test
```

Menjalankan tes tertentu:

```bash
php artisan test tests/Feature/PaymentTest.php
```

Memformat kode PHP:

```bash
vendor/bin/pint --dirty
```

## Keamanan

- File `.env` dan database SQLite lokal tidak disertakan ke Git.
- Jangan pernah meng-commit `MIDTRANS_SERVER_KEY` atau kredensial lain.
- Ganti kata sandi akun demo sebelum aplikasi digunakan di lingkungan nyata.
- Gunakan HTTPS di production.
- Cadangkan database secara berkala.

## Lisensi

Proyek ini menggunakan lisensi MIT.
