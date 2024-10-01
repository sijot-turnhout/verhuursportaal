<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\ArticleCategoryResource\Pages;

use App\Filament\Clusters\PropertyManagement\Resources\ArticleCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateArticleCategory extends CreateRecord
{
    protected static string $resource = ArticleCategoryResource::class;
}
