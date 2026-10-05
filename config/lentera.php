<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Batas Maksimum Upload (dalam KB)
    |--------------------------------------------------------------------------
    */
    'max_upload_size' => 2048,

    /*
    |--------------------------------------------------------------------------
    | Default Kuota Peserta Magang
    |--------------------------------------------------------------------------
    | Digunakan jika tidak ada kuota yang ditentukan untuk suatu entitas
    */
    'default_quota' => 10,

    /*
    |--------------------------------------------------------------------------
    | Masa Berlaku Surat Rekomendasi & Pengajuan (dalam Hari)
    |--------------------------------------------------------------------------
    */
    'masa_berlaku_surat_hari' => (int) env('LENTERA_MASA_BERLAKU_SURAT_HARI', 30),
    'masa_berlaku_pengajuan_hari' => (int) env('LENTERA_MASA_BERLAKU_PENGAJUAN_HARI', 30),

    /*
    |--------------------------------------------------------------------------
    | Status yang Menghitung / Memotong Kuota Magang
    |--------------------------------------------------------------------------
    */
    'status_memakai_kuota' => ['menunggu', 'diterima', 'aktif'],

    /*
    |--------------------------------------------------------------------------
    | Batas Waktu Token Reset Password (dalam Menit)
    |--------------------------------------------------------------------------
    */
    'reset_token_ttl_minutes' => (int) env('RESET_TOKEN_TTL_MINUTES', 60),

    /*
    |--------------------------------------------------------------------------
    | Default Pejabat Penandatangan Kesbangpol
    |--------------------------------------------------------------------------
    | Digunakan jika data kepala dinas di tabel dinas Kesbangpol belum diatur
    */
    'pejabat_kesbangpol' => [
        'nama' => env('KESBANGPOL_PEJABAT_NAMA', 'FERDINANDO SELMI PARDEDE, S.IP, M.AP'),
        'nip' => env('KESBANGPOL_PEJABAT_NIP', '196805121990031005'),
        'pangkat' => env('KESBANGPOL_PEJABAT_PANGKAT', 'Pembina Tk. I'),
        'jabatan' => env('KESBANGPOL_PEJABAT_JABATAN', 'KEPALA BADAN KESATUAN BANGSA DAN POLITIK KABUPATEN BOGOR'),
    ],
];
