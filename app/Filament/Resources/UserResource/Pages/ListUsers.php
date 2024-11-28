<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/**
 * Class ListUsers
 *
 * Represents the page for listing user records in the Filament admin panel.
 * This class extends the `ListRecords` page from Filament and specifies the resource
 * it is associated with, allowing for the management and display of user records.
 *
 * @package App\Filament\Resources\UserResource\Pages
 */
final class ListUsers extends ListRecords
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
     * creating new user records. In this case, it includes a create action with a user-plus icon.
     *
     * @return array<Actions\Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->icon('heroicon-o-user-plus'),
        ];
    }
}
