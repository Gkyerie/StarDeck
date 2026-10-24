# StarDeck

**Sistem Peminjaman Board Game & Console untuk Astronot Stasiun Luar Angkasa**

## Identitas

| Item | Isi |
|---|---|
| Nama Kelompok | StarDeck |
| Nama Project | StarDeck: Sistem Peminjaman Board Game & Console untuk Astronot Stasiun Luar Angkasa |
| Anggota | 1. Muhammad Galih Ilham 2. Jasmine Adinda Wibowo 3. Artadina Septiani  |

## Deskripsi Singkat

StarDeck adalah aplikasi web untuk mengelola peminjaman board game dan console hiburan di stasiun luar angkasa. Sistem membatasi peminjaman maksimal 2 unit per anggota dan maksimal 5 hari, menghitung denda keterlambatan otomatis (Rp 5.000 per hari), serta menyimpan riwayat peminjaman yang bisa dicetak.

## Hak Akses

| Peran | Istilah di aplikasi | Kemampuan |
|---|---|---|
| Admin | Komandan Stasiun | Kelola unit, kategori, anggota; catat peminjaman; proses pengembalian; lihat daftar pinjaman; lihat dan cetak riwayat |
| User (Anggota) | Astronot | Lihat dan cari unit, ubah profil sendiri, lihat pinjaman sendiri |

## List Fitur

**Umum**
- Login dan logout (semua halaman wajib login)
- Pencarian unit berdasarkan nama unit

**Anggota (Astronot)**
- Lihat katalog unit beserta kategori dan status
- Lihat dan ubah profil sendiri (satu user satu profil)
- Lihat daftar unit yang sedang dipinjam sendiri

**Admin (Komandan Stasiun)**
- CRUD unit (satu unit bisa punya banyak kategori; nama boleh sama, kode unit unik)
- CRUD kategori
- CRUD user/anggota beserta profil
- Input peminjaman (validasi maksimal 2 unit aktif per anggota)
- Proses pengembalian dan hitung denda otomatis
- Lihat daftar semua unit yang sedang dipinjam
- Lihat dan cetak riwayat peminjaman per anggota

## Aturan Bisnis Utama

- Maksimal 2 unit aktif per anggota
- Lama pinjam maksimal 5 hari, jatuh tempo = tanggal pinjam + 5 hari
- Denda = hari telat x Rp 5.000
- Hanya admin yang dapat mencatat peminjaman dan memproses pengembalian
- Kode unit harus unik

## Teknologi

- Framework: Laravel (MVC Architecture)
- Database: MySQL 8.x (InnoDB Engine)
- Frontend: Blade Template + Bootstrap CSS
- Version Control: GitLab Repository

## Cara Instalasi

```bash
git clone <url-repository>
cd <nama-folder>
composer install
cp .env.example .env
php artisan key:generate
# atur koneksi database di file .env
php artisan migrate --seed
php artisan serve
```

Buka `http://localhost:8000`.

## Akun Demo

| Peran | Email | Password |
|---|---|---|
| Admin | admin@stardeck.local | password |
| Anggota | aria@stardeck.local | password |
| Anggota | budi@stardeck.local | password |
| Anggota | citra@stardeck.local | password |
| Anggota | dian@stardeck.local | password |
| Anggota | eko@stardeck.local | password |

## Dokumentasi Terkait

BRD, Flow Chart/ERD, PRD, hasil UAT, dan manual book ada di folder `docs/`.
