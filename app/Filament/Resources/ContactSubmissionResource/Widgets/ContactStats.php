<?php

declare(strict_types=1);

namespace App\Filament\Resources\ContactSubmissionResource\Widgets;

use App\Models\ContactSubmission;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

final class ContactStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Alle contactnames', ContactSubmission::count()),
            Stat::make('Nieuwe contactnames', $this->statusCountBaseQuery('nieuwe contactname')),
            Stat::make('Contactnames in behandeling', $this->statusCountBaseQuery('in behandeling')),
            Stat::make('Behandelde contactnames', $this->statusCountBaseQuery('behandeld')),
        ];
    }

    protected function statusCountBaseQuery(string $status): int
    {
        return ContactSubmission::query()->where('status', $status)->count();
    }
}
