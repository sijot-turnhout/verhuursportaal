<?php

declare(strict_types=1);

namespace App\Filament\Resources\InvoiceResource\Actions;

use App\Jobs\InvoiceGenerator;
use App\Models\Invoice;
use App\Models\Lease;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Gate;

/**
 * Class GenerateInvoice
 *
 * Defines an action that allows users to generate an invoice related to a lease in the Filament administrative interface.
 *
 * @see \App\Policies\LeasePolicy::generateInvoice()
 */
final class GenerateInvoice extends Action
{
    /**
     * Method to build up the action that is responsible for generating invoices that are related to the lease.
     *
     * This method configures the action button, including its label, color, icon,
     * visibility conditions, and the action to be performed when the button is clicked.
     *
     * @param  string|null  $name  The name of the action; it will be displayed as the button label.
     * @return static The configured action instance.
     */
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? trans('facturatie voorstel'))
            ->color('gray')
            ->icon('heroicon-o-plus')
            ->visible(fn(Lease $record): bool => Gate::allows('generate-invoice', $record))
            ->action(function (Lease $record): void {
                InvoiceGenerator::process($record, trans('Facturatie voor de verhuringsperiode (:start tot en met :end)', [
                    'start' => $record->arrival_date->format('d/m/Y'),
                    'end' => $record->departure_date->format('d/m/Y'),
                ]));

                Notification::make()->title('De factuur is met success aangemaakt')->success()->send();
            });
    }
}
