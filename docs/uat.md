# StarDeck — Laporan User Acceptance Test (UAT)

## Informasi Proyek

| Item | Keterangan |
| --- | --- |
| Sistem | StarDeck — Sistem Peminjaman Board Game & Console untuk Astronot Stasiun Luar Angkasa |
| Kelompok | Kelompok 10 (Muhammad Galih, Jasmine Adinda Wibowo, Artadina Septiani) |
| Acuan | BRD, PRD, ERD, dan flow aplikasi StarDeck |
| Tanggal uji | 24 Oktober 2026 |
| Penguji | Pengujian otomatis melalui Laravel Feature Tests |
| Lingkungan | SQLite in-memory; database kerja tidak di-reset atau diubah |

## Metode dan Data Uji

Seluruh skenario dijalankan sebagai permintaan HTTP ke aplikasi Laravel dalam database SQLite in-memory yang terisolasi. Database diisi ulang dengan seeder untuk setiap skenario; tanggal dibekukan untuk menguji jatuh tempo dan denda. Pengujian ini menguji perilaku endpoint, validasi, isi halaman, status data, dan otorisasi. Ini bukan sesi uji manual oleh pengguna akhir; dialog cetak browser fisik tidak dibuka.

Akun di seeder saat ini menggunakan domain `stardeck.local`, bukan `stardeck.test`:

- Admin: `admin@stardeck.local`
- Anggota A: `aria@stardeck.local`
- Anggota B: `budi@stardeck.local`
- Kata sandi akun demo: `password`

Unit Catan yang diuji adalah `SD-BG-001` dan `SD-BG-002`; pencarian juga mencakup katalog unit seeded lainnya. Seeder tidak membuat pinjaman awal, maka skenario pinjaman aktif/terlambat menyiapkan data transaksinya masing-masing di database tes.

## Hasil Uji

| No | Fitur | Hasil Aktual | Status |
| ---: | --- | --- | --- |
| 1 | Login | Admin berhasil masuk dashboard Admin; Anggota A berhasil masuk dashboard Anggota dan tidak melihat menu admin. | **LULUS** |
| 2 | Login | Password salah dan email tidak terdaftar ditolak dengan pesan umum yang sama: “Email atau password salah.” | **LULUS** |
| 3 | Akses | Tamu yang membuka `/catalog` dialihkan ke `/login`. | **LULUS** |
| 4 | Akses | Anggota A menerima HTTP 403 saat membuka halaman kategori dan form peminjaman admin. | **LULUS** |
| 5 | Logout | Logout mengarahkan kembali ke `/login`; sesi tidak lagi dapat membuka dashboard. | **LULUS** |
| 6 | Profil | Nomor telepon dan modul stasiun tersimpan setelah profil diperbarui. | **LULUS** |
| 7 | Profil | Nomor telepon berisi huruf atau kurang dari 9 digit ditolak dengan error pada field telepon. | **LULUS** |
| 8 | Katalog | Pencarian `cat` menampilkan Catan `SD-BG-001` dan `SD-BG-002`, tanpa Codenames. | **LULUS** |
| 9 | Katalog | Pencarian `zzzz` menampilkan pesan “Unit tidak ditemukan”. | **LULUS** |
| 10 | Kategori | Kategori baru tersimpan; nama yang sama ditolak dan tidak membuat duplikat. | **LULUS** |
| 11 | Kategori | Penghapusan kategori “Strategi” yang digunakan unit ditolak; kategori tetap tersimpan. | **LULUS** |
| 12 | Unit | Unit dengan kode baru dan tiga kategori berhasil tersimpan, dengan ketiga relasi kategori. | **LULUS** |
| 13 | Unit | Kode unit yang sudah dipakai ditolak; nama “Catan” dengan kode berbeda berhasil disimpan. | **LULUS** |
| 14 | Unit | Unit yang masih memiliki pinjaman aktif tidak dapat dihapus dan tetap aktif di database. | **LULUS** |
| 15 | Anggota | Anggota dan profil berhasil dibuat; pembuatan ulang dengan email yang sama ditolak. | **LULUS** |
| 16 | Anggota | Anggota dengan pinjaman aktif tidak dapat dihapus dan tetap aktif di database. | **LULUS** |
| 17 | Peminjaman | Peminjaman tercatat pada 24 Oktober 2026 dengan jatuh tempo 29 Oktober 2026; status unit berubah menjadi `borrowed`. | **LULUS** |
| 18 | Peminjaman | Peminjaman unit ketiga ditolak dengan pesan batas dua pinjaman aktif; transaksi baru tidak tersimpan. | **LULUS** |
| 19 | Peminjaman | Unit yang sudah dipinjam tidak dapat dipinjam ulang; transaksi pinjaman kedua tidak dibuat. | **LULUS** |
| 20 | Peminjaman/Pengembalian | Menu admin tidak ditampilkan kepada anggota; URL form pinjam dan proses pengembalian admin menghasilkan HTTP 403. Menu pinjaman milik sendiri tetap tersedia. | **LULUS** |
| 21 | Pengembalian | Pengembalian tepat pada tanggal jatuh tempo menghasilkan 0 hari keterlambatan dan denda Rp0; unit kembali `available`. | **LULUS** |
| 22 | Pengembalian | Untuk pinjaman tanggal 16 Oktober 2026 dengan jatuh tempo 21 Oktober, pengembalian 24 Oktober menghasilkan keterlambatan 3 hari dan denda Rp15.000. | **LULUS** |
| 23 | Daftar pinjaman | Admin melihat pinjaman Anggota A dan B; halaman “pinjaman saya” tiap anggota hanya menampilkan pinjamannya sendiri. | **LULUS** |
| 24 | Riwayat | Riwayat Anggota A menampilkan unit dan tautan laporan; halaman cetak berhasil dibuka dengan rincian riwayat dan tombol cetak. | **LULUS** |

## Ringkasan

- **Total skenario:** 24
- **Lulus:** 24
- **Gagal:** 0
- **Hasil PHPUnit:** 24 test passed, 140 assertions

## Catatan

1. Dokumen sumber menyebut alamat `admin@stardeck.test`, sedangkan `DatabaseSeeder` aplikasi membuat `admin@stardeck.local`; akun anggota seeder juga memakai `@stardeck.local`. Gunakan alamat yang benar di atas untuk pengujian manual.
2. Hasil skenario 24 memverifikasi endpoint laporan cetak, isi laporan, dan tombol cetak melalui respons HTTP. Membuka dialog print/browser dan memeriksa hasil cetak fisik tetap memerlukan uji manual browser.
3. Kriteria LULUS di laporan ini mengacu pada pengujian otomatis terisolasi, bukan pengesahan formal dari pengguna akhir.
