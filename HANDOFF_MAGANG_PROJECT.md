# Handoff Session - Project Magang (Simalamn / Lentera)

## Status Terakhir (Update: Sistem Registrasi & Dashboard Peserta)
Pengerjaan sistem magang terbaru telah mencapai perbaikan logika pendaftaran (Registrasi) peserta serta penyempurnaan UI Dasbor Peserta:
1. **Fitur Registrasi Peserta (Baru):** Fitur register yang tadinya hanya "dummy", kini sudah berfungsi sepenuhnya melalui `AuthController@register`. Data biodata peserta (seperti NIK, asal instansi, NIM, program studi, dsb) kini akan tersimpan langsung ke dalam tabel `users`.
2. **Penambahan Kolom Database:** Sudah dijalankan _migration_ `2026_08_30_165859_add_biodata_to_users_table.php` yang menambahkan kolom biodata ke tabel `users`. Kolom juga telah didaftarkan di dalam `$fillable` model `App\Models\User`.
3. **Penyempurnaan Dasbor Peserta:** Halaman dasbor peserta (`resources/views/pelayanan/peserta/dashboard.blade.php` dan `DashboardController`) sudah disesuaikan agar mampu menangani peserta baru yang **belum** memiliki program magang aktif. Mereka tidak lagi dilemparkan ke beranda (_error redirect_), melainkan akan melihat informasi *"Anda belum memiliki program magang yang aktif saat ini"* dengan tombol *"Cari Program Magang"*. Sementara fitur absensi dan jurnal akan disembunyikan hingga mereka diterima di sebuah rekrutmen magang.
4. **Git Backup:** Seluruh pengerjaan hari ini (termasuk folder/script _scratch_) sudah di-abaikan lewat `.gitignore` yang diperbarui, lalu _commit_ dan _push_ telah dilakukan ke _branch_ baru yang dinamakan **`backup`**.

## Kondisi Model & Database Saat Ini
- **Tabel `users`:** Sekarang bertindak ganda sebagai tabel penyimpanan akun sekaligus penyimpanan biodata peserta magang (menyimpan NIK, no_hp, dll).
- **Auth:** Menggunakan `Auth::attempt` dengan multi-role (`isKesbangpol`, `dinas`, `bidang`, dan `peserta`).
- **Route Prefix:** Rute Kesbangpol memakai `kesbangpol.`, rute Dinas memakai `dinas.`, rute Bidang memakai `bidang.`.
- **Naming View/Layout:** Selalu gunakan *prefix* `pelayanan.` (misal: `pelayanan.layouts.dinas_stitch` atau `pelayanan.layouts.kesbangpol_stitch`).

## Next Steps (Tugas Selanjutnya)
- [ ] Menyelesaikan alur proses *Apply* Magang (Pendaftaran magang) setelah peserta baru mendaftar (Pastikan rute dan *form* pengajuan lamaran menyimpan data ke tabel `magang_applications` dengan status `menunggu`).
- [ ] Melanjutkan integrasi antarmuka Verifikasi Permohonan Magang secara penuh oleh Admin Kesbangpol maupun Kepala Dinas.
- [ ] Memastikan fitur "Manajemen Peserta" di Kesbangpol dan Dinas terhubung penuh dengan status lamaran terbaru.

## Instruksi untuk Agent Selanjutnya
- **BACA FILE INI** sebelum melanjutkan pengerjaan proyek agar mengerti konteks _database_ saat ini.
- Ingat bahwa seluruh progress saat ini tersimpan di _branch_ `backup` (atau _main_ jika di-_merge_ nantinya).
- Jika berurusan dengan akun baru yang mendaftar secara mandiri, ingat bahwa data mereka masuk ke tabel `users` dengan _role_ `peserta`.
- Gunakan perintah `php artisan view:clear` setiap kali merombak struktur *layout* untuk mencegah tampilan tidak ter-update.
