<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_public_landing_page_renders(): void
    {
        $this->withoutVite();
        $this->get('/')->assertOk()->assertSee('PLC — Manajemen Program Kerja');
    }

    public function test_private_api_requires_login(): void
    {
        $this->getJson('/api/v1/me')->assertUnauthorized();
    }
}
