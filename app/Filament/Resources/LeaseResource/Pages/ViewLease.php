<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\Pages;

use App\Enums\LeaseStatus;
use App\Filament\Resources\InvoiceResource\Actions\DownloadInvoiceAction;
use App\Filament\Resources\InvoiceResource\Actions\GenerateInvoice;
use App\Filament\Resources\InvoiceResource\Actions\ViewInvoice;
use App\Filament\Resources\LeaseResource;
use App\Models\Lease;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\ViewRecord;

final class ViewLease extends ViewRecord
{
    protected static string $resource = LeaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->registerStatusActions(),
            $this->registerCrudActions(),
        ];
    }

    /**
     * The method that contains all the actions that rae related to the status of the lease in the application.
     * In some action is the label method chained because the action ID strips the spaces out of the name.
     *
     * New request option is not defined in the action because it is the default state in the lease administration process.
     * When the state is changed we shouldn't be recovering from it.
     *
     * @return ActionGroup
     */
    protected function registerStatusActions(): ActionGroup
    {
        return ActionGroup::make([
            Action::make(LeaseStatus::Quotation->getLabel())
                ->label(LeaseStatus::Quotation->getLabel())
                ->color(LeaseStatus::Quotation->getColor())
                ->icon(LeaseStatus::Quotation->getIcon())
                ->visible(fn(Lease $lease): bool => $lease->state()->allowTransitionToQuotationOption())
                ->action(fn(Lease $lease) => $lease->state()->transitionToQuotationOption()),

            Action::make(LeaseStatus::Option->getLabel())
                ->label(LeaseStatus::Option->getLabel())
                ->color(LeaseStatus::Option->getColor())
                ->icon(LeaseStatus::Option->getIcon())
                ->visible(fn(Lease $lease): bool => $lease->state()->allowTransitionToOption())
                ->action(fn(Lease $lease) => $lease->state()->transitionToOption()),

            Action::make(LeaseStatus::Confirmed->getLabel())
                ->label(LeaseStatus::Confirmed->getLabel())
                ->color(LeaseStatus::Confirmed->getColor())
                ->icon(LeaseStatus::Confirmed->getIcon())
                ->visible(fn(Lease $lease): bool => $lease->state()->allowTransitionToConfirmed())
                ->action(fn(Lease $lease) => $lease->state()->transitionToConfirmed()),

            // These are final states for the lease.
            // From this states we can't recover and the lease will be registered as 'final'
            ActionGroup::make([
                Action::make(LeaseStatus::Cancelled->getLabel())
                    ->label(LeaseStatus::Cancelled->getLabel())
                    ->color(LeaseStatus::Cancelled->getColor())
                    ->icon(LeaseStatus::Cancelled->getIcon())
                    ->visible(fn(Lease $lease): bool => $lease->state()->allowTransitionToCancelled())
                    ->action(fn(Lease $lease) => $lease->state()->transitionToCancelled()),

                Action::make(LeaseStatus::Finalized->getLabel())
                    ->label(LeaseStatus::Finalized->getLabel())
                    ->color(LeaseStatus::Finalized->getColor())
                    ->icon(LeaseStatus::Finalized->getIcon())
                    ->visible(fn(Lease $lease): bool => $lease->state()->allowTransitionToFinalized())
                    ->action(fn(Lease $lease) => $lease->state()->transitionToFinalized()),
            ])->dropdown(false),
        ])
            ->button()
            ->label(trans('Markeren als'))
            ->icon('heroicon-o-tag')
            ->color('gray');
    }

    /**
     * Method for defining the actions in the information view that are related to the crud operations of the resource.
     *
     * @return ActionGroup
     */
    protected function registerCrudActions(): ActionGroup
    {
        return ActionGroup::make([
            Actions\EditAction::make()->color('gray'),
            GenerateInvoice::make(),
            ViewInvoice::make(),
            DownloadInvoiceAction::make(),

            ActionGroup::make([
                Actions\DeleteAction::make(),
            ])->dropdown(false),
        ])
            ->button()
            ->label('Acties')
            ->icon('heroicon-o-cog-8-tooth')
            ->color('primary');
    }
}
