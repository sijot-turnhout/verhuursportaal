<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\InventoryResource\Schemas;

use Filament\Tables;

/**
 * Class InventoryArticlesTable
 *
 * This class defines the schema for the table that displays inventory articles.
 * It contains a set of columns to display article attributes such as name, loanability, storage location, etc.
 *
 * @package App\Filament\Clusters\PropertyManagement\Resources\InventoryResource\Schemas
 */
final readonly class InventoryArticlesTable
{
    /**
     * Builds and returns the schema for the inventory articles table.
     *
     * This method defines the columns that will be shown in the inventory articles table, including
     * the article name, loanability status, storage location, total amount, and description.
     *
     * @return array  An array of table column definitions.
     */
    public static function make(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->label('Artikel')
                ->translateLabel()
                ->searchable()
                ->sortable(),

            Tables\Columns\IconColumn::make('is_loanable')
                ->boolean()
                ->label('Uitleenbaar')
                ->grow(false)
                ->translateLabel(),

            Tables\Columns\TextColumn::make('storageLocation.name')
                ->label('Opslaglocatie')
                ->translateLabel()
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('total_amount')
                ->label('Aantal')
                ->sortable()
                ->translateLabel(),

            Tables\Columns\TextColumn::make('description')
                ->label('Beschrijving')
                ->translateLabel()
                ->searchable()
                ->placeholder(trans('(geen beschrijving opgegeven)')),
        ];
    }
}
