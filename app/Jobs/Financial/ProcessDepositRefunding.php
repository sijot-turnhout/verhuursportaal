<?php

declare(strict_types=1);

namespace App\Jobs\Financial;

use App\Filament\Clusters\Billing\Resources\DepositResource\Enums\DepositStatus;
use App\Models\Deposit;
use Illuminate\Foundation\Queue\Queueable;

final class ProcessDepositRefunding
{
    use Queueable;

    /**
     * @todo Define the array in more detail for the phpstan static analysis
     * @see https://phpstan.org/blog/solving-phpstan-no-value-type-specified-in-iterable-type
     *
     * @param  array<string, string> $formData
     * @param  Deposit $deposit
     * @return void
     */
    public function __construct(
        public readonly array $formData,
        public readonly Deposit $deposit,
    ) {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->deposit->update(attributes: array_merge(
            $this->formData,
            ['status' => DepositStatus::PartiallyRefunded, 'refunded_at' => now(), 'refunded_amount' => $this->calculateRefund()],
        ));
    }

    /**
     * @todo We need a fix up of this calculation method.
     */
    private function calculateRefund(): float
    {
        return $this->deposit->paid_amount - (float) $this->formData['revoked_amount'];
    }
}
