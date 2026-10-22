# StarDeck — Product Requirements Document

Status: spesifikasi implementasi Laravel 12 untuk proyek yang sudah ada.
Sumber produk: `StarDeck – PRD(1).pdf`. Skema pendamping: `erd.md`.
Dokumen ini merangkum kebutuhan sumber dan menetapkan keputusan teknis implementasi; bukan bukti bahwa aplikasi sudah selesai atau lulus uji.

## 1. Tujuan dan cakupan

StarDeck mengelola peminjaman unit fisik board game dan console untuk kru stasiun luar angkasa. Sistem harus menjelaskan siapa memegang setiap unit, membatasi pinjaman, menghitung keterlambatan, serta menyimpan riwayat yang dapat dicetak.

Fitur wajib: login/logout, otorisasi admin/member, katalog dan pencarian nama unit, CRUD kategori/unit/anggota, profil sendiri, pencatatan pinjam dan kembali, denda otomatis, daftar pinjaman, riwayat per anggota, cetak, migration, seeder, pengujian, dan dokumentasi.

Di luar cakupan: registrasi publik, reservasi, pembayaran denda, notifikasi email/SMS, aplikasi native, upload banyak foto unit. Jangan membuat fitur tambahan tersebut.

## 2. Kondisi proyek yang harus diperiksa

- Proyek pengguna berada di `D:\stardeck`, Windows PowerShell dan Laragon.
- Versi yang dilaporkan: Laravel 12.70.1, PHP 8.3.31, Composer 2.8.6, Node 25.9.0, npm 11.12.1, Git 2.46.2.
- Pengguna telah mengonfirmasi koneksi MySQL berhasil. Jangan membuat ulang proyek atau mengganti framework.
- Migration awal `users` masih bawaan Laravel: id, name, email, email_verified_at, password, remember_token, timestamps; file yang sama membuat password_reset_tokens dan sessions.
- Migration tambahan `add_role_and_deleted_at_to_users_table` sudah diberikan sebagai instruksi, tetapi keberadaan file dan keberhasilan eksekusinya BELUM dikonfirmasi. Periksa file, `migrate:status`, dan struktur DB sebelum melakukan perubahan.
- Konfigurasi lokal sebelumnya diarahkan ke session=file, cache=file, queue=sync. Verifikasi konfigurasi efektif tanpa menampilkan isi `.env` atau secret.
- Desain Figma dimiliki pengguna, tetapi link/export belum tersedia dalam konteks ini. Jangan mengaku telah melihat desainnya.

## 3. Teknologi dan aturan pengerjaan

- Laravel 12, PHP 8.3, MySQL InnoDB/utf8mb4, Blade, Bootstrap 5 dan CSS khusus, Vite.
- Pertahankan dependency dan lockfile yang sudah ada bila kompatibel. Periksa kebutuhan Node dari dependency aktual; jangan memperbarui framework atau dependency besar secara otomatis.
- Gunakan MVC, Form Request, middleware/policy untuk akses, dan service transaksi peminjaman/pengembalian.
- Gunakan migration sebagai jalur instalasi. Jangan mengimpor SQL lama lalu membuat tabel yang sama lewat migration.
- Perubahan pada database yang telah dimigrasikan memakai migration tambahan. Jangan mengubah migration lama yang sudah dijalankan untuk menyamarkan perubahan skema.
- Jangan menjalankan `migrate:fresh`, `db:wipe`, drop database, reset Git, atau menimpa pekerjaan pengguna. Database pengujian harus terpisah dan diperiksa sebelum test yang mereset DB.
- Jangan menyimpan secret di kode, prompt, log, README atau Git. `.env.example` hanya berisi contoh tanpa secret.

## 4. Peran dan akses

| Fitur | Admin / Komandan Stasiun | Member / Astronot |
|---|---|---|
| Login, logout, katalog, pencarian | Ya | Ya |
| Dashboard | Ringkasan inventaris dan pinjaman seluruh anggota | Ringkasan pinjaman sendiri |
| CRUD kategori dan unit | Ya | Tidak |
| CRUD akun anggota beserta profil | Ya | Tidak |
| Profil anggota | Kelola melalui manajemen anggota | Lihat/ubah profil sendiri |
| Input peminjaman dan pengembalian | Ya | Tidak |
| Pinjaman aktif | Semua | Milik sendiri |
| Riwayat per anggota dan cetak | Ya | Tidak |

Semua halaman aplikasi selain login membutuhkan autentikasi. Aset publik yang diperlukan login tetap dapat dimuat. Tidak ada route registrasi publik. Pembatasan diberlakukan di server, bukan hanya menyembunyikan tombol. Akun yang soft deleted tidak boleh login maupun memakai sesi lama untuk mengakses aplikasi.

CRUD anggota selalu membuat role `member`; jangan mempercayai role dari form anggota/profil. Pembuatan akun admin awal dilakukan lewat seeder. Pengelolaan role admin tambahan bukan kebutuhan wajib.

## 5. Kebutuhan fungsional

| ID sumber | Kebutuhan implementasi |
|---|---|
| FR-01–05 | Login email/password, logout, redirect dashboard sesuai peran, autentikasi dan otorisasi server. Pesan login gagal bersifat umum. |
| FR-06–08 | Setiap member tepat satu profil; member hanya membaca dan mengubah profil sendiri. |
| FR-09–15 | CRUD unit, minimal satu kategori valid, nama unit boleh sama, kode unik, katalog/status/kategori dan pencarian berdasarkan nama. |
| FR-16–18 | CRUD kategori, nama unik; pilih kebijakan menolak penghapusan kategori yang masih direferensikan unit. |
| FR-19–22 | Admin membuat/mengubah anggota beserta profil; email unik; hapus hanya bila tidak memiliki pinjaman aktif. |
| FR-23–28 | Admin memilih member aktif dan unit tersedia; maksimal dua pinjaman aktif; tanggal otomatis; status unit diperbarui secara atomik. |
| FR-29–33 | Admin memproses pengembalian, tanggal otomatis, denda otomatis, status sinkron; daftar menandai keterlambatan. |
| FR-34–37 | Admin melihat semua pinjaman aktif; member hanya miliknya; admin melihat dan mencetak riwayat per anggota. |

### Peminjaman

1. Admin memilih satu member dan satu unit; satu baris `loans` selalu mewakili satu unit.
2. Server memeriksa member belum dihapus, role member, profil tersedia, jumlah pinjaman berstatus borrowed kurang dari dua.
3. Unit belum dihapus, berstatus available, dan tidak memiliki loan borrowed.
4. Server menetapkan borrowed_at = tanggal hari ini, due_date = borrowed_at + 5 hari, processed_by = admin yang login.
5. Simpan loan borrowed dan ubah unit menjadi borrowed dalam transaksi yang sama.

Pinjaman terlambat tetap dihitung sebagai pinjaman aktif. Keterlambatan tidak menambah larangan meminjam bila total aktif masih kurang dari dua.

### Pengembalian

1. Hanya admin; loan harus masih borrowed.
2. returned_at ditetapkan server ke hari ini dan tidak boleh sebelum borrowed_at.
3. late_days = max(0, selisih hari kalender returned_at dengan due_date).
4. fine_amount = late_days × 5000; simpan nilai final.
5. Ubah loan menjadi returned dan unit menjadi available secara atomik.
6. Pengembalian ulang ditolak tanpa mengubah tanggal atau denda sebelumnya.

Contoh: pinjam 1 November → jatuh tempo 6 November. Kembali 6 November: Rp0; kembali 9 November: 3 hari, Rp15.000. Semua perhitungan memakai tanggal kalender dalam satu timezone aplikasi. Keputusan implementasi lokal: Asia/Jakarta; tetapkan konsisten di konfigurasi dan test.

`processed_by` adalah admin pencatat PEMINJAMAN dan tidak ditimpa ketika pengembalian. Status terlambat dihitung dari borrowed dan due_date < hari ini; tidak menambah nilai enum. Denda pinjaman aktif belum merupakan denda final; bila ditampilkan, beri label estimasi.

### Penghapusan dan riwayat

- Unit/member dengan pinjaman aktif tidak dapat dihapus.
- Unit/member tanpa pinjaman aktif di-soft-delete agar riwayat tetap utuh. Profil dan pivot kategori tetap tersimpan.
- Email dan kode unit tetap unik termasuk milik data terhapus; tidak digunakan ulang.
- Kategori yang direferensikan pivot, termasuk oleh unit soft deleted, tidak dapat dihapus. Jangan melakukan detach otomatis.
- Riwayat mengambil identitas member, unit, dan admin termasuk soft deleted. Tidak ada fitur menghapus loan.
- Cetak melalui halaman browser A4 dengan print stylesheet; tampilkan anggota, kode/nama unit, tanggal pinjam/jatuh tempo/kembali, status dan denda. Sembunyikan navigasi dan tombol saat cetak.

## 6. Validasi

| Data | Ketentuan |
|---|---|
| Nama pengguna | Wajib, string, maksimal 100 karakter |
| Email | Wajib, email valid, maksimal 255, unik termasuk soft deleted |
| Password | Wajib saat tambah, minimal 8 karakter; opsional saat edit dan kosong berarti tidak diganti; hash Laravel |
| Phone | Wajib, string angka 9–15 digit; nol di depan tetap tersimpan |
| Rank/position dan station_module | Wajib, masing-masing maksimal 100 |
| Address | Opsional, maksimal 255 |
| Photo profil | Opsional, JPG/PNG yang tervalidasi MIME, maksimal 2 MB; nama file dibuat server dan DB menyimpan path |
| Nama unit | Wajib, maksimal 150; boleh duplikat |
| Unit code | Wajib, unik, pola `^SD-(BG|CN)-[0-9]{3}$`; BG untuk board_game, CN untuk console |
| Type | board_game atau console |
| Condition | Wajib, string maksimal 100 |
| Categories | Array minimal satu ID valid, tanpa duplikasi |
| Nama kategori | Wajib, maksimal 50, unik |
| Deskripsi unit/kategori | Opsional, teks |
| ID peminjaman | ID valid, role/status/soft delete diperiksa ulang di dalam transaksi |

Jangan menerima status unit, denda, tanggal pinjam, jatuh tempo, processed_by, user_id profil, atau role dari input yang tidak berwenang. Status unit berubah lewat transaksi pinjam/kembali. Pesan validasi dalam bahasa Indonesia di dekat field; input aman tetap tersedia setelah gagal.

## 7. Integritas, keamanan dan performa

- Implementasikan FK dan unique sesuai `erd.md`.
- Lock baris member lalu unit untuk peminjaman, hitung ulang pinjaman setelah lock. Semua operasi yang dapat mengubah ketersediaan atau menghapus data harus memakai urutan lock konsisten; lihat ERD.
- Buat user+profil dan perubahan unit+kategori dalam transaksi agar tidak tersimpan separuh.
- CSRF, hashing password, regenerasi sesi setelah login, invalidasi sesi saat logout, pembatasan percobaan login, authorization dan mass-assignment protection.
- Riwayat/member query dibatasi berdasarkan pengguna login. Uji manipulasi URL dan payload.
- Gunakan eager loading dan pagination. Target halaman utama/pencarian <3 detik pada data demo; catat hasil ukur, jangan mengklaim tanpa pengukuran.
- Responsif pada HP/desktop; target Chrome, Edge dan Firefox modern.

## 8. UI dan Figma

Utamakan desain Figma pengguna jika link/export dapat diakses. Petakan halaman, komponen, warna, typography, spacing dan state sebelum membangun UI final. Jika belum tersedia, lanjutkan backend dan pengujian; minta link atau export Figma sebagai input yang masih dibutuhkan. UI sementara harus ditandai sementara, bukan diklaim sesuai Figma.

Tema: luar angkasa yang tenang dan fungsional. Arah fallback: sidebar navy, area kerja terang, aksen teal, border dan shadow ringan, tabel mudah dibaca. Hindari neon berlebihan, glassmorphism di semua panel, gradient ungu generik, animasi bintang yang mengganggu, emoji sebagai seluruh ikon, dan kartu dekoratif tanpa fungsi. Istilah tematik boleh dipakai dengan label tindakan yang jelas.

Halaman: login; dashboard admin/member; katalog dan detail unit; CRUD kategori; CRUD unit; CRUD anggota/profil; profil sendiri; form peminjaman; pinjaman aktif admin; pinjaman saya; konfirmasi pengembalian dengan rincian denda; riwayat per anggota dan halaman cetak. Sediakan keadaan kosong, error, sukses dan validasi.

## 9. Seed dan dokumentasi

Seed lokal: satu admin, 3–5 member dengan profil lengkap, kategori Strategi/Party/Kooperatif/2 Pemain/Console. Unit: Catan BG001 dan BG002, Codenames BG003, Pandemic BG004, PS5 CN001 dan CN002, Nintendo Switch CN003; gunakan kode lengkap SD-BG-001 dan seterusnya. Setiap unit minimal satu kategori.

Seeder demo tidak boleh menimpa password/data pengguna yang sudah ada. Akun demo hanya untuk lokal/testing dan kredensial demo terdokumentasi sebagai data contoh. Pinjaman contoh opsional; bila dibuat, status loan dan unit wajib sinkron.

README: setup Windows/PowerShell, konfigurasi MySQL tanpa secret, install dependency, migration/seeder, storage link, menjalankan Vite/server, test, akun demo lokal, fitur, diagram, troubleshooting dan keterbatasan. Tambahkan hasil UAT dan panduan pemakaian. Nama tim/anggota, URL repo GitLab dan video demo belum tersedia; tandai belum diisi, jangan direkayasa. Publikasi/push repo dilakukan hanya bila diminta.

## 10. Acceptance criteria dan pengujian

| ID | Skenario dan hasil wajib |
|---|---|
| AC-01 | Tamu membuka halaman aplikasi → redirect login. |
| AC-02 | Member membuka URL admin → akses ditolak. |
| AC-03 | Kode unit duplikat → ditolak. |
| AC-04 | Nama unit sama, kode berbeda → berhasil. |
| AC-05 | Tiga kategori pada unit → tersimpan dan tampil seluruhnya. |
| AC-06 | Cari cat → tampil nama unit yang mengandung cat. |
| AC-07 | Pinjaman aktif ketiga → ditolak, DB tidak berubah. |
| AC-08 | Unit sedang borrowed → peminjaman ditolak. |
| AC-09 | Pinjam berhasil → jatuh tempo +5 hari, loan dan unit borrowed. |
| AC-10 | Kembali tepat jatuh tempo → denda nol. |
| AC-11 | Kembali tiga hari setelah jatuh tempo → 3 hari, Rp15.000. |
| AC-12 | Member hanya melihat pinjamannya. |
| AC-13 | Member mengakses/mengubah profil lain → ditolak. |
| AC-14 | Admin dapat menampilkan dan mencetak riwayat lengkap. |
| AC-15 | Hapus unit/member dengan pinjaman aktif → ditolak. |
| AC-16 | Field wajib kosong → pesan pada field terkait. |

Uji tambahan: pengembalian ganda; soft delete mempertahankan riwayat; login/sesi akun terhapus ditolak; kategori terpakai tidak dapat dihapus; akun tanpa profil tidak tercipta; edit password kosong tidak mengganti hash; manipulasi role/denda ditolak; pinjaman kedua tetap boleh walau pinjaman pertama terlambat.

Uji persaingan request pada MySQL terpisah: dua request untuk unit sama hanya satu berhasil; dua request ketika member sudah punya satu pinjaman hanya satu tambahan berhasil. Uji rollback saat transaksi gagal. SQLite saja tidak membuktikan perilaku row locking MySQL.

## 11. Tahapan dan definisi selesai

1. Audit proyek, migration, schema dan dependency; rangkum yang sudah ada dan gap.
2. Migration aman, model/relasi, seeder; verifikasi pada DB yang sesuai.
3. Login/logout, middleware/policy, profil.
4. CRUD kategori/anggota/unit dan pencarian.
5. Service peminjaman/pengembalian dan integritas transaksi.
6. Dashboard, daftar, riwayat, cetak, UI Figma.
7. Test, build asset, UAT dan dokumentasi.

Pada setiap tahap perbaiki error yang ditemukan sebelum lanjut. Selesai berarti fitur terimplementasi, test terkait lolos, build berhasil, akses/perhitungan terverifikasi, dan keterbatasan dilaporkan. Jalankan test/build jika lingkungan tersedia; bila tidak, jelaskan apa yang belum dapat diuji. Jangan menjanjikan nol error atau menyebut desain sesuai Figma tanpa acuan.
