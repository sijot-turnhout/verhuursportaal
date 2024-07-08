<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\Pages;

use App\Filament\Resources\InvoiceResource;
use App\Filament\Resources\LeaseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLease extends EditRecord
{
    protected static string $resource = LeaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            InvoiceResource\Actions\GenerateInvoice::make(),
            InvoiceResource\Actions\ViewInvoice::make(),
            Actions\DeleteAction::make()->icon('heroicon-o-trash'),
        ];
    }
}
