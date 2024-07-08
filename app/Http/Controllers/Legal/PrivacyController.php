<?php

declare(strict_types=1);

namespace App\Http\Controllers\Legal;

use Illuminate\Contracts\Support\Renderable;

final readonly class PrivacyController
{
    public function __invoke(): Renderable
    {
        return view('legal.privacy');
    }
}
