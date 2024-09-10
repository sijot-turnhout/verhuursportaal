<?php

declare(strict_types=1);

namespace App\Filament\Resources\QuotationResource\Pages;

use App\Filament\Resources\QuotationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/**
 * Class EditQuotation
 *
 * This class is responsible for handling the editing of existing quotation records
 * in the `QuotationResource`. It extends the `EditRecord` class provided by Filament,
 * which gives access to form rendering and record update logic.
 *
 * @package App\Filament\Resources\QuotationResource\Pages
 */
final class EditQuotation extends EditRecord
{
    /**
     * Specifies the resource class associated with this page.
     * This resource manages quotation records in the system.
     *
     * @var string
     */
    protected static string $resource = QuotationResource::class;

    /**
     * Define the header actions for the Edit Quotation page.
     * These actions allow users to finalize, approve, decline, or delete a quotation.
     *
     * @return array The array of actions to be rendered in the header.
     */
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
