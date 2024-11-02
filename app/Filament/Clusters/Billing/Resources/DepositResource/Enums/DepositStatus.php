<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\DepositResource\Enums;

use ArchTech\Enums\Comparable;

enum DepositStatus: string
{
    use Comparable;

    case Paid = 'Betaald';
    case WithDrawn = 'Ingetrokken';
    case PartiallyRefunded = 'Gedeeltelijk terugbetaald';
    case FullyRefunded = 'Volledig terugbetaald';
}
