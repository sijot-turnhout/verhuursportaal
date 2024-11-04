<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\DepositResource\Pages;

use App\Filament\Clusters\Billing\Resources\DepositResource;
use App\Filament\Clusters\Billing\Resources\DepositResource\Actions\RegisterPartiallyRefundAction;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\ViewRecord;

final class ViewDeposit extends ViewRecord
{
    protected static string $resource = DepositResource::class;

    public function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                RegisterPartiallyRefundAction::make(),
            ])
                ->button()
                ->icon('heroicon-o-credit-card')
                ->label('Terugbetaling registreren')
        ];
    }
}
