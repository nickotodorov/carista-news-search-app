<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;

class NewsSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_page_loads(): void
    {
        $response = $this->get(route('news.index'));

        $response->assertOk()
            ->assertSee('Carista News Search');
    }

    public function test_search_post_not_allowed(): void
    {
        $response = $this->post(route('news.search'), ['q' => 'laravel']);

        $response->assertStatus(405);
    }

    public function test_search_q_mandatory(): void
    {
        $response = $this->get(route('news.search'), ['q' => '']);

        $response->assertSessionHasErrors();
    }

    public function test_search_returns_articles(): void
    {
        $this->app->bind(ClientInterface::class, function () {
            $mock = new MockHandler([
                new Response(200, [], json_encode([
                    'status' => 'ok',
                    'totalResults' => 1,
                    'articles' => [
                        [
                            'title' => 'Fake Laravel Article',
                            'source' => ['name' => 'Test Source'],
                            'publishedAt' => now()->toIso8601String(),
                            'url' => 'https://example.com/fake',
                        ],
                    ],
                ])),
            ]);
            $handlerStack = HandlerStack::create($mock);

            return new Client(['handler' => $handlerStack]);
        });

        $response = $this->get(route('news.search', ['q' => 'Laravel']));

        $response->assertOk()
            ->assertSee('Fake Laravel Article');
    }
}
