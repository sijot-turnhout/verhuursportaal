<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Filament\Resources\InvoiceResource\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'payment_reference' => $this->faker->numberBetween(1, 100_000),
            'status' => InvoiceStatus::Open,
            'description' => $this->faker->paragraph(),
        ];
    }

    /**
     * Indicate that the invoice is open
     *
     * @return Factory<\App\Models\Invoice>
     */
    public function openInvoice(): Factory
    {
        return $this->state(fn(array $attributes): array => ['status' => InvoiceStatus::Open]);
    }

    public function dueInvoice(): Factory
    {
        return $this->state(fn(array $attributes): array => ['status' => InvoiceStatus::Uncollected, 'due_at' => now()->subDay()]);
    }

    public function cancelledInvoice(): Factory
    {
        return $this->state(fn(array $attributes): array => ['status' => InvoiceStatus::Void, 'cancelled_at' => now()->subDay()]);
    }
}
