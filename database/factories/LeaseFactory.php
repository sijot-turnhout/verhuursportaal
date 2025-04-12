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
     * This state modifier sets the 'status' attribute of the lease to the value representing a "quotation option" as defined in the LeaseStatus enum.
     * It is useful for testing or seeding scenarios where a lease should be simulated as being in the optional reservation state pending quotation approval.
     *
     * @return static Returns the current factory instance with the 'quotation option' state applied.
     */
    public function quotationOption(): static
    {
        return $this->state(fn(array $attributes): array => ['status' => LeaseStatus::Quotation]);
    }

    /**
     * Indicate that the lease request is a new request in the system and is awaiting further actions.
     *
     * This state modifier sets the 'status' attribute of the lease to the value representing a "new request" as defined in the LeaseStatus enum.
     * This is appropriate when generating lease instances that are freshly submitted and pending processing.
     *
     * @return static Returns the current factory instance with the 'new request' state applied.
     */
    public function newRequest(): static
    {
        return $this->state(fn(array $attributes): array => ['status' => LeaseStatus::Request]);
    }

    /**
     * Indicate that the lease request is an optional reservation.
     * This state modifier sets the 'status' attribute to the value corresponding to "option" in the LeaseStatus enum, indicating a lease that is conditionally reserved.
     *
     * @return static Returns the current factory instance with the 'option' state applied.
     */
    public function option(): static
    {
        return $this->state(fn(array $attributes): array => ['status' => LeaseStatus::Option]);
    }

    /**
     * Indicate that the lease request is a confirmed reservation.
     * This state modifier sets the 'status' attribute to the "confirmed" value as defined in the LeaseStatus enum, meaning that the lease has been fully agreed upon.
     *
     * @return static Returns the current factory instance with the 'confirmed' state applied.
     */
    public function confirmed(): static
    {
        return $this->state(fn(array $attributes): array => ['status' => LeaseStatus::Confirmed]);
    }

    /**
     * Indicate that the lease is finalized in the system.
     *
     * This state modifier sets the 'status' attribute to the "finalized" value from the LeaseStatus enum.
     * This state is used once all lease processing has been completed, marking the lease as fully closed.
     *
     * @return static Returns the current factory instance with the 'finalized' state applied.
     */
    public function finalized(): static
    {
        return $this->state(fn(array $attributes): array => ['status' => LeaseStatus::Finalized]);
    }

    /**
     * Indicate that the lease is cancelled during the processing of the request.
     * This state modifier sets the 'status' attribute to the "cancelled" value as defined in the LeaseStatus enum, representing leases that have been aborted or terminated.
     *
     * @return static Returns the current factory instance with the 'cancelled' state applied.
     */
    public function cancelled(): static
    {
        return $this->state(fn(array $attributes): array => ['status' => LeaseStatus::Cancelled]);
    }
}
