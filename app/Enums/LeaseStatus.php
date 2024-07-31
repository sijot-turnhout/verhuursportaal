<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

/**
 * LeaseStatus Enum
 *
 * Represents the various statuses a lease can have in the system.
 * This enum implements the HasColor, HasIcon, and HasLabel interfaces,
 * allowing each status to provide associated color, icon, and label data.
 */
enum LeaseStatus: string implements HasColor, HasIcon, HasLabel
{
    /**
     * State: Quotation
     *
     * Indicates that a lease quotation has been requested by the customer.
     * This status is often used to represent an offer or a preliminary agreement that has not yet been accepted or finalized.
     *
     * Typical secenario: This status is set when the organisation that organizes the lease sends a quote to the customer
     * outlining the terms and associated costs with the lease. It serves as a formal proposal which the customer can consider before committing.
     */
    case Quotation = 'optie (offerte)';

    /**
     * State: Request
     *
     * Respresents the initial submission of a lease request by the customer.
     * This is the default state when a new leazse request is created.
     * Once the lease transitions from Request, it should not revert back.
     *
     * Typical scenario: This status is set when a customer submits a new request for a lease.
     * It indicates that the leasing process has begun but not yet reached the stage of providing an offer, quotation or Garanties from the customer.
     *
     * Business rule: Once a request has been processed and moved to another status, it cannot return
     * to the request state, senuring a forward-only progression in the lease lifecycle.
     */
    case Request = 'nieuwe aanvraag';

    /**
     * State: Option
     *
     * Signifies that the lease has been marked as an option. This statustypically indicates that
     * the customer has expressed interest in proceeding with the lease but has not yet committed.
     *
     * Typical scenario: The lease organiation may set this status when a customer indicate a preference
     * for a particular lease agreement but still needs to finalize with the lease but has not yet committed to the agreements.
     *
     * Business rule: The option status may often involve reserving the domain lease for a period for a specified period,
     * during which the customer can confirm their intention to lease.
     */
    case Option = 'optie';

    /**
     * State: Confirmed
     *
     * Indicates that the leasqe has been confirmed by both the customer and the leasing organisation.
     * All the terms have been agreed upon, and the lease is ready to proceed.
     *
     * Typical scenario: Thi status us reached after negotiations are completed, and both partiess heve signed their agreement.
     * It represents a binding commitmenbt to the lease terms.
     *
     * Business Rule: At this stage, any necessary pre-lease preparations, such as inspections or documentation, should be finalized.
     * The lease is legally binding upon confirmation.
     */
    case Confirmed = 'bevestigd';

    /**
     * Stae Finalized
     *
     * Represents the completion of the lease agreement. The lease has been executed, and all parties have fulfilled their obligations.
     *
     * Typical Scenario: This status is set once the lease term has ended, the domain has been closed for the lease and inspected
     * ragarding the cleanup and state after the lease.
     *
     * Business Rule: Finalization involves clearing any outstanding payments or obligations and closing
     * the lease request.
     */
    case Finalized = 'afgesloten';

    /**
     * State: Cancelled
     *
     * Indicates that the lease has been cancelled. This status can be set at any stage before the lease
     * is finalized, depending on the terms of the agreement.
     *
     * Typical Scenario: A lease may be cancelled due to various reasons such as customer withdrawal,
     * inability to agree on terms, or failure to meet certain conditions.
     *
     * Business Rule: Cancellation usually requires appropriate documentation and may involve cancellation
     * fees or penalties, depending on the agreement's terms.
     */
    case Cancelled = 'geannuleerd';

    /**
     * Get the color associated with the lease status.
     *
     * @return string|array|null The color code(s) for the status.
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
     * Get the icon associated with the lease status.
     *
     * @return string|null The icon name for the status.
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
     * Get the label associated with the lease status.
     *
     * @return string The translated label for the status.
     */
    public function getLabel(): string
    {
        return trans($this->value);
    }
}
