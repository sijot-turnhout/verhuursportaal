<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\Pages;

use App\Enums\LeaseStatus;
use App\Filament\Resources\LeaseResource;
use App\Models\Lease;
use Filament\Actions;
use Filament\Actions\CreateAction;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

/**
 * Class ListLeases
 *
 * The `ListLeases` class is responsible for displaying a list of lease records within
 * the system. It extends the `ListRecords` class to provide the functionality for listing
 * lease records and integrates additional actions such as creating new leases.
 *
 * @package App\Filament\Resources\LeaseResource\Pages
 */
final class ListLeases extends ListRecords
{
    /**
     * The associated resource for the list page.
     *
     * This property links the `ListLeases` page to the `LeaseResource` class, which defines
     * the schema and behavior for managing lease records.
     *
     * @var string
     */
    protected static string $resource = LeaseResource::class;

    /**
     * Handle the event when the active tab is updated.
     *
     * This method is triggered whenever the active tab changes in the interface. It performs two actions:
     *
     * 1. Resets the pagination to the first page, ensuring that users see a fresh set of records that correspond to the newly selected tab.
     * 2. Deselects all previously selected table records to avoid any accidental actions that might be carried over from the previous tab.
     *
     * This method helps maintain consistency in the UI and ensures that no records from
     * the previous tab remain selected when switching to a new one.
     *
     * @return void
     */
    public function updatedActiveTab(): void
    {
        $this->resetPage();
        $this->deselectAllTableRecords();
    }

    /**
     * Generates an array of tabs for displaying leases grouped by status.
     *
     * This method creates a default "all" tab, then dynamically generates a tab
     * for each defined lease status. Each status-specific tab is labeled,
     * colored, and includes a badge count indicating the number of leases with
     * that status. The badge count is cached for efficient performance.
     *
     * @return array<int, Tab> An array of configured Tab objects, representing each lease status.
     */
    public function getTabs(): array
    {
        $statuses = collect(LeaseStatus::cases())
            ->map(
                fn(LeaseStatus $status) => Tab::make()
                    ->label($status->getLabel())
                    ->icon($status->getIcon())
                    ->badgeColor($status->getColor())
                    ->query(fn(Builder $query): Builder => $query->where('status', $status))
                    ->badge(Lease::query()->where('status', $status)->count()),
            )->toArray();

        return array_merge($this->configureDefaulttab(), $statuses);
    }

    /**
     * Configures the default tab for displaying all leases.
     *
     * This tab is labeled "alle" and displays the total count of leases,
     * regardless of status. The count is cached to optimize performance.
     *
     * @return array<int, Tab> An array containing the default Tab object.
     */
    public function configureDefaultTab(): array
    {
        return [
            Tab::make()
                ->label(__('alle'))
                ->icon('heroicon-o-queue-list')
                ->badge(Cache::flexible('all_leases_count', [30, 60], fn() => Lease::query()->count())),
        ];
    }

    /**
     * @return array<int, CreateAction>
     */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Aanvraag toevoegen')
                ->icon('heroicon-o-plus'),
        ];
    }
}
