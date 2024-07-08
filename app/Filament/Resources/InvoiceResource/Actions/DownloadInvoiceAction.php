<?php

declare(strict_types=1);

namespace App\Filament\Resources\InvoiceResource\Actions;

use App\Models\Invoice;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Gate;

/**
 * Class DownloadInvoiceAction
 *
 * Defines an action that allows users to download an invoice in the Filament administrative interface.
 *
 * @see \App\Policies\InvoicePolicy::downloadInvoice()
 */
final class DownloadInvoiceAction extends Action
{
    /**
     * Create a new instance of the DownloadInvoiceAction.
     *
     * This method configures the action button, including its label, icon, URL,
     * color, visibility conditions, and whether the URL should open in a new tab.
     *
     * @param  string|null  $name  Optional name parameter for the action button. If not provided, a default label is used.
     * @return static The configured action instance.
     */
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? trans('exporteer factuur'))
            ->icon('heroicon-o-arrow-down-tray')
            ->url(fn(Invoice $record) => route('invoices.download', $record))
            ->color('gray')
            ->visible(fn(Invoice $invoice) => Gate::allows('download-invoice', $invoice))
            ->openUrlInNewTab();
    }
}
