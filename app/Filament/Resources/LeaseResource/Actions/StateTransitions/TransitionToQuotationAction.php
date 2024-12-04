<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\Actions\StateTransitions;

use App\Enums\LeaseStatus;
use App\Filament\Support\Actions\StateTransitionAction;
use App\Models\Lease;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

/**
 * This actions marks a lease as a quotation option.
 *
 * It can only be performed if the user has the necessary permissions and the lease is currently in the "Request" state.
 *
 * @template TModel of \App\Models\Lease
 * @package  App\Filament\Resources\LeaseResource\Actions\StateTransitions
 */
final class TransitionToQuotationAction extends StateTransitionAction
{
    /**
     * Create a new instance of the action.
     *
     * @param  string|null $name The name of the action.
     * @return static
     */
    public static function make(?string $name = null): static
    {
        return self::buildStateTransitionAction(name: 'transition-to-quotation', label: 'Markeren as offerte optie', finalState: LeaseStatus::Quotation)
            /** @param TModel $lease - The resource entity from the database (lease information in this case) */
            ->visible(fn(Lease $lease): bool => self::canTransition($lease))
            /** @param TModel $lease - The resource entity from the database (lease information in this case) */
            ->action(fn(Lease $lease) => self::performActionLogic($lease));
    }

    /**
     * Checks if the action can be performed on the given lease.
     *
     * @param  Lease $lease The lease to check against.
     * @return bool          True if the action can be performed, false otherwise.
     */
    public static function canTransition(Model $lease): bool
    {
        return Gate::allows('update', $lease) && $lease->status->in(enums: self::configureAllowedStates());
    }

    /**
     * {@inheritDoc}
     */
    public static function configureAllowedStates(): array
    {
        return [LeaseStatus::Request];
    }

    /**
     * Performs the action logic on the given lease.
     *
     * @param  Lease $lease The lease to perform the action on.
     * @return void
     */
    public static function performActionLogic(Model $lease): void
    {
        $lease->state()->transitionToQuotationRequest();
    }
}
