<?php

declare(strict_types=1);

namespace App\Filament\Resources\QuotationResource\Pages;

use App\Filament\Resources\QuotationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/**
 * Class ListQuotations
 *
 * This class is responsible for displaying a list of quotations in the `QuotationResource`.
 * It extends the `ListRecords` class from Filament, providing the necessary structure to
 * show, search, and paginate records. This page also allows users to create a new quotation.
 *
 * @package App\Filament\Resources\QuotationResource\Pages
 */
final class ListQuotations extends ListRecords
{
    /**
     * Specifies the resource class associated with this page.
     * This resource manages quotation records in the system.
     *
     * @var string
     */
    protected static string $resource = QuotationResource::class;

    /**
     * Define the actions that will be displayed in the header of the list page.
     * Currently, this includes the ability to create a new quotation.
     *
     * @return array The array of actions for the header.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->icon('heroicon-o-plus'),
        ];
    }
}
