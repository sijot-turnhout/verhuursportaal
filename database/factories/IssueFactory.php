<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Local;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Issue>
 */
class IssueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $local = Local::factory()->create();
        $user = User::factory()->createQuietly();

        return [
            'issueable_type' => get_class($local),
            'issueable_id' => $local->id,
            'creator_id' => $user->id,
            'user_id' => $user->id,
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
        ];
    }
}
