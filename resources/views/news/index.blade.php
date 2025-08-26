@extends('layouts.app')

@section('content')
    {{-- Search Form --}}
    <form action="{{ route('news.search') }}" method="GET" class="mb-6 flex gap-2">
        <input
            type="text"
            name="q"
            value="{{ old('q', $vm->keyword ?? '') }}"
            placeholder="Enter keyword (e.g. Laravel, AI)..."
            class="flex-grow px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
            required
        >
        <button
            type="submit"
            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
        >
            Search
        </button>
    </form>

    {{-- Error Messages --}}
    @if($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md">
            {{ $errors->first('q') }}
        </div>
    @endif

    {{-- Results --}}
    @isset($paginator)
        <div class="space-y-4">
            @foreach($paginator as $article)
                <div class="p-4 bg-white rounded-lg shadow hover:shadow-md transition">
                    <h2 class="text-lg font-semibold">
                        <a href="{{ $article->url }}" target="_blank" class="text-indigo-600 hover:underline">
                            {{ $article->title }}
                        </a>
                    </h2>
                    <div class="text-sm text-gray-500">
                        {{ $article->source }} — {{ $article->publishedAt?->format('M d, Y H:i') }}
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $paginator->withQueryString()->links() }}
        </div>
    @endisset

    {{-- Chart --}}
    @isset($vm)
        <div class="mt-10 p-6 bg-white rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-4">Articles per Day (Last 7 days)</h3>
            <canvas id="articlesChart" height="100"></canvas>
        </div>

        <script>
            const ctx = document.getElementById('articlesChart');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_keys($vm->series)) !!},
                    datasets: [{
                        label: 'Articles',
                        data: {!! json_encode(array_values($vm->series)) !!},
                        backgroundColor: 'rgba(99, 102, 241, 0.6)',
                        borderColor: 'rgba(99, 102, 241, 1)',
                        borderWidth: 1,
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true, stepSize: 1 }
                    }
                }
            });
        </script>
    @endisset
@endsection
