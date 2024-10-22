<?php

declare(strict_types=1);

namespace App\Filament\Resources\QuotationResource\Pages;

use App\Enums\QuotationStatus;
use App\Filament\Resources\QuotationResource;
use App\Models\Quotation;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

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
    use ExposesTableToWidgets;

    /**
     * Specifies the resource class associated with this page.
     * This resource manages quotation records in the system.
     *
     * @var string
     */
    protected static string $resource = QuotationResource::class;

    /**
     * Returns the array of tabs to filter quotations based on their statuses.
     *
     * @return array
     */
    public function getTabs(): array
    {
        return [
            null => $this->buildTab('Alle', 'heroicon-o-list-bullet'),
            trans('Voorlopige offertes') => $this->buildStatusTab(QuotationStatus::Draft, 'heroicon-o-pencil-square'),
            trans('openstaande offertes') => $this->buildStatusTab(QuotationStatus::Open, 'heroicon-o-document-text'),
            trans('goedgekeurde offertes') => $this->buildStatusTab(QuotationStatus::Accepted, 'heroicon-o-document-check'),
            trans('afgewezen offertes') => $this->buildStatusTab(QuotationStatus::Declined, 'heroicon-o-x-circle'),
            trans('verlopen offertes') => $this->buildStatusTab(QuotationStatus::Expired, 'heroicon-o-lock-closed'),
        ];
    }

    /**
     * Builds a generic tab.
     *
     * @param  string $label The label of the tab.
     * @param  string $icon  The icon associated with the tab.
     * @return Tab
     */
    private function buildTab(string $label, string $icon): Tab
    {
        return Tab::make($label)->icon($icon);
    }

    /**
     * Builds a status-specific tab with a query filter.
     *
     * @param  QuotationStatus $status  The status to filter by.
     * @param  string $icon             The icon associated with the tab.
     * @return Tab
     */
    private function buildStatusTab(QuotationStatus $status, string $icon): Tab
    {
        return Tab::make()
            ->query(fn (Quotation $builder): Builder => $builder->where('status', $status))
            ->icon($icon);
    }
}
