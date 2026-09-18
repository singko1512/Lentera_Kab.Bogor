<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratRekomendasi extends Model
{
    protected $fillable = [
        'permohonan_layanan_id',
        'nomor_surat',
        'tanggal_surat',
        'sifat_surat',
        'lampiran_surat',
        'pejabat_nama',
        'pejabat_nip',
        'pejabat_pangkat',
        'pejabat_jabatan',
        'tembusan'
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tembusan' => 'array',
    ];

    public function permohonanLayanan()
    {
        return $this->belongsTo(PermohonanLayanan::class);
    }
}
