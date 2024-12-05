<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;

/**
 * Class ViewUser
 *
 * The 'ViewUser' class is designed for community use in managing user information.
 * It extends the 'ViewRecord' class and provides a user interface to display updated, and delete user accounts.
 *
 * @package App\Filament\Resources\UserResource\Pages
 */
final class ViewUser extends ViewRecord
{
    /**
     * Links the page to the associated UserResource, which defines the core schema for users.
     *
     * By associating this wiew with UserResource, the system knows that this page
     * is solely responsible for displaying the information that is connected to this user in the application.
     *
     * @var string
     */
    protected static string $resource = UserResource::class;

    /**
     * Get the header actions for this page.
     *
     * Defines the actions that will appear in the header page, such as buttons for
     * deleting/editing the record. In this case, it includes both actions with the needed icons.
     *
     * @return array<int, Actions\Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->icon('heroicon-o-pencil-square')->color('gray'),
            Actions\DeleteAction::make()->icon('heroicon-o-trash'),
        ];
    }
}
