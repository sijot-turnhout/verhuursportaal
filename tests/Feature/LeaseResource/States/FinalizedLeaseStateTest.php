<?php

declare(strict_types=1);

namespace Tests\Feature\LeaseResource\States;

use App\Models\Lease;

beforeEach(function (): void {
    $this->lease = Lease::factory()->finalized()->create();
});

describe('Test the transition method on the finalized state of the lease', function (): void {
    it ('cannot mark the finalized lease as Quotaton option', function (): void {
        $this->lease->state()->transitionToQuotationOption();
    })->throws('Cannot transition the lease to the quotation option state');

    it ('Cannot mark the finalized lease as option', function (): void {
        $this->lease->state()->transitionToOption();
    })->throws('Cannot transition to the Option state with the current state');

    it ('Cannot mark the finalized lease as cancelled', function (): void {
        $this->lease->state()->transitionToCancelled();
    })->throws('Cannot transition to the Cancelled state with the current state');

    it ('Cannot mark the finalized lease with the same status', function (): void {
        $this->lease->state()->transitionToFinalized();
    })->throws('Cannot transition to the Finalized state on the current state.');

    it ('Cannot mark the finalized lease to the confirmed status', function (): void {
        $this->lease->state()->transitionToConfirmed();
    })->throws('Cannot transition to the Confirmed state with the current state.');
});
