<?php

declare(strict_types=1);

namespace App\Filament\Resources\QuotationResource\Pages;

use App\Filament\Resources\QuotationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

/**
 * Class ViewQuotations
 *
 * This class represents the view page for viewing details of a quotation record within the Filament admin panel.
 * It extends the ViewRecord class and specifies the QuotationResource as the associated resource.
 */
final class ViewQuotations extends ViewRecord
{
    /**
     * The resource class associated with this view page.
     *
     * @var string
     */
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
