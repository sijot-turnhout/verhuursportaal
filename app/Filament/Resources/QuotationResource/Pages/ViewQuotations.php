<?php

declare(strict_types=1);

namespace App\Filament\Resources\QuotationResource\Pages;

use App\Filament\Resources\QuotationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

/**
 * Class ViewQuotations
 *
 * This class represents the view page for displaying detailed information about a specific quotation record
 * within the Filament admin panel. It extends the `ViewRecord` class and specifies the `QuotationResource`
 * as the associated resource.
 *
 * Users can view the quotation details, finalize it, approve it, decline it, or delete the quotation via
 * various header actions.
 *
 * @package App\Filament\Resources\QuotationResource\Pages
 */
final class ViewQuotations extends ViewRecord
{
    /**
     * Specifies the resource class associated with this view page.
     * This resource manages the quotation records in the system.
     *
     * @var string
     */
    protected static string $resource = QuotationResource::class;

    /**
     * Define the actions that will be displayed in the header of the view page.
     * These actions include finalizing, approving, declining, and deleting the quotation.
     *
     * @return array The array of actions for the header.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->color('gray')->icon('heroicon-o-pencil-square'),
            Actions\ActionGroup::make([
                QuotationResource\Actions\MarkAsFinalizedAction::make(),
                QuotationResource\Actions\MarkAsApprovedAction::make(),
                QuotationResource\Actions\MarkAsDeclinedAction::make(),
            ])->label('Offerte status')->icon('heroicon-o-tag')->color('gray')->button(),
            Actions\DeleteAction::make()->icon('heroicon-o-trash'),
        ];
    }
}
