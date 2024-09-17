<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Resources\LeaseResource\States;

use App\Models\Lease;
use LogicException;

beforeEach(function (): void {
    $this->lease = Lease::factory()->finalized()->create();
});

test('it forbids the transition to the quotation option state', function (): void {
    $this->lease->state()->transitionToQuotationRequest();
})->throws(LogicException::class, 'The transition to quotation request is not valid on the current state');

test('it forbids the transition to the option state', function (): void {
    $this->lease->state()->transitionToOption();
})->throws(LogicException::class, 'The transition to optional reservation is not valid on the current state');

test('it forbids the transition to the confirmed state', function (): void {
    $this->lease->state()->transitionToConfirmed();
})->throws(LogicException::class, 'The transition to finalized state is not valid on the current state.');

test('it forbids the transition to the finalized state', function (): void {
    $this->lease->state()->transitionToCompleted();
})->throws(LogicException::class, 'The transition to confirmed is not valid on the current state.');

test('it forbids the transition to the cancelled state', function (): void {
    $this->lease->state()->transitionToCancelled();
})->throws(LogicException::class, 'The transition to the cancelled state is not valid on the current state');
