# KONTEKS.md
# Konteks Arsitektur Project Baru

## 1. Gambaran Besar
Sistem adalah platform layanan administratif dan pengelolaan magang.

Konsep utamanya adalah dua lapisan proses:
1. Permohonan layanan administratif.
2. Pendaftaran dan lifecycle Magang.

Kesbangpol berfungsi sebagai instansi verifikator layanan dan tetap menggunakan akun/role Dinas.

## 2. Role
### User
Pengguna/peserta yang:
- memilih layanan,
- mengajukan layanan,
- menerima notifikasi,
- melanjutkan pendaftaran Magang jika layanan Magang disetujui,
- memilih Dinas dan Bidang,
- mengikuti proses Magang,
- mengisi absensi dan jurnal,
- melihat hasil akhir.

### Dinas
Akun operasional masing-masing instansi.

Setiap akun Dinas memiliki konteks instansinya sendiri melalui session/authentication.

Kesbangpol juga berada di role ini. Perbedaannya ditentukan oleh data instansi, bukan role baru.

Contoh:
- Akun Dinas Kesbangpol → UI verifikasi Kesbangpol.
- Akun Dinas Diskominfo → UI pengelolaan peserta Diskominfo.

### Admin
Pengelola sistem dan master data.

## 3. Kesbangpol
Kesbangpol bukan role independen.

Model konseptual:
`User(role=dinas) → Dinas(is_kesbangpol=true)`

Dengan demikian:
- autentikasi tetap sederhana,
- setiap instansi memiliki akun sendiri,
- UI dapat berbeda berdasarkan sesi,
- authorization tetap berbasis `dinas_id`,
- fungsi verifikator dapat dibedakan berdasarkan atribut instansi.

## 4. Alur Layanan
```text
User
 ↓
Daftar / Login
 ↓
Pilih Layanan
 ↓
Isi Form Layanan
 ↓
Submit
 ↓
Kesbangpol
 ↓
┌───────────────┬──────────────┬───────────────┐
│ Revisi        │ Tolak        │ Setujui       │
└───────┬───────┴──────┬───────┴───────┬───────┘
        ↓               ↓               ↓
      User           Selesai        Notifikasi
    perbaiki                           ↓
                                   Jika Magang
                                       ↓
                                Daftar Magang
                                       ↓
                                  Pilih Dinas
                                       ↓
                                  Pilih Bidang
                                       ↓
                              Verifikasi Dinas
                                       ↓
                                Terima / Tolak
                                       ↓
                                  Penempatan
                                       ↓
                                  Pembimbing
                                       ↓
                                     Aktif
                                       ↓
                                Absensi + Jurnal
                                       ↓
                                   Penilaian
                                       ↓
                                    Selesai
                                       ↓
                                   Sertifikat
```

## 5. Pemisahan Domain
### Permohonan Layanan
Menyimpan:
- siapa pemohon,
- jenis layanan,
- data formulir,
- dokumen,
- status verifikasi Kesbangpol,
- catatan/revisi,
- waktu proses.

### Pendaftaran Magang
Menyimpan:
- pemohon,
- referensi permohonan layanan Magang,
- Dinas tujuan,
- Bidang tujuan,
- data Magang,
- status lifecycle,
- penempatan,
- pembimbing.

Kedua domain berhubungan, tetapi tidak dicampur menjadi satu proses.

## 6. Status
Status harus mendukung lifecycle tanpa mengunci sistem pada enum PHP.

Contoh lifecycle layanan:
- menunggu verifikasi,
- perlu revisi,
- disetujui,
- ditolak,
- selesai.

Contoh lifecycle Magang:
- menunggu keputusan Dinas,
- diterima,
- ditolak,
- aktif,
- selesai.

Status sebaiknya menggunakan master/reference data sehingga dapat diperluas.

## 7. Dinas Isolation
Untuk akun Dinas:
- `Auth::user()` menjadi sumber identitas.
- `dinas_id` sesi menjadi batas data.
- Query harus mengikat data ke Dinas yang login.
- ID dari request tidak boleh menentukan kepemilikan.

Contoh prinsip:
```text
Auth user
   ↓
dinas_id
   ↓
scope seluruh data operasional
```

## 8. Kesbangpol Isolation
Kesbangpol mempunyai akses verifikasi layanan sesuai fungsi instansinya.

Ketika suatu layanan sudah masuk tahap pendaftaran Magang, Dinas tujuan hanya melihat pendaftaran yang diarahkan kepadanya.

Kesbangpol tidak otomatis memiliki akses seluruh data operasional Dinas lain hanya karena ia merupakan verifikator awal.

## 9. UI dan Resource View
Tampilan lama akan digunakan kembali.

Resource view yang dipertahankan namanya antara lain:
- `landing`
- `layanan`
- `participant`
- `dinas`
- `partials`
- `layouts`

Yang dipertahankan adalah **nama/lokasi resource view dan bahasa visualnya**.

Nama controller/model dari project lama tidak menjadi kontrak arsitektur baru.

Backend baru boleh memiliki nama class dan struktur folder sendiri selama konsisten dengan domain.

## 10. Form Layanan
Sistem mendukung beberapa jenis layanan administratif.

Form dapat berbeda sesuai jenis layanan.

Untuk layanan Magang, persetujuan Kesbangpol menjadi prasyarat untuk membuka tahap pendaftaran Magang.

## 11. Notifikasi
Notifikasi bukan sekadar kosmetik UI. Ia menjadi bagian dari flow bisnis.

Contoh:
```text
Kesbangpol setujui
       ↓
Notification
       ↓
User melihat status
       ↓
User membuka Pendaftaran Magang
```

## 12. Kuota
Kuota harus dirancang sebagai aturan data/configurable.

Jangan menanam batas seperti `10`, `20`, atau nilai minimum lain langsung di controller apabila nilai tersebut merupakan business rule yang dapat berubah.

Gunakan:
- `config/*.php` untuk aturan konfigurasi aplikasi,
- database/master data untuk kapasitas yang berbeda antar-entitas.

## 13. Security
Wajib:
- CSRF pada form.
- Authorization backend.
- Ownership validation.
- Session-based identity.
- Validation input.
- Password hashing.
- Transaction untuk perubahan state penting.
- Locking untuk operasi kapasitas yang rentan race condition.

## 14. Tampilan Akun Dinas
Satu fondasi layout dapat digunakan, tetapi isi UI ditentukan oleh akun.

```text
role = dinas
      ↓
Auth::user()->dinas
      ↓
is_kesbangpol?
   ├── true  → UI Verifikator Kesbangpol
   └── false → UI Dinas Tujuan
```

Dengan cara ini satu sistem dapat memiliki pengalaman berbeda untuk setiap instansi tanpa membuat role baru untuk setiap Dinas.

## 15. Prinsip Pengembangan
Project baru harus dibangun secara bertahap.

Jangan:
- mengaudit project lama sebagai pekerjaan utama,
- menghidupkan kembali arsitektur lama,
- menjadikan class lama sebagai dependency,
- membuat role Kesbangpol terpisah,
- mengandalkan hidden input sebagai authorization,
- hardcode business limit,
- mencampur permohonan layanan dengan lifecycle Magang.

Gunakan project lama hanya sebagai:
- referensi visual,
- referensi komponen UI,
- referensi pola interaksi yang masih relevan.

## 16. Target Akhir
Target akhir bukan sekadar dashboard yang terlihat bagus.

Targetnya adalah flow yang benar-benar dapat berjalan:

**User pilih layanan → submit → Kesbangpol verifikasi → user mendapat notifikasi → user mendaftar Magang → pilih Dinas → pilih Bidang → Dinas tujuan verifikasi → diterima → penempatan/pembimbing → aktif → absensi/jurnal → penilaian → selesai → sertifikat.**
