<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplateSurat extends Model
{
    use HasFactory;

    protected $table = 'template_surats';

    protected $fillable = [
        'jenis_layanan_id',
        'nama',
        'slug',
        'file_nama',
        'file_path',
        'nomor_prefix',
        'sifat_surat',
        'lampiran_surat',
        'perihal',
        'pejabat_nama',
        'pejabat_nip',
        'pejabat_pangkat',
        'pejabat_jabatan',
        'keterangan',
        'konten_html',
        'dasar_hukum',
        'ketentuan',
        'tembusan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'dasar_hukum' => 'array',
        'ketentuan' => 'array',
        'tembusan' => 'array',
    ];

    public function jenisLayanan(): BelongsTo
    {
        return $this->belongsTo(JenisLayanan::class, 'jenis_layanan_id');
    }

    /**
     * Dapatkan path absolut fisik file template
     */
    public function getAbsolutePathAttribute(): string
    {
        if (file_exists($this->file_path)) {
            return $this->file_path;
        }

        $basePath = base_path($this->file_path);
        if (file_exists($basePath)) {
            return $basePath;
        }

        $storagePath = storage_path('app/public/' . $this->file_path);
        if (file_exists($storagePath)) {
            return $storagePath;
        }

        return base_path('template_surat/' . $this->file_nama);
    }
}
