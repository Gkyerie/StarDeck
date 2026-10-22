# StarDeck — Business Requirements Document

Versi: 1.0  
Status: acuan kebutuhan bisnis untuk implementasi; bukan laporan hasil pengujian.  
Dokumen terkait: `prd.md` dan `erd.md`.  
Dasar penyusunan: kebutuhan StarDeck pada PRD(1), keputusan ERD, dan arahan implementasi proyek Laravel yang sudah berjalan.

## 1. Gambaran bisnis

StarDeck adalah aplikasi web pengelolaan peminjaman board game dan console dengan konteks stasiun luar angkasa. Pengelola disebut Komandan Stasiun (admin) dan anggota disebut Astronot (member). Setiap unit mewakili satu barang fisik, termasuk ketika beberapa barang memiliki nama yang sama.

Sistem membantu pengelola mengetahui ketersediaan dan pemegang unit, membagi akses hiburan secara adil, mencatat pengembalian, menghitung denda, serta menyediakan riwayat sebagai bukti operasional. Tema luar angkasa merupakan konteks produk dan antarmuka; aplikasi tidak membutuhkan integrasi perangkat atau sistem penerbangan antariksa.

## 2. Permasalahan dan manfaat

| ID | Permasalahan | Dampak | Manfaat yang diharapkan |
|---|---|---|---|
| PS-01 | Pemegang unit tidak diketahui | Waktu pencarian meningkat dan timbul perselisihan | Pinjaman aktif menghubungkan unit dengan anggota |
| PS-02 | Anggota memegang terlalu banyak unit | Akses anggota lain berkurang | Batas dua unit aktif berlaku konsisten |
| PS-03 | Barang bernama sama sulit dibedakan | Kondisi dan riwayat barang tertukar | Setiap barang memiliki kode unik |
| PS-04 | Pengembalian terlambat tidak tercatat | Rotasi pemakaian terhambat | Jatuh tempo dan penanda terlambat terlihat |
| PS-05 | Denda dihitung manual | Kesalahan hitung dan perbedaan penagihan | Denda dihitung dengan rumus yang sama |
| PS-06 | Riwayat sulit dihimpun | Bukti evaluasi tidak tersedia | Riwayat anggota dapat ditampilkan dan dicetak |
| PS-07 | Akses tidak dibatasi sesuai tugas | Perubahan tidak berwenang dan kebocoran data | Login dan otorisasi peran serta kepemilikan data |

## 3. Tujuan bisnis

1. Seluruh unit dapat diidentifikasi dan ditelusuri menggunakan kode unik.
2. Ketersediaan unit mencerminkan transaksi peminjaman dan pengembalian yang tercatat.
3. Setiap anggota memiliki kesempatan meminjam dengan batas yang seragam.
4. Jatuh tempo dan denda dapat dijelaskan serta dihitung secara konsisten.
5. Pengelola dapat menampilkan dan mencetak riwayat tanpa menyusun ulang data secara manual.
6. Anggota dapat melihat informasi yang relevan tanpa mengakses data pribadi anggota lain.

## 4. Pemangku kepentingan

| Pihak | Tanggung jawab dan kebutuhan |
|---|---|
| Admin / Komandan Stasiun | Mengelola inventaris, kategori, akun/profil anggota, mencatat pinjaman dan pengembalian, melihat denda dan mencetak riwayat |
| Member / Astronot | Melihat katalog dan ketersediaan, mencari unit, memperbarui profil sendiri, memantau pinjaman sendiri |
| Pemilik proyek | Menyediakan desain Figma, menetapkan kebutuhan dan menilai kesesuaian hasil melalui UAT |
| Pengembang / penguji | Menerapkan PRD dan ERD, menjaga data proyek yang ada, menguji aturan bisnis serta mendokumentasikan hasil |

Nama tim, anggota kelompok dan penanggung jawab penerimaan belum diberikan. Jangan membuat identitas atau persetujuan fiktif.

## 5. Ruang lingkup

### Termasuk

- Login dan logout, dua peran, pembatasan akses sesuai peran.
- Akun anggota dibuat admin beserta profil wajib; anggota dapat mengubah profilnya sendiri.
- Pengelolaan unit fisik dan kategori, termasuk satu unit dengan beberapa kategori.
- Katalog dan pencarian berdasarkan nama unit.
- Pencatatan peminjaman dan pengembalian oleh admin.
- Pembatasan jumlah pinjaman, penetapan jatuh tempo dan perhitungan denda otomatis.
- Daftar pinjaman aktif, riwayat per anggota dan cetak oleh admin.
- Penghapusan logis anggota/unit dengan riwayat tetap tersimpan.
- Migration, data contoh lokal, dokumentasi penggunaan dan bukti pengujian.

### Tidak termasuk

Registrasi mandiri, reservasi, perpanjangan pinjaman, pembayaran atau pelunasan denda, notifikasi email/SMS, aplikasi mobile native, banyak foto unit, pelacakan lokasi fisik otomatis, dan integrasi eksternal. Penambahan fitur di luar cakupan harus menjadi perubahan kebutuhan yang dinyatakan secara eksplisit.

## 6. Kebutuhan bisnis dan keterlacakan

Kode BREQ di bawah digunakan untuk kebutuhan tingkat bisnis agar tidak tertukar dengan kode aturan BR pada PRD sumber.

| ID | Kebutuhan bisnis | Rujukan PRD | Data utama |
|---|---|---|---|
| BREQ-01 | Hanya pengguna terdaftar yang dapat mengakses aplikasi sesuai perannya | FR-01–05 | users |
| BREQ-02 | Identitas dan profil setiap anggota tercatat lengkap serta terlindungi | FR-06–08, FR-19–22 | users, member_profiles |
| BREQ-03 | Setiap barang fisik dapat dibedakan, dikelompokkan dan dicari | FR-09–18 | units, categories, category_unit |
| BREQ-04 | Peminjaman berlangsung adil dan tidak menyebabkan peminjaman ganda | FR-23–28 | users, units, loans |
| BREQ-05 | Pengembalian dan denda tercatat akurat serta konsisten | FR-29–33 | loans, units |
| BREQ-06 | Pengelola dan anggota mendapat informasi pinjaman sesuai kewenangan | FR-34–35 | loans, users, units |
| BREQ-07 | Riwayat anggota dapat digunakan sebagai bukti dan dicetak | FR-36–37 | loans dan relasi historis |
| BREQ-08 | Pengelolaan inventaris/anggota tidak menghilangkan riwayat transaksi | FR-11, FR-21, FR-36–37 dan keputusan soft delete | users, units, loans |

## 7. Kebijakan operasional

1. Hanya ada dua peran: admin dan member. Admin membuat akun anggota; tidak ada pendaftaran publik.
2. Setiap member wajib memiliki tepat satu profil. Profil admin tidak diwajibkan oleh kebutuhan anggota.
3. Nama unit boleh sama; kode unit wajib unik. Contoh: SD-BG-001 dan SD-BG-002 untuk dua unit Catan.
4. Setiap unit wajib memiliki minimal satu kategori dan dapat memiliki beberapa kategori.
5. Admin mencatat peminjaman; member menyampaikan kebutuhan meminjam kepada admin di luar aplikasi. Tidak ada alur permohonan/persetujuan digital tambahan.
6. Satu catatan peminjaman mewakili satu unit. Maksimal dua pinjaman aktif per member, termasuk pinjaman terlambat.
7. Unit yang masih dipinjam tidak dapat dipinjamkan lagi. Aturan ini juga harus berlaku pada permintaan yang masuk bersamaan.
8. Tanggal pinjam ditetapkan otomatis. Jatuh tempo adalah tanggal pinjam ditambah lima hari kalender.
9. Hanya admin memproses pengembalian. Tanggal kembali ditetapkan otomatis dan tidak boleh mendahului tanggal pinjam.
10. Hari terlambat = maksimum antara nol dan selisih hari tanggal kembali terhadap jatuh tempo. Denda = hari terlambat × Rp5.000.
11. Contoh: pinjam 1 November, jatuh tempo 6 November, kembali 9 November menghasilkan tiga hari terlambat dan denda Rp15.000. Kembali paling lambat 6 November menghasilkan denda nol.
12. Denda final ditetapkan saat pengembalian. Sistem mencatat jumlah denda, bukan pembayaran atau status pelunasannya.
13. Pinjaman terlambat tidak menambah larangan meminjam apabila jumlah pinjaman aktif masih di bawah dua.
14. Member hanya mengakses profil dan pinjamannya sendiri. Admin dapat mengakses daftar seluruh pinjaman dan riwayat per anggota.
15. Unit/member dengan pinjaman aktif tidak boleh dihapus. Setelah tidak memiliki pinjaman aktif, penghapusan menggunakan soft delete agar riwayat bertahan.
16. Email dan kode unit yang telah dipakai tetap tidak dapat digunakan ulang setelah soft delete.
17. Kategori yang masih direferensikan unit, termasuk unit soft deleted, tidak dapat dihapus; relasi tidak dilepas otomatis.
18. Pengembalian kedua atas transaksi yang sama ditolak tanpa mengubah hasil pengembalian pertama.
19. Catatan admin pemroses pada loan menunjukkan admin saat peminjaman. Pencatatan admin pengembalian secara terpisah belum termasuk skema saat ini.

## 8. Proses bisnis yang dituju

### Persiapan

Admin masuk ke aplikasi, menyiapkan kategori dan unit, lalu membuat akun anggota beserta profilnya. Katalog menampilkan barang yang masih aktif beserta kategorinya dan ketersediaannya.

### Peminjaman

Anggota melihat katalog dan menghubungi admin. Admin memilih anggota serta unit. Sistem memeriksa identitas, ketersediaan dan batas pinjaman. Jika valid, transaksi tersimpan dan unit menjadi dipinjam. Jika tidak valid, sistem menampilkan alasan tanpa menyimpan perubahan sebagian.

### Pengembalian

Anggota menyerahkan unit kepada admin. Admin membuka pinjaman aktif dan memproses pengembalian. Sistem menetapkan tanggal, menghitung keterlambatan/denda, menutup pinjaman dan mengembalikan status unit menjadi tersedia. Kondisi fisik barang diperiksa oleh admin; aplikasi tidak memverifikasinya otomatis.

### Pemantauan dan pelaporan

Member memantau pinjamannya sendiri. Admin melihat seluruh pinjaman aktif dan penanda keterlambatan, membuka riwayat per anggota, lalu mencetaknya melalui halaman cetak. Riwayat tetap terbaca ketika unit atau anggota telah dinonaktifkan melalui soft delete.

## 9. Indikator keberhasilan

Target berikut merupakan sasaran penerimaan/operasional, bukan hasil yang sudah dicapai.

| Indikator | Target | Cara verifikasi |
|---|---|---|
| Kriteria penerimaan fitur | Seluruh AC-01–AC-16 terpenuhi | UAT dan test terkait pada prd.md |
| Anggota dengan lebih dari dua pinjaman aktif | Nol | Uji batas dan pemeriksaan data |
| Unit dengan lebih dari satu pinjaman aktif | Nol | Uji transaksi bersamaan dan pemeriksaan data |
| Perhitungan denda | Sesuai rumus pada semua skenario uji | Tanggal sebelum, tepat dan setelah jatuh tempo |
| Kesesuaian status unit dengan kondisi peminjaman fisik | 100% sebagai target operasional | Pencocokan inventaris berkala oleh admin |
| Waktu mencatat satu peminjaman | Kurang dari satu menit pada demo | Uji tugas admin sejak memilih data sampai tersimpan |
| Waktu menampilkan riwayat dan membuka tampilan cetak | Kurang dari sepuluh detik pada demo | Pengukuran sampai tampilan siap; tidak termasuk printer fisik |
| Waktu halaman utama/pencarian | Kurang dari tiga detik pada data demo | Pengukuran dengan lingkungan dan jumlah data dicatat |
| Proporsi pinjaman terlambat | Kurang dari 10% sebagai sasaran setelah penggunaan | Per periode: loan yang returned_at > due_date, atau masih borrowed dan lewat due_date, dibagi seluruh loan yang jatuh tempo pada periode tersebut; tanpa data dilaporkan N/A |

Penurunan keterlambatan bergantung pada perilaku anggota dan pengawasan admin; aplikasi tidak menjamin target perilaku hanya dengan keberadaan fitur.

## 10. Batasan, asumsi dan ketergantungan

- Implementasi melanjutkan proyek Laravel 12 dan MySQL yang sudah disiapkan pengguna; detail teknis ada di PRD/ERD.
- Pencatatan admin menjadi sumber kebenaran sistem. Tidak ada sensor atau pengecekan otomatis barang fisik.
- Tarif Rp5.000/hari adalah keputusan produk yang digunakan secara konsisten di dokumen, aplikasi dan pengujian.
- UI mengikuti desain Figma pengguna saat dapat diakses. Link/export belum tersedia pada penyusunan dokumen; kesesuaian visual final belum dapat dinilai.
- Tema luar angkasa harus tetap mudah dibaca, responsif dan mendukung pekerjaan operasional.
- Data pengguna yang sudah ada harus dijaga. Status migration aktual harus diperiksa sebelum perubahan.
- Ruang lingkup tidak mencakup tanggal komitmen rilis baru, anggaran, atau jumlah pengguna produksi karena belum diberikan.

## 11. Risiko dan penanganan

| Risiko | Penanganan |
|---|---|
| Dua transaksi lolos bersamaan untuk unit/member yang sama | Transaksi atomik dan penguncian sesuai ERD, dibuktikan lewat test MySQL |
| Status tidak sesuai karena barang berpindah tanpa dicatat | Prosedur admin wajib mencatat serah terima dan melakukan pencocokan berkala |
| Penghapusan data menghilangkan bukti pinjaman | Soft delete dan relasi historis; larangan hapus saat masih aktif |
| Anggota melihat data orang lain | Otorisasi server berdasarkan peran dan kepemilikan; uji manipulasi URL/payload |
| Perbedaan perhitungan tanggal/denda | Satu timezone aplikasi dan rumus hari kalender yang konsisten |
| Implementasi mengulang tabel yang sudah ada | Audit migration dan database sebelum perubahan; tidak melakukan reset destruktif |
| Desain Figma belum dapat diakses | Lanjutkan backend; minta acuan sebelum menyatakan UI final sesuai desain |

## 12. Penerimaan dan hasil kerja

Hasil kerja mencakup aplikasi sesuai PRD, skema/migration sesuai ERD beserta adaptasi Laravel yang terdokumentasi, data demo, README setup, panduan penggunaan, dan laporan pengujian/UAT. Source repository serta video demo dilengkapi ketika tersedia; jangan mengarang URL atau bukti pengujian.

Penerimaan dilakukan dengan menjalankan AC-01–AC-16 pada PRD serta pengujian tambahan keamanan, soft delete, pengembalian ganda dan transaksi bersamaan. Catat hasil aktual dan masalah yang masih terbuka. Tidak boleh menyatakan selesai atau bebas error hanya karena tampilan berhasil dibuka.

## 13. Penggunaan oleh Antigravity

Baca `docs/brd.md`, kemudian `docs/prd.md` dan `docs/erd.md` sebelum implementasi. BRD menjelaskan tujuan, cakupan dan kebijakan bisnis; PRD menetapkan perilaku fitur dan kriteria uji; ERD menetapkan struktur data serta integritas. Gunakan desain Figma untuk acuan visual saat tersedia.

Ketiga dokumen harus diterapkan bersama. Jangan menafsirkan BRD sebagai izin memperluas fitur, membuat ulang proyek, menghapus data, atau mengabaikan aturan teknis PRD/ERD. Bila ada kontradiksi nyata, sebutkan bagian yang bertentangan dan selesaikan kebutuhan terkait sebelum mengubah implementasinya.
