<?php

declare(strict_types=1);

namespace App\Filament\Resources\LocalResource\Pages;

use App\Filament\Resources\LocalResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

final class ViewLocal extends ViewRecord
{
    protected static string $resource = LocalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->icon('heroicon-o-pencil-square')->color('gray'),
            DeleteAction::make()->icon('heroicon-o-trash'),
        ];
    }
}
