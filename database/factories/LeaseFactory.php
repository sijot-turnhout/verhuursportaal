<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\LeaseStatus;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lease>
 */
class LeaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory()->create()->id,
            'group' => $this->faker->company,
            'arrival_date' => $this->faker->date(),
            'departure_date' => $this->faker->date(),
            'persons' => $this->faker->numberBetween(0, 214),
            'status' => $this->faker->randomElement(LeaseStatus::cases()),
            'created_at' => $this->faker->dateTimeBetween(now()->startOfYear(), now()->endOfYear()),
        ];
    }

    /**
     * Indicate that the lease needs a quotation first.
     *
     * @return static
     */
    public function quotationRequest(): static
    {
        return $this->state(fn(array $attributes): array => ['status' => LeaseStatus::Quotation]);
    }

    /**
     * Indicates that the lease is a new request.
     *
     * @return static
     */
    public function newRequest(): static
    {
        return $this->state(fn (array $attributes): array => ['status' => LeaseStatus::Request]);
    }


    /**
     * Indicates that the request is an optional lease request.
     *
     * @return static
     */
    public function option(): static
    {
        return $this->state(fn (array $attributes) => ['status' => LeaseStatus::Option]);
    }

    /**
     * Indicates that the lease is confirmed.
     *
     * @return static
     */
    public function confirmed(): static
    {
        return $this->state(fn (array $attributes): array => ['status' => LeaseStatus::Confirmed]);
    }

    /**
     * Indicates that the lease in finalized and performed.
     *
     * @return static
     */
    public function finalized(): static
    {
        return $this->state(fn (array $attributes): array => ['status' => LeaseStatus::Finalized]);
    }

    /**
     * INdicatres that the lease request is cancelled.
     *
     * @return static
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes): array => ['status' => LeaseStatus::Cancelled]);
    }
}
