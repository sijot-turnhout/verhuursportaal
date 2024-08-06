<?php

declare(strict_types=1);

namespace App\Builders;

use App\Enums\LeaseStatus;
use Illuminate\Database\Eloquent\Builder;

/**
 * @template TModelClass of \App\Models\Lease>
 * @extends Builder<\App\Models\Lease>
 */
final class LeaseBuilder extends Builder
{
    public function unlockMetrics(): bool
    {
        return $this->model->update(['metrics_registered_at' => null]);
    }

    public function setStatus(LeaseStatus $leaseStatus): bool
    {
        return $this->model->update(['status' => $leaseStatus]);
    }
}
