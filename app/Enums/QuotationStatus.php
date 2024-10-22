<?php

declare(strict_types=1);

namespace App\Enums;

use ArchTech\Enums\Comparable;

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
enum QuotationStatus: string
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
}
