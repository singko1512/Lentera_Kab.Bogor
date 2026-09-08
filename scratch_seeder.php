<?php

use App\Models\User;
use App\Models\Dinas;
use App\Models\Bidang;
use App\Models\Rekrutmen;
use App\Models\PermohonanLayanan;
use App\Models\MagangApplication;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

$dinas = Dinas::where('name', 'like', '%komunikasi%')->first() ?? Dinas::where('is_kesbangpol', false)->first();

if (!$dinas) {
    echo "No Dinas found.\n";
    exit;
}

// Create a Bidang for the Dinas if not exists
$bidang = Bidang::firstOrCreate([
    'dinas_id' => $dinas->id,
    'name' => 'Bidang Aplikasi Informatika'
]);

// Create a Rekrutmen for the Dinas if not exists
$rekrutmen = Rekrutmen::firstOrCreate(
    ['dinas_id' => $dinas->id, 'bidang_id' => $bidang->id],
    [
        'judul' => 'Magang Programmer',
        'deskripsi' => 'Dibutuhkan programmer web',
        'kuota' => 10,
        'tanggal_mulai' => now()->addDays(7),
        'tanggal_selesai' => now()->addDays(37),
        'status' => 'aktif'
    ]
);

for ($i = 1; $i <= 5; $i++) {
    $email = "dummy.peserta{$i}@example.com";
    $user = User::firstOrCreate(
        ['email' => $email],
        [
            'name' => "Peserta Magang {$i}",
            'password' => Hash::make('password'),
            'role' => 'peserta',
        ]
    );

    // Give some random asal_instansi to user if exists, but we know it's not on User, so nevermind

    // Create PermohonanLayanan
    $permohonan = PermohonanLayanan::firstOrCreate(
        ['user_id' => $user->id],
        [
            'jenis_layanan_id' => 1,
            'status_master_id' => 4,
            'nomor_tiket' => 'MAG-' . strtoupper(Str::random(6)),
            'atas_nama' => $user->name,
            'no_hp' => '08123456789',
            'asal_instansi' => 'Universitas Indonesia',
            'judul_kegiatan' => 'Magang Programmer',
            'tempat_kegiatan' => $dinas->name,
            'tanggal_mulai' => now()->addDays(7),
            'tanggal_selesai' => now()->addDays(37),
            'file_ktp' => 'dummy/ktp.pdf',
            'file_surat_pengantar' => 'dummy/pengantar.pdf',
            'file_proposal' => 'dummy/proposal.pdf'
        ]
    );

    // Create MagangApplication
    MagangApplication::firstOrCreate(
        ['user_id' => $user->id, 'rekrutmen_id' => $rekrutmen->id],
        [
            'permohonan_layanan_id' => $permohonan->id,
            'status' => 'menunggu',
            'tanggal_mulai' => now()->addDays(7),
            'tanggal_selesai' => now()->addDays(37),
            'pesan_lamaran' => 'Saya ingin magang di dinas ini.'
        ]
    );
}

echo "Berhasil membuat 5 data peserta dummy untuk Dinas: {$dinas->name}\n";
