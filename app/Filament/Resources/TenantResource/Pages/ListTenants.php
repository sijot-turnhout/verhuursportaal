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
     *
     * This property tells Filament which resource this page is tied to.
     * In this case, it is bound to the TenantResource, ensuring that all configuration (such as form fields, table columns, and actions) defined in TenantResource is used.
     *
     * @var string This should be the fully-qualified class name of a Filament Resource.
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
