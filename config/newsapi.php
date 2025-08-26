<?php

declare(strict_types=1);


return [
    'endpoint' => env('NEWSAPI_ENDPOINT', 'https://newsapi.org/v2/everything'),
    'key' => env('NEWSAPI_KEY', ''),
    'page_size' => (int) env('NEWSAPI_PAGE_SIZE', 20),
    'timeout' => 5,
];

