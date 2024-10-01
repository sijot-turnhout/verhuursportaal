<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\InventoryResource\Pages;

use App\Filament\Clusters\PropertyManagement\Resources\InventoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/**
 * This class represents the page that lists all Inventory records.
 *
 * It extends the `ListRecords` page from Filament and specifies the
 * `InventoryResource` as the associated resource. This page includes
 * actions like creating new inventory records.
 *
 * @package App\Filament\Clusters\PropertyManagement\Resources\InventoryResource
 */
final class ListInventories extends ListRecords
{
    /**
     * The resource class associated with this page.
     *
     * This tells Filament which resource (in this case, InventoryResource)
     * the `ListInventories` page should display records from.
     *
     * @var string
     */
    protected static string $resource = InventoryResource::class;

    /**
     * Defines the header actions available on the inventory list page.
     *
     * This method returns an array of actions that will appear in the
     * header of the page. Currently, it includes a "Create" action,
     * allowing users to register a new article (inventory item).
     *
     * @return array  The array of header actions.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(trans('artikel registreren'))
                ->icon('heroicon-o-squares-plus'),
        ];
    }
}
