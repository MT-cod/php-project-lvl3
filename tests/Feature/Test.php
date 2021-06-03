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
        $this->assertDatabaseHas('urls', [
            'name' => 'http://kaka.com',
        ]);
        $response->assertStatus(302);

        //$response->dumpHeaders();
        //$response->dumpSession();
        //$response->dump();
    }
}
