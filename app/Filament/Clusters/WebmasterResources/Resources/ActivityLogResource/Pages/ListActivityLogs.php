<?php

namespace App\Filament\Clusters\WebmasterResources\Resources\ActivityLogResource\Pages;

use App\Filament\Clusters\WebmasterResources\Resources\ActivityLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListActivityLogs extends ListRecords
{
    protected static string $resource = ActivityLogResource::class;

    protected function getHeaderWidgets(): array
    {
        return ActivityLogResource::getWidgets();
    }
}
