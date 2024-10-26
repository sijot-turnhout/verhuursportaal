<?php

declare(strict_types=1);

namespace App\Filament\Resources\InvoiceResource\Actions;

use App\Models\Lease;
use App\Models\Quotation;
use App\Jobs\QuotationGenerator;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Gate;

final class GenerateQuotation extends Action
{
    public static function make(?string $name = null): static
    {
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
