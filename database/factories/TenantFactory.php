<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant>
 */
final class TenantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'firstName' => $this->faker->firstName(),
            'lastName' => $this->faker->lastName(),
            'email' => $this->faker->safeEmail(),
            'phone_number' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'created_at' => $this->faker->dateTimeBetween(now()->startOfYear(), now()->endOfYear()),
        ];
    }

    /**
     * Indicate that the tenant has been put on the blacklist in the application.
     *
     * This factory state modifier sets the 'banned_at' attribute to the current timestamp, marking the tenant as blacklisted.
     * This can be used when generating model instances that require a "banned" state for testing or seeding purposes.
     *
     * @return static Returns the current factory instance with the updated state.
     */
    public function blacklisted(): static
    {
        return $this->state(fn(array $attributes): array => ['banned_at' => now()]);
    }
}
