<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\Pages;

use App\Filament\Resources\InvoiceResource;
use App\Filament\Resources\LeaseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/**
 * Class EditLease
 *
 * The `EditLease` class is responsible for providing the functionality to edit lease records
 * within the system. It extends the `EditRecord` class to allow modifications to lease details
 * and integrates actions related to invoices, such as generating and viewing invoices.
 *
 * @package App\Filament\Resources\LeaseResource\Pages
 */
final class EditLease extends EditRecord
{
    /**
     * The associated resource for the edit page.
     *
     * This property links the `EditLease` page to the `LeaseResource` class, which defines
     * the schema and behavior for managing lease records.
     *
     * @var string
     */    protected static string $resource = LeaseResource::class;

    /**
     * Get the header actions available on the edit page.
     *
     * This method returns an array of actions that are displayed in the header of the edit
     * page. It includes actions for generating and viewing invoices, as well as a delete action
     * for removing the lease record.
     *
     * @return array An array of actions for the edit page header.
     */
    protected function getHeaderActions(): array
    {
        return [
            InvoiceResource\Actions\GenerateInvoice::make(),
            InvoiceResource\Actions\ViewInvoice::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
