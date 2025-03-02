<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\DepositResource\Pages;

use App\Filament\Clusters\Billing\Resources\DepositResource;
use App\Filament\Clusters\Billing\Resources\DepositResource\Enums\DepositStatus;
use App\Models\Deposit;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ListDeposits
 *
 * This class represents the page for listing deposit records in the application.
 * It extends the ListRecords class provided by Filament, which provides the basic functionality
 * for listing records in a tabular format.
 *
 * The ListDeposits class is responsible for:
 * - Defining the resource associated with this page.
 * - Handling the logic when the active tab is updated.
 * - Generating the tabs for different deposit statuses.
 * - Providing header widgets for additional information or actions.
 *
 * This class is a crucial part of the billing cluster in the application, allowing users to
 * view and manage deposits efficiently.
 *
 * @package App\Filament\Clusters\Billing\Resources\DepositResource\Pages
 */
final class ListDeposits extends ListRecords
{
    /**
     * Specifies the associated resource for this view page, linking it to the DepositResource.
     *
     * @var string
     */
    protected static string $resource = DepositResource::class;

    /**
     * Method called when the active tab is updated.
     * It resets the pagination and deselects all table records.
     *
     * This method ensures that the user sees the first page of results
     * and that no records are selected when switching between tabs.
     *
     * @return void
     */
    public function updatedActiveTab(): void
    {
        $this->resetPage();
        $this->deselectAllTableRecords();
    }

    /**
     * Get the tabs for the page.
     *
     * This method generates an array of tabs based on the different deposit statuses.
     * Each tab is configured with a label, icon, badge, color and a query to filter deposits by status.
     *
     * @return array
     */
    public function getTabs(): array
    {
        return collect(DepositStatus::cases())
            ->map(
                fn(DepositStatus $status) => Tab::make()
                    ->label($status->getLabel())
                    ->icon($status->getIcon())
                    ->badgeColor($status->getColor())
                    ->query(fn(Builder $query): Builder => $query->where('status', $status))
                    ->badge(Deposit::query()->where('status', $status)->count()),
            )->toArray();
    }

    /**
     * Get the header widgets for this page.
     *
     * This method returns an array of widgets to be displayed in the header of the page.
     * These widgets can provide additional information or actions related to the deposits.
     *
     * @return array The array off header widgets.
     */
    protected function getHeaderWidgets(): array
    {
        return DepositResource::getWidgets();
    }
}
