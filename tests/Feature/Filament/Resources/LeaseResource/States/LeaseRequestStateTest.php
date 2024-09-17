<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Resources\LeaseResource\States;

use App\Enums\LeaseStatus;
use App\Models\Lease;
use LogicException;

beforeEach(function (): void {
    $this->lease = Lease::factory()->newRequest()->create();
});

test('it allows to transition to the quotation option state', function (): void {
    expect($this->lease->status)->toBe(LeaseStatus::Request);
    $this->lease->state()->transitionToQuotationRequest();
    expect($this->lease->fresh()->status)->toBe(LeaseStatus::Quotation);

});

test('It allows to transitition to the option state', function (): void {
    expect($this->lease->status)->toBe(LeaseStatus::Request);
    $this->lease->state()->transitionToOption();
    expect($this->lease->fresh()->status)->toBe(LeaseStatus::Option);
});

test('it Forbids to transition to the finalized state', function (): void {
    $this->lease->state()->transitionToCompleted();
})->throws(LogicException::class, 'The transition to confirmed is not valid on the current state');

test('It allows to transition to the confirmed state', function (): void {
    expect($this->lease->status)->toBe(LeaseStatus::Request);
    $this->lease->state()->transitionToConfirmed();
    expect($this->lease->fresh()->status)->toBe(LeaseStatus::Confirmed);
});

test('it allows to transition to the cancelled state', function (): void {
    expect($this->lease->status)->toBe(LeaseStatus::Request);
    $this->lease->state()->transitionToCancelled();
    expect($this->lease->fresh()->status)->toBe(LeaseStatus::Cancelled);
});
