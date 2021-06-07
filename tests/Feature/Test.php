<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
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
        $response->assertSeeTextInOrder(
            ['Анализатор страниц', 'Все добавленные страницы', 'Проверить'],
            true
        );
    }
    public function testAddUrl(): void
    {
        $response = $this->post(route('addUrl'), ['url' => ['name' => 'http://test.test']]);
        $this->assertDatabaseHas('urls', ['name' => 'http://test.test']);
        $response->assertStatus(302);

        //$response->dumpHeaders();
        //$response->dumpSession();
        //$response->dump();
    }
    public function testShowUrls(): void
    {
        $this->post(route('addUrl'), ['url' => ['name' => 'http://test.test']]);
        $response = $this->get(route('showUrls'));
        $response->assertOk();
        $response->assertSee('http://test.test',true);
    }
    public function testShowUrl(): void
    {
        $this->post(route('addUrl'), ['url' => ['name' => 'http://test.test']]);
        $response = $this->get(route('showUrl', ['id' => 1]));
        $response->assertOk();
        $response->assertSee('http://test.test', true);
    }
    public function testCheckUrl(): void
    {
        Http::fake(['*' => Http::response('Hello World', 222, ['Headers'])]);
        $this->post(route('addUrl'), ['url' => ['name' => 'http://example.com']]);
        $this->post('/urls/{id}/checks', ['id' => 1]);
        $this->assertDatabaseHas('url_checks', ['id' => 1, 'url_id' => 1, 'status_code' => 222]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
