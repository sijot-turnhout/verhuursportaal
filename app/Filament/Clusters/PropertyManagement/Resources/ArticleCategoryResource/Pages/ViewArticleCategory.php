<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\ArticleCategoryResource\Pages;

use App\Filament\Clusters\PropertyManagement\Resources\ArticleCategoryResource;
use App\Filament\Resources\ArticleCategoryResource\RelationManagers\ArticlesRelationManager;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;

final class ViewArticleCategory extends ViewRecord
{
    protected static string $resource = ArticleCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ActionGroup::make([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
                ->button()
                ->color('gray')
                ->icon('heroicon-o-cog-8-tooth'),
        ];
    }
}
