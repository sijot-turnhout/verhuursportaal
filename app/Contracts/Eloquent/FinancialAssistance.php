<?php

declare(strict_types=1);

namespace App\Contracts\Eloquent;

use Illuminate\Database\Eloquent\Casts\Attribute;

interface FinancialAssistance
{
    /**
     * @phpstan-ignore-next-line
     */
    public function billableTotal(): Attribute;

    public function getDiscountTotal(): int|float|string;

    public function getSubTotal(): int|float|string;
}
