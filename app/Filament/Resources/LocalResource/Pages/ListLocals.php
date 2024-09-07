<?php

declare(strict_types=1);

namespace App\Filament\Resources\LocalResource\Pages;

use App\Filament\Resources\LocalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/**
 * Class ListLocals
 *
 * The `ListLocals` class is responsible for displaying a list of local resource records
 * within the system. It extends the `ListRecords` class to provide the list functionality
 * and integrates additional actions such as creating new local resource records.
 *
 * @package App\Filament\Resources\LocalResource\Pages
 */
final class ListLocals extends ListRecords
{
    /**
     * The associated resource for the list page.
     *
     * This property links the `ListLocals` page to the `LocalResource` class, which defines
     * the schema and behavior for managing local resources.
     *
     * @var string
     */
    protected static string $resource = LocalResource::class;

    /**
     * Get the header actions available on the list page.
     *
     * This method returns an array of actions that are displayed in the header of the list
     * page. Currently, it includes a create action that allows users to add new local resource
     * records.
     *
     * @return array An array of actions for the list page header.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-squares-plus'),
        ];
    }
}
