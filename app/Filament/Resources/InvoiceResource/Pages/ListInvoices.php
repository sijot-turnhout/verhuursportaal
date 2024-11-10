<?php

declare(strict_types=1);

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use App\Filament\Resources\InvoiceResource\Enums\InvoiceStatus;
use App\Models\Invoice;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

final class ListInvoices extends ListRecords
{
    use ExposesTableToWidgets;

    /**
     * Specifies the Filament resource thatthis page belongs to.
     * This property is essential for Filament to associate this page with the
     * appropriate resource class and its configuration.
     *
     * @var string $resource The fully qualified class name of the resource.
     */
    protected static string $resource = InvoiceResource::class;

    /**
     * Generates an array of tabs for tiltering invoices based on their status.
     * Each tab displays a label, an icon, and a count of invoices for that status.
     * The count is cached for performance, and the cache duration is set to either
     * 30 or 60 seconds, depending on system load.
     *
     * @return array<int, Tab> An array of Tab Components, each representing a status filter.
     */
    public function getTabs(): array
    {
        $statuses = collect(InvoiceStatus::cases())
            ->map(fn(InvoiceStatus $status) => Tab::make()
                ->label($status->getLabel())
                ->icon($status->getIcon())
                ->badgeColor($status->getColor())
                ->query(fn(Builder $query) => $query->where('status', $status))
                ->badge(Cache::flexible($status->value . '_invoices_count', [30, 60], function () use ($status) {
                    return Invoice::query()
                        ->where('status', $status)
                        ->count();
                })))
            ->toArray();

        // Adds an "All" tab as the default view, allowing users to see all invoices.
        return array_merge([Tab::make()->label(__('Alle'))], $statuses);
    }

    /**
     * Retrieves an array of header widgets for the current page.
     *
     * This method calls `getWidgets` on the `InvoiceResource` class to obtain
     * a collection of widgets, which are displayed in the header area of the page.
     * Widgets can include varous components, such as statistics or quick action links,
     * to provide users with useful insights of shortcuts.
     *
     * @return array<string>  The array of header widget provided by the InvoiceResource.
     */
    protected function getHeaderWidgets(): array
    {
        return InvoiceResource::getWidgets();
    }

}
