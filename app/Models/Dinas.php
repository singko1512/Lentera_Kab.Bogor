<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Dinas extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nama',
        'is_kesbangpol',
        'deskripsi',
        'alamat',
        'email',
        'telepon',
        'logo',
        'status_magang',
        'masa_berlaku_hari',
        'nama_kepala',
        'nip_kepala',
        'kop_surat',
    ];

    public function getNamaAttribute(): ?string
    {
        return $this->attributes['name'] ?? null;
    }

    public function setNamaAttribute($value): void
    {
        $this->attributes['name'] = $value;
    }

    public function getDeskripsiAttribute(?string $value): ?string
    {
        if (!empty($value)) {
            return self::formatTitleCaseKbbi($value);
        }

        $namaInstansi = self::formatTitleCaseKbbi($this->name ?? $this->nama ?? '');
        return !empty($namaInstansi)
            ? 'Fasilitas pelayanan, riset, dan magang di ' . $namaInstansi . '.'
            : null;
    }

    public function setDeskripsiAttribute($value): void
    {
        $this->attributes['deskripsi'] = !empty($value) ? self::formatTitleCaseKbbi(trim($value)) : null;
    }

    /**
     * Format teks ke Title Case sesuai kaidah KBBI/PUEBI:
     * - Huruf pertama tiap kata kapital (Title Case), bukan all-caps.
     * - Kata tugas/hubung/depan (dan, di, ke, dari, pada, untuk, dll) tetap huruf kecil.
     * - Mempertahankan singkatan resmi (RSUD, BKPSDM, BPBD, dll).
     */
    public static function formatTitleCaseKbbi(?string $text): string
    {
        if (empty($text)) {
            return '';
        }

        $kataTugas = [
            'dan', 'atau', 'serta', 'di', 'ke', 'dari', 'pada', 'dalam',
            'untuk', 'dengan', 'oleh', 'tentang', 'terhadap', 'bagi',
            'antara', 'sampai', 'seperti', 'tanpa', 'guna', 'yang', 'atas',
            'pun', 'per', 'sebagai'
        ];

        $acronyms = [
            'RSUD', 'UPTD', 'BKPSDM', 'BPBD', 'BPKAD', 'DPUPR', 'DPKPP',
            'DPTR', 'DP3AP2KB', 'DKP', 'DLH', 'DPMD', 'DPMPTSP', 'UKM',
            'DPRD', 'SETDA', 'SETWAN', 'TIK', 'KB', 'SD', 'SMP', 'SMA', 'SMK'
        ];

        $parts = preg_split('/(\s+)/u', trim($text), -1, PREG_SPLIT_DELIM_CAPTURE);
        $result = '';
        $wordIndex = 0;

        foreach ($parts as $part) {
            if (preg_match('/^\s+$/u', $part)) {
                $result .= $part;
                continue;
            }

            preg_match('/^([^\p{L}\p{N}]*)([\p{L}\p{N}\-]+)([^\p{L}\p{N}]*)$/u', $part, $matches);
            if (!empty($matches)) {
                $prefix = $matches[1];
                $word = $matches[2];
                $suffix = $matches[3];

                $wordUpper = mb_strtoupper($word);
                $wordLower = mb_strtolower($word);

                if (in_array($wordUpper, $acronyms)) {
                    $w = $wordUpper;
                } elseif ($wordIndex === 0) {
                    $w = mb_convert_case($wordLower, MB_CASE_TITLE, 'UTF-8');
                } elseif (in_array($wordLower, $kataTugas)) {
                    $w = $wordLower;
                } else {
                    $w = mb_convert_case($wordLower, MB_CASE_TITLE, 'UTF-8');
                }

                $result .= $prefix . $w . $suffix;
            } else {
                $result .= $part;
            }

            $wordIndex++;
        }

        return $result;
    }

    protected $casts = [
        'is_kesbangpol' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('dinas_slug_records');
            Cache::forget('dinas_slug_to_id_map_v3');
        });
        static::deleted(function () {
            Cache::forget('dinas_slug_records');
            Cache::forget('dinas_slug_to_id_map_v3');
        });
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function admins(): HasMany
    {
        return $this->hasMany(Admin::class);
    }

    public function bidang(): HasMany
    {
        return $this->hasMany(Bidang::class);
    }

    public function rekrutmens(): HasMany
    {
        return $this->hasMany(Rekrutmen::class);
    }

    public function getSisaKuotaAttribute(): int
    {
        if ($this->status_magang === 'tidak_tersedia' || $this->status_magang === 'penuh') {
            return 0;
        }

        $activeRekrutmens = $this->rekrutmens->where('is_active', true);
        if ($activeRekrutmens->count() === 0) {
            return 0;
        }

        return (int) $activeRekrutmens->sum(function ($r) {
            return $r->slot_tersedia;
        });
    }

    public function getSlotTersediaAttribute(): int
    {
        return $this->sisa_kuota;
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

        if ($this->sisa_kuota <= 0) {
            return 'penuh';
        }

        return 'tersedia';
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
            'BAKESBANGPOL' => 'Bakesbangpol',
            'BAPPENDA' => 'Bappenda',
            'BAPPERIDA' => 'Bapperida',
            'BKPSDM' => 'BKPSDM',
            'BPBD' => 'BPBD',
            'BPKAD' => 'BPKAD',
            'DAMKAR' => 'Damkar',
            'DAPD' => 'DAPD',
            'DINKES' => 'Dinkes',
            'DINSOS' => 'Dinsos',
            'DISBUD' => 'Disbud',
            'DISDAGIN' => 'Disdagin',
            'DISDIK' => 'Disdik',
            'DISDUKCAPIL' => 'Disdukcapil',
            'DISHUB' => 'Dishub',
            'DISKANAK' => 'Diskanak',
            'DISKOMINFO' => 'Diskominfo',
            'DISKOPUKM' => 'Diskop UKM',
            'DISNAKER' => 'Disnaker',
            'DISPAREKRAF' => 'Disparekraf',
            'DISPORA' => 'Dispora',
            'DISTANHORBUN' => 'Distanhorbun',
            'DKP' => 'DKP',
            'DLH' => 'DLH',
            'DP3AP2KB' => 'DP3AP2KB',
            'DPKP' => 'DPKP',
            'DPMD' => 'DPMD',
            'DPMPTSP' => 'DPMPTSP',
            'DPTR' => 'DPTR',
            'DPU' => 'DPU',
            'INSPEKTORAT' => 'Inspektorat',
            'RSUD CIAWI' => 'RSUD Ciawi',
            'RSUD CIBINONG' => 'RSUD Cibinong',
            'RSUD CILEUNGSI' => 'RSUD Cileungsi',
            'RSUD LEUWILIANG' => 'RSUD Leuwiliang',
            'SATPOL PP' => 'Satpol PP',
            'SETDA' => 'Setda',
            'SETWAN' => 'Setwan',
            // Nama panjang
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

    /**
     * Format nama instansi menjadi format URL slug yang bersih dan deskriptif.
     * Contoh:
     * - "BADAN KESATUAN BANGSA DAN POLITIK" -> "bakesbangpol"
     * - "DINAS KOMUNIKASI DAN INFORMATIKA" -> "diskominfo"
     * - "KECAMATAN CIBINONG" -> "kecamatan-cibinong"
     * - "RUMAH SAKIT UMUM DAERAH CIAWI" -> "rsud-ciawi"
     */
    public static function formatSlug(?string $name): string
    {
        if (empty($name)) {
            return '';
        }

        $name = trim($name);
        $upper = strtoupper($name);

        if (str_starts_with($upper, 'KECAMATAN')) {
            return Str::slug($name);
        }

        $singkatan = self::formatSingkatan($name);
        if (!empty($singkatan) && $singkatan !== '-') {
            return Str::slug($singkatan);
        }

        return Str::slug($name);
    }

    public function getSlugAttribute(): string
    {
        $name = $this->name ?? $this->nama ?? '';
        $slug = self::formatSlug($name);
        return !empty($slug) ? $slug : $this->hashid;
    }

    /**
     * Non-sequential encrypted identifier (Anti-enumeration / IDOR mitigation)
     */
    public function getHashidAttribute(): string
    {
        return self::encodeId($this->id);
    }

    public function getRouteKey()
    {
        return $this->slug;
    }

    /**
     * Cari instansi berdasarkan slug bersih atau hashid terenkripsi.
     * Raw sequential ID (1, 2, 3...) ditolak untuk mencegah enumerasi IDOR.
     */
    public static function resolveBySlugOrId(?string $identifier): ?self
    {
        if (empty($identifier)) {
            return null;
        }

        $identifier = trim($identifier);

        // 1. Fallback hashid (kompatibilitas link lama / bookmark)
        $decodedId = self::decodeId($identifier);
        if ($decodedId) {
            return self::find($decodedId);
        }

        // 2. Tolak direct sequential integer ID (misal /instansi/1) demi mencegah enumerasi IDOR
        if (ctype_digit($identifier)) {
            return null;
        }

        // 3. Pencarian berbasis slug bersih
        $normalized = strtolower($identifier);

        // Gunakan associative array map [slug => id] yang aman dari serialisasi objek Eloquent
        $slugMap = Cache::remember('dinas_slug_to_id_map_v3', 3600, function () {
            $map = [];
            foreach (self::all() as $dinas) {
                if ($dinas instanceof self) {
                    $slug = strtolower(trim($dinas->slug));
                    if ($slug !== '') {
                        $map[$slug] = $dinas->id;
                    }
                }
            }
            return $map;
        });

        if (is_array($slugMap) && isset($slugMap[$normalized])) {
            return self::find($slugMap[$normalized]);
        }

        // 4. Fallback langsung: iterasi model dengan pengecekan ketat instanceof self
        foreach (self::all() as $dinas) {
            if ($dinas instanceof self) {
                if (strtolower(trim($dinas->slug)) === $normalized) {
                    return $dinas;
                }
            }
        }

        return null;
    }

    public static function encodeId(int|string|null $id): string
    {
        if (empty($id)) {
            return '';
        }
        $id = (int) $id;
        $salt = substr(hash('sha256', config('app.key', 'lentera_default_key') . '_lentera_dinas_id'), 0, 8);
        $key = hexdec(substr(hash('sha256', $salt . '_key'), 0, 8)) & 0x7FFFFFFF;
        $scrambled = $id ^ $key;
        $checksum = substr(hash_hmac('sha256', (string) $id, $salt), 0, 6);
        return dechex($scrambled) . '-' . $checksum;
    }

    public static function decodeId(?string $token): ?int
    {
        if (empty($token) || !str_contains($token, '-')) {
            return null;
        }

        $parts = explode('-', $token);
        if (count($parts) !== 2 || !ctype_xdigit($parts[0]) || !ctype_xdigit($parts[1])) {
            return null;
        }

        $scrambled = hexdec($parts[0]);
        $checksum = $parts[1];

        $salt = substr(hash('sha256', config('app.key', 'lentera_default_key') . '_lentera_dinas_id'), 0, 8);
        $key = hexdec(substr(hash('sha256', $salt . '_key'), 0, 8)) & 0x7FFFFFFF;
        $id = $scrambled ^ $key;

        if ($id <= 0) {
            return null;
        }

        $expectedChecksum = substr(hash_hmac('sha256', (string) $id, $salt), 0, 6);
        if (!hash_equals($expectedChecksum, $checksum)) {
            return null;
        }

        return $id;
    }
}
