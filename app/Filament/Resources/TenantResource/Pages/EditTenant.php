<?php

declare(strict_types=1);

namespace App\Filament\Resources\TenantResource\Pages;

use App\Filament\Resources\TenantResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/**
 * Class EditTenant
 *
 * Represents the page for editing an existing tenant record in the Filament admin panel.
 * This page extends the `EditRecord` class provided by Filament, allowing users
 * to modify tenant records with the specified resource configuration.
 *
 * @package App\Filament\Resources\TenantResource\Pages
 */
final class EditTenant extends EditRecord
{
    /**
     * The resource associated with this page.
     * This is used by Filament to determine which resource the page is associated with.
     *
     * @var string
     */
    protected static string $resource = TenantResource::class;

    /**
     * Method to define the header actions for the edit page.
     * This allows specifying actions such as delete buttons or other custom actions in the page header.
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
