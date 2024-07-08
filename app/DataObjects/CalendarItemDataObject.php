<?php

declare(strict_types=1);

namespace App\DataObjects;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

final class CalendarItemDataObject extends Data
{
    public function __construct(
        public readonly string|Carbon $start,
        public readonly string|Carbon $end,
        public readonly string $title = 'Niet beschikbaar',
        public readonly string $color = 'red',
    ) {}
}
