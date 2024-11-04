<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\DepositResource\Pages;

use App\Filament\Clusters\Billing\Resources\DepositResource;
use App\Filament\Clusters\Billing\Resources\DepositResource\Actions;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\ViewRecord;

/**
 * Class ViewDeposit
 *
 * This class represents the page used to view a single deposit record within the DepositResource.
 * It customizes the header actions available, allowing users to register full or partial refunds
 * or withdrawn deposits through an action group in the page's header.
 *
 * @package App\Filament\Clusters\Billing\Resources\DepositResource\Pages
 */
final class ViewDeposit extends ViewRecord
{
    /**
     * Specifies the associated resource for this view page, linking it to the DepositResource.
     *
     * @var string
     */
    protected static string $resource = DepositResource::class;

    /**
     * Retrieves the header actions available for this view page, grouping together actions 0related to refund registration.
     * These actions include options for both full and partial refunds or withdrawn deposits.
     *
     * @return array An array of header actions, encapsulated in an action group for the deposit refund.
     */
    public function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Actions\RegisterFullyRefundedAction::make(),
                Actions\RegisterPartiallyRefundAction::make(),
                Actions\RegisterWithdrawnDepositAction::make(),
            ])
                ->button()
                ->icon('heroicon-o-credit-card')
                ->label('Terugbetaling registreren'),
        ];
    }
}
