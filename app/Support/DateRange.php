<?php

namespace App\Support;

use Illuminate\Support\Carbon;

class DateRange
{
    public static function labels(): array
    {
        return [
            'today' => 'Today',
            'last_week' => 'Last Week',
            'last_month' => 'Last Month',
            'this_month' => 'This Month',
        ];
    }

    public static function fromKey(?string $key): ?array
    {
        return match ($key) {
            'today' => [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()],
            'last_week' => [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()],
            'last_month' => [Carbon::now()->subMonthNoOverflow()->startOfMonth(), Carbon::now()->subMonthNoOverflow()->endOfMonth()],
            'this_month' => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
            default => null,
        };
    }
}
