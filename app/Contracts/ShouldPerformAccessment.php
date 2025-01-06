<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Enums\RiskLevel;

/**
 * Interface for performing risk assessment operations on leases.
 *
 * Classes implementing this interface are expected to handle the execution of a risk assessment,
 * calculate an assessment score, determine the risk level label from the score,
 * and register the final result.
 *
 * @package App\Contracts
 */
interface ShouldPerformAccessment
{
    /**
     * Executes the risk assessment process.
     *
     * This method coordinates the assessment by calculating the score, assigning a risk label,
     * and registering the result on the associated entity.
     *
     * @return void
     */
    public function handle(): void;

    /**
     * Calculates the assessment score based on predefined criteria.
     *
     * The score represents the assessed impact or likelihood of risk based on associated data,
     * such as tenant incident history.
     *
     * @return int The calculated risk score.
     */
    public function calculateTheAssesmentScore(): int;

    /**
     * Determines the risk assessment label based on the given score.
     *
     * Maps the calculated score to a `RiskLevel` enum, categorizing the risk level.
     *
     * @param  int $impactScore  The risk score to evaluate.
     * @return RiskLevel         The corresponding risk level for the provided score.
     */
    public function getRiskAssesmentLabelFromScore(int $impactScore): RiskLevel;

    /**
     * Registers the assessment result, updating the associated entity with the score and label.
     *
     * @param  int       $impactScore          The calculated assessment score.
     * @param  RiskLevel $finalAssesmentLabel  The final risk label based on the score.
     * @return void
     */
    public function registerAccessmentResult(int $impactScore, RiskLevel $finalAssesmentLabel): void;
}
