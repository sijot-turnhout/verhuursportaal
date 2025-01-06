<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;

/**
 * @todo <1.0.0 Document the procedure for changing the logo and org details in the footer.
 */
final readonly class FrontPageController
{
    public function __invoke(): Renderable
    {
        return view('welcome');
    }
}
