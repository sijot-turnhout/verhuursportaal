<?php

declare(strict_types=1);

namespace App\Enums;

enum ContactMessageStatus: string
{
    case New = 'nieuwe contactname';
    case InProgress = 'in behandeling';
    case Completed = 'behandeld';
}
