<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Contracts\ShouldPerformAccessment;
use App\Enums\RiskLevel;
use App\Models\Lease;
use App\Models\Tenant;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

/**
 * Job to perform a risk assessment on a lease and tenant, assigning a risk score and label.
 *
 * This job calculates a tenant's risk based on past incidents and updates the lease with
 * an assessment score and label derived from configured thresholds.
 *
 * @return App\Jobs
 */
final class PerformRiskAssesment implements ShouldQueue, ShouldPerformAccessment
{
    use Queueable;

    /**
     * Create a new job instance.
     *
     * @param  Lease  $lease The lease associated with the risk assessment.
     * @param  Tenant $tenant The tenant whose incidents contribute to the risk score.
     * @return void
     */
    public function __construct(
        public readonly Lease $lease,
        public readonly Tenant $tenant,
    ) {}

    /**
     * Execute the job.
     *
     * Calculates the risk assessment score, determines the appropriate label based on
     * configuration thresholds, and stores the results in the lease.
     *
     * @return void
     */
    public function handle(): void
    {
        $impactScore = $this->calculateTheAssesmentScore();
        $finalAssesmentLabel = $this->getRiskAssesmentLabelFromScore($impactScore);

        $this->registerAccessmentResult($impactScore, $finalAssesmentLabel);
    }

    /**
     * Calculates the assessment score based on the average impact score of tenant incidents.
     * This calculation derives the tenant's average incident impact score to assess risk.
     *
     * @return int The calculated average impact score of tenant incidents.
     */
    public function calculateTheAssesmentScore(): int
    {
        /** @phpstan-ignore-next-line */
        return (int) $this->tenant->incidents()->avg('impact_score') ?? 0;
    }

    /**
     * Registers the assessment result by updating the lease with the risk score and label.
     *
     * @param  int       $impactScore           The calculated risk score.
     * @param  RiskLevel $finalAssesmentLabel   The risk level derived from the score.
     * @return void
     */
    public function registerAccessmentResult(int $impactScore, RiskLevel $finalAssesmentLabel): void
    {
        $this->lease->update(attributes: [
            'risk_accessment_score' => $impactScore, 'risk_accessment_label' => $finalAssesmentLabel,
        ]);
    }

    /**
     * Determines the risk assessment label based on the score, using configured thresholds.
     *
     * Maps the calculated score to a RiskLevel enum value by comparing it to thresholds in the
     * application’s configuration. The label helps categorize the tenant’s risk profile.
     *
     * @param  int $impactScore The score to map to a risk level.
     * @return RiskLevel        The determined risk level for the provided score.
     */
    public function getRiskAssesmentLabelFromScore(int $impactScore): RiskLevel
    {
        return match (true) {
            $impactScore <= config('sijot-verhuur.risk_assesment.very_low') => RiskLevel::VeryLow,
            $impactScore <= config('sijot-verhuur.risk_assesment.low') => RiskLevel::Low,
            $impactScore <= config('sijot-verhuur.risk_assesment.medium') => RiskLevel::Medium,
            $impactScore <= config('sijot-verhuur.risk_assesment.high') => RiskLevel::High,
            default => RiskLevel::VeryHigh,
        };
    }
}
