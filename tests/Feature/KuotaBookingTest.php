<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Dinas;
use App\Models\Bidang;
use App\Models\Rekrutmen;
use App\Models\PermohonanLayanan;
use App\Models\JenisLayanan;
use App\Models\MagangApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\MasterDataSeeder;

/**
 * KuotaBookingTest
 *
 * Verifikasi manajemen kuota magang:
 * 1. Batas kuota: pendaftaran melebihi kuota ditolak (422).
 * 2. Penolakan mengembalikan kuota: penolakan oleh Kesbangpol/Dinas mengembalikan kuota yang dibooking.
 * 3. Perubahan dinas memindahkan booking: mengubah dinas tujuan mengembalikan kuota dinas lama dan memotong kuota dinas baru.
 */
class KuotaBookingTest extends TestCase
{
    use RefreshDatabase;

    protected Dinas $kesbangpolDinas;
    protected Dinas $dinasA;
    protected Dinas $dinasB;
    protected Bidang $bidangA;
    protected Bidang $bidangB;
    protected Rekrutmen $rekrutmenA;
    protected Rekrutmen $rekrutmenB;
    protected User $kesbangpolUser;
    protected User $dinasAUser;
    protected User $student1;
    protected User $student2;
    protected User $student3;
    protected $jenisLayanan;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(MasterDataSeeder::class);

        // Kesbangpol
        $this->kesbangpolDinas = Dinas::create([
            'name'          => 'Badan Kesatuan Bangsa dan Politik',
            'nama'          => 'Badan Kesatuan Bangsa dan Politik',
            'is_kesbangpol' => true,
        ]);

        $this->kesbangpolUser = User::factory()->create([
            'name'              => 'Admin Kesbangpol',
            'email'             => 'kesbangpol@bogorkab.go.id',
            'role'              => 'kesbangpol',
            'dinas_id'          => $this->kesbangpolDinas->id,
            'status_akun'       => 'aktif',
            'email_verified_at' => now(),
        ]);

        // Dinas A dengan Kuota = 2
        $this->dinasA = Dinas::create([
            'name'          => 'Dinas Komunikasi dan Informatika',
            'nama'          => 'Dinas Komunikasi dan Informatika',
            'is_kesbangpol' => false,
            'status_magang' => 'tersedia',
        ]);

        $this->bidangA = Bidang::create([
            'dinas_id' => $this->dinasA->id,
            'name'     => 'Bidang Aplikasi Informatika',
        ]);

        $this->rekrutmenA = Rekrutmen::create([
            'dinas_id'  => $this->dinasA->id,
            'judul'     => 'Rekrutmen Magang Diskominfo',
            'kuota'     => 2,
            'is_active' => true,
        ]);

        $this->dinasAUser = User::factory()->create([
            'name'              => 'Admin Diskominfo',
            'email'             => 'diskominfo@bogorkab.go.id',
            'role'              => 'dinas',
            'dinas_id'          => $this->dinasA->id,
            'status_akun'       => 'aktif',
            'email_verified_at' => now(),
        ]);

        // Dinas B dengan Kuota = 5
        $this->dinasB = Dinas::create([
            'name'          => 'Dinas Pendidikan',
            'nama'          => 'Dinas Pendidikan',
            'is_kesbangpol' => false,
            'status_magang' => 'tersedia',
        ]);

        $this->bidangB = Bidang::create([
            'dinas_id' => $this->dinasB->id,
            'name'     => 'Bidang Kurikulum',
        ]);

        $this->rekrutmenB = Rekrutmen::create([
            'dinas_id'  => $this->dinasB->id,
            'judul'     => 'Rekrutmen Magang Disdik',
            'kuota'     => 5,
            'is_active' => true,
        ]);

        // Mahasiswa
        $this->student1 = User::factory()->create([
            'name'              => 'Mahasiswa 1',
            'email'             => 'mhs1@kampus.ac.id',
            'role'              => 'peserta',
            'status_akun'       => 'aktif',
            'email_verified_at' => now(),
        ]);

        $this->student2 = User::factory()->create([
            'name'              => 'Mahasiswa 2',
            'email'             => 'mhs2@kampus.ac.id',
            'role'              => 'peserta',
            'status_akun'       => 'aktif',
            'email_verified_at' => now(),
        ]);

        $this->student3 = User::factory()->create([
            'name'              => 'Mahasiswa 3',
            'email'             => 'mhs3@kampus.ac.id',
            'role'              => 'peserta',
            'status_akun'       => 'aktif',
            'email_verified_at' => now(),
        ]);

        $this->jenisLayanan = JenisLayanan::where('slug', 'kkl_mahasiswa')->first()
            ?: JenisLayanan::first();
    }

    /**
     * 1. Batas kuota: kuota 2, permohonan ke-3 ditolak (422)
     */
    public function test_batas_kuota_menolak_pengajuan_saat_kuota_habis(): void
    {
        $this->assertEquals(2, $this->rekrutmenA->slot_tersedia);

        // Mahasiswa 1 daftar -> sisa 1
        $res1 = $this->actingAs($this->student1)->postJson(route('layanan.submit'), [
            'jenis_layanan_slug' => $this->jenisLayanan->slug,
            'dinas_id'           => $this->dinasA->id,
            'atas_nama'          => 'Mahasiswa 1',
            'asal_instansi'      => 'Universitas Indonesia',
            'judul_kegiatan'     => 'Riset 1',
            'tanggal_mulai'      => now()->toDateString(),
            'tanggal_selesai'    => now()->addMonth()->toDateString(),
        ]);
        $res1->assertStatus(200);
        $this->assertEquals(1, $this->rekrutmenA->fresh()->slot_tersedia);

        // Mahasiswa 2 daftar -> sisa 0
        $res2 = $this->actingAs($this->student2)->postJson(route('layanan.submit'), [
            'jenis_layanan_slug' => $this->jenisLayanan->slug,
            'dinas_id'           => $this->dinasA->id,
            'atas_nama'          => 'Mahasiswa 2',
            'asal_instansi'      => 'IPB University',
            'judul_kegiatan'     => 'Riset 2',
            'tanggal_mulai'      => now()->toDateString(),
            'tanggal_selesai'    => now()->addMonth()->toDateString(),
        ]);
        $res2->assertStatus(200);
        $this->assertEquals(0, $this->rekrutmenA->fresh()->slot_tersedia);

        // Mahasiswa 3 daftar -> harus ditolak 422 karena kuota penuh
        $res3 = $this->actingAs($this->student3)->postJson(route('layanan.submit'), [
            'jenis_layanan_slug' => $this->jenisLayanan->slug,
            'dinas_id'           => $this->dinasA->id,
            'atas_nama'          => 'Mahasiswa 3',
            'asal_instansi'      => 'ITB',
            'judul_kegiatan'     => 'Riset 3',
            'tanggal_mulai'      => now()->toDateString(),
            'tanggal_selesai'    => now()->addMonth()->toDateString(),
        ]);
        $res3->assertStatus(422);
        $this->assertStringContainsString('penuh', $res3->json('message'));
        $this->assertEquals(0, $this->rekrutmenA->fresh()->slot_tersedia);
    }

    /**
     * 2. Penolakan mengembalikan kuota yang dibooking
     */
    public function test_penolakan_mengembalikan_kuota_booking(): void
    {
        // Isi kuota penuh
        $this->actingAs($this->student1)->postJson(route('layanan.submit'), [
            'jenis_layanan_slug' => $this->jenisLayanan->slug,
            'dinas_id'           => $this->dinasA->id,
            'atas_nama'          => 'Mahasiswa 1',
            'asal_instansi'      => 'Universitas Indonesia',
            'judul_kegiatan'     => 'Riset 1',
            'tanggal_mulai'      => now()->toDateString(),
            'tanggal_selesai'    => now()->addMonth()->toDateString(),
        ]);

        $this->actingAs($this->student2)->postJson(route('layanan.submit'), [
            'jenis_layanan_slug' => $this->jenisLayanan->slug,
            'dinas_id'           => $this->dinasA->id,
            'atas_nama'          => 'Mahasiswa 2',
            'asal_instansi'      => 'IPB University',
            'judul_kegiatan'     => 'Riset 2',
            'tanggal_mulai'      => now()->toDateString(),
            'tanggal_selesai'    => now()->addMonth()->toDateString(),
        ]);

        $this->assertEquals(0, $this->rekrutmenA->fresh()->slot_tersedia);

        $permohonan1 = PermohonanLayanan::where('user_id', $this->student1->id)->firstOrFail();

        // Kesbangpol menolak permohonan Mahasiswa 1
        $this->actingAs($this->kesbangpolUser)->post(route('kesbangpol.layanan.verify', $permohonan1->id), [
            'status'     => 'ditolak',
            'keterangan' => 'Proposal tidak lengkap',
        ]);

        // Kuota otomatis bertambah kembali
        $this->assertEquals(1, $this->rekrutmenA->fresh()->slot_tersedia);

        // Mahasiswa 3 sekarang bisa mendaftar
        $res3 = $this->actingAs($this->student3)->postJson(route('layanan.submit'), [
            'jenis_layanan_slug' => $this->jenisLayanan->slug,
            'dinas_id'           => $this->dinasA->id,
            'atas_nama'          => 'Mahasiswa 3',
            'asal_instansi'      => 'ITB',
            'judul_kegiatan'     => 'Riset 3',
            'tanggal_mulai'      => now()->toDateString(),
            'tanggal_selesai'    => now()->addMonth()->toDateString(),
        ]);
        $res3->assertStatus(200);
        $this->assertEquals(0, $this->rekrutmenA->fresh()->slot_tersedia);
    }

    /**
     * 3. Perubahan dinas memindahkan booking
     */
    public function test_perubahan_dinas_memindahkan_booking_antar_instansi(): void
    {
        $this->actingAs($this->student1)->postJson(route('layanan.submit'), [
            'jenis_layanan_slug' => $this->jenisLayanan->slug,
            'dinas_id'           => $this->dinasA->id,
            'atas_nama'          => 'Mahasiswa 1',
            'asal_instansi'      => 'Universitas Indonesia',
            'judul_kegiatan'     => 'Riset A',
            'tanggal_mulai'      => now()->toDateString(),
            'tanggal_selesai'    => now()->addMonth()->toDateString(),
        ]);

        $permohonan = PermohonanLayanan::where('user_id', $this->student1->id)->firstOrFail();

        // Dinas A: sisa 1 slot; Dinas B: sisa 5 slot
        $this->assertEquals(1, $this->rekrutmenA->fresh()->slot_tersedia);
        $this->assertEquals(5, $this->rekrutmenB->fresh()->slot_tersedia);

        // Mahasiswa memindahkan permohonan ke Dinas B
        $response = $this->actingAs($this->student1)->postJson(route('layanan.update', $permohonan->id), [
            'dinas_id'  => $this->dinasB->id,
            'atas_nama' => 'Mahasiswa 1 Update',
        ]);
        $response->assertStatus(200);

        // Kuota Dinas A dikembalikan (2), Kuota Dinas B dipotong (4)
        $this->assertEquals(2, $this->rekrutmenA->fresh()->slot_tersedia);
        $this->assertEquals(4, $this->rekrutmenB->fresh()->slot_tersedia);

        // Magang application diperbarui ke dinas B
        $magangApp = MagangApplication::where('permohonan_layanan_id', $permohonan->id)->firstOrFail();
        $this->assertEquals($this->dinasB->id, $magangApp->dinas_id);
    }

    /**
     * 4. Pendaftaran kelompok memotong kuota sesuai total peserta
     */
    public function test_pendaftaran_kelompok_memotong_kuota_sesuai_jumlah_peserta(): void
    {
        // Dinas B memiliki kuota 5
        $this->assertEquals(5, $this->rekrutmenB->slot_tersedia);

        // Mahasiswa 1 mendaftar kelompok 3 orang (1 perwakilan + 2 anggota)
        $res = $this->actingAs($this->student1)->postJson(route('layanan.submit'), [
            'jenis_layanan_slug'   => $this->jenisLayanan->slug,
            'dinas_id'             => $this->dinasB->id,
            'jumlah_peserta'       => 'Kelompok (> 1 Orang)',
            'jumlah_anggota_count' => 3,
            'atas_nama'            => 'Ketua Kelompok',
            'nama_anggota'         => ['Anggota 1', 'Anggota 2'],
            'asal_instansi'        => 'Universitas Indonesia',
            'judul_kegiatan'       => 'Riset Kelompok',
            'tanggal_mulai'        => now()->toDateString(),
            'tanggal_selesai'      => now()->addMonth()->toDateString(),
        ]);
        $res->assertStatus(200);

        // Kuota Dinas B harus berkurang 3 (5 - 3 = 2)
        $this->assertEquals(2, $this->rekrutmenB->fresh()->slot_tersedia);

        // Mahasiswa 2 mendaftar kelompok 3 orang ke Dinas B -> harus ditolak karena sisa kuota tinggal 2
        $res2 = $this->actingAs($this->student2)->postJson(route('layanan.submit'), [
            'jenis_layanan_slug'   => $this->jenisLayanan->slug,
            'dinas_id'             => $this->dinasB->id,
            'jumlah_peserta'       => 'Kelompok (> 1 Orang)',
            'jumlah_anggota_count' => 3,
            'atas_nama'            => 'Ketua Lain',
            'nama_anggota'         => ['Anggota A', 'Anggota B'],
            'asal_instansi'        => 'IPB University',
            'judul_kegiatan'       => 'Riset Kelompok Lain',
            'tanggal_mulai'        => now()->toDateString(),
            'tanggal_selesai'      => now()->addMonth()->toDateString(),
        ]);
        $res2->assertStatus(422);

        // Mahasiswa 2 mendaftar kelompok 2 orang -> berhasil karena sisa kuota pas 2
        $res3 = $this->actingAs($this->student2)->postJson(route('layanan.submit'), [
            'jenis_layanan_slug'   => $this->jenisLayanan->slug,
            'dinas_id'             => $this->dinasB->id,
            'jumlah_peserta'       => 'Kelompok (> 1 Orang)',
            'jumlah_anggota_count' => 2,
            'atas_nama'            => 'Ketua Pas',
            'nama_anggota'         => ['Anggota Pas'],
            'asal_instansi'        => 'IPB University',
            'judul_kegiatan'       => 'Riset Pas',
            'tanggal_mulai'        => now()->toDateString(),
            'tanggal_selesai'      => now()->addMonth()->toDateString(),
        ]);
        $res3->assertStatus(200);

        // Kuota Dinas B sekarang habis (0)
        $this->assertEquals(0, $this->rekrutmenB->fresh()->slot_tersedia);
    }
}
