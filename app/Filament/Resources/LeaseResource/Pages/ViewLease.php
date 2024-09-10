<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\Pages;

use App\Filament\Resources\InvoiceResource\Actions\DownloadInvoiceAction;
use App\Filament\Resources\InvoiceResource\Actions\GenerateInvoice;
use App\Filament\Resources\InvoiceResource\Actions\ViewInvoice;
use App\Filament\Resources\LeaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

/**
 * Class ViewLease
 *
 * The `ViewLease` class is responsible for displaying the details of a lease record
 * within the system. It extends the `ViewRecord` class to provide functionality for
 * viewing a specific lease and integrates additional actions related to invoices.
 *
 * @package App\Filament\Resources\LeaseResource\Pages
 */
final class ViewLease extends ViewRecord
{
    /**
     * The associated resource for the view page.
     *
     * This property links the `ViewLease` page to the `LeaseResource` class, which defines
     * the schema and behavior for managing lease records.
     *
     * @var string
     */
    protected static string $resource = LeaseResource::class;

    /**
     * Get the header actions available on the view page.
     *
     * This method returns an array of actions that are displayed in the header of the view
     * page. It includes an action group with options for editing, generating, viewing,
     * and downloading invoices related to the lease. Additionally, it provides a delete
     * action grouped in a dropdown.
     *
     * @return array An array of actions for the view page header.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\ActionGroup::make([
                Actions\EditAction::make()->color('gray'),
                GenerateInvoice::make(),
                ViewInvoice::make(),
                DownloadInvoiceAction::make(),

                Actions\ActionGroup::make([
                    Actions\DeleteAction::make(),
                ])->dropdown(false),
            ])
                ->button()
                ->label('opties')
                ->icon('heroicon-o-cog-8-tooth')
                ->color('gray'),
        ];
    }
}
