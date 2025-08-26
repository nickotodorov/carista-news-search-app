<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewsSearch extends Model
{
    use HasFactory;

    protected $table = 'new_searches';

    protected $fillable = [
        'raw_payload',
        'fetched_at',
        'keyword',
    ];

    protected $casts = [
        'raw_payload' => 'array',
        'fetched_at'  => 'datetime',
    ];
}
