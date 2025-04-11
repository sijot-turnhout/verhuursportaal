<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\UserGroup;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
final class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'user_group' => UserGroup::Rvb,
            'email_verified_at' => now(),
            'phone_number' => fake()->phoneNumber(),
            'password' => self::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * This method updates the user state by setting the 'email_verified_at' attribute to null,
     * meaning that the email is not verified. This is useful for testing scenarios where a user
     * should not be considered as having a verified email.
     *
     * @return static Returns the current factory instance with the updated unverified state.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes): array => ['email_verified_at' => null]);
    }

    /**
     * Indicate that the user has the webmaster role in the application.
     *
     * This method updates the user state by setting the 'user_group' attribute to the value
     * representing the Webmaster role. Use this state when testing or seeding users with elevated access.
     *
     * @return static Returns the current factory instance with the updated state for a webmaster user.
     */
    public function webmaster(): static
    {
        return $this->state(fn(array $attributes): array => ['user_group' => UserGroup::Webmaster]);
    }
}
