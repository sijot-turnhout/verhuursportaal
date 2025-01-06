<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Models\Lease;

it('can successfully display the availability page when no leases are stored', function (): void {
    $this->get(route('availability'))->assertSuccessful();
});

it('can successfully display the availability page when leases are stored', function (): void {
    Lease::factory(25)->create();
    $this->get(route('availability'))->assertSuccessful();
});
