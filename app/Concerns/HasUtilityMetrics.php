<?php

declare(strict_types=1);

namespace App\Concerns;

use App\Enums\LeaseStatus;
use App\Models\Utility;
use Illuminate\Database\Eloquent\Relations\HasMany;
use JetBrains\PhpStorm\Deprecated;

/**
 * Trait HasUtilityMetrics
 *
 * This trait provides comprehensive functionality for managing utility metrics in lease agreements.
 * It serves as a central component for tracking, recording, and finalizing utility consumption measurements throughout the duration of a lease.
 * The trait implements methods for managing, the relationship between leases and their associated utility readings, handling the complete lifecycle from initial registration through to final confirmation.
 *
 * The trait integrates deeply with the lease management system, offering status verification, metric finalization controls, and validation of temporal constraints.
 * It ensures proper timing of utility metric finalization by checking departure dates and existing readings.
 *
 * @package App\Concerns
 */
trait HasUtilityMetrics
{
    /**
     * Establishes and retrieves the relationship between a lease and its utility readings.
     * This relationship maps all utility measurements recorded during the lease period, including gas, water, and electricity consumption metrics.
     * The relationship enables tracking of utility usage patterns and facilitates final billing calculations.
     *
     * @return HasMany<Utility, covariant $this> A collection of utility readings.
     */
    public function utilityStatistics(): HasMany
    {
        return $this->hasMany(Utility::class);
    }

    /**
     * Determines whether the lease has reached its finalized state.
     * This method performs a direct comparison between the current lease status and the Finalized enum value.
     * This implementation will be superseded by the more robust comparison functionality provided by the ArchTech/enums package.
     *
     * @return bool Returns true when the lease status matches Finalized.
     */
    #[Deprecated(reason: 'Will be solved with the Comperable trait from ArchTech/enums', since: '1.0')]
    public function isFinalized(): bool
    {
        return LeaseStatus::Finalized === $this->status;
    }

    /**
     * Verifies if the lease has been confirmed by all parties.
     * Performs a direct status comparison to determine if the lease has received necessary confirmations.
     * This implementation will be replaced by enhanced comparison capabilities from the ArchTech/enums package.
     *
     * @return bool Returns true when the lease status matches Confirmed
     */
    #[Deprecated(reason: 'Will be solved with the Comperable trait from ArchTech/enums', since: '1.0')]
    public function isConfirmed(): bool
    {
        return LeaseStatus::Confirmed === $this->status;
    }

    /**
     * Evaluates whether the utility finalization button should be displayed.
     * This method performs a comprehensive check of multiple conditions that must be satisfied before allowing utility metric finalization.
     * It ensures proper timing and prevents premature or duplicate finalizations by verifying the existence of readings, checking finalization status, and validating the departure date.
     *
     * @return bool Returns true only when all finalization conditions are met
     */
    public function canDisplayTheFinalizeButton(): bool
    {
        return $this->utilityStatistics()->exists()
            && $this->hasntFinalizedUtilityMetrics()
            && $this->hasDepartureDateReachedOrPassed();
    }

    /**
     * Checks the registration status of utility metrics.
     * Examines the metrics_registered_at timestamp to determine if utility readings have been officially recorded and finalized in the system.
     * This timestamp serves as an immutable marker of metric finalization.
     *
     * @return bool Returns true if metrics have been officially registered
     */
    public function hasDepartureDateReachedOrPassed(): bool
    {
        return now()->startOfDay()->gte($this->departure_date);
    }

    /**
     * Executes the finalization process for utility metrics.
     * This critical operation marks utility readings as final by recording the current timestamp.
     * The method includes a safety check to prevent finalization when no utility statistics exist, maintaining data integrity.
     * Once finalized, the metrics become immutable to prevent unauthorized modifications.
     */
    public function hasRegisteredMetrics(): bool
    {
        return null !== $this->metrics_registered_at;
    }

    /**
     * Executes the finalization process for utility metrics.
     * This critical operation marks utility readings as final by recording the current timestamp.
     * The method includes a safety check to prevent finalization when no utility statistics exist, maintaining data integrity.
     * Once finalized, the metrics become immutable to prevent unauthorized modifications.
     */
    public function finalizeUtilityMetrics(): void
    {
        if ($this->utilityStatistics()->exists()) {
            $this->update(attributes: ['metrics_registered_at' => now()]);
        }
    }

    /**
     * Provides an inverse check of utility metric finalization status.
     * This semantic helper method improves code readability when checking for unfinalized metrics.
     * It simply inverts the hasRegisteredMetrics check to provide a more natural language construct in conditional statements.
     *
     * @return bool Returns true when metrics have not yet been finalized
     */
    public function hasntFinalizedUtilityMetrics(): bool
    {
        return ! $this->hasRegisteredMetrics();
    }
}
