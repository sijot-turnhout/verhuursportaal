<?php

declare(strict_types=1);

namespace App\Concerns;

use App\Enums\LeaseStatus;
use App\Models\Utility;
use Illuminate\Database\Eloquent\Relations\HasMany;
use JetBrains\PhpStorm\Deprecated;

trait HasUtilityMetrics
{
    /**
     * @return HasMany<Utility, covariant $this>
     */
    public function utilityStatistics(): HasMany
    {
        return $this->hasMany(Utility::class);
    }

    /**
     * Method to check if the lease is registered as finalized.
     */
    #[Deprecated(reason: 'Will be solved with the Comperable trait from ArchTech/enums', since: '1.0')]
    public function isFinalized(): bool
    {
        return LeaseStatus::Finalized === $this->status;
    }

    /**
     * Method to check if the lease is registered as confirmed.
     */
    #[Deprecated(reason: 'Will be solved with the Comperable trait from ArchTech/enums', since: '1.0')]
    public function isConfirmed(): bool
    {
        return LeaseStatus::Confirmed === $this->status;
    }

    public function canDisplayTheFinalizeButton(): bool
    {
        return $this->utilityStatistics()->exists()
            && $this->hasntFinalizedUtilityMetrics()
            && $this->hasDepartureDateReachedOrPassed();
    }

    public function hasDepartureDateReachedOrPassed(): bool
    {
        return now()->startOfDay()->gte($this->departure_date);
    }

    public function hasRegisteredMetrics(): bool
    {
        return null !== $this->metrics_registered_at;
    }

    public function finalizeUtilityMetrics(): void
    {
        if ($this->utilityStatistics()->exists()) {
            $this->update(attributes: ['metrics_registered_at' => now()]);
        }
    }

    public function hasntFinalizedUtilityMetrics(): bool
    {
        return ! $this->hasRegisteredMetrics();
    }
}
