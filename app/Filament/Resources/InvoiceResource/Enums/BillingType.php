<?php

declare(strict_types=1);

namespace App\Filament\Resources\InvoiceResource\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum BillingType: int implements HasColor, HasLabel
{
    case Discount = 1;
    case BillingLine = 0;

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::BillingLine => 'success',
            self::Discount => 'danger',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::BillingLine => 'facturatieregel',
            self::Discount => 'vermindering',
        };
    }
}
