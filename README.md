# Layanan Elektronik Terpadu Evaluasi dan Rekomendasi (LENTERA)

LENTERA adalah sistem informasi berbasis web yang memfasilitasi pelayanan publik secara elektronik, khususnya dalam proses pendaftaran, evaluasi, dan pemberian rekomendasi izin pelaksanaan kegiatan akademik (seperti PKL, KKL, Magang, dan Penelitian).

Sistem ini mengintegrasikan portal pelayanan dengan instansi terkait, memberikan kemudahan akses satu pintu bagi mahasiswa, siswa, akademisi, serta peneliti.

## Fitur Utama

- **Pendaftaran Terpadu**: Peserta dapat mengajukan permohonan Magang, Penelitian, PKL, KKN secara online.
- **Masquerade Superadmin**: Fitur khusus bagi pengelola sistem utama (Superadmin) untuk dapat login dan memantau dasbor setiap instansi bawahan tanpa memerlukan akses kata sandi secara spesifik.
- **Pembuatan Surat Otomatis (PDF)**: Dokumen rekomendasi, penerimaan, dan sertifikat dihasilkan secara otomatis oleh sistem lengkap dengan Tanda Tangan Elektronik (*e-sign*).
- **Pengaturan Kop & Penandatangan**: Setiap instansi dapat mengatur kop surat dan profil penandatangan (nama & NIP Kepala Instansi) secara mandiri.
- **Manajemen Kuota Instansi**: Setiap instansi dapat memantau, membuka/menutup lowongan, dan mengatur ketersediaan kuota penerimaan magang secara *real-time*.
- **Portal Absensi**: Sistem terintegrasi dengan modul pencatatan kehadiran harian peserta magang.

## Teknologi

Sistem LENTERA dikembangkan menggunakan:
- **Framework Utama**: Laravel 11 (PHP 8.x)
- **Frontend**: Blade Templating, Tailwind CSS, Alpine.js
- **Database**: MySQL / MariaDB
- **PDF Generation**: `dompdf` untuk pembuatan dokumen otomatis

## Cara Instalasi / Menjalankan Secara Lokal

1. Lakukan *clone* repositori ini.
2. Jalankan perintah `composer install` untuk mengunduh seluruh *dependencies* Laravel.
3. Salin file konfigurasi env: `cp .env.example .env` dan sesuaikan pengaturan *database*.
4. Jalankan perintah `php artisan key:generate`.
5. Lakukan migrasi *database*: `php artisan migrate`
6. (Opsional) Jalankan *seeder* jika tersedia: `php artisan db:seed`
7. Tautkan *folder* penyimpanan publik (*storage link*): `php artisan storage:link`
8. Nyalakan server pengembangan lokal: `php artisan serve`
