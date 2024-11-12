<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Resources\TenantResource\Actions;

use App\Filament\Resources\TenantResource\Pages\ListTenants;
use App\Models\Tenant;
use App\Models\User;
use Filament\Notifications\Notification;
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

<<<<<<< HEAD
it ('can successfully put a tenant on the blacklist', function (): void {
=======
it('djdjd', function (): void {
>>>>>>> c765d661870ae1f71512c2e8081f017de01fce26
    $tenant = Tenant::factory()->create();
    $deactivationReason = 'Violation of terms';

    livewire(ListTenants::class)
<<<<<<< HEAD
        ->callTableAction('Huurder blokkeren', $tenant, ['deactivation_reason' => $deactivationReason]);

    // Refresh tenant and verify they are banned
    $bannedTenant = $tenant->refresh();

    expect($bannedTenant->isBanned())->toBeTrue();
    expect($bannedTenant->bans->first()->comment)->toBe($deactivationReason);
    expect($bannedTenant->bans->first()->expired_at->isSameDay(now()->addMonths(6)))->toBeTrue();
=======
        ->callTableAction('Huurder blokkeren', $tenant, [
            'deactivation_reason' => $deactivationReason,
        ]);

    // Refresh tenant and verify they are banned
    $bannedTenant = $tenant->refresh();
    expect($bannedTenant->isBanned())->toBeTrue();
    expect($bannedTenant->bans->first()->comment)->toBe($deactivationReason);
    expect($bannedTenant->bans->first()->expired_at->isSameDay(now()->addMonths(6)))->toBeTrue();

    // Assert the notification was sent with the correct message
>>>>>>> c765d661870ae1f71512c2e8081f017de01fce26
});
