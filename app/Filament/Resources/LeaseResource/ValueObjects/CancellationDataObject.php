
<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\ValueObjects;

use Spatie\LaravelData\Data;

/**
 * Class CancellationDataObject
 *
 * This class represents a data object for lease cancellation information.
 * It is used to encapsulate the reason for cancellation in a structured way.
 *
 * @property-read string $cancellationReason The reason for the lease cancellation.
 *
 * @package App\Filament\Resources\LeaseResource\ValueObjects
 */
final class CancellationDataObject extends Data
{
    /**
     * CancellationDataObject constructor.
     *
     * @param string $cancellationReason The reason for the lease cancellation.
     */
    public function __construct(
        protected readonly string $cancellationReason,
    ) {}

    /**
     * Get the reason for the lease cancellation.
     *
     * @return string The reason for the lease cancellation.
     */
    public function getReason(): string
    {
        return $this->cancellationReason;
    }
}
