<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\DepositResource\Enums;

use ArchTech\Enums\Comparable;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum DepositStatus: string implements HasLabel, HasColor, HasIcon
{
    use Comparable;

    case Paid = 'Betaald';
    case WithDrawn = 'Ingetrokken';
    case PartiallyRefunded = 'Gedeeltelijk terugbetaald';
    case FullyRefunded = 'Volledig terugbetaald';

    public function getLabel(): ?string
    {
        return $this->value;
    }

    public function getColor(): string|array|null
    {
        return match($this) {
            self::Paid, self::FullyRefunded => 'success',
            self::WithDrawn => 'danger',
            self::PartiallyRefunded => 'warning',
        };
    }

    public function getIcon(): ?string
    {
        return match($this) {
            self::Paid, self::PartiallyRefunded, self::FullyRefunded => 'heroicon-o-credit-card',
            self::WithDrawn => 'heroicon-o-exclamation-triangle',
        };
    }
}
