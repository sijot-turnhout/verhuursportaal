<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\Actions\StateTransitions;

use App\Enums\LeaseStatus;
use App\Filament\Support\Actions\StateTransitionAction;
use App\Models\Lease;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

final class transitionToCancelledAction extends StateTransitionAction
{
    public static function make(?string $name = null): static
    {
        return self::buildStateTransitionAction(name: 'transition-to-cacnelled', label: 'Markeren als geannuleerd', finalState: LeaseStatus::Cancelled)
            ->visible(fn(Lease $lease): bool => self::canTransition($lease))
            ->action(fn(Lease $lease) => self::performActionLogic($lease));
    }

    public static function canTransition(Model $model): bool
    {
        return Gate::allows('update', $model) && $model->status->in(enums: self::configureAllowedStates());
    }

    public static function configureAllowedStates(): array
    {
        return [LeaseStatus::Request, LeaseStatus::Quotation, LeaseStatus::Option, LeaseStatus::Confirmed];
    }

    public static function performActionLogic(Model $model): void
    {
        $model->state()->transitionToCancelled();
    }
}
