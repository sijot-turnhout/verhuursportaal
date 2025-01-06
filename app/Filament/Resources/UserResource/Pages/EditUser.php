<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/**
 * Class EditUser
 *
 * Represents the page for editing an existing user record in the Filament admin panel.
 * This class extends the `EditRecord` page from Filament and specifies the resource
 * it is associated with, allowing the modification of existing user records.
 *
 * @package App\Filament\Resources\UserResource\Pages
 */
final class EditUser extends EditRecord
{
    /**
     * The resource class associated with this page.
     * This defines the resource that this page will manage.
     *
     * @var string
     */
    protected static string $resource = UserResource::class;

    /**
     * Get the header actions for this page.
     *
     * Defines the actions that will appear in the page header, such as buttons for
     * deleting the record. In this case, it includes a delete action with a trash icon.
     *
     * @return array<Actions\Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->icon('heroicon-s-trash'),
        ];
    }
}
