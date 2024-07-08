<?php

declare(strict_types=1);

namespace App\Builders;

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
}
