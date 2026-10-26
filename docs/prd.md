# **StarDeck – Product Requirements Document (PRD)**

**StarDeck: Sistem Peminjaman Board Game & Console untuk Astronot Stasiun Luar Angkasa**

## **1\. Ringkasan Produk**

StarDeck adalah aplikasi web untuk mengelola peminjaman board game dan console hiburan di stasiun luar angkasa. Aplikasi mencatat inventaris unit, membatasi peminjaman secara adil (maksimal 2 unit per anggota, maksimal 5 hari), menghitung denda keterlambatan secara otomatis, dan menyimpan riwayat peminjaman yang bisa dicetak.

### **1.1 Masalah yang Diselesaikan**

* Tidak jelas siapa yang sedang memegang unit tertentu.  
* Satu kru bisa menimbun banyak unit sehingga kru lain tidak kebagian.  
* Keterlambatan pengembalian tidak tercatat dan tidak ada konsekuensi.  
* Ruang penyimpanan stasiun terbatas sehingga stok harus terkontrol.

**Skenario masalah di lapangan:**

| ID | Skenario | Dampak | Dijawab oleh |
| :---- | :---- | :---- | :---- |
| PS-01 | Astronot A meminjam PS5 lalu lupa mengembalikan. Astronot B ingin bermain tetapi tidak tahu unit itu dipegang siapa | Waktu terbuang mencari unit, kru berselisih | FR-14, FR-34 |
| PS-02 | Satu astronot membawa 4 board game ke modulnya, kru lain tidak kebagian dan modul penuh barang | Penyimpanan sesak, pembagian tidak adil | FR-25 |
| PS-03 | Ada dua unit board game dengan judul yang sama, satu rusak ringan, dan tidak ada yang tahu unit mana dipinjam siapa | Unit fisik tidak bisa dilacak | FR-12, FR-13 |
| PS-04 | Sebuah unit terlambat dikembalikan berminggu-minggu tanpa catatan dan tanpa konsekuensi | Rotasi antar kru macet | FR-26, FR-30 |
| PS-05 | Komandan menghitung denda manual dan hasilnya sering berbeda-beda | Perselisihan, tidak konsisten | FR-30, FR-32 |
| PS-06 | Komandan butuh bukti riwayat pinjam untuk evaluasi akhir misi | Tidak ada arsip | FR-36, FR-37 |
| PS-07 | Orang yang tidak berwenang membuka atau mengubah data pinjaman | Data tidak aman | FR-02, FR-05, FR-08 |

### **1.2 Tujuan Produk**

1. Semua unit hiburan terdata dengan kode unik dan status yang lebih akurat.  
2. Peminjaman mengikuti aturan maksimal 2 unit dan 5 hari tanpa bisa dilanggar.  
3. Denda dihitung otomatis dan konsisten.  
4. Admin dapat menelusuri dan mencetak riwayat peminjaman setiap anggota.

### **1.3 Indikator Keberhasilan**

| Indikator | Target |
| :---- | :---- |
| Seluruh poin soal (1 sampai 20\) terpenuhi | 100% |
| Peminjaman ke-3 oleh anggota yang sudah meminjam 2 unit | Ditolak sistem |
| Unit berstatus dipinjam tidak bisa dipinjam lagi | Ditolak sistem |
| Perhitungan denda sesuai rumus | Akurat pada semua skenario uji |
| Akses halaman tanpa login | Selalu dialihkan ke halaman login |

**Metrik dampak setelah sistem dipakai:**

| Metrik | Target | Cara mengukur |
| :---- | :---- | :---- |
| Anggota yang memegang lebih dari 2 unit sekaligus | 0 | Cek daftar pinjaman aktif |
| Kesesuaian status unit di sistem dengan kondisi fisik | 100% | Pencocokan stok berkala oleh Admin |
| Waktu mencatat satu peminjaman | Kurang dari 1 menit | Uji coba saat demo |
| Pinjaman yang terlambat dibanding total pinjaman | Kurang dari 10% | Riwayat peminjaman |
| Pengembalian yang denda otomatisnya sesuai rumus | 100% | Uji skenario AC-10 dan AC-11 |
| Waktu menampilkan dan mencetak riwayat satu anggota | Kurang dari 10 detik | Uji coba saat demo |

## **2\. Ruang Lingkup**

### **2.1 Dalam Lingkup**

* Autentikasi (login dan logout) dengan dua peran: Admin dan Anggota.  
* Manajemen unit, kategori, dan user/anggota oleh Admin.  
* Profil anggota (satu user satu profil) yang bisa diubah sendiri.  
* Pencarian unit berdasarkan nama.  
* Peminjaman, pengembalian, dan perhitungan denda.  
* Daftar pinjaman aktif (Admin: semua; Anggota: miliknya sendiri).  
* Riwayat peminjaman yang dapat dicetak oleh Admin.  
* Migration dan seeder data awal.  
* README, skema database, dan video demo.

### **2.2 Di Luar Lingkup**

Pembayaran denda online, notifikasi email/SMS, registrasi mandiri, reservasi unit, dan aplikasi mobile native.

## **3\. Pengguna dan Peran**

| Peran | Istilah UI | Deskripsi | Hak Akses Utama |
| :---- | :---- | :---- | :---- |
| Admin | Komandan Stasiun | Pengelola sistem dan inventaris | Kelola unit, kategori, user; proses peminjaman dan pengembalian; lihat dan cetak riwayat |
| Anggota | Astronot | Kru stasiun yang terdaftar | Lihat katalog, cari unit, kelola profil sendiri, lihat pinjaman sendiri |

### **3.1 Persona**

**Komandan Stasiun (Admin).** Bertanggung jawab atas inventaris hiburan kru. Butuh data stok yang akurat, proses pinjam dan kembali yang cepat, dan laporan yang bisa dicetak.

**Astronot (Anggota).** Ingin tahu game apa yang tersedia, mengajukan pinjam ke admin, dan memantau kapan harus mengembalikan agar tidak kena denda.

## **4\. Aturan Bisnis dan Keputusan Tim**

| Kode | Aturan | No Soal |
| :---- | :---- | :---- |
| BR-01 | Ada 2 peran: Admin dan User (anggota) | 2 |
| BR-02 | Semua pengguna wajib login | 3 |
| BR-03 | Hanya user terdaftar sebagai anggota yang dapat meminjam | 4 |
| BR-04 | Satu user hanya punya satu profil | 5 |
| BR-05 | Setiap user dapat mengubah profilnya sendiri | 6 |
| BR-06 | Satu unit dapat memiliki banyak kategori | 7 |
| BR-07 | Nama unit boleh sama, dibedakan kode unit | 8 |
| BR-08 | Kode unit harus unik | 8a |
| BR-09 | Pencarian unit berdasarkan nama | 9 |
| BR-10 | Admin dapat tambah, ubah, hapus unit, kategori, user | 10 |
| BR-11 | Maksimal 2 unit dipinjam per anggota | 11 |
| BR-12 | Maksimal 5 hari, lebih dari itu kena denda | 12 |
| BR-13 | Hanya Admin yang memproses pengembalian | 13 |
| BR-14 | Admin melihat daftar unit yang dipinjam | 14 |
| BR-15 | Anggota hanya melihat pinjamannya sendiri | 15 |
| BR-16 | Admin melihat dan mencetak riwayat peminjaman anggota | 16 |
| BR-17 | Validasi pada field yang diperlukan | 17 |

**Keputusan tim yang berlaku di seluruh dokumen:**

1. Tidak ada registrasi publik; akun anggota dibuat Admin.  
2. Satu baris peminjaman mewakili satu unit.  
3. Jatuh tempo \= tanggal pinjam \+ 5 hari.  
4. Denda \= Rp 5.000 per hari keterlambatan.  
5. Status unit: available atau borrowed.  
6. Unit atau anggota yang masih punya pinjaman aktif tidak boleh dihapus.  
7. Peminjaman diinput oleh Admin.

## **5\. Kebutuhan Fungsional**

Prioritas: **High** \= wajib untuk lolos soal, **Medium** \= pendukung.

### **5.1 Autentikasi dan Akses**

| ID | Kebutuhan | Prioritas | Aturan |
| :---- | :---- | :---- | :---- |
| FR-01 | Sistem menyediakan halaman login dengan email dan password | High | BR-02 |
| FR-02 | Semua halaman selain login hanya dapat diakses setelah login | High | BR-02 |
| FR-03 | Sistem mengarahkan pengguna ke dashboard sesuai peran setelah login | High | BR-01 |
| FR-04 | Sistem menyediakan logout | High | BR-02 |
| FR-05 | Fitur Admin tidak dapat diakses Anggota, termasuk lewat URL langsung (otorisasi per peran) | High | BR-01 |

### **5.2 Manajemen Profil**

| ID | Kebutuhan | Prioritas | Aturan |
| :---- | :---- | :---- | :---- |
| FR-06 | Setiap user anggota memiliki tepat satu profil | High | BR-04 |
| FR-07 | Anggota dapat melihat dan mengubah profilnya sendiri | High | BR-05 |
| FR-08 | Anggota tidak dapat mengubah profil anggota lain | High | BR-05 |

### **5.3 Manajemen Unit (Admin)**

| ID | Kebutuhan | Prioritas | Aturan |
| :---- | :---- | :---- | :---- |
| FR-09 | Admin dapat menambah unit dengan nama, kode unit, tipe, kondisi, deskripsi, dan satu atau lebih kategori | High | BR-06, BR-10 |
| FR-10 | Admin dapat mengubah data unit dan kategorinya | High | BR-10 |
| FR-11 | Admin dapat menghapus unit yang tidak sedang dipinjam | High | BR-10 |
| FR-12 | Sistem mengizinkan beberapa unit dengan nama sama selama kode unit berbeda | High | BR-07 |
| FR-13 | Sistem menolak kode unit yang sudah digunakan | High | BR-08 |
| FR-14 | Semua pengguna dapat melihat daftar unit beserta kategori dan statusnya | High | BR-03 |
| FR-15 | Semua pengguna dapat mencari unit berdasarkan nama unit | High | BR-09 |

### **5.4 Manajemen Kategori (Admin)**

| ID | Kebutuhan | Prioritas | Aturan |
| :---- | :---- | :---- | :---- |
| FR-16 | Admin dapat menambah, mengubah, dan menghapus kategori | High | BR-10 |
| FR-17 | Nama kategori harus unik | Medium | BR-17 |
| FR-18 | Kategori yang masih dipakai unit tidak dapat dihapus, atau relasinya dilepas dengan konfirmasi (pilih satu, lalu seragamkan di tim) | Medium | BR-10 |

### **5.5 Manajemen User/Anggota (Admin)**

| ID | Kebutuhan | Prioritas | Aturan |
| :---- | :---- | :---- | :---- |
| FR-19 | Admin dapat menambah user anggota beserta profilnya | High | BR-03, BR-10 |
| FR-20 | Admin dapat mengubah data user dan profilnya | High | BR-10 |
| FR-21 | Admin dapat menghapus user yang tidak memiliki pinjaman aktif | High | BR-10 |
| FR-22 | Email user harus unik | High | BR-17 |

### **5.6 Peminjaman**

| ID | Kebutuhan | Prioritas | Aturan |
| :---- | :---- | :---- | :---- |
| FR-23 | Admin dapat mencatat peminjaman dengan memilih anggota dan unit | High | BR-03 |
| FR-24 | Sistem menolak peminjaman jika unit berstatus borrowed | High | BR-07 |
| FR-25 | Sistem menolak peminjaman jika anggota sudah memiliki 2 pinjaman aktif | High | BR-11 |
| FR-26 | Sistem mengisi tanggal pinjam otomatis dan menghitung jatuh tempo \= tanggal pinjam \+ 5 hari | High | BR-12 |
| FR-27 | Setelah peminjaman tersimpan, status unit menjadi borrowed | High | BR-07 |
| FR-28 | Hanya user dengan peran anggota yang dapat dipilih sebagai peminjam | High | BR-03 |

### **5.7 Pengembalian dan Denda**

| ID | Kebutuhan | Prioritas | Aturan |
| :---- | :---- | :---- | :---- |
| FR-29 | Hanya Admin yang dapat memproses pengembalian | High | BR-13 |
| FR-30 | Sistem mencatat tanggal kembali dan menghitung hari telat serta denda otomatis | High | BR-12 |
| FR-31 | Setelah pengembalian, status pinjaman menjadi returned dan status unit kembali available | High | BR-13 |
| FR-32 | Sistem menampilkan total denda pada saat pengembalian | High | BR-12 |
| FR-33 | Pinjaman aktif yang melewati jatuh tempo ditandai "terlambat" di daftar | Medium | BR-12 |

**Rumus denda:**

* hari\_telat \= max(0, tanggal\_kembali \- jatuh\_tempo) dalam hari  
* denda \= hari\_telat x Rp 5.000  
* Contoh: dipinjam 1 Nov, jatuh tempo 6 Nov, dikembalikan 9 Nov, maka telat 3 hari dan denda Rp 15.000. Dikembalikan 6 Nov atau lebih awal, denda Rp 0\.

### **5.8 Daftar Pinjaman dan Riwayat**

| ID | Kebutuhan | Prioritas | Aturan |
| :---- | :---- | :---- | :---- |
| FR-34 | Admin dapat melihat daftar semua unit yang sedang dipinjam beserta peminjam dan jatuh tempo | High | BR-14 |
| FR-35 | Anggota hanya dapat melihat daftar unit yang dipinjam olehnya | High | BR-15 |
| FR-36 | Admin dapat melihat riwayat peminjaman per anggota (unit, tanggal pinjam, jatuh tempo, tanggal kembali, denda) | High | BR-16 |
| FR-37 | Admin dapat mencetak riwayat peminjaman anggota (halaman cetak atau PDF) | High | BR-16 |

## **6\. User Story**

| ID | Sebagai | Saya ingin | Agar |
| :---- | :---- | :---- | :---- |
| US-01 | Pengguna | login dengan email dan password | hanya orang berwenang yang mengakses sistem |
| US-02 | Astronot | mencari game berdasarkan nama | cepat tahu apakah tersedia |
| US-03 | Astronot | melihat kategori dan status unit | memilih game sesuai selera dan jumlah pemain |
| US-04 | Astronot | mengubah profil saya | data kontak dan penempatan saya selalu benar |
| US-05 | Astronot | melihat daftar unit yang saya pinjam beserta jatuh tempo | tidak terlambat mengembalikan |
| US-06 | Komandan | menambah, mengubah, menghapus unit | inventaris selalu mutakhir |
| US-07 | Komandan | mengelola kategori | unit mudah dikelompokkan |
| US-08 | Komandan | mengelola akun dan profil astronot | hanya kru resmi yang bisa meminjam |
| US-09 | Komandan | mencatat peminjaman dengan validasi otomatis | batas 2 unit tidak terlanggar |
| US-10 | Komandan | memproses pengembalian dengan denda otomatis | tidak perlu menghitung manual |
| US-11 | Komandan | melihat semua unit yang sedang dipinjam | tahu posisi setiap unit |
| US-12 | Komandan | mencetak riwayat peminjaman astronot | punya arsip dan bukti |

## **7\. Kebutuhan Data dan Validasi**

### **7.1 Struktur Data (ringkas, rinci ada di ERD)**

| Tabel | Kolom utama | Catatan |
| :---- | :---- | :---- |
| users | id, name, email (unique), password, role (admin/member), timestamps | Login dan peran |
| member\_profiles | id, user\_id (FK, unique), phone, rank\_or\_position, station\_module, address, photo | Relasi 1:1 ke users |
| categories | id, name (unique), description | Genre unit |
| units | id, name, unit\_code (unique), type (board\_game/console), condition, status (available/borrowed), description | Nama boleh duplikat |
| category\_unit | unit\_id (FK), category\_id (FK) | Pivot many-to-many |
| loans | id, member\_id (FK), unit\_id (FK), processed\_by (FK), borrowed\_at, due\_date, returned\_at (nullable), status (borrowed/returned), late\_days, fine\_amount | Satu baris satu unit |

### **7.2 Aturan Validasi Field (BR-17)**

**Login**

| Field | Aturan |
| :---- | :---- |
| email | Wajib, format email valid |
| password | Wajib, minimal 8 karakter |

**User / Anggota**

| Field | Aturan |
| :---- | :---- |
| name | Wajib, string, maksimal 100 karakter |
| email | Wajib, format email, unik di tabel users |
| password | Wajib saat tambah (minimal 8 karakter), opsional saat ubah |
| role | Wajib, hanya admin atau member |

**Profil**

| Field | Aturan |
| :---- | :---- |
| phone | Wajib, hanya angka, 9 sampai 15 digit |
| rank\_or\_position | Wajib, maksimal 100 karakter |
| station\_module | Wajib, maksimal 100 karakter |
| address | Opsional, maksimal 255 karakter |
| photo | Opsional, gambar jpg/png, maksimal 2 MB |
| user\_id | Unik (satu user satu profil) |

**Unit**

| Field | Aturan |
| :---- | :---- |
| name | Wajib, maksimal 150 karakter |
| unit\_code | Wajib, unik, format SD-BG-001 atau SD-CN-001 |
| type | Wajib, board\_game atau console |
| condition | Wajib, misalnya baik, rusak ringan |
| categories | Wajib, minimal 1 kategori yang ada di tabel categories |
| description | Opsional |

**Kategori**

| Field | Aturan |
| :---- | :---- |
| name | Wajib, unik, maksimal 50 karakter |
| description | Opsional |

**Peminjaman**

| Field | Aturan |
| :---- | :---- |
| member\_id | Wajib, user ada dan berperan anggota |
| unit\_id | Wajib, unit ada dan berstatus available |
| (aturan bisnis) | Anggota belum memiliki 2 pinjaman aktif |

**Pengembalian**

| Field | Aturan |
| :---- | :---- |
| loan\_id | Wajib, pinjaman ada dan berstatus borrowed |
| returned\_at | Otomatis hari ini, tidak boleh lebih awal dari tanggal pinjam |

Pesan error ditampilkan dalam bahasa Indonesia di dekat field yang bermasalah.

## **8\. Daftar Halaman (Sitemap)**

| Halaman | Admin | Anggota |
| :---- | :---- | :---- |
| Login | Ya | Ya |
| Dashboard | Ya (ringkasan stok dan pinjaman aktif) | Ya (ringkasan pinjaman saya) |
| Katalog dan pencarian unit | Ya | Ya |
| Profil saya | Tidak perlu | Ya |
| Pinjaman saya | Tidak | Ya |
| Kelola unit (daftar, tambah, ubah, hapus) | Ya | Tidak |
| Kelola kategori | Ya | Tidak |
| Kelola user/anggota dan profil | Ya | Tidak |
| Input peminjaman | Ya | Tidak |
| Daftar unit yang dipinjam / proses pengembalian | Ya | Tidak |
| Riwayat peminjaman per anggota dan cetak | Ya | Tidak |

## **9\. Alur Utama**

1. **Login:** pengguna isi email dan password, sistem cek peran, lalu arahkan ke dashboard yang sesuai.  
2. **Peminjaman:** Admin pilih anggota dan unit, sistem cek unit tersedia dan pinjaman aktif anggota kurang dari 2, simpan loan dengan jatuh tempo \+5 hari, status unit menjadi borrowed.  
3. **Pengembalian:** Admin pilih pinjaman aktif, sistem hitung hari telat dan denda, status pinjaman menjadi returned, status unit kembali available.  
4. **Riwayat:** Admin pilih anggota, sistem tampilkan riwayat, Admin cetak.

### **Kasus Pengecualian (Edge Cases)**

Kasus berikut harus ditangani sistem dan diuji saat UAT.

| ID | Kasus | Penanganan sistem | Terkait |
| :---- | :---- | :---- | :---- |
| EC-01 | Anggota yang sudah memegang 2 unit mengajukan pinjaman ke-3 | Ditolak, tampil pesan batas maksimal 2 unit | FR-25, AC-07 |
| EC-02 | Admin memilih unit yang berstatus dipinjam | Ditolak; unit dipinjam tidak muncul di pilihan peminjaman | FR-24, AC-08 |
| EC-03 | Dua pencatatan untuk unit yang sama masuk hampir bersamaan | Proses dalam satu transaksi database, yang kedua ditolak | NFR-05 |
| EC-04 | Pengembalian setelah jatuh tempo | Hari telat dan denda dihitung otomatis dan ditampilkan | FR-30, AC-11 |
| EC-05 | Pengembalian tepat di hari ke-5 atau lebih awal | Denda Rp 0 | FR-30, AC-10 |
| EC-06 | Admin menekan proses pengembalian dua kali untuk pinjaman yang sama | Ditolak karena status sudah returned, denda tidak berubah | FR-31 |
| EC-07 | Admin menghapus unit yang sedang dipinjam | Ditolak dengan pesan unit masih dipinjam | FR-11, AC-15 |
| EC-08 | Admin menghapus anggota yang masih punya pinjaman aktif | Ditolak dengan pesan masih ada pinjaman aktif | FR-21, AC-15 |
| EC-09 | Admin menghapus kategori yang masih dipakai unit | Sesuai keputusan tim pada FR-18 | FR-18 |
| EC-10 | Admin menambah unit dengan kode unit yang sudah ada | Ditolak, tampil pesan kode sudah dipakai | FR-13, AC-03 |
| EC-11 | Login dengan email atau password salah | Ditolak dengan pesan umum tanpa menyebut mana yang salah | FR-01 |
| EC-12 | Anggota membuka URL halaman Admin secara langsung | Akses ditolak (otorisasi di sisi server) | FR-05, AC-02 |
| EC-13 | Anggota mencoba melihat atau mengubah profil anggota lain | Ditolak | FR-08, AC-13 |
| EC-14 | Pencarian unit tidak menemukan hasil | Tampil pesan unit tidak ditemukan | FR-15 |
| EC-15 | Admin membuat user anggota tanpa melengkapi profil | Ditolak, profil wajib diisi (satu user satu profil) | FR-06, FR-19 |
| EC-16 | Anggota yang masih punya pinjaman terlambat mengajukan pinjaman baru | Tidak diblokir, karena soal tidak mengatur; tim boleh menambah aturan ini jika disepakati | Asumsi |

## **10\. Kebutuhan Non-Fungsional**

| ID | Kategori | Kebutuhan |
| :---- | :---- | :---- |
| NFR-01 | Keamanan | Password disimpan terenkripsi (hash), bukan teks asli |
| NFR-02 | Keamanan | Perlindungan CSRF pada semua form dan otorisasi berbasis peran di sisi server |
| NFR-03 | Keamanan | Pengguna anggota tidak dapat mengakses data anggota lain lewat manipulasi URL |
| NFR-04 | Integritas data | Foreign key dan constraint unik di database untuk kode unit, email, dan user\_id profil |
| NFR-05 | Integritas data | Proses pinjam dan kembali berjalan dalam satu transaksi database |
| NFR-06 | Kegunaan | Antarmuka responsif (nyaman di desktop dan HP) dengan tema luar angkasa dan istilah tematik |
| NFR-07 | Kinerja | Halaman utama dan pencarian tampil kurang dari 3 detik pada data demo |
| NFR-08 | Pemeliharaan | Kode mengikuti struktur MVC Laravel dan terdokumentasi di README |
| NFR-09 | Kompatibilitas | Berjalan di browser modern (Chrome, Edge, Firefox) |

## **11\. Data Seed Awal**

| Data | Isi |
| :---- | :---- |
| Admin | 1 akun (contoh: admin@stardeck.test) |
| Anggota | 3 sampai 5 astronot beserta profil |
| Kategori | Strategi, Party, Kooperatif, 2 Pemain, Console |
| Unit | Catan (SD-BG-001, SD-BG-002), Codenames (SD-BG-003), Pandemic (SD-BG-004), PS5 (SD-CN-001, SD-CN-002), Nintendo Switch (SD-CN-003); masing-masing dengan 1 atau lebih kategori |
| Pinjaman contoh (opsional) | 1 atau 2 pinjaman aktif dan 1 yang terlambat, untuk demo denda |

## **12\. Kriteria Penerimaan (Acceptance Criteria)**

| ID | Skenario | Hasil yang diharapkan |
| :---- | :---- | :---- |
| AC-01 | Buka halaman apa pun tanpa login | Dialihkan ke login |
| AC-02 | Login sebagai anggota lalu buka URL halaman admin | Akses ditolak |
| AC-03 | Admin menambah unit dengan kode yang sudah ada | Ditolak dengan pesan kode sudah dipakai |
| AC-04 | Admin menambah dua unit bernama sama dengan kode berbeda | Berhasil |
| AC-05 | Admin menambah unit dengan 3 kategori | Ketiganya tersimpan dan tampil |
| AC-06 | Cari "cat" di katalog | Muncul unit yang namanya mengandung "cat" |
| AC-07 | Anggota meminjam 2 unit, lalu ke-3 diajukan | Ditolak dengan pesan batas 2 unit |
| AC-08 | Pinjam unit yang berstatus dipinjam | Ditolak |
| AC-09 | Pinjam unit berhasil | Jatuh tempo \= tanggal pinjam \+ 5 hari, status unit borrowed |
| AC-10 | Kembalikan unit pada hari ke-5 | Denda Rp 0 |
| AC-11 | Kembalikan unit pada hari ke-8 | Telat 3 hari, denda Rp 15.000 |
| AC-12 | Anggota membuka halaman pinjaman | Hanya pinjaman miliknya yang tampil |
| AC-13 | Anggota mencoba mengakses profil anggota lain | Ditolak |
| AC-14 | Admin membuka riwayat anggota lalu cetak | Riwayat lengkap tampil dan dapat dicetak |
| AC-15 | Hapus unit atau anggota yang masih punya pinjaman aktif | Ditolak |
| AC-16 | Kirim form dengan field wajib kosong | Pesan validasi tampil di field terkait |

## **13\. Asumsi dan Ketergantungan**

* Teknologi: Laravel, MySQL, Blade dan Bootstrap, dikelola di GitLab.  
* Pengembalian dan peminjaman selalu lewat Admin karena anggota menghubungi admin secara langsung.  
* Tarif denda Rp 5.000 per hari adalah asumsi tim dan dapat diubah selama konsisten di semua dokumen.  
* Satu orang hanya satu akun, dan akun anggota dibuat oleh Admin.

### **Ketergantungan Antar Fitur dan Urutan Pengerjaan**

Urutan di bawah menjadi acuan agar pengerjaan paralel oleh 3 orang tidak saling menunggu. Fitur di baris bawah bergantung pada fitur yang disebut di kolom ketiga.

| Urutan | Komponen | Bergantung pada | Alasan |
| :---- | :---- | :---- | :---- |
| 1 | Migration dan seeder | Tidak ada | Semua fitur butuh tabel dan data awal, termasuk akun admin |
| 2 | Autentikasi dan otorisasi peran | Urutan 1 (tabel users, seeder admin) | Semua halaman wajib login dan dibatasi per peran |
| 3 | CRUD kategori | Urutan 2 | Kategori harus ada sebelum unit dibuat |
| 3 | CRUD user dan profil | Urutan 2 | Anggota harus ada sebelum bisa meminjam |
| 4 | CRUD unit dan pencarian | Urutan 3 (kategori) | Unit wajib memiliki minimal 1 kategori |
| 5 | Peminjaman | Urutan 3 (user), 4 (unit) | Peminjaman butuh anggota dan unit yang tersedia |
| 6 | Pengembalian dan denda | Urutan 5 | Hanya pinjaman aktif yang bisa dikembalikan |
| 6 | Daftar pinjaman Admin dan Anggota | Urutan 5 | Menampilkan data peminjaman |
| 7 | Riwayat dan cetak | Urutan 5 dan 6 | Riwayat memuat pinjaman yang sudah dikembalikan beserta denda |
| 8 | UAT, manual book, dokumentasi | Semua fitur selesai | Pengujian dan dokumentasi memakai fitur yang sudah jadi |

**Ketergantungan teknis dan eksternal:**

* Laravel dan Composer untuk backend, MySQL untuk database.  
* Bootstrap (atau CSS sejenis) untuk tampilan responsif.  
* Fitur cetak riwayat memakai halaman cetak browser atau library PDF; pilih satu dan seragamkan di tim.  
* GitLab untuk penyimpanan source code dan hasil project.  
* Dokumen BRD dan ERD sebagai rujukan: nama tabel dan kolom di PRD harus sama dengan ERD.

## **14\. Rencana Pengerjaan (2 Hari)**

| Tahap | Keluaran | Waktu |
| :---- | :---- | :---- |
| Hari 1 | Nama Project, BRD, Flow Chart / ERD, PRD dikumpulkan | Hari ini |
| Hari 2 pagi | Migration, seeder, autentikasi, CRUD unit, kategori, user | Hari berikutnya |
| Hari 2 siang | Peminjaman, pengembalian, denda, daftar pinjaman, riwayat dan cetak | Hari berikutnya |
| Hari 2 sore | Validasi, pengujian (UAT), README, skema DB, video demo, push ke GitLab | Hari berikutnya |

## **15\. Deliverable Akhir**

* Source code di GitLab  
* README: nama kelompok, nama tim, nama project, daftar fitur, gambar skema database, video demo  
* Migration dan seeder  
* Dokumen: BRD, Flow Chart / ERD, PRD, hasil UAT, manual book, dokumentasi project

## **16\. Matriks Keterlacakan (Soal ke Kebutuhan)**

| No Soal | Kebutuhan terkait |
| :---- | :---- |
| 1 | Deliverable README (bagian 15\) |
| 2 | FR-01 sampai FR-05 |
| 3 | FR-01, FR-02 |
| 4 | FR-19, FR-23, FR-28 |
| 5 | FR-06 |
| 6 | FR-07, FR-08 |
| 7 | FR-09, FR-10 |
| 8 | FR-12, FR-13 |
| 9 | FR-15 |
| 10 | FR-09 sampai FR-11, FR-16, FR-19 sampai FR-21 |
| 11 | FR-25 |
| 12 | FR-26, FR-30, FR-32 |
| 13 | FR-29, FR-31 |
| 14 | FR-34 |
| 15 | FR-35 |
| 16 | FR-36, FR-37 |
| 17 | Bagian 7.2 |
| 18 | Bagian 7.1 (migration) |
| 19 | Bagian 11 (seeder) |
| 20 | Bagian 14 dan 15 (alur dan deliverable) |

