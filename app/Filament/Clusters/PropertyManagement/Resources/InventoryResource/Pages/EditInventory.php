<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\InventoryResource\Pages;

use App\Filament\Clusters\PropertyManagement\Resources\InventoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/**
 * This class represents the page for editing an Inventory record.
 *
 * It extends Filament's `EditRecord` page and provides custom header actions
 * (such as a delete action) that can be performed while editing the inventory.
 *
 *  @package App\Filament\Clusters\PropertyManagement\Resources\InventoryResource
 */
final class EditInventory extends EditRecord
{
    /**
     * Defines the header actions available while editing an inventory record.
     *
     * This method returns an array of actions that will appear in the
     * header of the page. In this case, it includes the "Delete" action,
     * allowing users to delete the currently edited inventory record.
     *
     * @return array The array of header actions.
     */
    protected static string $resource = InventoryResource::class;

    /**
     * Defines the header actions available while editing an inventory record.
     *
     * This method returns an array of actions that will appear in the
     * header of the page. In this case, it includes the "Delete" action,
     * allowing users to delete the currently edited inventory record.
     *
     * @return array The array of header actions.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->icon('heroicon-o-trash'),
        ];
    }
}
