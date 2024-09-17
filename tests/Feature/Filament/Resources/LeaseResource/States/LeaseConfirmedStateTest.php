<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Resources\LeaseResource\States;

use App\Enums\LeaseStatus;
use App\Models\Lease;
use LogicException;

beforeEach(function (): void {
    $this->lease = Lease::factory()->confirmed()->create();
});

test('it forbids to transition to the quotation request state', function (): void {
    $this->lease->state()->transitionToQuotationRequest();
})->throws(LogicException::class, 'The transition to quotation request is not valid on the current state.');

test('it forbids to transition to the optional reservation state', function (): void {
    $this->lease->state()->transitionToOption();
})->throws(LogicException::class, 'The transition to optional reservation is not valid on the current state');

test('it forbids to transition to the confirmed state', function (): void {
    $this->lease->state()->transitionToConfirmed();
})->throws(LogicException::class, 'The transition to finalized state is not valid on the current state.');

test('it can transition to the finalized state', function (): void {
    expect($this->lease->status)->toBe(LeaseStatus::Confirmed);
    $this->lease->state()->transitionToCompleted();
    expect($this->lease->fresh()->status)->toBe(LeaseStatus::Finalized);
});

test('it can transition to the cancelled state', function (): void {
    expect($this->lease->status)->toBe(LeaseStatus::Confirmed);
    $this->lease->state()->transitionToCancelled();
    expect($this->lease->fresh()->status)->toBe(LeaseStatus::Cancelled);
});
