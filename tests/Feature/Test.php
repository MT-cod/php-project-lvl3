<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class Test extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
        Artisan::call('db:seed');
    }

    public function testIndex(): void
    {
        $response = $this->get(route('home'));
        $response->assertOk();
    }
    public function testAddUrl(): void
    {
        $response = $this->post(route('addUrl'), ['url' => ['name' => 'http://kaka.com']]);
        $this->assertDatabaseHas('urls', ['name' => 'http://kaka.com']);
        $response->assertStatus(302);

        //$response->dumpHeaders();
        //$response->dumpSession();
        //$response->dump();
    }
    public function testShowUrls(): void
    {
        $this->post(route('addUrl'), ['url' => ['name' => 'http://kaka.com']]);
        $response = $this->get(route('showUrls'));
        $response->assertOk();
        $response->assertSee('http://kaka.com', $escaped = true);
    }
    public function testShowUrl(): void
    {
        $this->post(route('addUrl'), ['url' => ['name' => 'http://kaka.com']]);
        $response = $this->get(route('showUrl', ['id' => 1]));
        $response->assertOk();
        $response->assertSee('http://kaka.com', $escaped = true);
    }
    public function testCheckUrl(): void
    {
        $this->post(route('addUrl'), ['url' => ['name' => 'http://kaka.com']]);
        $this->post('/urls/{id}/checks', ['id' => 1]);
        $this->assertDatabaseHas('url_checks', ['id' => 1, 'url_id' => 1]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
