<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\IssueResource\Support;

use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Class IssueOverviewTable
 *
 * This class is responsible for rendering the issue overview table within the Property Management system.
 * The `IssueOverviewTable` class provides a static method that is used to configure and return the table
 * settings. As a `readonly` class, it is immutable, ensuring that once instantiated, its properties cannot
 * be modified.
 *
 * @package App\Filament\Clusters\PropertyManagement\Resources\IssueResource\Support
 */
final readonly class IssueOverviewTable
{
    /**
     * Define the columns to be displayed in the issue overview table.
     *
     * This static method returns an array of columns that will be displayed in the issue overview table.
     * Each column is defined with specific properties such as label, searchability, sortability,
     * and formatting. The columns provide a user-friendly interface for viewing and managing issues.
     *
     * @return array<int, Tables\Columns\TextColumn> The array of column configurations.
     */
    public static function columns(): array
    {
        return [
            Tables\Columns\TextColumn::make('reference_number')->label('#')->weight(FontWeight::SemiBold)->searchable(),
            Tables\Columns\TextColumn::make('issueable.name')->label('Lokaal')->sortable(),
            Tables\Columns\TextColumn::make('status')->label('Status')->sortable()->badge(),
            Tables\Columns\TextColumn::make('priority')->label('Prioriteit')->sortable()->badge(),
            Tables\Columns\TextColumn::make('title')->label('Titel')->searchable(),
        ];
    }
}
