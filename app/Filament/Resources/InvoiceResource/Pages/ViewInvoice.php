<?php

declare(strict_types=1);

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use App\Filament\Resources\InvoiceResource\Actions\DownloadInvoiceAction;
use App\Filament\Resources\InvoiceResource\Actions\PaymentStatus\MarkAsPaidAction;
use App\Filament\Resources\InvoiceResource\Actions\PaymentStatus\MarkAsUncollectedAction;
use App\Filament\Resources\InvoiceResource\Actions\PaymentStatus\MarkAsVoidedAction;
use Filament\Actions;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

final class ViewInvoice extends ViewRecord
{
    protected static string $resource = InvoiceResource::class;

    public function getHeaderActions(): array
    {
        return [
            Actions\ActionGroup::make([MarkAsPaidAction::make(), MarkAsUncollectedAction::make(), MarkAsVoidedAction::make()])
                ->label('Factuur status')
                ->color('warning')
                ->button(),

            DownloadInvoiceAction::make(),
            EditAction::make()->color('gray')->icon('heroicon-o-pencil-square'),
            DeleteAction::make()->color('danger')->icon('heroicon-o-trash'),
        ];
    }
}
