<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\Pages;

use App\Filament\Clusters\Billing\Resources\DepositResource\Actions as DepositActions;
use App\Filament\Clusters\Billing\Resources\QuotationResource\Actions\ViewQuotation;
use App\Filament\Resources\InvoiceResource\Actions\GenerateInvoice;
use App\Filament\Resources\InvoiceResource\Actions\GenerateQuotation;
use App\Filament\Resources\InvoiceResource\Actions\ViewInvoice;
use App\Filament\Resources\LeaseResource;
use App\Filament\Resources\LeaseResource\Actions\AssignAuthenticatedUserAction;
use App\Filament\Resources\LeaseResource\Actions\StateTransitions;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

/**
 * Class ViewLease
 *
 * The `ViewLease` class is designed for community use in managing lease details.
 * It extends the `ViewRecord` class and provides a user interface to display, update, and
 * transition the status of a specific lease. This class includes tools for navigating through
 * different states of the lease lifecycle, registering deposits, and generating financial documents.
 *
 * This documentation aims to help community members understand and use each part of this codebase,
 * with additional resources like actions and guards that support accessibility and reliability for lease management.
 *
 * @todo Refine individual method documentation to make each transition and action more descriptive.
 * @package App\Filament\Resources\LeaseResource\Pages
 */
final class ViewLease extends ViewRecord
{
    /**
     * Links this page to the LeaseResource, which defines the core schema for leases.
     *
     * By associating this view with LeaseResource, the system knows that this page manages leases,
     * ensuring that actions taken here are applied specifically to lease records.
     *
     * @var string
     */
    protected static string $resource = LeaseResource::class;

    /**
     * Configures the main header actions available in the lease view.
     *
     * @return array<int, ActionGroup> Contains groups of actions for managing deposits, statuses, and general options.
     */
    protected function getHeaderActions(): array
    {
        return [
            $this->registerDepositActions(),
            $this->registerStatusManipulationActions(),
            $this->registerManipulationActions(),
        ];
    }

    /**
     * Defines and configures actions for manipulating the lease's status.
     *
     * This method provides options for updating the status of a lease, only allowing transitions
     * when they are permitted by the current state. These actions help users navigate the lifecycle
     * of a lease—from request to confirmation to cancellation.
     *
     * @return ActionGroup  The configured action groud that contains the state trkansition action classes.
     */
    protected function registerStatusManipulationActions(): ActionGroup
    {
        return ActionGroup::make([
            StateTransitions\TransitionToOptionAction::make(),
            StateTransitions\TransitionToQuotationAction::make(),
            StateTransitions\TransitionToConfirmedAction::make(),
            StateTransitions\TransitionToFinalizedAction::make(),
            StateTransitions\TransitionToCancelledAction::make(),
        ])
            ->button()
            ->label(trans('markeren als')) // Label translates to 'Mark as' for accessibility.
            ->icon('heroicon-o-tag')
            ->color('gray');
    }

    /**
     * Configures deposit-related actions, such as registering or viewing deposits.
     *
     * Allows users to easily access deposit information, view existing deposits, and initiate
     * financial transactions, all within the same interface.
     *
     * @return ActionGroup  An action group with options for registering and viewing deposits.
     */
    protected function registerDepositActions(): ActionGroup
    {
        return ActionGroup::make([
            ActionGroup::make([
                DepositActions\RegisterDepositAction::make(),
            ])->dropdown(false),

            GenerateInvoice::make(),
            GenerateQuotation::make(),
            ViewInvoice::make(),
            ViewQuotation::make(),
        ])
            ->color('gray')
            ->icon('heroicon-o-banknotes')
            ->label(trans('Financiën')) // Label translates to 'Finances' for ease of understanding.
            ->button();
    }

    /**
     * Registers general options for lease record manipulation, including editing and deleting leases.
     *
     * These options are gathered in a simple, accessible group to support common actions that users
     * may need when working with lease data.
     *
     * @return ActionGroup  A group with edit and delete options.
     */
    protected function registerManipulationActions(): ActionGroup
    {
        return ActionGroup::make([
            AssignAuthenticatedUserAction::make(),
            EditAction::make()->color('gray'),
            DeleteAction::make(),
        ])
            ->button()
            ->label('opties') // 'Options' for user understanding
            ->icon('heroicon-o-cog-8-tooth')
            ->color('primary');
    }
}
