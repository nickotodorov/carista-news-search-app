<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Carbon;
use DateTimeZone;

class ChartHelper
{
    public static function prepareSeries(array $data): array
    {
        $series = [];

        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i)->format('Y-m-d');
            $series[$day] = 0;
        }

        foreach ($data as $article) {
            if ($article->publishedAt) {
                $d = $article->publishedAt
                    ->setTimezone(new DateTimeZone(date_default_timezone_get()))
                    ->format('Y-m-d');
                if (array_key_exists($d, $series)) {
                    $series[$d]++;
                }
            }
        }

        return $series;
    }
}
