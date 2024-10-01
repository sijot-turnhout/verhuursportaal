<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\InventoryResource\Schemas;

use App\Models\ArticleCategory;
use App\Models\Local;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;

/**
 * Class InventoryArticleForm
 *
 * InventoryArticleForm is a schema class that defines the form components
 * used to create or edit an inventory article in the property management system.
 *
 * It sets up the form's layout, input fields, validation, and relationships
 * with other models, such as the storage location.
 *
 * @package App\Filament\Clusters\PropertyManagement\Resources\InventoryResource\Schemas
 */
final readonly class InventoryArticleForm
{
    /**
     * Creates and returns the form schema for the inventory article.
     *
     * The schema includes input fields for the article name, storage location, amount,
     * and a description. Each field is defined with specific properties, such as
     * validation rules, column spans, and relationships.
     *
     * @return array  The array of form components used to define the form.
     */
    public static function make(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->label('Naam')
                ->translateLabel()
                ->required()
                ->maxLength(255)
                ->columnSpan(6),

            Forms\Components\Select::make('storage_location_id')
                ->columnSpan(4)
                ->required()
                ->relationship(name: 'storageLocation', titleAttribute: 'name')
                ->options(fn() => Local::query()->where('storage_location', true)->pluck('name', 'id'))
                ->createOptionAction(fn(Action $action) => self::configureStorageLocationFormModal($action))
                ->createOptionForm(self::storageLocationCreateForm())
                ->createOptionUsing(fn(array $data, Local $local): int => $local->query()->create(array_merge($data, ['storage_location' => true]))->getKey()),

            Forms\Components\TextInput::make('amount')
                ->label('Aantal')
                ->required()
                ->columnSpan(2)
                ->minValue(1)
                ->numeric(),

            Forms\Components\Select::make('labels')
                ->multiple()
                ->columnSpan(12)
                ->relationship('labels', 'name')
                ->options(fn() => ArticleCategory::query()->pluck('name', 'id'))
                ->searchable(),

            Forms\Components\Textarea::make('description')
                ->label(trans('Beschrijving / Extra informatie'))
                ->rows(4)
                ->columnSpan(12),
        ];
    }

    /**
     * Configures the modal for creating a new storage location.
     *
     * This method sets up a slide-over modal with a custom heading and description for registering
     * a new storage location. It provides a user-friendly interface for entering essential
     * details about the storage location.
     *
     * @param  Action $action  The action representing the modal.
     * @return void
     */
    private static function configureStorageLocationFormModal(Action $action): void
    {
        $action
            ->slideOver()
            ->modalHeading(trans('Opslaglocatie registreren'))
            ->modalDescription(trans('Alle basis informatie die benodigd is om een opslaglocatie te registreren in het systeem.'));
    }

    /**
     * Defines the form schema for creating a storage location.
     *
     * This method provides a form schema for registering a new storage location, including
     * a name and a description field, with appropriate validation and layout settings.
     *
     * @todo Refactor this method to use the form input declarations from the LocalResourceForm class to maintain consistency and avoid code duplication.
     * @see \App\Filament\Resources\LocalResource\Forms\LocalResourceForm::render()
     *
     * @return array  The schema for the storage location creation form.
     */
    private static function storageLocationCreateForm(): array
    {
        return [
            Grid::make(12)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->columnSpan(9)
                        ->label(trans('Naam'))
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),

                    Forms\Components\Textarea::make('description')
                        ->label('Beschrijving en of extra informatie')
                        ->rows(6)
                        ->columnSpan(12),
                ]),
        ];
    }
}
