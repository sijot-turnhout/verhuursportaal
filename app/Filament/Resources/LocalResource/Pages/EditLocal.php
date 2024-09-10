<?php

declare(strict_types=1);

namespace App\Filament\Resources\LocalResource\Pages;

use App\Filament\Resources\LocalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/**
 * Class EditLocal
 *
 * The `EditLocal` class is responsible for handling the editing of local resource records
 * within the system. It extends the `EditRecord` class to provide edit functionality and
 * integrates additional actions such as deleting the record.
 *
 * @package App\Filament\Resources\LocalResource\Pages
 */
final class EditLocal extends EditRecord
{
    /**
     * The associated resource for the edit page.
     *
     * This property links the `EditLocal` page to the `LocalResource` class, which defines
     * the resource schema and behavior for local resources.
     *
     * @var string
     */
    protected static string $resource = LocalResource::class;

    /**
     * Get the header actions available on the edit page.
     *
     * This method returns an array of actions that are displayed in the header of the edit
     * page. Currently, it adds a delete action that allows users to delete the local resource.
     *
     * @return array  An array of actions for the edit page header.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->icon('heroicon-o-trash'),
        ];
    }
}
