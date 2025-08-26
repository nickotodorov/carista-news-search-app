<?php

declare(strict_types=1);

namespace App\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ClientInterface::class, function () {
            return new Client([
                'base_uri' => 'https://newsapi.org/v2/',
                'timeout'  => 5.0,
            ]);
        });
    }

    public function boot(): void
    {
        Paginator::useTailwind();
    }
}
