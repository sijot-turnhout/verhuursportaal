<?php

declare(strict_types=1);

namespace App\Filament\Resources\InvoiceResource\Actions;

use App\Jobs\QuotationGenerator;
use App\Models\Lease;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Gate;

/**
 * Class GenerateQuotation
 *
 * This action is responsible for generating a new quotation for a specified lease record.
 * It checks if the authenticated user has permission to generate the quotation, and if
 * allowed, it utilizes the QuotationGenerator job to create it. A success notification is
 * displayed upon completion.
 *
 * @package App\Filament\Resources\InvoiceResource\Actions
 */
final class GenerateQuotation extends Action
{
    public static function make(?string $name = null): static
    {
        /**
         * Create and configure a new instance of the GenerateQuotation action.
         *
         * This method customizes the action's appearance, visibility, and the callback
         * logic executed when the action is triggered. It only displays the action button
         * if the user has permission to generate quotations for the given lease record.
         *
         * @param string|null $name  The display name of the action (optional).
         * @return static            The configured action instance.
         */
        return parent::make($name ?? trans('offerte opstellen'))
            ->color('gray')
            ->icon('heroicon-o-plus')
            ->visible(fn(Lease $record): bool => Gate::allows('generate-quotation', $record))
            ->action(function (Lease $record): void {
                QuotationGenerator::process($record, $record->tenant);

                Notification::make()
                    ->title('De offerte is met success aangemaakt')
                    ->success()
                    ->send();
            });
    }
}
