<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Resources\TenantResource\RelationManagers;

use App\Filament\Resources\TenantResource\Pages\EditTenant;
use App\Filament\Resources\TenantResource\RelationManagers\NotesRelationManager;
use App\Models\Tenant;

use function Pest\Livewire\livewire;

describe('Notes relation manager tests', function (): void {
    it('can render the trelation manager', function (): void {
        $tenant = Tenant::factory()->create();
        livewire(NotesRelationManager::class, ['ownerRecord' => $tenant, 'pageClass' => EditTenant::class])->assertSuccessful();
    });

    describe('table action tests', function (): void {
        it ('implement all the needed table actions on the relation manager', function (string $action): void {
            $tenant = Tenant::factory()->create();

            livewire(NotesRelationManager::class, ['ownerRecord' => $tenant, 'pageClass' => EditTenant::class])
                ->assertTableActionExists($action);
        })->with(['delete', 'view', 'edit']);
    });
});
