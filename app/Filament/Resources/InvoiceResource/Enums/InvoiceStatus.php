<?php

declare(strict_types=1);

namespace App\Filament\Resources\InvoiceResource\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

/**
 * Enum InvoiceStatus
 *
 * Represents the status of an invoice within the application.
 *
 * This enum defines the various states an invoice can be in, with
 * each status indicating a specific stage in the invoice lifecycle.
 */
enum InvoiceStatus: string implements HasColor, HasIcon, HasLabel
{
    /**
     * Status: draft
     *
     * The invoice isn't ready to use. All invoices start in draft status.
     * You can update almost any details of a draft invoice. You can also delete it.
     * Note that you can't recover any deleted invoices.
     *
     * Below we documented a couple of possible actions on this status:
     *
     * - Edit any part of the invoice.
     * - When the invoice is ready to use, finalize it by changing its status to open.
     * - If the invoice isn't associated with a lease, delete it.
     */
    case Draft = 'voorstel';

    /**
     * Status: open
     *
     * The invoice is finalized and awaiting payment.
     * Below we documented a couple of actions on this status:
     *
     * - Send the invoice to a customer for payment.
     * - Change the invoice's status to paid, void or uncollected.
     */
    case Open = 'open';

    /**
     * Status: paid
     *
     * The customer has paid the invoice. The status is terminal, which means that the invoice's status can never change.
     */
    case Paid = 'paid';

    /**
     * Status: void
     *
     * Voiding an invoice is conceptually similar to deleting or cancelling it. However, voiding an invoice maintains a paper trail,
     * which allows you to look up the invoice by number. Voided invoices are treated as zero-value for reporting purposes,
     * and aren't payable. This status is terminal, which means that the invoice's status can never change.
     */
    case Void = 'void';

    /**
     * Status: Uncollected
     *
     * Sometimes your customers can't pay their outstanding bills, For example, assume that you provided for 1.00O EUR worth of
     * services to your customer. but they've since declared bankruptcy and have no assets to pay the invoice.
     *
     * As a result, you decide to wrtie off the invoice as unlikely to be paid. In this case, you can update the status of the
     * invoice to be 'uncollected'. This allows you to track the amount owed for reporting purpose as part of your bad
     * debt accounting process.
     *
     * Below we documented a couple of possible actions on this status:
     *
     * - Change the invoice's status to void or paid.
     */
    case Uncollected = 'uncollected';

    /**
     * Status: Quotation Request
     *
     * Indicates that the customer has requested a quotation, which is a preliminary step before an official invoice is created.
     * In this status, the quotation can still be modified or refined before it's sent to the customer for approval.
     */
    case Quotation_Request = 'offerte aanvraag';

    /**
     * Status: Quotation
     *
     * Represents an open quotation that has been provided to the customer but is awaiting acceptance or further action.
     * In this stage, the customer can approve or decline the quotation, which will then determine the next course of action.
     */
    case Quotation = 'openstaande offerte';

    /**
     * Status: Quotation Declined
     *
     * The customer has reviewed and declined the quotation, indicating that no further action will be taken on this specific offer.
     * The quotation remains in the system for reference, but no financial transactions or invoicing will occur.
     */
    case Quotation_Declined = 'afgewezen offerte';

    /**
     * Get the color associated with each invoice status for UI purposes.
     *
     * @return string|array|null  The color code(s) representing the status.
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Draft, self::Void => 'gray',
            self::Open, self::Quotation_Request => 'info',
            self::Paid, self::Quotation => 'success',
            self::Uncollected, self::Quotation_Declined => 'danger',
        };
    }

    /**
     * Get the user-friendly label for each invoice status.
     *
     * @return string|null  The localized label representing the status.
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::Draft => trans('klad factuur'),
            self::Open => trans('openstaand'),
            self::Paid => trans('betaald'),
            self::Void => trans('geannuleerd'),
            self::Uncollected => trans('onbetaald'),
            self::Quotation_Request => trans('offerte aanvraag'),
            self::Quotation => trans('openstaande offerte'),
            self::Quotation_Declined => trans('afgewezen offerte'),
        };
    }

    /**
     * Get the icon associated with each invoice status for UI purposes.
     *
     * @return string|null  The icon representing the status.
     */
    public function getIcon(): ?string
    {
        return match ($this) {
            self::Draft, self::Quotation_Request => 'heroicon-o-pencil-square',
            self::Open, self::Quotation => 'heroicon-o-document-text',
            self::Paid => 'heroicon-o-check-circle',
            self::Void, self::Quotation_Declined => 'heroicon-o-x-circle',
            self::Uncollected => 'heroicon-o-exclamation-triangle',
        };
    }
}
