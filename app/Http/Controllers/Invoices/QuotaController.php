<?php

declare(strict_types=1);

namespace App\Http\Controllers\Invoices;

use Illuminate\Contracts\Support\Renderable;

final readonly class QuotaController
{
    public function index(): Renderable
    {
        return view('lease.quota');
    }
}
