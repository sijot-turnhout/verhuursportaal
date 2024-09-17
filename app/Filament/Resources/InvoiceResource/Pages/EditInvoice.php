<?php

declare(strict_types=1);

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/**
 * Class EditInvoice
 *
 * Represents the page for editing an existing invoice record within the Filament admin panel.
 * This class extends the `EditRecord` page from Filament, providing the configuration needed
 * for editing invoice records through the Filament resource management system.
 *
 * @package App\Filament\Resources\InvoiceResource\Pages
 */
final class EditInvoice extends EditRecord
{
    /**
     * The Filament resource class associated with this page.
     *
     * @var string
     */
    protected static string $resource = InvoiceResource::class;

    /**
     * Retrieves the actions available in the header of the edit page.
     *
     * This method defines and returns an array of actions that are displayed in the header
     * of the edit invoice page. It includes custom actions such as completing an invoice proposal
     * and deleting the invoice.
     *
     * @return array  An array of header action objects.
     */
    protected function getHeaderActions(): array
    {
        return [
            InvoiceResource\Actions\CompleteInvoiceProposalAction::make(),
            Actions\DeleteAction::make()
                ->icon('heroicon-o-document-minus')
                ->label(trans('voorstel verwijderen')),
        ];
    }
}
