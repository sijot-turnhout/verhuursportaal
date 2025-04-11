<?php

declare(strict_types=1);

namespace App\Filament\Resources\TenantResource\Pages;

use App\Filament\Resources\TenantResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/**
 * Class EditTenant
 *
 * This class represents the page for editing an existing tenant record
 * in the Filament admin panel. It extends the EditRecord class provided by Filament,
 * thus inheriting the functionality required to display and process edit forms for records.
 *
 * The resource property associates this page with the TenantResource, ensuring
 * that the correct model and configuration are used. The getHeaderActions() method
 * defines a set of actions (in this case, a delete action with an appropriate icon)
 * that are displayed in the header of the edit page.
 *
 * @package App\Filament\Resources\TenantResource\Pages
 */
final class EditTenant extends EditRecord
{
    /**
     * The resource associated with this page.
     * This property is used by Filament to determine the resource configuration, including the form fields, table columns, and other resource-specific settings.
     */
    protected static string $resource = TenantResource::class;

    /**
     * Define the header actions for the edit page.
     *
     * This method returns an array of header actions available on the page.
     * In this instance, it includes the delete action, which allows users to remove the current tenant record, represented by an icon.
     *
     * @return array<Actions\Action> An array of actions to be displayed in the page header.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->icon('heroicon-o-user-minus'),
        ];
    }
}
