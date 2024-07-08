<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\UtilityMetricTypes;
use App\Models\Lease;
use App\Models\Utility;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\Attributes\WithoutRelations;
use Illuminate\Queue\SerializesModels;

final readonly class RegisterInitialUtilityMetrics
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        #[WithoutRelations]
        public readonly Lease $lease,
    ) {}

    public function handle(): void
    {
        // The utility usage metrics are already so there is no need to run down the logic
        // THat is implemented below.So return early
        if ($this->lease->utilityStatistics()->exists()) {
            return;
        }

        $this->lease->utilityStatistics()->saveMany([
            new Utility(['name' => UtilityMetricTypes::Gas, 'unit_price' => config('sijot-verhuur.billing.utilities.gas', '1')]),
            new Utility(['name' => UtilityMetricTypes::Water, 'unit_price' => config('sijot-verhuur.billing.utilities.water', '1')]),
            new Utility(['name' => UtilityMetricTypes::Electricity, 'unit_price' => config('sijot-verhuur.billing.utilities.electricity', '1')]),
        ]);
    }
}
