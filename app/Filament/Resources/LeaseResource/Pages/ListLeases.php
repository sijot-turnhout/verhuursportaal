<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\Pages;

use App\Enums\LeaseStatus;
use App\Filament\Resources\LeaseResource;
use App\Models\Lease;
use CodeWithDennis\FactoryAction\FactoryAction;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

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
     * Get the header actions available on the list page.
     *
     * This method returns an array of actions that are displayed in the header of the list
     * page. It currently includes a create action that allows users to add new lease records.
     *
     * @return array An array of actions for the list page header.
     */
    protected function getHeaderActions(): array
    {
        return [
            FactoryAction::make()
                ->color('gray')
                ->slideOver()
                ->label('Genereer test verhuringen')
                ->icon('heroicon-o-wrench'),

            Actions\CreateAction::make()
                ->icon('heroicon-o-plus')
                ->visible(Lease::query()->count() > 0),
        ];
    }

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
     * Generates an array of tabs for the resource view, each representing a different lease status.
     *
     * The tabs represent various stages in the leasing process, and each tab displays a filtered view
     * of the records based on their lease status. Additionally, there is an 'archive' tab that shows
     * the soft-deleted (archived) leases.
     *
     * @return array<string, mixed>  An array of tab configurations, where each tab is mapped to a specific lease status or archive view.
     */
    public function getTabs(): array
    {
        return [
            LeaseStatus::Request->value => $this->resourceOverviewTab('Nieuwe aanvragen', LeaseStatus::Request),
            LeaseStatus::Option->value => $this->optionResourceOverviewTab('Opties', LeaseStatus::Option, LeaseStatus::Quotation),
            LeaseStatus::Confirmed->value => $this->resourceOverviewTab('Bevestigde verhuringen', LeaseStatus::Confirmed),
            LeaseStatus::Finalized->value => $this->resourceOverviewTab('Afgesloten verhuringen', LeaseStatus::Finalized),
            LeaseStatus::Cancelled->value => $this->resourceOverviewTab('Geannuleerde aanvragen', LeaseStatus::Finalized),

            // Archive tab for soft-deleted leases
            // Note that the soft deletes system from laravel is used to mark leases/requests as archieved.
            'archive' => Tab::make(trans('Archief'))
                ->icon('heroicon-o-archive-box')
                ->badge(Lease::query()->onlyTrashed()->count())
                ->modifyQueryUsing(function (Builder $query): Builder {
                    return $query->onlyTrashed();
                }),
        ];
    }

    /**
     * Creates a resource overview tab for a specific lease status.
     *
     * This method generates a tab that filters lease records based on the provided `LeaseStatus`.
     * It also adds an icon and a badge displaying the count of records that match the lease status.
     *
     * @param  string       $tabLabel     The label displayed on the tab (translatable).
     * @param  LeaseStatus  $leaseStatus  The lease status used to filter records for this tab.
     * @return Tab                        A tab configuration object with filters applied to the corresponding lease status.
     */
    private function resourceOverviewTab(string $tabLabel, LeaseStatus $leaseStatus): Tab
    {
        return Tab::make(trans($tabLabel))
            ->icon($leaseStatus->getIcon())
            ->badge(Lease::query()->where('status', $leaseStatus)->count())
            ->modifyQueryUsing(function (Builder $query) use ($leaseStatus): Builder {
                return $query->where('status', $leaseStatus);
            });
    }

    /**
     * Creates a resource overview tab for the 'Option' and 'Quotation' statuses.
     *
     * This method generates a tab that filters lease records based on both the 'Option' and 'Quotation' statuses.
     * It applies the necessary filters, adds an icon, and shows a badge displaying the count of matching records.
     *
     * @param  string       $tabLabel            The label displayed on the tab (translatable).
     * @param  LeaseStatus  $optionStatus        The primary lease status ('Option') used for filtering.
     * @param  LeaseStatus  $quotationStatus     The secondary lease status ('Quotation') used for filtering.
     * @return Tab                               A tab configuration object with filters applied to the 'Option' and 'Quotation' statuses.
     */
    private function optionResourceOverviewTab(string $tabLabel, LeaseStatus $optionStatus, LeaseStatus $quotationStatus): Tab
    {
        return Tab::make(trans($tabLabel))
            ->icon($optionStatus->getIcon())
            ->badge(
                badge: Lease::query()
                    ->where('status', $optionStatus)
                    ->orWhere('status', $quotationStatus)
                    ->count()
            )
            ->modifyQueryUsing(function (Builder $query) use ($optionStatus, $quotationStatus): Builder {
                return $query->where('status', $optionStatus)
                    ->orWhere('status', $quotationStatus);
            });
    }
}
