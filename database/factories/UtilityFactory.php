<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\UtilityMetricTypes;
use App\Models\Lease;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Utility>
 */
class UtilityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startValue = $this->faker->randomNumber(4);
        $endValue = $startValue + $this->faker->randomNumber(4);

        return [
            'lease_id' => Lease::factory()->create()->id,
            'name' => $this->faker->randomElement(UtilityMetricTypes::cases()),
            'start_value' => $startValue,
            'end_value' => $endValue,
            'unit_price' => 1,
            'created_at' => $this->faker->dateTimeBetween(now()->startOfYear(), now()->endOfYear()),
        ];
    }
}
