<?php

declare(strict_types=1);

namespace App\Filament\Resources\InvoiceResource\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

/**
 * Enum BillingType
 *
 * This enum defines different types of billing items in the invoice system.
 * It is used to categorize lines on an invoice, such as regular billing lines or discounts.
 *
 * Each case in the enum is associated with a color and a label, which can be used for UI purposes
 * such as rendering colored badges or displaying user-friendly labels.
 *
 * @package App\Filament\Resources\InvoiceResource\Enums
 */
enum BillingType: int implements HasColor, HasLabel
{
    /**
     * Represents a discount on the invoice.
     *
     * @var int
     */
    case Discount = 1;

    /**
     * Represents a regular billing line on the invoice.
     *
     * @var int
     */
    case BillingLine = 0;

    /**
     * Returns the color associated with each billing type.
     *
     * - 'BillingLine' is represented by the color 'success'.
     * - 'Discount' is represented by the color 'danger'.
     *
     * This color can be used in UI elements like badges, tables, etc.
     *
     * @return string|array|null  The color associated with the billing type.
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::BillingLine => 'success',
            self::Discount => 'danger',
        };
    }

    /**
     * Returns the label associated with each billing type.
     *
     * - 'BillingLine' is labeled as 'facturatieregel' (billing line).
     * - 'Discount' is labeled as 'vermindering' (discount).
     *
     * These labels are useful for presenting user-friendly names in the application.
     *
     * @return string|null  The label associated with the billing type.
     */

    public function getLabel(): ?string
    {
        return match ($this) {
            self::BillingLine => 'facturatieregel',
            self::Discount => 'vermindering',
        };
    }
}
