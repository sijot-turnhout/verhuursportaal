<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;

/**
 * @todo 2024-04-29: Remove the Controller.php extrazct controller out of the project because it is not used.
 *
 * @todo <1.0.0 Document the procedure for changing the logo and org details in the footer.
 */
final readonly class FrontPageController
{
    public function __invoke(): Renderable
    {
        return view('welcome');
    }
}
