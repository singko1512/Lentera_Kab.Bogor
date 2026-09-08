# PLAN.md
# Implementation Plan Project Baru

## A. Pendekatan
Project dibangun dari 0 berdasarkan domain bisnis baru. Resource view lama hanya dipakai kembali untuk menjaga tampilan yang sudah dikenal.

Backend, model, migration, controller/service, route, authorization, dan status lifecycle dirancang berdasarkan kebutuhan baru, bukan berdasarkan nama class dari project lama.

## B. Phase 0: Foundation
- Siapkan Laravel structure.
- Konfigurasi database.
- Siapkan autentikasi.
- Definisikan role: `user`, `dinas`, `admin`.
- Siapkan relasi User → Dinas.
- Siapkan penanda instansi Kesbangpol pada entitas Dinas.
- Siapkan konfigurasi batas sistem.
- Pastikan aturan numerik tidak bergantung pada enum.

Output:
- Login berjalan.
- Role berjalan.
- Session identity aman.
- Struktur fondasi siap.

## C. Phase 1: Layanan Publik
- Master jenis layanan.
- Form dinamis per layanan.
- Penyimpanan permohonan.
- Penyimpanan dokumen.
- Status awal permohonan.
- Halaman user untuk melihat status permohonan.

Output:
User dapat memilih dan mengirim layanan.

## D. Phase 2: Verifikasi Kesbangpol
- Dashboard khusus akun Dinas yang merupakan Kesbangpol.
- Daftar permohonan menunggu verifikasi.
- Detail permohonan.
- Aksi setujui, revisi, dan tolak.
- Validasi transition status.
- Authorization berdasarkan sesi.
- Tidak membuat role Kesbangpol baru.

Output:
Kesbangpol dapat menjadi gerbang verifikasi layanan.

## E. Phase 3: Notifikasi + Bridge Magang
- Integrasikan notification system.
- Notifikasi setiap keputusan.
- Setelah layanan Magang disetujui, user memperoleh akses pendaftaran Magang.
- Validasi backend memastikan hanya user dengan permohonan Magang yang disetujui yang dapat melanjutkan.

Output:
Alur Kesbangpol → User → Pendaftaran Magang tersambung.

## F. Phase 4: Pendaftaran Magang
- Form pendaftaran Magang.
- Ambil data yang relevan dari permohonan layanan yang telah disetujui.
- Pilih Dinas.
- Pilih Bidang berdasarkan Dinas.
- Simpan relasi permohonan layanan.
- Simpan data pendaftaran.
- Validasi ownership dan input.

Output:
User menghasilkan satu pendaftaran Magang yang valid.

## G. Phase 5: Verifikasi Dinas Tujuan
- Dashboard Dinas berdasarkan `dinas_id` sesi.
- Kesbangpol tidak diperlakukan sebagai Dinas tujuan biasa bila flow memerlukan fungsi verifikator.
- Dinas tujuan hanya melihat pengajuan miliknya.
- Aksi terima/tolak.
- Transactional status update.
- Authorization backend.

Output:
Dinas tujuan dapat mengambil keputusan dengan isolasi antar-Dinas.

## H. Phase 6: Penempatan & Pembimbing
- Penempatan peserta ke Bidang.
- Pengelolaan Pembimbing.
- Assignment peserta → Bidang → Pembimbing.
- Validasi bahwa Bidang dan Pembimbing berada pada Dinas yang benar.
- UI menggunakan resource view Dinas yang telah dipertahankan.

Output:
Peserta diterima memiliki penempatan operasional yang jelas.

## I. Phase 7: Kuota & Concurrency
- Tentukan sumber kuota.
- Tentukan kapasitas per Dinas/Bidang sesuai kebutuhan bisnis.
- Letakkan batas numerik dinamis pada config/database.
- Gunakan transaction + locking untuk operasi yang memengaruhi kapasitas.
- Cegah overbooking.
- Uji concurrent assignment dan perubahan kuota.

Output:
Kapasitas konsisten meskipun terdapat request bersamaan.

## J. Phase 8: Absensi & Jurnal
- Dashboard peserta aktif.
- Absensi harian.
- Jurnal/kegiatan.
- Riwayat.
- Validasi tanggal dan kepemilikan.
- Integrasi dengan lifecycle peserta.

Output:
Pelaksanaan magang tercatat dalam satu alur.

## K. Phase 9: Penyelesaian, Penilaian, Sertifikat
- Transition aktif → selesai.
- Penilaian pembimbing/Dinas.
- Rekap hasil.
- Generate/kelola sertifikat.
- Validasi bahwa peserta memenuhi syarat penyelesaian.

Output:
Lifecycle peserta selesai secara utuh.

## L. Phase 10: Laporan & Final Verification
- Rekap peserta.
- Filter berdasarkan Dinas/Bidang/status/periode.
- Export.
- Penyempurnaan UI.
- Authorization review.
- End-to-end smoke test.
- Regression test.

## M. Aturan Teknis Global
- Tidak menggunakan enum untuk status/batas yang perlu fleksibel.
- Status bisnis disimpan sebagai data master/reference yang dapat dikembangkan.
- Batas numerik yang bersifat konfigurasi ditempatkan di `config`.
- Authorization selalu backend-first.
- Session adalah sumber identitas user dan Dinas.
- Jangan menerima ownership dari hidden input sebagai sumber kebenaran.
- Transaction digunakan untuk perubahan state penting.
- Locking digunakan ketika operasi dapat menyebabkan race condition.
- Setiap phase berhenti setelah verifikasi sampai phase berikutnya disetujui.

## N. Urutan End-to-End
Layanan → Verifikasi Kesbangpol → Notifikasi → Pendaftaran Magang → Dinas/Bidang → Verifikasi Dinas → Penempatan → Pembimbing → Aktif → Absensi/Jurnal → Penilaian → Selesai → Sertifikat.
