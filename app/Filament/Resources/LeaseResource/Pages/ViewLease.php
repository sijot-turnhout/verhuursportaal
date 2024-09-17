<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\Pages;

use App\Enums\LeaseStatus;
use App\Filament\Resources\InvoiceResource\Actions\DownloadInvoiceAction;
use App\Filament\Resources\InvoiceResource\Actions\GenerateInvoice;
use App\Filament\Resources\InvoiceResource\Actions\ViewInvoice;
use App\Filament\Resources\LeaseResource;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

/**
 * Class ViewLease
 *
 * The `ViewLease` class is responsible for displaying the details of a lease record
 * within the system. It extends the `ViewRecord` class to provide functionality for
 * viewing a specific lease and integrates additional actions related to invoices.
 *
 * @package App\Filament\Resources\LeaseResource\Pages
 */
final class ViewLease extends ViewRecord
{
    /**
     * The associated resource for the view page.
     *
     * This property links the `ViewLease` page to the `LeaseResource` class, which defines
     * the schema and behavior for managing lease records.
     *
     * @var string
     */
    protected static string $resource = LeaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->registerStatusManipulationActions(),
            $this->registerManipulationActions(),
        ];
    }

    protected function registerStatusManipulationActions(): ActionGroup
    {
        return ActionGroup::make([
            $this->changeStateTransitionAction(state: LeaseStatus::Option),
        ])
            ->button()
            ->label(trans('markeren als'))
            ->icon('heroicon-o-tag')
            ->color('gray');
    }

    protected function registerManipulationActions(): ActionGroup
    {
        return ActionGroup::make([
            EditAction::make()->color('gray'),
            GenerateInvoice::make(),
            ViewInvoice::make(),
            DownloadInvoiceAction::make(),

            ActionGroup::make([
                DeleteAction::make(),
            ])->dropdown(false),
        ])
            ->button()
            ->label('opties')
            ->icon('heroicon-o-cog-8-tooth')
            ->color('primary');
    }

    private function changeStateTransitionAction(LeaseStatus $state): Action
    {
        return Action::make(trans($state->getLabel()))
            ->color($state->getColor())
            ->icon($state->getIcon());
    }
}
