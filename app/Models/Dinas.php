<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dinas extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_kesbangpol',
        'deskripsi',
        'alamat',
        'email',
        'telepon',
        'logo',
        'status_magang',
        'nama_kepala',
        'nip_kepala',
        'kop_surat',
    ];

    protected $casts = [
        'is_kesbangpol' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function bidang(): HasMany
    {
        return $this->hasMany(Bidang::class);
    }

    public function rekrutmens(): HasMany
    {
        return $this->hasMany(Rekrutmen::class);
    }

    public function getComputedStatusAttribute()
    {
        $setting = $this->status_magang ?? 'otomatis';
        if ($setting !== 'otomatis' && !empty($setting)) {
            return $setting;
        }

        $activeRekrutmens = $this->rekrutmens->where('is_active', true);
        if ($activeRekrutmens->count() === 0) {
            return 'tidak_tersedia';
        }

        $slotTersedia = $activeRekrutmens->sum(function ($r) {
            return $r->slot_tersedia;
        });

        if ($slotTersedia > 0) {
            return 'tersedia';
        }

        return 'penuh';
    }

    public function getStatusBadgeAttribute()
    {
        $status = $this->computed_status;

        if ($status === 'tersedia') {
            return [
                'key' => 'tersedia',
                'label' => 'KUOTA TERSEDIA',
                'bg_class' => 'bg-[#E8F5E9] text-[#2E7D32] border border-[#C8E6C9]',
                'icon' => 'check_circle',
            ];
        } elseif ($status === 'penuh') {
            return [
                'key' => 'penuh',
                'label' => 'KUOTA PENUH',
                'bg_class' => 'bg-[#FFEBEE] text-[#C62828] border border-[#FFCDD2]',
                'icon' => 'cancel',
            ];
        } else {
            return [
                'key' => 'tidak_tersedia',
                'label' => 'TIDAK TERSEDIA',
                'bg_class' => 'bg-surface-variant text-on-surface-variant border border-outline-variant/50',
                'icon' => 'block',
            ];
        }
    }

    public static function formatSingkatan(?string $name): string
    {
        if (empty($name)) {
            return '-';
        }

        $nameUpper = strtoupper(trim($name));
        $custom = [
            'BADAN KESATUAN BANGSA DAN POLITIK' => 'Bakesbangpol',
            'DINAS KOMUNIKASI DAN INFORMATIKA' => 'Diskominfo',
            'BADAN PERENCANAAN PEMBANGUNAN DAERAH, PENELITIAN DAN PENGEMBANGAN' => 'Bappedalitbang',
            'BADAN PERENCANAAN PEMBANGUNAN, RISET DAN INOVASI DAERAH' => 'Bapperida',
            'BADAN PENGELOLAAN PENDAPATAN DAERAH' => 'Bappenda',
            'BADAN KEPEGAWAIAN DAN PENGEMBANGAN SUMBER DAYA MANUSIA' => 'BKPSDM',
            'BADAN PENGELOLAAN KEUANGAN DAN ASET DAERAH' => 'BPKAD',
            'BADAN PENANGGULANGAN BENCANA DAERAH' => 'BPBD',
            'BADAN PENANGGGULANGAN BENCANA DAERAH' => 'BPBD',
            'DINAS PENDIDIKAN' => 'Disdik',
            'DINAS KESEHATAN' => 'Dinkes',
            'DINAS PEKERJAAN UMUM' => 'DPUPR',
            'DINAS PEKERJAAN UMUM DAN PENATAAN RUANG' => 'DPUPR',
            'DINAS PERUMAHAN DAN KAWASAN PERMUKIMAN' => 'DPKPP',
            'DINAS PERUMAHAN, KAWASAN PERMUKIMAN DAN PERTANAHAN' => 'DPKPP',
            'DINAS PERTANAHAN DAN TATA RUANG' => 'DPTR',
            'DINAS SOSIAL' => 'Dinsos',
            'DINAS TENAGA KERJA' => 'Disnaker',
            'DINAS PEMBERDAYAAN PEREMPUAN DAN PERLINDUNGAN ANAK, PENGENDALIAN PENDUDUK DAN KB' => 'DP3AP2KB',
            'DINAS PEMBERDAYAAN PEREMPUAN DAN PERLINDUNGAN ANAK, PENGENDALIAN PENDUDUK DAN KELUARGA BERENCANA' => 'DP3AP2KB',
            'DINAS KETAHANAN PANGAN' => 'DKP',
            'DINAS LINGKUNGAN HIDUP' => 'DLH',
            'DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL' => 'Disdukcapil',
            'DINAS PEMBERDAYAAN MASYARAKAT DAN DESA' => 'DPMD',
            'DINAS PERHUBUNGAN' => 'Dishub',
            'DINAS KOPERASI, USAHA KECIL DAN MENENGAH' => 'Diskop UKM',
            'DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU' => 'DPMPTSP',
            'DINAS PEMUDA DAN OLAH RAGA' => 'Dispora',
            'DINAS KEPEMUDAAN DAN OLAHRAGA' => 'Dispora',
            'DINAS PARIWISATA DAN EKONOMI KREATIF' => 'Disbudpar',
            'DINAS KEBUDAYAAN DAN PARIWISATA' => 'Disbudpar',
            'DINAS ARSIP DAN PERPUSTAKAAN' => 'Dispusip',
            'DINAS PERPUSTAKAAN DAN KEARSIPAN' => 'Dispusip',
            'DINAS PERIKANAN DAN PETERNAKAN' => 'Diskannak',
            'DINAS TANAMAN PANGAN, HORTIKULTURA DAN PERKEBUNAN' => 'Distanhorbun',
            'DINAS PERDAGANGAN DAN PERINDUSTRIAN' => 'Disdagin',
            'DINAS PEMADAM KEBAKARAN' => 'Damkar',
            'DINAS PEMADAM KEBAKARAN DAN PENYELAMATAN' => 'Damkar',
            'SATUAN POLISI PAMONG PRAJA' => 'Satpol PP',
            'INSPEKTORAT' => 'Inspektorat',
            'INSPEKTORAT DAERAH' => 'Inspektorat',
            'SEKRETARIAT DAERAH' => 'Setda',
            'SEKRETARIAT DPRD' => 'Setwan',
        ];

        if (isset($custom[$nameUpper])) {
            return $custom[$nameUpper];
        }

        if (str_starts_with($nameUpper, 'KECAMATAN ')) {
            return 'Kec. ' . ucwords(strtolower(trim(str_replace('KECAMATAN ', '', $nameUpper))));
        }

        if (str_starts_with($nameUpper, 'RUMAH SAKIT UMUM DAERAH ')) {
            return 'RSUD ' . ucwords(strtolower(trim(str_replace('RUMAH SAKIT UMUM DAERAH ', '', $nameUpper))));
        }

        return ucwords(strtolower($nameUpper));
    }

    public function getSingkatanAttribute(): string
    {
        return self::formatSingkatan($this->name ?? $this->nama ?? '');
    }
}
