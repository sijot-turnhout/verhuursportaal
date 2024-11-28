<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\KeyResource\Pages;

use App\Filament\Clusters\PropertyManagement\Resources\KeyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;


/**
 * This class represents the page in the Filament admin panel that lists all the keys.
 * It handles displaying the keys and provides actions like creating a new key registration.
 *
 * @package App\Filament\Clusters\PropertyManagement\Resources\KeyResource\Pages
 */
final class ListKeys extends ListRecords
{
    /**
     * Connects this page to the KeyResource, which defines how keys are mapped.
     *
     * @var string
     */
    protected static string $resource = KeyResource::class;

    /**
     * Defines the actions available in the header of the page, such as creating a new key.
     * Returns an array of actions.
     *
     * @return array|Actions\Action[]|Actions\ActionGroup[]
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(trans('Sleutel registreren'))
                ->translateLabel()
                ->icon('heroicon-o-plus')
                ->modalIcon('heroicon-o-key')
                ->modalIconColor('primary')
                ->modalHeading(trans('Sleutel registreren'))
                ->modalDescription(trans('Alle nodige informatie om een sleutel te registreren en te beheren in de applicatie.'))
                ->slideOver()
                ->createAnother(false)
                ->modalSubmitActionLabel('Sleutel registreren'),
        ];
    }
}
