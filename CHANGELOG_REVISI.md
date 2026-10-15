# CHANGELOG REVISI & DOKUMENTASI SISTEM LENTERA KAB. BOGOR

Dokumen ini mencatat seluruh rangkaian perubahan, penguatan keamanan, refaktorisasi arsitektur, pengujian otomatis, serta panduan operasional hasil revisi menyeluruh dari **Fase 1 hingga Fase 6**.

---

## 1. Ringkasan Per Fase

### FASE 1 — Template & Generate Surat Rekomendasi
- **Masalah Semula**: Template DOCX memiliki placeholder yang pecah pada level XML (`<w:t>`), menyebabkan replacement teks gagal atau menghasilkan dokumen rusak. Generate PDF dari view Blade tidak presisi dibandingkan standar tata naskah dinas.
- **Penyelesaian**:
  - Merevisi template resmi DOCX kop surat Pemkab Bogor & Bakesbangpol.
  - Memperbarui `DocxSuratService` dan `SuratRekomendasiService` dengan parser XML yang aman dan membersihkan format tag bersarang.
  - Standarisasi format penomoran surat, data pemohon, instansi tujuan, dan tanggal berlaku.

### FASE 2 — Alur Surat Rekomendasi Final Sampai ke Mahasiswa
- **Masalah Semula**: Mahasiswa mengunduh file yang digenerate ulang (berpotensi draft atau belum bertanda tangan basah/elektronik resmi). Tombol upload surat final di admin menghilang setelah disimpan sekali.
- **Penyelesaian**:
  - Menambahkan kolom `file_surat_final`, `surat_final_diunggah_pada`, dan `surat_final_diunggah_oleh` pada tabel `permohonan_layanans`.
  - Memisahkan akses unduh: Tombol "Unduh Draft" hanya untuk internal Kesbangpol; Mahasiswa hanya dapat mengunduh `file_surat_final` yang telah sah.
  - Memastikan form upload surat final di Kesbangpol dapat diperbarui kapan saja (replace file).
  - Mengirim notifikasi otomatis ke akun mahasiswa saat surat final diunggah.
  - Penambahan QR Code verifikasi publik (`/verifikasi-surat/{token}`) yang hanya menampilkan metadata publik tanpa membocorkan data pribadi (NIK, email, nomor HP).

### FASE 3 — Kuota Booking, Expire, dan Status Pengajuan
- **Masalah Semula**: Kuota tidak terpotong saat pendaftaran masuk sehingga terjadi over-capacity; penolakan tidak mengembalikan kuota; pengajuan usang tidak memiliki mekanisme kedaluwarsa.
- **Penyelesaian**:
  - Mengimplementasikan sistem booking kuota otomatis saat mahasiswa melakukan submit permohonan (`status: menunggu`).
  - Validasi ketat batas kuota (`slot_tersedia`): jika kuota habis, pengajuan baru langsung ditolak dengan pesan informatif (HTTP 422).
  - Penolakan berkas oleh Bakesbangpol otomatis mengembalikan kuota ke instansi tujuan.
  - Pengalihan instansi tujuan oleh pemohon membatalkan booking instansi lama dan memotong kuota instansi baru dalam satu transaksi database (`DB::transaction`).
  - Pembuatan artisan command `lentera:expire-pengajuan` untuk mengidentifikasi pengajuan/surat yang melewati masa berlaku, menandainya sebagai `expired`, memulihkan kuota dinas, dan mengirim notifikasi ke mahasiswa.

### FASE 4 — Alur & UI Sisi Mahasiswa dan Landing Page
- **Masalah Semula**: Mahasiswa diarahkan ke pilihan dinas dan formulir berulang; landing page belum informatif terkait kuota seluruh dinas.
- **Penyelesaian**:
  - Landing page publik menampilkan katalog lengkap seluruh dinas (±38 dinas + kecamatan) dengan status kuota transparan (`Tersedia` / `Penuh` / `Tidak Tersedia`).
  - Rincian kuota dan daftar bidang ditampilkan interaktif setelah kartu dinas diklik.
  - Penyederhanaan alur pendaftaran mahasiswa: satu pintu melalui layanan permohonan. Menonaktifkan `MagangController::applyForm` untuk menghindari alur ganda yang membingungkan.
  - Setelah login, dashboard mahasiswa difokuskan pada pemantauan status permohonan dan menu operasional SIMALAM (Absensi GPS & Jurnal Harian) jika telah diterima.

### FASE 5 — Data Master Bidang, Role, dan Kebersihan Arsitektur
- **Masalah Semula**: Duplikasi fitur kelola bidang di `Dinas\BidangController` dan `Simalam\AdminController`; ketidaksinkronan data bidang antara database dengan lampiran resmi (`dinas_bidang.json`), seperti hilangnya Bidang PIKAP di Diskominfo.
- **Penyelesaian**:
  - Mengarahkan pengelolaan bidang ke satu sumber master data tunggal (`bidang` table via `Dinas\BidangController`).
  - Membuat artisan command `lentera:audit-bidang` untuk mengaudit: dinas tanpa bidang, kuantitas bidang per OPD, duplikasi nama, dan disparitas database vs JSON.
  - Menyediakan flag `--fix-pikap` untuk menyisipkan bidang resmi PIKAP ke Diskominfo tanpa mengarang nama bidang lain yang belum diverifikasi.
  - Memperbaiki isolasi data: Admin Dinas A tidak dapat melihat, memverifikasi, atau mengutak-atik pengajuan, peserta, bidang, atau absensi milik Dinas B.

### FASE 6 — Test Suite & Dokumentasi Lengkap
- **Masalah Semula**: Test bawaan Laravel (`ExampleTest`) belum mencerminkan fitur bisnis nyata; belum ada pengujian otomatis untuk keamanan autentikasi, isolasi dinas, batas kuota, dan privilege escalation.
- **Penyelesaian**:
  - Mengganti seluruh test bawaan dengan 94 automated feature & unit tests yang 100% lolos (green) di SQLite in-memory / MySQL testing.
  - Pembuatan test khusus keamanan, isolasi, integritas kuota, dan upload file.
  - Pembaruan menyeluruh pada `README.md` (Laravel 13, Scheduler, Konfigurasi `config/lentera.php`, Arsitektur Dua Proses Bisnis).
  - Penyusunan dokumen `CHANGELOG_REVISI.md` beserta daftar TODO konfirmasi manusia dan checklist deployment.

---

## 2. File yang Diubah / Ditambahkan

### File Konfigurasi & Core
- `config/lentera.php` — Penambahan pengaturan sentral masa berlaku surat, kedaluwarsa pengajuan, kuota default, dan identitas pejabat penandatangan.
- `routes/web.php` — Penataan rute autentikasi, pembatasan akses dashboard admin/superadmin, perlindungan rute `switch-instansi`, isolasi peran, serta pembersihan alias route lama.
- `app/Support/CurrentDinas.php` — Pembatasan session instansi context hanya bagi superadmin.
- `app/Models/User.php` — Penguatan mutator/accessor, pengecekan role terpusat (`isAdmin()`, `isDinas()`, `isKesbangpol()`, `isUser()`).
- `app/Models/Rekrutmen.php` — Helper perhitungan slot kuota tersedia berbasis status pemakaian kuota.

### Controller & Service
- `app/Http/Controllers/AuthController.php` — Pengamanan login terpusat, eliminasi backdoor password, penambahan throttle rate limiter, generic response pada forgot-password/resend-activation (anti-enumeration), import `Mail` facade.
- `app/Http/Controllers/LayananController.php` — Booking kuota pada saat submit, pengembalian kuota saat batal/hapus, pemindahan kuota saat ganti dinas tujuan.
- `app/Http/Controllers/Kesbangpol/LayananController.php` — Upload surat final bertanda tangan, generate docx/pdf draft terpisah, verifikasi rekomendasi dengan update status kuota.
- `app/Http/Controllers/Dinas/ApplicationController.php` — Scoping data hanya untuk instansi bersangkutan (Dinas Isolation), validasi kepemilikan bidang internal.
- `app/Http/Controllers/LandingController.php` — Update profil dengan whitelist field yang aman (menangkal privilege escalation), katalog dinas publik, verifikasi surat QR publik.

### Console Commands
- `app/Console/Commands/ExpirePengajuanCommand.php` — Command `lentera:expire-pengajuan`.
- `app/Console/Commands/AuditBidangCommand.php` — Command `lentera:audit-bidang` (dengan opsi `--fix-pikap` dan `--json`).
- `routes/console.php` — Penjadwalan harian command expire pada scheduler Laravel.

### Migrasi Database Baru
- `database/migrations/2026_10_05_000001_add_verification_token_to_surat_rekomendasis_table.php`
- `database/migrations/2026_10_05_000002_remove_default_credentials_from_md_pengaturan.php`
- `database/migrations/2026_10_05_000003_add_surat_final_to_permohonan_layanans_table.php`
- `database/migrations/2026_10_05_000004_add_quota_and_expiry_fields.php`

### Test Suite (`tests/Feature/` & `tests/Unit/`)
- `tests/Feature/AuthSecurityTest.php` — Uji tanpa backdoor password, throttle login, reset token kedaluwarsa, respons generik anti-enumerasi.
- `tests/Feature/PrivilegeEscalationTest.php` — Uji update profil tidak bisa memanipulasi role, dinas_id, bidang_id, atau status akun.
- `tests/Feature/RoleAccessTest.php` — Matriks izin akses lintas role (peserta, dinas, kesbangpol, admin, superadmin).
- `tests/Feature/DinasIsolationTest.php` — Uji isolasi data Dinas A vs Dinas B (permohonan, peserta, bidang, absensi/jurnal, switch instansi).
- `tests/Feature/KuotaBookingTest.php` — Uji batas kuota, penolakan mengembalikan kuota, perubahan dinas memindahkan kuota.
- `tests/Feature/ExpirePengajuanTest.php` — Uji artisan command expire dengan simulasi waktu `Carbon::setTestNow()`.
- `tests/Feature/SuratFinalTest.php` — Uji penerimaan surat final (bukan draft), perlindungan akses non-pemilik, verifikasi QR code minimalis.
- `tests/Feature/UploadValidationTest.php` — Uji validasi MIME type, ukuran file maksimum, penolakan ekstensi berbahaya (`.php`, `.exe`, `.sh`, dll.).
- `tests/Feature/SecurityHardeningTest.php` — Uji pembersihan kredensial default dari database dan header keamanan.
- `tests/Unit/LenteraConfigTest.php` — Uji integritas file konfigurasi `config/lentera.php`.

---

## 3. Keputusan yang Memerlukan Konfirmasi Manusia (TODO)

Berikut adalah daftar hal-hal yang **sengaja tidak diasumsikan** dan memerlukan keputusan/konfirmasi dari stakeholder/mentor:

### TODO-001: Definisi Hari Kedaluwarsa (Expire)
- **Saat ini**: Menggunakan default **30 hari kalender** untuk batas verifikasi pengajuan dan masa berlaku Surat Rekomendasi (`config/lentera.php`).
- **Pertanyaan Konfirmasi**:
  - Apakah 30 hari dihitung berdasarkan **hari kerja** atau **hari kalender**?
  - Apakah diperlukan masa tenggang (grace period) sebelum permohonan berstatus `expired` (misal notifikasi pengingat H-3 sebelum kedaluwarsa)?

### TODO-002: Master Bidang yang Belum Lengkap pada Data Master
Berdasarkan hasil audit command `lentera:audit-bidang`, beberapa instansi memiliki struktur yang belum sinkron dengan data riil:
- **Diskominfo**: Bidang PIKAP telah ditambahkan via `--fix-pikap`. Namun, perlu konfirmasi apakah Bidang Statistik dan Bidang Persandian berstatus bidang mandiri atau sub-bagian.
- **Sekretariat Daerah (Setda)**: `dinas_bidang.json` memuat 10 bagian, sedangkan nomenklatur Perbup terbaru mungkin memiliki penyesuaian.
- **DP3AP2KB & Dispora**: Di data master hanya tercatat 2-3 bidang, belum mencakup Bidang Kepemudaan / Pemberdayaan Perempuan.
- **Kecamatan (40 Kecamatan)**: Saat ini hanya memiliki satu bidang default ("SEKRETARIAT"). Apakah magang di kecamatan memerlukan pemilihan Seksi (Pemerintahan, Ekbang, Trantib, dll.) atau cukup lingkup Sekretariat Kecamatan?
> *Rekomendasi*: Minta daftar resmi Susunan Organisasi dan Tata Kerja (SOTK) terbaru dari Bagian Organisasi Setda Kab. Bogor sebelum melakukan seeder bidang tambahan.

### TODO-003: Normalisasi Role Akun Sistem
- **Saat ini**: Sistem mendukung role `peserta` (alias `user`), `dinas`, `bidang`, `kesbangpol`, `admin`, dan `superadmin`.
- **Pertanyaan Konfirmasi**:
  - Apakah role `admin` (admin instansi/Simalam) dan `dinas` perlu dilebur secara permanen menjadi satu role kanonik `dinas`?
  - Apakah akun Bakesbangpol disepakati tetap menggunakan role `kesbangpol` atau diintegrasikan sebagai akun dinas khusus dengan flag `is_kesbangpol = true`?

---

## 4. Checklist Deployment Production

Gunakan panduan checklist ini sebelum merilis sistem ke server produksi:

### Langkah 1: Kredensial & Lingkungan (.env)
- [ ] Ganti `APP_KEY` dengan key baru: `php artisan key:generate`
- [ ] Set `APP_ENV=production` dan pastikan `APP_DEBUG=false`
- [ ] Ganti kata sandi database MySQL ke kata sandi acak yang kuat (minimal 16 karakter).
- [ ] Konfigurasikan SMTP resmi Pemkab Bogor (`MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`).
- [ ] Ubah PIN default admin pada modul Simalam (`md_pengaturan`) melalui database atau form pengaturan instansi.
- [ ] Pastikan tidak ada akun bawaan/dummy dengan password umum (`password123`, `admin123`) di database produksi.

### Langkah 2: Migrasi Database
Jalankan migrasi bertahap untuk menerapkan field dan indeks baru:
```bash
php artisan migrate --force
```

### Langkah 3: Penyiapan Storage
Pastikan symlink storage publik telah terpasang dan memiliki hak akses tulis (*write permissions*):
```bash
php artisan storage:link
chmod -R 775 storage bootstrap/cache
```

### Langkah 4: Pembersihan & Optimasi Cache
Bersihkan seluruh cache konfigurasi, view, dan rute lama:
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Optimasi untuk produksi
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Langkah 5: Pemasangan Cron Scheduler
Tambahkan Laravel Scheduler ke crontab server (`crontab -e` pada user web server, misal `www-data`):
```bash
* * * * * cd /var/www/Lentera_Kab.Bogor && php artisan schedule:run >> /dev/null 2>&1
```

### Langkah 6: Verifikasi Akhir
- [ ] Jalankan `php artisan test` untuk memastikan seluruh test suite hijau di lingkungan server.
- [ ] Jalankan `php artisan route:list` untuk memverifikasi tidak ada rute yang ambigu atau error.
- [ ] Lakukan uji coba alur pendaftaran permohonan hingga download Surat Final.
- [ ] Uji coba verifikasi QR Code di `/verifikasi-surat/{token}` dari perangkat mobile.
