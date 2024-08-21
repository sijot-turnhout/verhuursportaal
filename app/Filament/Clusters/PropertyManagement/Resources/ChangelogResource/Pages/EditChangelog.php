<?php

namespace App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource\Pages;

use App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChangelog extends EditRecord
{
    protected static string $resource = ChangelogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
