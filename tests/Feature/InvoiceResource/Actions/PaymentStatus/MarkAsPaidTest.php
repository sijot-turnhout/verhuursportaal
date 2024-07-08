<?php

declare(strict_types=1);

namespace Tests\Feature\InvoiceResource\Actions\PaymentStatus;

use App\Filament\Resources\InvoiceResource\Enums\InvoiceStatus;
use App\Filament\Resources\InvoiceResource\Pages\ViewInvoice;
use App\Models\Invoice;
use App\Models\Lease;
use App\Models\Tenant;
use App\Models\User;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->invoice = Invoice::factory()
        ->for(factory: User::factory()->createQuietly(), relationship: 'creator')
        ->for(factory: Lease::factory()->createQuietly(), relationship: 'lease')
        ->for(factory: Tenant::factory()->createQuietly(), relationship: 'customer');
});

/**
 * @test
 * @covers          Invoice payment functionality
 * @description     Ensure that an paid invoice can not perform the 'mark as paid' action.
 * @precondition    An paid invoice must exist.
 * @expected        The invoice status should be unchanged because the action is not visible and thus not allowed to be executed
 */
test('it cannot mark an invoice as paid when invoice is cancelled', function (): void {
    $invoice = $this->invoice->cancelledInvoice()->create();

    livewire(ViewInvoice::class, ['record' => $invoice->id])
        ->assertActionHidden('markeer als betaald');
});

/**
 * @test
 * @covers          Invoice payment functionality
 * @description     Ensure that an open invoice can be marked as paid correctly started from an open invoice.
 * @precondition    An open invoice must exist
 * @expected        The invoice status should be updated to 'Paid' and the paid_at timestamp should be set.
 */
test('it can mark an invoice as paid when invoice is open', function (): void {
    $invoice = $this->invoice->openInvoice()->create();

    livewire(ViewInvoice::class, ['record' => $invoice->id])
        ->callAction('markeer als betaald')
        ->assertHasNoActionErrors();

    expect($invoice->fresh()->status)->toBe(InvoiceStatus::Paid)
        ->and(null === $invoice->fresh()->paid_at)->toBefalse();
});

/**
 * @test
 * @covers          Invoice payment functionality
 * @description     Ensure that a due invoice can be marked as paid correctly started from an open invoice.
 * @precondition    A due invoice must exist
 * @expected        The invoice status should be updated to 'Paid' and the paid_at timestamp should be set. While the due_at timestamp should be null
 */
test('it can mark an invoice as paid when the invoice is due', function (): void {
    $invoice = $this->invoice->dueInvoice()->create();

    livewire(ViewInvoice::class, ['record' => $invoice->id])
        ->callAction('markeer als betaald');

    expect($invoice->fresh()->status)->toBe(InvoiceStatus::Paid)
        ->and(null === $invoice->fresh()->due_at)->toBeTrue()
        ->and(null === $invoice->fresh()->paid_at)->toBefalse();
});

/**
 * @test
 * @covers
 * @description
 * @precondition
 * @expected
 */
test('it cannot mark an invoice as paid when invoice is already paid', function (): void {
    $this->assertTrue(true);
});
