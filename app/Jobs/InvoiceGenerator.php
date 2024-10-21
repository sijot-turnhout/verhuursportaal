<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Facades\DB;

final readonly class InvoiceGenerator
{
    public static function process()
    {
        echo 'work';
    }
}
