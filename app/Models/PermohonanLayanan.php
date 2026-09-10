<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermohonanLayanan extends Model
{
    use HasFactory;

    protected $table = 'permohonan_layanans';

    protected $fillable = [
        'user_id',
        'jenis_layanan_id',
        'status_master_id',
        'jenis_permohonan',
        'atas_nama',
        'no_hp',
        'asal_instansi',
        'judul_kegiatan',
        'tempat_kegiatan',
        'tanggal_mulai',
        'tanggal_selesai',
        'keterangan',
        'file_ktp',
        'file_ktm',
        'file_surat_permohonan',
        'file_surat_pengantar',
        'file_surat_lokasi',
        'file_proposal',
        'file_surat_kesbangpol_jabar',
        'file_surat_kemendagri',
        'file_surat_rekomendasi_lama',
        'file_pendukung',
        'file_surat_keluaran',
        'status_revisi',
        'catatan_pemohon',
        'dokumen_direvisi',
        'tanggal_revisi',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_revisi' => 'datetime',
        'dokumen_direvisi' => 'array',
    ];

    public function isRevisiSelesai(): bool
    {
        if ($this->status_revisi === 'sudah_direvisi') {
            return true;
        }
        return optional($this->statusMaster)->kode === 'menunggu_verifikasi'
            && !empty($this->keterangan)
            && ($this->tanggal_revisi !== null || $this->updated_at > $this->created_at);
    }

    public function isMenungguRevisiUser(): bool
    {
        return $this->status_revisi === 'menunggu_user'
            || optional($this->statusMaster)->kode === 'perlu_revisi';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jenisLayanan(): BelongsTo
    {
        return $this->belongsTo(JenisLayanan::class);
    }

    public function statusMaster(): BelongsTo
    {
        return $this->belongsTo(StatusMaster::class);
    }
}
