<?php

declare(strict_types=1);

namespace App\Services;

use GuzzleHttp\ClientInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Psr\Log\LoggerInterface;


final class NewsApiService
{
    public function __construct(
        private readonly ClientInterface $http,
        private readonly LoggerInterface $logger,
    )
    {
    }


    /** @return array{status?:string,totalResults?:int,articles?:array} */
    public function fetchArticles(string $keyword): array
    {
        $keyword = trim($keyword);

        if ($keyword === '') {
            return [
                'status' => 'ok',
                'totalResults' => 0,
                'articles' => []
            ];
        }

        $cacheKey = 'newsapi:' . Str::slug($keyword);

        return Cache::store(config('cache.default'))
            ->remember($cacheKey, now()->addMinutes(5), function () use ($keyword) {
                $query = [
                    'q' => $keyword,
                    'pageSize' => (int)config('newsapi.page_size', 20),
                    'sortBy' => 'publishedAt',
                    'language' => 'en',
                ];

                try {
                    $res = $this->http->request(
                        'GET',
                        config('newsapi.endpoint'),
                        [
                            'query' => $query,
                            'headers' =>
                                [
                                    'X-Api-Key' => (string)config('newsapi.key'),
                                    'Accept' => 'application/json',
                                ],
                            'timeout' => (float)config('newsapi.timeout', 8),
                        ]
                    );

                } catch (\Throwable $e) {
                    dd($e);
                    $this->logger->error('NewsAPI request failed', [
                        'keyword' => $keyword,
                        'error' => $e->getMessage(),
                    ]);

                    return [
                        'status' => 'error',
                        'totalResults' => 0,
                        'articles' => []
                    ];
                }

                $json = json_decode((string)$res->getBody(), true);

                if (!is_array($json)) {
                    return ['status' => 'error', 'totalResults' => 0, 'articles' => []];
                }

                if (isset($json['articles']) && is_array($json['articles'])) {
                    $json['articles'] = array_slice($json['articles'], 0, 20);
                }

                return $json;
            });
    }
}
