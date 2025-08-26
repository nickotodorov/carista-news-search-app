<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;

Route::get('/', [NewsController::class, 'index'])
    ->name('news.index');

Route::get('/search', [NewsController::class, 'search'])
    ->middleware('throttle:20,1')
    ->name('news.search');
