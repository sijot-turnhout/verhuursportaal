<?php

declare(strict_types=1);

namespace App\Filament\Clusters\WebmasterResources\Resources\ActivityLogResource\Pages;

use App\Filament\Clusters\WebmasterResources\Resources\ActivityLogResource;
use Filament\Resources\Pages\ListRecords;

final class ListActivityLogs extends ListRecords
{
    protected static string $resource = ActivityLogResource::class;

    protected function getHeaderWidgets(): array
    {
        return ActivityLogResource::getWidgets();
    }
}
