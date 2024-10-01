<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\ArticleCategoryResource\Pages;

use App\Filament\Clusters\PropertyManagement\Resources\ArticleCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArticleCategory extends EditRecord
{
    protected static string $resource = ArticleCategoryResource::class;

    public function getRelationManagers(): array
    {
        return [];
    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
