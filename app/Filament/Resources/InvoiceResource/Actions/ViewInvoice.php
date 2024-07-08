<?php

declare(strict_types=1);

namespace App\Filament\Resources\InvoiceResource\Actions;

use App\Models\Lease;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Gate;

/**
 * Class ViewInvoice
 *
 * Defines an action that allows users to view an invoice related to a record in the Filament administrative interface.
 *
 * @see \App\Policies\LeasePolicy::viewInvoice()
 */
final class ViewInvoice extends Action
{
    /**
     * Create a new instance of the ViewInvoice action.
     *
     * This method configures the action button, including its label, icon, URL,
     * color, and visibility conditions.
     *
     * @param  string|null  $name  The name of the action; it will be displayed as the button label.
     * @return static The configured action instance.
     */
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? trans('Bekijk factuur'))
            ->icon('heroicon-o-eye')
            ->visible(fn(Lease $record): bool => Gate::allows('view-invoice', $record))
            ->url(fn(Lease $record): string => route('filament.admin.resources.invoices.edit', $record->invoice))
            ->color('gray');
    }
}
