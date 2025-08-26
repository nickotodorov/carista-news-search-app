<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Carista News Search</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50 text-gray-800">
<div class="min-h-screen flex flex-col">
    <nav class="bg-white shadow mb-6">
        <div class="max-w-5xl mx-auto px-4 py-3">
            <h1 class="text-xl font-semibold text-indigo-600">Carista News Search</h1>
        </div>
    </nav>

    <main class="flex-grow max-w-5xl mx-auto px-4">
        @yield('content')
    </main>

    <footer class="bg-white shadow mt-8">
        <div class="max-w-5xl mx-auto px-4 py-4 text-sm text-gray-500">
            © {{ date('Y') }} Carista Demo
        </div>
    </footer>
</div>
</body>
</html>
