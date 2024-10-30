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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Class ListQuotations
 *
 * This class represents a Filament page responsible for displaying a list of quotation records.
 * It extends Filament's ListRecords to provide features such as listing, searching, filtering,
 * and pagination of quotations within the `QuotationResource`.
 *
 * The ListQuotations page includes tabs that filter quotations by their various statuses.
 * Each tab dynamically shows a badge with the count of quotations matching the specific status.
 *
 * @package App\Filament\Resources\QuotationResource\Pages
 */
final class ListQuotations extends ListRecords
{
    use ExposesTableToWidgets;

    /**
     * Specifies the Filament resource that this page belongs to.
     * This property is essential for Filament to associate this page with the
     * appropriate resource class and its configuration.
     *
     * @var string $resource The fully qualified class name of the resource.
     */
    protected static string $resource = QuotationResource::class;

    /**
     * Generates an array of tabs for filtering quotations based on their status.
     * Each tab displays a label, an icon, and a count of quotations for that status.
     * The count is cached for performance, and the cache duration is set to either
     * 30 or 60 seconds, depending on system load.
     *
     * @return array An array of Tab components, each representing a status filter.
     */
    public function getTabs(): array
    {
        $statuses = collect(QuotationStatus::cases())
            ->map(fn(QuotationStatus $status) => Tab::make()
                ->label(Str::plural($status->getLabel()))
                ->icon($status->getIcon())
                ->badgeColor($status->getColor())
                ->query(fn(Builder $query) => $query->where('status', $status))
                ->badge(Cache::flexible($status->value . '_quotations_count', [30, 60], function () use ($status) {
                    return Quotation::query()
                        ->where('status', $status)
                        ->count();
                })))
            ->toArray();

        // Adds an "All" tab as the default view, allowing users to see all quotations.
        return array_merge([Tab::make()->label(__('Alle'))], $statuses);
    }
}
