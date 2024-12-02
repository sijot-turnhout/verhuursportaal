<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\Actions\StateTransitions;

use App\Enums\LeaseStatus;
use App\Filament\Support\StateMachines\StateTransitionActionContract;
use App\Models\Lease;
use Filament\Actions\Action;

final class TransitionToQuotationAction extends Action implements StateTransitionActionContract
{
    public static function make(?string $name = null): static
    {
        $finalState = LeaseStatus::Quotation;

        return parent::make($name)
            ->label('Markeren als offerte optie')
            ->translateLabel()
            ->color($finalState->getColor())
            ->icon($finalState->getIcon())
            ->visible(fn(Lease $lease): bool => self::canTransition($lease))
            ->action(fn(Lease $lease) => self::performActionLogic($lease));
    }
}
