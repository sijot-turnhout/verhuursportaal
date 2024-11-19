<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Resources\TenantResource\RelationManagers;

use App\Filament\Resources\TenantResource\Pages\EditTenant;
use App\Filament\Resources\TenantResource\RelationManagers\LeasesRelationManager;
use App\Models\Lease;
use App\Models\Tenant;
use Filament\Actions\CreateAction;
use Filament\Tables\Actions\DeleteBulkAction;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->tenant = Tenant::factory()
        ->has(Lease::factory()->count(3), 'leases')
        ->create();

    $this->leasesRelationManager = livewire(LeasesRelationManager::class, [
        'ownerRecord' => $this->tenant, 'pageClass' => EditTenant::class,
    ]);
});

describe('Lease relation manager tests for the tenant', function (): void {
    it('can render the relation manager successfully', function (): void {
        $this->leasesRelationManager->assertSuccessful();
    });

    it('implements all the needed table actions for the relation manager', function (string $action): void {
        $this->leasesRelationManager->assertTableActionExists($action);
    })->with(['view', 'edit', 'delete']);

    it('implements the header action for creating leases correctly', function (): void {
        $this->leasesRelationManager->assertTableHeaderActionsExistInOrder([CreateAction::class]);
    });

    it('implements the bulk delete action for the leases', function (): void {
        $this->leasesRelationManager->assertTableBulkActionExists(DeleteBulkAction::class);
    });

    it('can display the creation view modal through the relation manager', function (): void {
        $this->leasesRelationManager->callTableAction('create')
            ->assertSee('Aanspreekpunt / Verantwoordelijke')
            ->assertSee('Groep')
            ->assertSee('Aantal personen')
            ->assertSee('aankomst datum')
            ->assertSee('vertrek')
            ->assertSee('Lokalen');
    });

    it('can create a lease through the relation manager', function (): void {
        $this->leasesRelationManager->callTableAction('create', $this->tenant, Lease::factory()->make()->toArray())
            ->assertHasNoTableActionErrors();
    });
});
