# **StarDeck – Business Requirements Document (BRD)**

**Sistem Peminjaman Board Game & Console untuk Astronot Stasiun Luar Angkasa**

Kelompok 10:  
1\. Muhammad Galih  
2\. Jasmine Adinda Wibowo  
3\. Artadina Septiani

## **1\. Ringkasan Eksekutif**

StarDeck adalah aplikasi web untuk mengelola inventaris unit hiburan (board game dan console) di stasiun luar angkasa, mengatur peminjaman secara adil, menghitung denda keterlambatan, dan menyimpan riwayat peminjaman. Dengan StarDeck, Komandan Stasiun (Admin) dapat mengetahui secara pasti siapa memegang unit apa, sedangkan Astronot (anggota) mendapat akses yang adil terhadap hiburan selama misi berbulan-bulan.

## **2\. Latar Belakang**

Astronot di stasiun luar angkasa menjalani misi berbulan-bulan dalam kondisi terisolasi. Hiburan kru penting untuk menjaga kesehatan mental dan kekompakan tim. Saat ini peminjaman board game dan console dilakukan secara manual sehingga sering terjadi:

* Tidak jelas siapa yang sedang memegang unit tertentu.  
* Satu kru menimbun banyak unit sehingga kru lain tidak kebagian.  
* Unit terlambat dikembalikan tanpa catatan dan tanpa konsekuensi.  
* Ruang penyimpanan stasiun terbatas sehingga stok harus dikelola ketat.

## **3\. Tujuan Bisnis**

1. Mencatat inventaris unit hiburan secara terpusat dan akurat.  
2. Mengatur peminjaman yang adil: **maksimal 2 unit** per anggota dan **maksimal 5 hari** per peminjaman.  
3. Menghitung denda keterlambatan secara otomatis (**Rp 5.000 per hari** keterlambatan).  
4. Menyimpan riwayat peminjaman yang dapat dilihat dan dicetak oleh Admin.  
5. Memberi kejelasan status setiap unit (tersedia atau sedang dipinjam) kepada seluruh kru.

### **Alasan Batasan Bisnis**

* **Maksimal 2 unit:** ruang penyimpanan modul kru terbatas.  
* **Maksimal 5 hari:** unit harus bergilir antar kru dalam satu siklus misi.

## **4\. Ruang Lingkup**

### **4.1 Dalam Lingkup**

* Login dan logout untuk Admin dan Anggota.  
* Pencarian unit berdasarkan nama unit.  
* Katalog unit beserta status ketersediaannya.  
* Pengelolaan data unit, kategori, dan anggota (CRUD) oleh Admin.  
* Pencatatan peminjaman oleh Admin dengan validasi batas 2 unit.  
* Pemrosesan pengembalian dan perhitungan denda otomatis.  
* Daftar unit yang sedang dipinjam (Admin: semua; Anggota: miliknya sendiri).  
* Riwayat peminjaman per anggota beserta fitur cetak.  
* Profil anggota yang dapat diubah oleh anggota sendiri.

### **4.2 Di Luar Lingkup (Versi Ini)**

* Pembayaran denda online.  
* Notifikasi email/SMS.  
* Registrasi mandiri oleh anggota.  
* Reservasi unit.  
* Upload banyak foto unit.

## **5\. Pemangku Kepentingan dan Aktor**

| Aktor | Istilah Tematik (UI) | Peran |
| :---- | :---- | :---- |
| Admin | Komandan Stasiun | Mengelola data, mencatat peminjaman, memproses pengembalian, melihat dan mencetak riwayat |
| User / Anggota (member) | Astronot | Kru yang meminjam unit; melihat katalog, profil, dan pinjaman sendiri |

## **6\. Istilah dan Glosarium**

Istilah tematik hanya dipakai pada tampilan (UI) dan sebagai gaya bahasa di BRD/PRD. Pada ERD dan database wajib memakai istilah baku.

| Istilah Baku (DB/ERD) | Istilah Tematik (UI) | Keterangan |
| :---- | :---- | :---- |
| Admin | Komandan Stasiun | Mengelola data dan pengembalian |
| User / Anggota (member) | Astronot | Kru yang meminjam unit |
| Unit | Unit Hiburan | Satu fisik barang (board game atau console) |
| Kode Unit | Kode Inventaris | Unik per unit fisik, format SD-\<BG\|CN\>-\<3 digit\> |
| Kategori | Genre | Satu unit dapat memiliki banyak kategori |
| Peminjaman (loan) | Misi Pinjam | Transaksi pinjam |
| Denda (fine) | Denda Orbit | Akibat keterlambatan lebih dari 5 hari |

## **7\. Aturan Bisnis (Business Rules)**

| Kode | Aturan Bisnis | No Soal |
| :---- | :---- | :---- |
| BR-01 | Ada 2 peran: Admin dan User (anggota) | 2 |
| BR-02 | Semua pengguna wajib login untuk mengakses web | 3 |
| BR-03 | Hanya user yang terdaftar sebagai anggota yang dapat meminjam | 4 |
| BR-04 | Satu user hanya memiliki satu profil (relasi 1:1) | 5 |
| BR-05 | Setiap user dapat mengubah profilnya sendiri | 6 |
| BR-06 | Satu unit dapat memiliki banyak kategori (many-to-many) | 7 |
| BR-07 | Nama unit boleh sama untuk banyak unit fisik, dibedakan oleh kode unit | 8 |
| BR-08 | Kode unit harus unik | 8a |
| BR-09 | Pencarian unit berdasarkan nama unit | 9 |
| BR-10 | Admin dapat tambah, ubah, hapus data unit, kategori, dan user | 10 |
| BR-11 | Satu anggota maksimal meminjam 2 unit pada waktu yang sama | 11 |
| BR-12 | Lama pinjam maksimal 5 hari, lebih dari itu dikenakan denda | 12 |
| BR-13 | Hanya Admin yang dapat memproses pengembalian; anggota menghubungi admin | 13 |
| BR-14 | Admin dapat melihat daftar semua unit yang sedang dipinjam | 14 |
| BR-15 | Anggota hanya melihat daftar pinjaman miliknya sendiri | 15 |
| BR-16 | Admin dapat melihat dan mencetak riwayat peminjaman anggota | 16 |
| BR-17 | Validasi pada setiap field yang diperlukan (required, unique, format, dll.) | 17 |

## **8\. Keputusan Tim (Penjelasan Aturan Bisnis)**

Hal-hal yang tidak ditentukan secara eksplisit pada soal telah diputuskan bersama agar semua dokumen konsisten. Perubahan hanya dilakukan lewat kesepakatan seluruh tim.

1. **Tidak ada registrasi publik.** Akun anggota dibuat oleh Admin; anggota tidak dapat mendaftar sendiri.  
2. **Satu baris peminjaman \= satu unit.** Peminjaman 2 unit sekaligus tercatat sebagai 2 baris pada tabel loans. Batas maksimal 2 dihitung dari jumlah baris loans berstatus borrowed milik anggota tersebut.  
3. **Tanggal jatuh tempo** \= tanggal pinjam \+ 5 hari.  
4. **Denda** \= Rp 5.000 per hari keterlambatan, dihitung saat Admin memproses pengembalian: max(0, hari\_telat) × tarif\_denda.  
5. **Status unit:** available atau borrowed. Unit berstatus borrowed tidak dapat dipinjam lagi.  
6. **Penghapusan:** unit atau anggota yang masih memiliki pinjaman aktif tidak boleh dihapus.  
7. **Peminjaman dicatat oleh Admin.** Anggota datang ke Admin, lalu Admin menginput peminjaman, sehingga alur pinjam dan kembali sama-sama dikendalikan Admin. Anggota tetap dapat melihat daftar unit dan pinjamannya sendiri.

## **9\. Kebutuhan Bisnis**

| ID | Kebutuhan Bisnis | Aturan Terkait |
| :---- | :---- | :---- |
| BRQ-01 | Sistem harus membatasi akses hanya untuk pengguna yang sudah login dan membedakan hak akses Admin dan Anggota. | BR-01, BR-02, BR-03 |
| BRQ-02 | Sistem harus menyimpan data anggota beserta satu profil per anggota, dan anggota dapat memperbarui profilnya sendiri. | BR-04, BR-05 |
| BRQ-03 | Sistem harus mengelola inventaris unit dengan kode unik, mendukung nama ganda, dan banyak kategori per unit. | BR-06, BR-07, BR-08 |
| BRQ-04 | Sistem harus menyediakan pencarian unit berdasarkan nama. | BR-09 |
| BRQ-05 | Admin harus dapat mengelola data unit, kategori, dan anggota. | BR-10 |
| BRQ-06 | Sistem harus mencegah anggota meminjam lebih dari 2 unit aktif dan mencegah peminjaman unit yang sedang dipinjam. | BR-11 |
| BRQ-07 | Sistem harus menetapkan jatuh tempo 5 hari dan menghitung denda otomatis atas keterlambatan. | BR-12 |
| BRQ-08 | Hanya Admin yang dapat memproses pengembalian. | BR-13 |
| BRQ-09 | Admin harus dapat memantau semua unit yang sedang dipinjam; anggota hanya melihat pinjaman miliknya. | BR-14, BR-15 |
| BRQ-10 | Admin harus dapat melihat dan mencetak riwayat peminjaman per anggota. | BR-16 |
| BRQ-11 | Sistem harus memvalidasi seluruh input (wajib diisi, unik, format). | BR-17 |

## **10\. Proses Bisnis**

### **10.1 Kondisi Saat Ini (As-Is)**

Peminjaman dicatat manual tanpa pencatatan terpusat. Tidak ada batas jumlah pinjaman, tidak ada pelacakan jatuh tempo, dan tidak ada konsekuensi keterlambatan.

### **10.2 Kondisi yang Diusulkan (To-Be)**

1. **Login:** pengguna login, sistem memeriksa peran, lalu mengarahkan ke dashboard Admin atau Anggota.  
2. **Peminjaman:** Admin memilih anggota dan unit. Sistem memeriksa bahwa unit available dan pinjaman aktif anggota kurang dari 2\. Jika valid, sistem menyimpan loan dengan due\_date \= hari ini \+ 5 hari, dan unit berubah menjadi borrowed.  
3. **Pengembalian:** Admin memilih loan aktif. Sistem menghitung hari telat dan denda, loan menjadi returned, dan unit kembali available.  
4. **Riwayat:** Admin memilih anggota, sistem menampilkan riwayat, lalu Admin dapat mencetaknya.

Diagram alur proses disajikan secara terpisah dalam dokumen Flowchart StarDeck.

## **11\. Manfaat yang Diharapkan**

* Kejelasan pemegang setiap unit pada setiap waktu.  
* Pemerataan akses hiburan antar kru karena ada batas 2 unit dan 5 hari.  
* Disiplin pengembalian melalui denda otomatis.  
* Pengelolaan stok dan ruang penyimpanan yang lebih terkendali.  
* Riwayat peminjaman yang tercatat dan dapat dicetak sebagai bahan evaluasi.  
* Dukungan terhadap kesehatan mental dan kekompakan kru selama misi.

## **12\. Asumsi dan Batasan**

* **Teknologi (asumsi):** Laravel, MySQL, Blade/Bootstrap, dan Git (GitLab).  
* Tarif denda Rp 5.000 per hari adalah angka asumsi; jika diubah, harus diubah di seluruh dokumen.  
* Denda hanya dicatat dan dihitung; pembayaran dilakukan di luar sistem.  
* Anggota tidak dapat meminjam sendiri melalui aplikasi; peminjaman dilakukan melalui Admin.  
* Data seed awal: 1 akun Admin (contoh admin@stardeck.test), 3–5 anggota beserta profil, dan kategori Strategi, Party, Kooperatif, 2 Pemain, Console.

## **13\. Risiko dan Mitigasi**

| Risiko | Dampak | Mitigasi |
| :---- | :---- | :---- |
| Admin menjadi satu-satunya titik proses pinjam dan kembali | Antrean atau keterlambatan pencatatan | Antarmuka input yang ringkas; pertimbangkan lebih dari satu akun Admin pada versi berikutnya |
| Data unit tidak sesuai kondisi fisik | Status tidak akurat | Pencocokan stok berkala oleh Admin |
| Kode unit ganda atau salah format | Unit sulit dilacak | Validasi unik dan format SD-\<BG\|CN\>-\<3 digit\> |
| Penghapusan data yang masih dipakai | Riwayat hilang atau tidak konsisten | Larang hapus unit/anggota yang punya pinjaman aktif |

## **14\. Kriteria Keberhasilan**

* Seluruh aturan BR-01 sampai BR-17 berjalan sesuai spesifikasi.  
* Peminjaman ke-3 oleh anggota yang sama ditolak sistem.  
* Unit berstatus borrowed tidak dapat dipinjam kembali.  
* Denda terhitung benar pada setiap pengembalian terlambat.  
* Admin dapat mencetak riwayat peminjaman per anggota.  
* Nama project, istilah, dan angka batasan (2 unit, 5 hari, Rp 5.000) konsisten di BRD, ERD, dan PRD.

## **15\. Persetujuan**

| Nama | Peran | Tanda Tangan | Tanggal |
| :---- | :---- | :---- | :---- |
| (isi) | Anggota 1 |  |  |
| (isi) | Anggota 2 |  |  |
| (isi) | Anggota 3 |  |  |

