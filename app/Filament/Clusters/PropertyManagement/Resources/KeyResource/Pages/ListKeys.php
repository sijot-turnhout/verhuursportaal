<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\KeyResource\Pages;

use App\Filament\Clusters\PropertyManagement\Resources\KeyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListKeys extends ListRecords
{
    protected static string $resource = KeyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Sleutel registreren')
                ->translateLabel()
                ->icon('heroicon-o-plus')
                ->modalIcon('heroicon-o-key')
                ->modalIconColor('primary')
                ->modalHeading(trans('Sleutel registreren'))
                ->modalDescription(trans('Alle nodige informatie om een sleutel te registreren en te beheren in de applicatie.'))
                ->slideOver(),
        ];
    }
}
