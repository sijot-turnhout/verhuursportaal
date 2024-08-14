<?php

declare(strict_types=1);

namespace Tests\Feature\LeaseResource\States;

use App\Models\Lease;

beforeEach(function (): void {
    $this->lease = Lease::factory()->cancelled()->create();
});

describe('Test the cancelled state transition from a lease in the application', function (): void {
    it('cannot transition to quotation option', function (): void {
        $this->lease->state()->transitionToQuotationOption();
    })->throws('Cannot transition the lease to the quotation option state');

    it('cannot transition to option', function (): void {
        $this->lease->state()->transitionToOption();
    })->throws('Cannot transition to the Option state with the current state');

    it ('cannot transition to cancelled', function (): void {
        $this->lease->state()->transitionToCancelled();
    })->throws('Cannot transition to the Cancelled state with the current state');

    it ('cannot transition to finalized', function (): void {
        $this->lease->state()->transitionToFinalized();
    })->throws('Cannot transition to the Finalized state on the current state.');

    it ('cannot transition to confirmed', function (): void {
        $this->lease->state()->transitionToConfirmed();
    })->throws('Cannot transition to the Confirmed state with the current state.');
});
