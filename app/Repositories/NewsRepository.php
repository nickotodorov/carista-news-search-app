<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\NewsSearch;
use Illuminate\Database\Eloquent\Collection;

class NewsRepository
{
    public function latestByKeyword(string $keyword): ?NewsSearch
    {
        return NewsSearch::query()
            ->where('keyword', $keyword)
            ->orderByDesc('fetched_at')
            ->first();
    }

    public function store(string $keyword, array $payload): NewsSearch
    {
        return NewsSearch::create([
            'keyword' => $keyword,
            'raw_payload' => $payload,
            'fetched_at'  => now(),
        ]);
    }

    public function countByDayLast7(): Collection
    {
        return NewsSearch::query()
            ->where('fetched_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(fetched_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
}
