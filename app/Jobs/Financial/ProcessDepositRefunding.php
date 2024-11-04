<?php

declare(strict_types=1);

namespace App\Jobs\Financial;

use App\Filament\Clusters\Billing\Resources\DepositResource\Enums\DepositStatus;
use App\Models\Deposit;
use Illuminate\Foundation\Queue\Queueable;

final class ProcessDepositRefunding
{
    use Queueable;

    public function __construct(
        public readonly array $formData,
        public readonly Deposit $deposit,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->deposit->update(attributes: array_merge(
            $this->formData,
            ['status' => DepositStatus::PartiallyRefunded, 'refunded_at' => now(), 'refunded_amount' => $this->calculateRefund()],
        ));
    }

    private function calculateRefund(): float
    {
        return $this->deposit->paid_amount - $this->formData['revoked_amount'];
    }
}
