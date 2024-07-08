<?php

declare(strict_types=1);

namespace App\Filament\Resources\QuotationResource\Pages;

use App\Filament\Resources\QuotationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuotation extends EditRecord
{
    protected static string $resource = QuotationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            QuotationResource\Actions\MarkAsFinalizedAction::make(),
            QuotationResource\Actions\MarkAsApprovedAction::make(),
            QuotationResource\Actions\MarkAsDeclinedAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
