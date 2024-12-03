<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\Actions\StateTransitions;

use App\Enums\LeaseStatus;
use App\Filament\Support\Actions\StateTransitionAction;
use App\Models\Lease;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

final class TransitionToQuotationAction extends StateTransitionAction
{
    public static function make(?string $name = null): static
    {
        return self::buildStateTransitionAction(name: 'transition-to-quotation', label: 'Markeren as offerte optie', finalState: LeaseStatus::Quotation)
            ->visible(fn(Lease $lease): bool => self::canTransition($lease))
            ->action(fn(Lease $lease) => self::performActionLogic($lease));
    }

    public static function canTransition(Model $lease): bool
    {
        return Gate::allows('update', $lease) && $lease->status->in(enums: self::configureAllowedStates());
    }

    public static function configureAllowedStates(): array
    {
        return [LeaseStatus::Request];
    }

    public static function performActionLogic(Model $lease): void
    {
        $lease->state()->transitionToQuotationRequest();
    }
}
