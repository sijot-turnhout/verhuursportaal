<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;

final readonly class PriceInformationController
{
    public function __invoke(): Renderable
    {
        return view('pricing');
    }
}
