<?php

declare(strict_types=1);

namespace App\Filament\Resources\TenantResource\Pages;

use App\Filament\Resources\TenantResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/**
 * Class ListTenants
 *
 * Represents the page for listing tenant records in the Filament admin panel.
 * This page extends the `ListRecords` class provided by Filament, allowing users
 * to view and manage a list of tenant records with the specified resource configuration.
 *
 * @package App\Filament\Resources\TenantResource\Pages
 */
final class ListTenants extends ListRecords
{
    /**
     * The resource associated with this page.
     * This is used by Filament to determine which resource the page is associated with.
     *
     * @var string
     */
    protected static string $resource = TenantResource::class;

    /**
     * Method to define the header actions for the list page.
     * This allows specifying actions such as create buttons or other custom actions in the page header.
     *
     * @return array<Actions\Action> An array of actions to be displayed in the page header.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->icon('heroicon-o-user-plus'),
        ];
    }
}
