<?php

declare(strict_types=1);

namespace App\Builders;

use App\Filament\Resources\LocalResource\Enums\Status;
use Illuminate\Database\Eloquent\Builder;
use JetBrains\PhpStorm\Deprecated;

/**
 * @template TModelClass of \App\Models\Issue
 * @extends Builder<\App\Models\Issue>
 */
final class IssueBuilder extends Builder
{
    #[Deprecated(reason: 'In favor of the new setStatus method on models. (See GH #108)', since: '1.0')]
    public function markAsClosed(): bool
    {
        return $this->model->update(['status' => Status::Closed, 'closed_at' => now()]);
    }

}
