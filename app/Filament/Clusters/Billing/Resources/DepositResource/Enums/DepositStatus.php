<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\DepositResource\Enums;

use ArchTech\Enums\Comparable;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

/**
 * Enum representing the different statuses of a deposit in the billing system.
 *
 * This enum categorizes deposits based on their financial state, providing
 * a clear understanding of the deposit's status at any given time.
 *
 * @package App\Filament\Clusters\Billing\Resources\DepositResource\Enums
 */
enum DepositStatus: string implements HasLabel, HasColor, HasIcon
{
    use Comparable;

    /**
     * Indicates that the deposit has been fully paid.
     * The deposit amount is held in custody and is eligible for
     * refunding according to the lease terms.
     */
    case Paid = 'Betaald';

    /**
     * Indicates that the deposit has been fully withdrawn.
     * This status signifies that the tenant or group has received
     * their deposit back and it is no longer under the organization's control.
     */
    case WithDrawn = 'Ingetrokken';

    /**
     * Indicates that the deposit has been partially refunded.
     * A portion of the deposit has been returned to the tenant or group,
     * while the remainder is still held by the organization.
     */
    case PartiallyRefunded = 'Gedeeltelijk terugbetaald';

    /**
     * Indicates that the deposit has been fully refunded.
     * The entire deposit amount has been returned to the tenant or group,
     * and there are no further obligations regarding this deposit.
     */
    case FullyRefunded = 'Volledig terugbetaald';

    /**
     * Status indicating the deposit refund is overdue
     *
     * This status is applied when a deposit should have been refunded after the lease ended
     * but the refund deadline has passed. It signals that immediate action is needed to
     * process the pending refund and resolve the overdue status.
     */
    case DueRefund = 'Terugbetaling vereist';

    /**
     * Returns the label for the deposit status.
     *
     * @return string|null The user-friendly label for the current status.
     */
    public function getLabel(): ?string
    {
        return $this->value;
    }

    /**
     * Returns the color associated with the deposit status.
     *
     * This color can be used for visual representation in the UI,
     * helping users quickly identify the status at a glance.
     *
     * Returns the color or colors associated with the status.
     *
     * {@inheritDoc}
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Paid, self::FullyRefunded => 'success',
            self::WithDrawn, self::DueRefund => 'danger',
            self::PartiallyRefunded => 'warning',
        };
    }

    /**
     * Returns the icon associated with the deposit status.
     *
     * Icons are used to provide a visual cue for the status,
     * enhancing the user experience by making it easier to recognize
     * different states of deposits.
     *
     * @return string|null The icon associated with the current status.
     */
    public function getIcon(): ?string
    {
        return match ($this) {
            self::Paid, self::PartiallyRefunded, self::FullyRefunded => 'heroicon-o-credit-card',
            self::WithDrawn, self::DueRefund => 'heroicon-o-exclamation-triangle',
        };
    }
}
