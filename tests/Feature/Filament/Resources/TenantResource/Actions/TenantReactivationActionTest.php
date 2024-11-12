<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Resources\TenantResource\Actions;

use App\Filament\Resources\TenantResource\Pages\ListTenants;
use App\Models\Tenant;
use App\Models\User;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('cannot see the deactivation button for the tenants', function (): void {
    $records = Tenant::factory(5)->blacklisted()->create();
    $record = $records->first();

    livewire(ListTenants::class)->assertTableActionHidden('Huurder blokkeren', $record);
});

it ('can successfully reactivate a tenant in the application', function (): void {
    $tenant = Tenant::factory()->blacklisted()->create();
    expect($tenant->isBanned())->toBeTrue();

    livewire(ListTenants::class)
        ->assertTableActionExists(name:'Huurder heractiveren', record: $tenant)
        ->callTableAction(name: 'Huurder heractiveren', record: $tenant)
        ->assertHasNoTableActionErrors();
});

it ('has the correct layout configuration for the action', function (): void {
    livewire(ListTenants::class)
        ->assertTableActionHasLabel('Huurder heractiveren', 'Huurder heractiveren')
        ->assertTableActionHasIcon('Huurder heractiveren', 'heroicon-o-lock-open')
        ->assertTableActionHasColor('Huurder heractiveren', 'success');
});

