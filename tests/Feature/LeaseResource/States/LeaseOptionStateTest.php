<?php

declare(strict_types=1);

namespace Tests\Feature\LeaseResource\States;

use App\Enums\LeaseStatus;
use App\Models\Lease;

beforeEach(function (): void {
    $this->lease = Lease::factory()->option()->create();
});

describe('Test the state machine for the lease option state', function(): void {
    it ('can mark the lease as quotation option', function (): void {
        expect($this->lease->status)->toBe(LeaseStatus::Option);
        $this->lease->state()->transitionToQuotationOption();
        expect($this->lease->fresh()->status)->toBe(LeaseStatus::Quotation);
    });

    it ('cannot mark the lease as option because its already an option', function (): void {
        expect($this->lease->status)->toBe(LeaseStatus::Option);
        $this->lease->state()->transitionToOption();
        expect($this->lease->status)->toBe(LeaseStatus::Option);
    })->throws('Cannot transition to the Option state with the current state');

    it ('cannot mark the lease the lease as finalized', function (): void {
        expect($this->lease->status)->toBe(LeaseStatus::Option);
        $this->lease->state()->transitionToFinalized();
        expect($this->lease->status)->toBe(LeaseStatus::Option);
    })->throws('Cannot transition to the Finalized state on the current state.');

    it ('can mark the lease as confirmed', function (): void {
        expect($this->lease->status)->toBe(LeaseStatus::Option);
        $this->lease->state()->transitionToConfirmed();
        expect($this->lease->status)->toBe(LeaseStatus::Confirmed);
    });

    it ('can mark the lease as cancelled', function (): void {
        expect($this->lease->status)->toBe(LeaseStatus::Option);
        $this->lease->state()->transitionToCancelled();
        expect($this->lease->status)->toBe(LeaseStatus::Cancelled);
    });
});
