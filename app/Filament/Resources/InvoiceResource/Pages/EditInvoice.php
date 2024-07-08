<?php

declare(strict_types=1);

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;

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
