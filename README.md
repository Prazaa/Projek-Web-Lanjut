# SIMKA — Sistem Informasi Pengajuan Surat Kemahasiswaan

Aplikasi web pengajuan surat keterangan mahasiswa untuk **Fakultas Matematika dan Ilmu Pengetahuan Alam (FMIPA) Universitas Riau**. Mahasiswa mengajukan surat secara daring, staf akademik memverifikasi, pimpinan menandatangani, dan surat terbit sebagai PDF ber-QR code yang dapat diverifikasi publik.

## Fitur

- **Mahasiswa** — login dengan email/kata sandi atau Google, mengajukan surat, mengunggah lampiran, melacak status, mengunduh surat PDF, dan mengelola profil.
- **Staf Akademik** — antrean pengajuan, meninjau lampiran, menyetujui (sekaligus menyusun isi surat) atau menolak dengan alasan.
- **Pimpinan** — menandatangani surat, dengan nomor surat otomatis dan kode verifikasi unik.
- **Publik** — memverifikasi keaslian surat tanpa login; NIM pemohon disamarkan.

Alur status: `diajukan → diproses/revisi → siap_ttd → selesai` atau `ditolak`.

## Teknologi

Laravel 13 · PHP 8.3+ · MySQL 8 · Blade + Tailwind CSS (Vite) · Laravel Breeze · Laravel Socialite (Google) · DomPDF · chillerlan/php-qrcode · PHPUnit 12.

## Persyaratan

- PHP 8.3+ dengan ekstensi `bcmath`, `curl`, `fileinfo`, `gd`, `intl`, `mbstring`, `openssl`, `pdo_mysql`, `zip`
- Composer 2, Node.js 20+, npm, MySQL 8, Git

---

## Instalasi

### 1. Clone repositori

```bash
git clone https://github.com/Prazaa/Projek-Web-Lanjut.git simka
cd simka
```

### 2. Pasang dependensi PHP

```bash
composer install
```

### 3. Siapkan environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Siapkan database

**Opsi A — MySQL lokal**

```sql
CREATE DATABASE simka CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'simka'@'localhost' IDENTIFIED BY 'simka123';
GRANT ALL PRIVILEGES ON simka.* TO 'simka'@'localhost';
FLUSH PRIVILEGES;
```

**Opsi B — Docker (disarankan)**

> Kredensial di bawah hanya contoh untuk pengembangan lokal. Ganti sebelum dipakai di server mana pun dan jangan ekspos port database ke internet.

```bash
docker run -d --name simka-mysql \
  -e MYSQL_ROOT_PASSWORD=root123 \
  -e MYSQL_DATABASE=simka \
  -e MYSQL_USER=simka \
  -e MYSQL_PASSWORD=simka123 \
  -p 127.0.0.1:3306:3306 \
  -v simka-mysql-data:/var/lib/mysql \
  mysql:8.0
```

### 5. Sesuaikan `.env`

```dotenv
APP_NAME=SIMKA
APP_URL=http://127.0.0.1:8001

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=simka
DB_USERNAME=simka
DB_PASSWORD=simka123
```

### 6. Migrasi dan seed data awal

```bash
php artisan migrate --seed
```

Untuk mereset seluruh data (menghapus semua tabel lalu membuat ulang):

```bash
php artisan migrate:fresh --seed
```

### 7. Pasang dependensi frontend dan build aset

```bash
npm install
npm run build
```

### 8. Jalankan aplikasi

```bash
php artisan serve --port=8001
```

Buka **http://127.0.0.1:8001** di peramban. Untuk mode pengembangan dengan hot reload, jalankan `npm run dev` di terminal terpisah.

### Masalah umum

| Gejala | Solusi |
|---|---|
| `could not find driver` | aktifkan ekstensi `pdo_mysql` |
| `Connection refused` | nyalakan MySQL atau jalankan `docker start simka-mysql` |
| `Vite manifest not found` | jalankan `npm install && npm run build` |
| `419 Page Expired` | pastikan `APP_KEY` terisi, lalu muat ulang halaman |
| Port 3306 sudah dipakai | ubah mapping Docker ke `-p 127.0.0.1:3307:3306` dan set `DB_PORT=3307` |

---

## Akun Demo

Dibuat otomatis oleh `PenggunaSeeder`:

| Peran | Email | Kata sandi |
|---|---|---|
| Staf Akademik | `staf.akademik@unri.ac.id` | `password123` |
| Pimpinan | `pimpinan.fmipa@unri.ac.id` | `password123` |
| Mahasiswa | `budi.santoso@student.unri.ac.id` | `password123` |


## Login Google (Opsional)

1. Buat **OAuth client ID** bertipe *Web application* di [Google Cloud Console](https://console.cloud.google.com/).
2. Tambahkan **Authorized redirect URI**: `http://127.0.0.1:8001/auth/google/callback` (sesuaikan dengan `APP_URL`).
3. Isi `.env`:
   ```dotenv
   GOOGLE_CLIENT_ID=isi-client-id
   GOOGLE_CLIENT_SECRET=isi-client-secret
   GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
   ```
4. Tombol **Masuk dengan Google** tersedia di halaman login.

> Pastikan redirect URI sama persis dengan `APP_URL` agar tidak muncul `redirect_uri_mismatch`.

## Pengujian

```bash
php artisan test
```

## Keamanan

- Kata sandi di-hash dengan bcrypt dan tidak pernah diserialisasi.
- Otorisasi berlapis: middleware `role:...` pada route group + pemeriksaan kepemilikan data di controller.
- Lampiran disimpan pada disk privat; unduhan melewati controller yang memeriksa hak akses.
- Nomor surat aman dari duplikasi melalui `DB::transaction` dan `lockForUpdate`.
- `.env` tidak di-commit; hanya `.env.example` yang tersedia di repositori.

## Tim

| Mhd. Irsal | [@mhdirrsl23](https://github.com/mhdirrsl23) |
| Maulana Praza | [@Prazaa](https://github.com/Prazaa) |
| Rifqi Fajrian | [@rifqifajrian](https://github.com/rifqifajrian) |
| Muhammad Dafa Ariady | [@muhammaddafaariady](https://github.com/muhammaddafaariady) |
| Yusuf Albarr Fahrozi | [@yusufalbarr](https://github.com/yusufalbarr) |
| Grahadi Arma Winata | [@grahadiarmawinata-cyber](https://github.com/grahadiarmawinata-cyber) |
