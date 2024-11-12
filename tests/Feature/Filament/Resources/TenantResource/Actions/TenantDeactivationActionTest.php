<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Resources\TenantResource\Actions;

use App\Filament\Resources\TenantResource\Pages\ListTenants;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\HtmlString;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->tenant = Tenant::factory()->create();

    $this->actingAs($this->user);
});

it('cannot see the reactivation action for the tenant when the tenant is active', function (): void {
    livewire(ListTenants::class)->assertTableActionHidden('Huurder heractiveren', $this->tenant);
});

it('can deactivate tenants that are inactive in the application', function (): void {
    livewire(ListTenants::class)
        ->callTableAction('Huurder blokkeren', $this->tenant)
        ->assertSee('Huurder blokkeren')
        ->assertSee('Reden tot plaatsing op de zwarte lijst')
        ->assertSeeHtml((string) new HtmlString('De blokkering van een gebruiker is van kracht tot <strong>6 maanden</strong> na de invoering'));
});

it('requires an deactivation reason to block a tenant', function (): void {
    livewire(ListTenants::class)
        ->callTableAction('Huurder blokkeren', $this->tenant)
        ->fillForm(['deactivation_reason' => null])
        ->assertHasFormErrors(['deactivation_reason' => 'required']);
});
