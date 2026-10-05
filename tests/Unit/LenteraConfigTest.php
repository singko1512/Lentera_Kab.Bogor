<?php

namespace Tests\Unit;

use Tests\TestCase;

class LenteraConfigTest extends TestCase
{
    public function test_lentera_config_has_required_keys(): void
    {
        $this->assertNotNull(config('lentera'));
        $this->assertArrayHasKey('masa_berlaku_pengajuan_hari', config('lentera'));
        $this->assertArrayHasKey('masa_berlaku_surat_hari', config('lentera'));
        $this->assertArrayHasKey('max_upload_size', config('lentera'));
        $this->assertArrayHasKey('default_quota', config('lentera'));
        $this->assertArrayHasKey('status_memakai_kuota', config('lentera'));
        $this->assertArrayHasKey('reset_token_ttl_minutes', config('lentera'));
        $this->assertArrayHasKey('pejabat_kesbangpol', config('lentera'));
    }

    public function test_lentera_config_values(): void
    {
        $this->assertGreaterThan(0, config('lentera.max_upload_size'));
        $this->assertEquals(30, config('lentera.masa_berlaku_surat_hari'));
        $this->assertEquals(30, config('lentera.masa_berlaku_pengajuan_hari'));
        $this->assertIsArray(config('lentera.status_memakai_kuota'));
        $this->assertContains('menunggu', config('lentera.status_memakai_kuota'));
    }
}
