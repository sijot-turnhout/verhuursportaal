<?php

declare(strict_types=1);

namespace Tests\Feature\LeaseResource\States;

use App\Models\Lease;

beforeEach(function (): void {
    $this->lease = Lease::factory()->create();
});

it ('can mark the leasqe request as a quotation option', function (): void {
});


it ('can mark the lease with the option state', function (): void {
});

it ('can mark the lease with the cancelled state', function (): void {
});

it ('cannot mark the lease with the finalized state', function (): void {
    $this->lease->state()->transitionToFinalized();
})->throws('Cannot transition to the Finalized state on the current state.');

it ('cannot mark the lease with the confirmed state', function (): void {
    $this->lease->state()->transitionToConfirmed();
})->throws('Cannot transition to the Confirmed state with the current state.');

