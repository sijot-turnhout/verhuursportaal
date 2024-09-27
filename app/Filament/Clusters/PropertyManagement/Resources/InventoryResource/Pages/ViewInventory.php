<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\InventoryResource\Pages;

use App\Filament\Clusters\PropertyManagement\Resources\InventoryResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;

/**
 * Class ViewInventory
 *
 * This class is responsible for displaying the details of a single inventory record.
 * It extends the `ViewRecord` page provided by Filament and specifies the
 * `InventoryResource` as the associated resource.
 *
 * It includes header actions such as editing or deleting the inventory item.
 *
 * @package App\Filament\Clusters\PropertyManagement\Resources\InventoryResource\Pages
 */
final class ViewInventory extends ViewRecord
{
    /**
     * The resource class associated with this view page.
     *
     * This specifies that the `ViewInventory` page is tied to the `InventoryResource`.
     * It uses this resource to pull the record data and display it.
     *
     * @var string
     */
    protected static string $resource = InventoryResource::class;

    /**
     * Defines the header actions available when viewing an inventory record.
     *
     * This method returns an array of actions grouped together, such as
     * editing or deleting the current inventory item. The actions are
     * visually represented with a gray button and a cog icon.
     *
     * @return array The array of header actions.
     */
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
