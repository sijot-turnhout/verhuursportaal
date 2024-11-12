<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

it ('Can display the price information page in the application', function (): void {
    $this->get(route('price-information'))->assertSuccessful();
});
