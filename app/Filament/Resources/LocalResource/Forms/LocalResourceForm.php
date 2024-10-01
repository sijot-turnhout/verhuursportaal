<?php

declare(strict_types=1);

namespace App\Filament\Resources\LocalResource\Forms;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

/**
 * Class LocalResourceForm
 *
 * The `LocalResourceForm` class is responsible for rendering the form associated with managing local resources
 * within the system. It defines the form schema and layout used to capture necessary information when registering
 * or editing a local resource. The class defines a single static method, `render`, which handles the configuration
 * of the form and its fields.
 *
 * As a `readonly` class, its properties and state cannot be modified after initialization.
 *
 * @package App\Filament\Resources\LocalResource\Forms
 */
final readonly class LocalResourceForm
{
    /**
     * Renders the form for managing local resources.
     *
     * This method configures and returns the form schema used to collect essential information about the local
     * resource, such as the name, description, and whether the location serves as a storage facility.
     *
     * The form includes:
     * - A text input for the name of the location.
     * - A textarea for any additional description or information.
     * - A checkbox to specify if the location functions as a storage facility.
     *
     * The section is collapsible and in 'edit' mode, the form loads in a collapsed state by default.
     *
     * @todo Refactor the Section setup out of this function so that we only register the form inputs here.
     *       That we can use them in multiple forms like the create option in select input on the inventory article managers.
     *       See: \App\Filament\Clusters\PropertyManagement\Resources\InventoryResource\Schemas\InventoryArticleForm::class
     *
     * @param  Form $form   The instance of the Filament `Form` component being configured.
     * @return Form         The fully configured form schema.
     */
    public static function render(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(trans('Algemene informatie'))
                    ->description('Alle basis informatie die benodigd is om een lokaal te registreren in het systeem')
                    ->icon('heroicon-o-home-modern')
                    ->iconColor('primary')
                    ->collapsible()
                    ->collapsed(fn(string $operation): bool => 'edit' === $operation)
                    ->columns(12)
                    ->schema([
                        TextInput::make('name')->label('Naam')->required()->unique(ignoreRecord: true)->columnSpan(8),
                        Textarea::make('description')->label('Beschrijving en of extra informatie')->rows(6)->columnSpan(12),
                        Checkbox::make('storage_location')->label('Dit lokaal fungeert als een opslag locatie van materieel')->columnSpan(12),
                    ])
                    ->compact(),
            ]);
    }
}
