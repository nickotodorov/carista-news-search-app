<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class ArticleData
{
    public function __construct(
        public string $title,
        public string $source,
        public ?\DateTimeImmutable $publishedAt,
        public string $url,
    )
    {
    }
}
