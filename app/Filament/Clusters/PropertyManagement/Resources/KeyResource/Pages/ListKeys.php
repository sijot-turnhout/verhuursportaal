<?php

namespace App\Filament\Clusters\PropertyManagement\Resources\KeyResource\Pages;

use App\Filament\Clusters\PropertyManagement\Resources\KeyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKeys extends ListRecords
{
    protected static string $resource = KeyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Sleutel registreren')
                ->translateLabel()
                ->icon('heroicon-o-plus'),
        ];
    }
}
