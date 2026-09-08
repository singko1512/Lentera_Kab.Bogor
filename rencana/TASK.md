# TASK.md
# Blueprint Implementasi Project Baru

## 1. Tujuan
Membangun ulang sistem dari 0 berdasarkan alur bisnis baru yang telah disepakati. Project baru tidak melakukan audit, restorasi, atau migrasi arsitektur project lama.

Project lama hanya menjadi referensi visual. Nama resource view yang sudah digunakan dipertahankan agar desain/tampilan sebelumnya dapat digunakan kembali.

## 2. Role Sistem
Sistem hanya memiliki 3 role utama:
- `user` : peserta/pemohon.
- `dinas` : akun setiap instansi, termasuk Kesbangpol.
- `admin` : pengelola sistem/master data.

Kesbangpol bukan role tersendiri. Kesbangpol adalah akun `dinas` yang terhubung ke instansi dengan identitas khusus sehingga UI dan hak operasionalnya menyesuaikan akun yang sedang login.

## 3. Alur Bisnis Utama
### Tahap A: Layanan Publik
1. User login.
2. User melihat daftar layanan.
3. User memilih salah satu layanan.
4. User mengisi formulir sesuai layanan.
5. User mengirim permohonan.
6. Permohonan tersimpan dan menunggu verifikasi Kesbangpol.

### Tahap B: Verifikasi Kesbangpol
1. Akun dinas yang merupakan Kesbangpol melihat permohonan yang masuk.
2. Kesbangpol memeriksa data dan dokumen.
3. Keputusan:
   - Revisi: permohonan dikembalikan kepada user dan user menerima notifikasi.
   - Tolak: permohonan selesai/ditolak dan user menerima notifikasi.
   - Setujui: permohonan disetujui dan user menerima notifikasi.
4. Jika layanan yang disetujui bukan Magang, proses layanan berakhir sesuai kebutuhan layanan tersebut.
5. Jika layanan adalah Magang, user mendapatkan akses untuk melanjutkan ke pendaftaran magang.

### Tahap C: Pendaftaran Magang
1. User menerima notifikasi bahwa layanan Magang telah disetujui.
2. User masuk ke pendaftaran Magang.
3. Data dasar yang telah disetujui dari permohonan layanan dapat digunakan kembali.
4. User memilih Dinas tujuan.
5. User memilih Bidang tujuan yang tersedia pada Dinas tersebut.
6. User melengkapi data magang yang diperlukan.
7. User mengirim pendaftaran magang.
8. Sistem membuat data pendaftaran magang.

### Tahap D: Verifikasi Dinas Tujuan
1. Admin dari Dinas tujuan melihat hanya pendaftaran yang ditujukan kepada instansinya.
2. Dinas memeriksa pengajuan.
3. Keputusan:
   - Tolak.
   - Terima.
4. Data tidak boleh terlihat atau diproses oleh Dinas lain.

### Tahap E: Penempatan
Setelah diterima:
1. Peserta ditempatkan pada Bidang.
2. Peserta dapat diberikan Pembimbing.
3. Penempatan harus mengikuti kapasitas/kuota yang berlaku.
4. Operasi yang memengaruhi kapasitas harus aman terhadap concurrent request.

### Tahap F: Pelaksanaan
Peserta yang aktif dapat menjalankan:
- Absensi.
- Jurnal/kegiatan magang.
- Pemantauan progres.

### Tahap G: Penyelesaian
1. Magang dinyatakan selesai.
2. Penilaian dilakukan.
3. Data hasil magang direkap.
4. Sertifikat dapat diterbitkan sesuai aturan sistem.

## 4. Notifikasi
Notifikasi menjadi penghubung penting antara perubahan status dan tindakan user.

Minimal notifikasi untuk:
- Permohonan layanan berhasil dikirim.
- Permohonan direvisi.
- Permohonan ditolak.
- Permohonan layanan disetujui.
- Layanan Magang siap dilanjutkan.
- Pendaftaran magang diterima/ditolak.
- Perubahan penting pada lifecycle peserta.

## 5. Prinsip Data
- Data layanan dan data pendaftaran magang merupakan dua tahap bisnis yang berbeda.
- Pendaftaran magang dapat memiliki referensi ke permohonan layanan Magang yang telah disetujui.
- User hanya dapat mengakses data miliknya.
- Akun `dinas` bekerja berdasarkan `dinas_id` dari sesi login.
- Akun Kesbangpol menggunakan role `dinas` dan identitas instansi untuk menentukan tampilan serta fungsi verifikasi.
- Akun Dinas tujuan hanya dapat mengakses pendaftaran yang memang ditujukan kepada Dinas tersebut.
- Akun `admin` mengelola master data dan fungsi administratif sistem.

## 6. Aturan Kuota
Jangan menggunakan enum untuk aturan yang dapat berubah.

Nilai seperti:
- minimum kuota,
- batas kapasitas,
- batas jumlah peserta,
- batas upload,
- atau aturan numerik sejenis

harus ditempatkan pada konfigurasi (`config/*.php`) atau data master/database sesuai sifatnya.

Aturan bisnis yang bersifat dinamis jangan di-hardcode di controller.

## 7. Resource View
Project baru mempertahankan nama resource view yang sudah digunakan sebelumnya agar tampilan dapat dipakai kembali.

Contoh resource view yang dipertahankan:
- `resources/views/landing/*`
- `resources/views/layanan/*`
- `resources/views/participant/*`
- `resources/views/dinas/*`
- `resources/views/partials/*`
- `resources/views/layouts/*`

Nama controller/model lama tidak dijadikan ketergantungan atau acuan arsitektur. Struktur backend baru boleh menggunakan penamaan yang lebih sesuai dengan domain baru.

## 8. UI
Tampilan menggunakan desain project sebelumnya sebagai basis visual:
- layout,
- komponen,
- sidebar,
- navbar,
- form,
- tabel,
- card,
- modal,
- notifikasi.

Namun alur, data, authorization, dan backend mengikuti blueprint baru.

UI Dinas bersifat dinamis berdasarkan akun:
- Kesbangpol mendapatkan tampilan/fungsi verifikasi Kesbangpol.
- Dinas tujuan mendapatkan tampilan/fungsi operasional Dinas.
- Semua tetap berada dalam role `dinas`.

## 9. Security
- Authorization wajib dilakukan di backend.
- Jangan mempercayai `dinas_id`, `user_id`, atau identitas pemilik yang dikirim dari frontend.
- Identitas user berasal dari sesi autentikasi.
- Identitas Dinas berasal dari akun login.
- Validasi kepemilikan harus dilakukan sebelum membaca/mengubah data.
- Validasi target Bidang harus memastikan Bidang berada pada Dinas yang dipilih.
- Perubahan status penting harus tervalidasi terhadap state sebelumnya.
- Operasi kuota/kapasitas yang rentan race condition menggunakan transaksi database dan locking yang sesuai.

## 10. Prinsip Implementasi
Setiap phase harus:
1. Memiliki tujuan jelas.
2. Memeriksa dependency yang benar-benar dibutuhkan.
3. Mengubah hanya scope phase tersebut.
4. Memiliki verifikasi setelah implementasi.
5. Tidak melakukan refactor besar tanpa kebutuhan bisnis.
6. Tidak membuat fitur yang belum diminta.
7. Menjaga backward visual compatibility dengan resource view yang dipertahankan.

## 11. Urutan Pembangunan
- Phase 0: Fondasi project, autentikasi, role, konfigurasi, dan struktur database.
- Phase 1: Layanan Publik dan formulir layanan.
- Phase 2: Verifikasi Kesbangpol dan status layanan.
- Phase 3: Notifikasi dan jembatan ke pendaftaran Magang.
- Phase 4: Pendaftaran Magang, pemilihan Dinas dan Bidang.
- Phase 5: Verifikasi Dinas tujuan.
- Phase 6: Penempatan Bidang dan Pembimbing.
- Phase 7: Kuota dan perlindungan concurrency.
- Phase 8: Absensi dan Jurnal.
- Phase 9: Penyelesaian, Penilaian, dan Sertifikat.
- Phase 10: Laporan, penyempurnaan UI, dan final verification.

## 12. Definition of Done
Sistem dianggap siap ketika alur berikut dapat dilakukan end-to-end:

User → pilih layanan → submit layanan → Kesbangpol verifikasi → user menerima notifikasi → jika Magang user melanjutkan pendaftaran → pilih Dinas → pilih Bidang → Dinas tujuan memverifikasi → diterima → penempatan/pembimbing → aktif → absensi/jurnal → penilaian → selesai → sertifikat.

Tidak ada tahap Booking dalam alur tersebut.
