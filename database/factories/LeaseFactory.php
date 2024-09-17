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
     * Indicate that the lease request is an optional reservation in waiting of the quotation approval.
     *
     * @return static
     */
    public function quotationOption(): static
    {
        return $this->state(fn(array $attributes): array => ['status' => LeaseStatus::Quotation]);
    }

    /**
     * Indicate the the lease request is a new request in the system. And awaiting for further actions.
     *
     * @return static
     */
    public function newRequest(): static
    {
        return $this->state(fn(array $attributes): array => ['status' => LeaseStatus::Request]);
    }

    /**
     * Indicate that the lease request is an optional reservation.
     *
     * @return static
     */
    public function option(): static
    {
        return $this->state(fn(array $attributes): array => ['status' => LeaseStatus::Option]);
    }

    /**
     * Indicate that the lease request is a confirmed reservation.
     *
     * @return static
     */
    public function confirmed(): static
    {
        return $this->state(fn(array $attributes): array => ['status' => LeaseStatus::Confirmed]);
    }

    /**
     * Indicate that the lease is finalized in the system.
     *
     * @return static
     */
    public function finalized(): static
    {
        return $this->state(fn(array $attributes): array => ['status' => LeaseStatus::Finalized]);
    }

    /**
     * Indicate that the lease is cancelled during the processing of the request.
     *
     * @return static
     */
    public function cancelled(): static
    {
        return $this->state(fn(array $attributes): array => ['status' => LeaseStatus::Cancelled]);
    }
}
