<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\ValueObjects;

use Spatie\LaravelData\Data;

final class CancellationDataObject extends Data
{
    public function __construct(
        protected readonly string $cancellationReason,
    ) {}

    public function getReason(): string
    {
        return $this->cancellationReason;
    }
}
