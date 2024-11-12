<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

it('can successfully display the availability page', function (): void {
    $this->get(route('availability'))->assertSuccessful();
});
