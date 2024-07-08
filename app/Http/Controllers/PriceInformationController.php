<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;

final class PriceInformationController extends Controller
{
    public function __invoke(): Renderable
    {
        return view('pricing');
    }
}
