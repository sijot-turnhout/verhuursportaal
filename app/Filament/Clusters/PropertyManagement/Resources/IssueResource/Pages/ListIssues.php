<?php

namespace App\Filament\Clusters\PropertyManagement\Resources\IssueResource\Pages;

use App\Filament\Clusters\PropertyManagement\Resources\IssueResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIssues extends ListRecords
{
    protected static string $resource = IssueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
