<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\Pages;

use App\Enums\LeaseStatus;
use App\Filament\Resources\LeaseResource;
use App\Models\Lease;
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
            Actions\CreateAction::make()->icon('heroicon-o-plus'),
        ];
    }

    public function getTabs(): array
    {
        return [
            LeaseStatus::Request->value => $this->resourceOverviewTab('Nieuwe aanvragen', LeaseStatus::Request),
            LeaseStatus::Option->value => $this->optionResourceOverviewTab('Opties', LeaseStatus::Option, LeaseStatus::Quotation),
            LeaseStatus::Confirmed->value => $this->resourceOverviewTab('Bevestigde verhuringen', LeaseStatus::Confirmed),
            LeaseStatus::Finalized->value => $this->resourceOverviewTab('Afgesloten verhuringen', LeaseStatus::Finalized),
            LeaseStatus::Cancelled->value => $this->resourceOverviewTab('Geannuleerde aanvragen', LeaseStatus::Finalized),
            LeaseStatus::Archived->value => $this->resourceOverviewTab('Gearchiveerde verhuringen', LeaseStatus::Archived),
        ];
    }

    private function resourceOverviewTab(string $tabLabel, LeaseStatus $leaseStatus): Tab
    {
        return Tab::make(trans($tabLabel))
            ->icon($leaseStatus->getIcon())
            ->badge(Lease::query()->where('status', $leaseStatus)->count())
            ->modifyQueryUsing(function (Builder $query) use ($leaseStatus): Builder {
                return $query->where('status', $leaseStatus);
            });
    }

    private function optionResourceOverviewTab(string $tabLabel, LeaseStatus $optionStatus, LeaseStatus $quotationStatus): Tab
    {
        return Tab::make(trans('Opties'))
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
