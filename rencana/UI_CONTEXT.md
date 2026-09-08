# UI_CONTEXT.md
# Konteks Tampilan & Visual Project Baru

## 1. Tujuan Dokumen

Dokumen ini mendefinisikan aturan penggunaan tampilan untuk project baru.

Project baru dibangun dari 0 untuk backend dan alur bisnis, tetapi **tampilan/resource view yang sudah tersedia dari project sebelumnya tetap menjadi referensi utama visual**.

Tujuan utamanya:
- mempertahankan identitas visual yang sudah ada,
- menggunakan kembali layout dan komponen yang sudah tersedia,
- menghindari pembuatan desain baru tanpa kebutuhan,
- menyesuaikan tampilan dengan alur bisnis baru tanpa mengubah karakter visual utama.

---

# 2. Prinsip Utama UI

## 2.1 Existing View = Visual Source of Truth

Jika sebuah halaman atau komponen sudah tersedia pada resource view project sebelumnya, gunakan tampilan tersebut sebagai referensi utama.

Jangan mengganti desain hanya karena backend dibangun ulang.

Yang boleh berubah:
- data yang ditampilkan,
- sumber data,
- route/action,
- field form,
- status,
- tombol berdasarkan authorization,
- alur navigasi.

Yang sebisa mungkin dipertahankan:
- layout,
- struktur halaman,
- sidebar,
- navbar,
- card,
- tabel,
- modal,
- typography,
- spacing,
- icon,
- bentuk tombol,
- badge,
- alert,
- warna,
- responsive behavior.

---

# 3. Resource View yang Dipertahankan

Nama dan lokasi resource view yang sudah digunakan sebelumnya tetap dipertahankan jika masih relevan.

Area utama:

```text
resources/views/
├── landing/
├── layanan/
├── participant/
├── dinas/
├── partials/
└── layouts/
```

Resource view tersebut boleh diperbaiki atau disesuaikan dengan flow baru, tetapi **jangan mengganti nama resource hanya karena backend baru menggunakan nama controller/model yang berbeda**.

Nama controller dan model backend baru tidak harus mengikuti project lama.

---

# 4. Layout Utama

Gunakan layout yang sudah tersedia sebagai dasar visual.

Minimal terdapat pengalaman UI untuk:

```text
USER
 └── Layout Peserta

DINAS
 ├── UI Verifikator Kesbangpol
 └── UI Dinas Tujuan

ADMIN
 └── Layout Administrasi
```

Kesbangpol dan Dinas biasa dapat memakai fondasi layout Dinas yang sama, tetapi isi menu, judul, statistik, aksi, dan informasi harus menyesuaikan akun yang sedang login.

---

# 5. UI User

User membutuhkan alur yang mudah dipahami dari awal sampai selesai.

## Halaman utama

Menampilkan:
- daftar layanan,
- kategori layanan,
- deskripsi singkat,
- tombol memilih/mengajukan layanan.

## Pilih Layanan

Setiap layanan ditampilkan sebagai card/list yang jelas.

Contoh:

```text
[ Layanan A ]
Deskripsi singkat
[ Ajukan ]

[ Layanan B ]
Deskripsi singkat
[ Ajukan ]

[ Magang ]
Deskripsi singkat
[ Ajukan ]
```

Jangan membuat halaman baru jika komponen daftar layanan yang sudah tersedia masih dapat digunakan.

---

# 6. Form Layanan

Form setiap layanan dapat memiliki field berbeda.

Tampilan harus mengikuti form yang sudah tersedia.

Pertahankan:
- section form,
- label,
- input,
- select,
- upload,
- validation message,
- tombol submit,
- alert.

Backend baru boleh mengubah field atau validation sesuai kebutuhan bisnis, tetapi perubahan tersebut harus tercermin secara natural pada form.

---

# 7. Status Layanan User

User harus dapat memahami posisi permohonannya.

Gunakan visual status seperti:
- badge,
- timeline,
- alert,
- card status.

Contoh:

```text
Permohonan
   ↓
Menunggu Verifikasi
   ↓
Disetujui
   ↓
Siap Melanjutkan Pendaftaran Magang
```

Jika revisi:

```text
Perlu Revisi
↓
Catatan Kesbangpol
↓
[ Perbaiki Permohonan ]
```

Jika ditolak:

```text
Ditolak
Catatan:
...
```

---

# 8. Notifikasi User

Notifikasi harus terlihat sebagai bagian dari UI yang sudah ada.

Gunakan komponen navbar/sidebar/notification yang tersedia.

Notifikasi penting:
- layanan berhasil diajukan,
- layanan perlu direvisi,
- layanan ditolak,
- layanan disetujui,
- pendaftaran Magang sudah dapat dilakukan,
- hasil keputusan Dinas,
- perubahan penting lifecycle Magang.

Jangan membuat sistem notifikasi kedua jika komponen notifikasi yang sudah tersedia dapat digunakan.

---

# 9. Pendaftaran Magang

Halaman pendaftaran Magang harus terasa sebagai kelanjutan dari layanan Magang yang telah disetujui.

Flow UI:

```text
Layanan Magang Disetujui
          ↓
[ Lanjut Pendaftaran Magang ]
          ↓
Data Dasar
          ↓
Dinas Tujuan
          ↓
Bidang Tujuan
          ↓
Data Magang
          ↓
Dokumen
          ↓
[ Kirim Pendaftaran ]
```

Data yang sudah berasal dari permohonan layanan dapat ditampilkan kembali sebagai data terisi/terkunci jika aturan bisnis mengharuskannya.

---

# 10. UI Dinas

UI Dinas menggunakan layout/sidebar Dinas yang sudah tersedia.

Informasi utama dapat meliputi:
- dashboard,
- pengajuan,
- peserta,
- kuota,
- bidang,
- pembimbing,
- surat,
- laporan,
- profil.

Namun menu harus disesuaikan dengan fitur yang benar-benar sudah aktif.

Jangan menampilkan menu yang belum memiliki backend hanya demi melengkapi sidebar.

---

# 11. UI Kesbangpol

Kesbangpol tetap merupakan akun `dinas`.

Jika:

```text
Auth::user()->dinas->is_kesbangpol = true
```

maka UI Dinas berubah menjadi mode Verifikator Kesbangpol.

Yang dapat berbeda:
- judul dashboard,
- statistik,
- daftar permohonan,
- tombol keputusan,
- filter,
- informasi proses.

Contoh aksi:

```text
[ Setujui ]
[ Revisi ]
[ Tolak ]
```

UI tidak boleh membuat Kesbangpol terlihat sebagai role autentikasi terpisah.

---

# 12. UI Dinas Tujuan

Untuk akun Dinas biasa, UI fokus pada pengajuan Magang yang diarahkan ke instansi tersebut.

Contoh:

```text
Pengajuan Masuk

Nama Peserta
Asal Institusi
Bidang Tujuan
Periode
Status

[ Lihat Detail ]
```

Pada detail:

```text
[ Terima ]
[ Tolak ]
```

Dinas hanya melihat data yang menjadi tanggung jawab instansinya.

---

# 13. Detail Peserta

Halaman detail peserta harus menggunakan struktur visual yang sudah tersedia.

Informasi dapat dikelompokkan:

### Identitas
- Nama
- NIK/NIM/NIS
- Email
- Kontak
- Asal institusi

### Magang
- Dinas
- Bidang
- Periode
- Status

### Dokumen
- KTP
- Proposal
- Surat Pengantar
- Dokumen relevan lainnya

### Penempatan
- Bidang
- Pembimbing

### Aktivitas
- Absensi
- Jurnal
- Penilaian

Gunakan card/section yang sudah tersedia daripada membuat desain baru.

---

# 14. Dashboard

Dashboard setiap role harus menggunakan bahasa visual yang konsisten.

## User

Fokus pada:
- status layanan,
- notifikasi,
- status pendaftaran Magang,
- aktivitas Magang.

## Kesbangpol

Fokus pada:
- jumlah permohonan,
- menunggu verifikasi,
- perlu revisi,
- selesai diverifikasi.

## Dinas Tujuan

Fokus pada:
- pengajuan masuk,
- peserta diterima,
- peserta aktif,
- kapasitas/kuota,
- peserta selesai.

## Admin

Fokus pada:
- master data,
- pengguna,
- Dinas,
- Bidang,
- konfigurasi sistem,
- laporan.

---

# 15. Sidebar

Sidebar menggunakan struktur yang sudah ada.

Aturan:
- menu aktif harus menunjukkan halaman saat ini,
- menu hanya muncul jika fitur tersedia untuk role/instansi,
- Kesbangpol dan Dinas biasa dapat memiliki menu berbeda walaupun role sama,
- tombol profil menggunakan akun aktif,
- logout tetap berada pada area navigasi yang konsisten.

Jangan membuat sidebar kedua jika sidebar existing masih dapat digunakan.

---

# 16. Navbar

Navbar mempertahankan struktur visual yang sudah ada.

Minimal:
- branding,
- navigasi utama bila ada,
- notifikasi,
- identitas user,
- akses profile,
- logout.

Informasi user harus berasal dari sesi autentikasi.

---

# 17. Form UX

Semua form baru harus mengikuti pola form existing.

Wajib:
- label jelas,
- required indicator jika diperlukan,
- validation error di dekat field,
- error umum jika diperlukan,
- disabled state saat submit jika tersedia,
- CSRF,
- tombol kembali/cancel,
- konfirmasi untuk aksi destruktif.

Jangan menggunakan modal besar jika form existing menggunakan halaman penuh, dan sebaliknya, kecuali ada alasan UX yang jelas.

---

# 18. Status Badge

Gunakan pola badge existing.

Status harus mudah dibedakan secara visual.

Contoh kategori:
- menunggu,
- revisi,
- disetujui,
- ditolak,
- diterima,
- aktif,
- selesai.

Warna/status visual harus mengikuti design system existing jika sudah tersedia.

---

# 19. Tabel

Untuk halaman daftar data:
- gunakan tabel existing,
- pertahankan header/spacing,
- gunakan pagination jika dataset besar,
- gunakan filter bila diperlukan,
- jangan menampilkan seluruh database tanpa kebutuhan.

Kolom tabel harus mengikuti kebutuhan role.

---

# 20. Responsive Design

Tampilan harus tetap usable pada:
- desktop,
- laptop,
- tablet,
- mobile.

Jangan mengorbankan responsive behavior existing hanya karena perubahan backend.

---

# 21. Prinsip Perubahan UI

Setiap perubahan UI harus menjawab salah satu dari tiga alasan:

1. Mendukung flow bisnis baru.
2. Menampilkan data baru yang diperlukan.
3. Memperbaiki usability yang memang dibutuhkan.

Hindari:
- redesign total,
- mengganti framework CSS tanpa kebutuhan,
- mengganti layout tanpa alasan,
- membuat style system baru yang bertabrakan dengan existing design.

---

# 22. Hubungan UI dengan Authorization

UI boleh menyembunyikan tombol yang tidak relevan, tetapi security tetap dilakukan backend.

Contoh:

```text
Kesbangpol:
[ Setujui ] [ Revisi ] [ Tolak ]

Dinas Tujuan:
[ Terima ] [ Tolak ]
```

Namun backend tetap harus memvalidasi bahwa akun memang memiliki hak atas aksi tersebut.

UI bukan sumber authorization.

---

# 23. Prinsip Visual untuk Project Baru

Project baru harus terasa seperti **versi baru dari aplikasi yang sama secara visual**, tetapi memiliki backend dan flow bisnis yang lebih bersih.

Target:

```text
VISUAL
Existing Project
      ↓
dipertahankan

BUSINESS FLOW
Arsitektur Baru
      ↓
digunakan

BACKEND
Project Baru
      ↓
dibangun dari 0
```

Dengan demikian user lama tetap mengenali tampilannya, sementara sistem baru tidak terikat pada struktur backend lama.

---

# 24. Definition of Done UI

UI dianggap siap apabila:

- User dapat memilih layanan dengan tampilan yang konsisten.
- User dapat mengisi dan mengirim form.
- User dapat melihat status.
- User menerima dan melihat notifikasi.
- User dapat melanjutkan ke pendaftaran Magang setelah syarat terpenuhi.
- User dapat memilih Dinas dan Bidang.
- Kesbangpol mendapatkan UI verifikasi yang sesuai akun.
- Dinas tujuan mendapatkan UI pengelolaan peserta yang sesuai akun.
- Admin mendapatkan UI administrasi.
- Detail peserta dapat menampilkan lifecycle secara jelas.
- Tampilan menggunakan resource view yang dipertahankan.
- Tidak ada redesign besar yang tidak diperlukan.

---

# 25. Rule Paling Penting

**Jangan membangun ulang visual jika visual yang dibutuhkan sudah tersedia.**

Gunakan resource view existing sebagai referensi desain.

Yang dibangun ulang adalah **sistem dan alur**, bukan identitas visual aplikasinya.
