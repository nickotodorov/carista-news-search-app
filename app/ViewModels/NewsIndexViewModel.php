<?php

declare(strict_types=1);

namespace App\ViewModels;

use App\DTOs\ArticleData;

readonly class NewsIndexViewModel
{
    /** @param ArticleData[] $articles */
    public function __construct(
        public string $keyword,
        public array $articles,
        public array $series,
    ) {}
}
