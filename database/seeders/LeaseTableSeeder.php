<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UtilityMetricTypes;
use App\Models\Lease;
use App\Models\Tenant;
use App\Models\Utility;
use Illuminate\Database\Seeder;

class LeaseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenant = Tenant::factory()->create();
        $lease = Lease::factory()->count(35)->create(['tenant_id' => $tenant->id]);

        Tenant::factory()->count(6)->create();

        $lease->each(function ($lease): void {
            Utility::factory()->create(['name' => UtilityMetricTypes::Gas, 'lease_id' => $lease->id, 'unit_price' => config('sijot-verhuur.billing.utilities.gas')]);
            Utility::factory()->create(['name' => UtilityMetricTypes::Water, 'lease_id' => $lease->id, 'unit_price' => config('sijot-verhuur.billing.utilities.water')]);
            Utility::factory()->create(['name' => UtilityMetricTypes::Electricity, 'lease_id' => $lease->id, 'unit_price' => config('sijot-verhuur.billing.utilities.electricity')]);
        });
    }
}
