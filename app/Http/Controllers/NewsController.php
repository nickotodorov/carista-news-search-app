<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTOs\ArticleData;
use App\Helpers\ChartHelper;
use App\Http\Requests\SearchRequest;
use App\Repositories\NewsRepository;
use App\Services\NewsApiService;
use App\ViewModels\NewsIndexViewModel;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Arr;
use Throwable;

final class NewsController extends Controller
{
    const int FE_PER_PAGE = 10;

    public function __construct(
        private readonly NewsApiService $service,
        private readonly NewsRepository $repo,
    ) {}

    public function index(): View
    {
        return view('news.index');
    }

    public function search(SearchRequest $request): View
    {
        $keyword = (string) $request->validated('q');
        $page = (int) ($request->validated('page') ?? 1);

        try {
            $latest = $this->repo->latestByKeyword($keyword);

            if ($latest) {
                $payload = $latest->raw_payload;
            } else {
                $payload = $this->service->fetchArticles($keyword);

                $this->repo->store($keyword, $payload);
            }
        } catch (Throwable $e) {
            report($e);

            return view('news.index')->withErrors([
                'q' => 'External API failed. Please try again later.',
            ]);
        }

        $articles = array_map(
            fn(array $a): ArticleData => new ArticleData(
                title: (string) ($a['title'] ?? 'Untitled'),
                source: (string) (Arr::get($a, 'source.name') ?? 'Unknown'),
                publishedAt: isset($a['publishedAt'])
                    ? new \DateTimeImmutable($a['publishedAt'])
                    : null,
                url: (string) ($a['url'] ?? '#'),
            ),
            $payload['articles'] ?? []
        );

        $perPage = self::FE_PER_PAGE;
        $total = count($articles);
        $items = array_slice($articles, ($page - 1) * $perPage, $perPage);

        $paginator = new Paginator(
            $items,
            $total,
            $perPage,
            $page,
            [
                'path'  => route('news.search'),
                'query' => ['q' => $keyword],
            ]
        );

        $vm = new NewsIndexViewModel(
            $keyword,
            $articles,
            ChartHelper::prepareSeries($articles)
        );

        return view('news.index', [
            'paginator' => $paginator,
            'vm'        => $vm,
        ]);
    }
}
