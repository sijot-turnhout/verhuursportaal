<?php

declare(strict_types=1);

namespace App\Builders;

use App\Filament\Resources\LocalResource\Enums\Status;
use Illuminate\Database\Eloquent\Builder;

/**
 * @template TModelClass of \App\Models\Issue>
 * @extends Builder<\App\Models\Issue>
 */
final class IssueBuider extends Builder
{
    public function markAsClosed(): bool
    {
        return $this->model->update(['status' => Status::Closed]);
    }

}
