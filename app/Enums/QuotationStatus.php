<?php

declare(strict_types=1);

namespace App\Enums;

use ArchTech\Enums\Comparable;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

/**
 * Enum QuotationStatus
 *
 * This enum defines the various statuses that a quotation can have throughout its lifecycle.
 * Each status represents a specific stage in the quotation process, allowing for clear
 * tracking and management of quotations. Understanding these statuses is crucial for
 * effectively handling quotations in the billing and sales workflow.
 *
 * @package App\Enums
 */
enum QuotationStatus: string implements HasLabel, HasIcon, HasColor
{
    use Comparable;

    /**
     * Indicates that the quotation is currently in the draft phase.
     * It is being prepared but has not yet been finalized or sent to the client.
     * This status allows for editing and review before sending.
     */
    case Draft = 'Klad offerte';

    /**
     * Indicates that the quotation has been sent to the recipient and is now open for review.
     * The recipient can evaluate the details of the quotation, including pricing and terms.
     * This status is crucial for tracking which quotations are currently pending a response.
     */
    case Open = 'Openstaande offerte';

    /**
     * Indicates that the quotation has been officially accepted by the recipient.
     * This status signifies that the terms have been agreed upon, and the quotation is
     * approved for execution. Further actions can be taken based on this acceptance.
     */
    case Accepted = 'Goedgekeurde offerte';

    /**
     * Indicates that the quotation has been declined by the recipient.
     * This status is important for understanding that the recipient has reviewed the quotation
     * but has chosen not to proceed with the offer. The reasons for decline can be captured for
     * future reference.
     */
    case Declined = 'Afgewezen offerte';

    /**
     * Indicates that the quotation has expired and is no longer valid for acceptance.
     * Quotations may have a time limit, and once that limit is reached, they transition to
     * this status. This helps in managing and cleaning up quotations that are no longer active.
     */
    case Expired = 'Verlopen offerte';

    /**
     * Returns the appropriate Heroicon name for the current status of the object.
     *
     * This function maps the current status of the object to a corresponding Heroicon
     * class name, which can be used to display an icon representing the status.
     *
     * @return string The Heroicon class name for the current status.
     */
    public function getIcon(): string
    {
        return match ($this) {
            self::Draft => 'heroicon-o-pencil-square',
            self::Declined => 'heroicon-o-x-circle',
            self::Accepted => 'heroicon-o-check-circle',
            self::Open => 'heroicon-o-document',
            self::Expired => 'heroicon-o-clock',
        };
    }

    /**
     * Returns a color string or array associated with each quotation status.
     *
     * This method uses a `match` expression to return a specific color for each
     * quotation status. The colors are intended for use in UI elements, such as
     * badges or indicators, to visually represent the current status of a quotation.
     *
     * Returns the color value as a string (e.g., 'danger', 'success'), an array for complex color schemes, or null if not applicable.
     *
     * {@inheritDoc}
     */
    public function getColor(): string
    {
        return match ($this) {
            self::Declined => 'danger',
            self::Accepted => 'success',
            self::Draft => 'primary',
            self::Expired => 'warning',
            self::Open => 'info',
        };
    }

    /**
     * Retrieves the label associated with the current status.
     *
     * This method returns the string value of the status, which can be used as a
     * label in the user interface to display the name of the status in a readable format.
     *
     * @return string The label for the current status, or null if not defined.
     */
    public function getLabel(): string
    {
        return $this->value;
    }
}
