<?php

declare(strict_types=1);

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use App\Filament\Resources\InvoiceResource\Actions\DownloadInvoiceAction;
use App\Filament\Resources\InvoiceResource\Actions\PaymentStatus\MarkAsPaidAction;
use App\Filament\Resources\InvoiceResource\Actions\PaymentStatus\MarkAsUncollectedAction;
use App\Filament\Resources\InvoiceResource\Actions\PaymentStatus\MarkAsVoidedAction;
use App\Filament\Resources\InvoiceResource\Enums\InvoiceStatus;
use App\Models\Invoice;
use Filament\Actions;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Gate;

/**
 * Class ViewInvoice
 *
 * Represents the page for viewing an existing invoice record within the Filament admin panel.
 * This class extends the `ViewRecord` page from Filament, providing the configuration needed
 * for displaying and interacting with invoice records through the Filament resource management system.
 *
 * @package App\Filament\Resources\InvoiceResource\Pages
 */
final class ViewInvoice extends ViewRecord
{
    /**
     * The Filament resource class associated with this page.
     *
     * @var string
     */
    protected static string $resource = InvoiceResource::class;

    /**
     * Retrieves the actions available in the header of the view page.
     *
     * This method defines and returns an array of actions that are displayed in the header
     * of the view invoice page. It includes actions for marking the invoice with different
     * payment statuses, downloading the invoice, editing the invoice, and deleting the invoice.
     *
     * @return array  An array of header action objects.
     */
    public function getHeaderActions(): array
    {
        return [
            InvoiceResource\Actions\CompleteInvoiceProposalAction::make(),
            Actions\ActionGroup::make([MarkAsPaidAction::make(), MarkAsUncollectedAction::make(), MarkAsVoidedAction::make()])
                ->label('Betalingsstatus')
                ->color('gray')
                ->button(),

            DownloadInvoiceAction::make(),
            EditAction::make()->color('gray')->icon('heroicon-o-pencil-square'),
            DeleteAction::make()->color('danger')->icon('heroicon-o-trash'),
        ];
    }
}
