<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

/**
 * Enum LeaseStatus
 *
 * Represents the various statuses that a lease can have within the application.
 * Each status is associated with a specific label, color, and icon to ensure
 * consistent and intuitive representation throughout the user interface.
 *
 * @package App\Enums
 */
enum LeaseStatus: string implements HasColor, HasIcon, HasLabel
{
    /**
     * Quotation Status
     *
     * Indicates that a quotation has been provided for the lease.
     * Typically used when an initial offer has been made but not yet accepted.
     *
     *  @var string
     */
    case Quotation = 'optie (offerte)';

    /**
     * Request Status
     *
     * Indicates that a new lease request has been initiated.
     * This is the starting point of the lease process.
     *
     * @var string
     */
    case Request = 'nieuwe aanvraag';

    /**
     * Option Status
     *
     * Indicates that an option has been set on the lease.
     * Represents a provisional agreement pending confirmation.
     *
     * @var string
     */
    case Option = 'optie';

    /**
     * Confirmed Status
     *
     * Indicates that the lease has been confirmed and agreed upon by all parties.
     * Represents a finalized agreement ready for execution.
     *
     * @var string
     */
    case Confirmed = 'bevestigd';

    /**
     * Finalized Status
     *
     * Indicates that the lease process has been completed successfully.
     * All terms have been fulfilled, and the lease is considered closed.
     *
     * @var string
     */
    case Finalized = 'afgesloten';

    /**
     * Cancelled Status
     *
     * Indicates that the lease has been cancelled.
     * This can occur at any stage before finalization due to various reasons.
     *
     * @var string
     */
    case Cancelled = 'geannuleerd';


    /**
     * Get the associated color for the current lease status.
     *
     * The returned color is used to visually represent the status in the application's UI,
     * allowing users to quickly identify the status through consistent color coding.
     *
     * @return string|array|null The color corresponding to the lease status. Possible values: 'info', 'warning', 'success', 'danger', or null.
     */
    public function getColor(): string|array|null
    {
        return match($this) {
            self::Request => 'info',
            self::Option, self::Quotation => 'warning',
            self::Confirmed => 'success',
            self::Finalized, self::Cancelled => 'danger',
        };
    }

    /**
     * Get the associated icon for the current lease status.
     *
     * The returned icon represents the status visually in the application's UI,
     * providing an intuitive and immediate understanding of the lease's state.
     *
     * @see https://heroicons.com/ for reference on available icons.
     *
     * @return string|null The icon name corresponding to the lease status. Uses Heroicons naming convention.
     */
    public function getIcon(): ?string
    {
        return match($this) {
            self::Request => 'heroicon-m-plus-circle',
            self::Option, self::Quotation => 'heroicon-m-document-text',
            self::Confirmed => 'heroicon-m-check-badge',
            self::Finalized => 'heroicon-m-document-check',
            self::Cancelled => 'heroicon-m-archive-box-x-mark',
        };
    }

    /**
     * Get the translated label for the current lease status.
     *
     * Retrieves a human-readable and localized label for the status,
     * suitable for display in various parts of the application.
     *
     * @return string The localized label corresponding to the lease status.
     */
    public function getLabel(): string
    {
        return trans($this->value);
    }
}
