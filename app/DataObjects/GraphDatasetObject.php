<?php

declare(strict_types=1);

namespace App\DataObjects;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

final class GraphDatasetObject extends Data
{
    /**
     * Undocumented function
     *
     * @param string $label
     * @param Collection<int|string, mixed> $data
     * @param string $backgroundColor
     * @param string $borderColor
     * @param string $pointBackgroundColor
     */
    public function __construct(
        public readonly string $label,
        public readonly Collection $data,
        public readonly string $backgroundColor,
        public readonly string $borderColor,
        public readonly string $pointBackgroundColor,
    ) {}
}
