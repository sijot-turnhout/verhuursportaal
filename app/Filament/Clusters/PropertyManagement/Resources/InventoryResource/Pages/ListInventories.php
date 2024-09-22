<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\InventoryResource\Pages;

use App\Filament\Clusters\PropertyManagement\Resources\InventoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventories extends ListRecords
{
    protected static string $resource = InventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(trans('artikel registreren'))
                ->icon('heroicon-o-squares-plus'),
        ];
    }
}
