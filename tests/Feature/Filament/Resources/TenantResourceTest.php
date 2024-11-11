<?php

declare(strict_types=1);

namespace Tesrs\Feature\Filament\Resources;

use App\Filament\Resources\TenantResource\Pages\CreateTenant;
use App\Filament\Resources\TenantResource\Pages\EditTenant;
use App\Filament\Resources\TenantResource\Pages\ListTenants;
use App\Models\Tenant;
use Filament\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Support\Str;

use function Pest\Livewire\livewire;

describe('Tenantesource tests', function (): void {
    it ('can render the index page', function (): void {
        livewire(ListTenants::class)->assertSuccessful();
    });

    it ('can render the create page', function (): void {
        livewire(CreateTenant::class)->assertSuccessful();
    });

    it ('can render the edit page', function (): void {
        $tenant = Tenant::factory()->create();
        livewire(EditTenant::class, ['record' => $tenant->getRouteKey()])->assertSuccessful();
    });

    it ('can render columns', function (string $column): void {
        Tenant::factory()->create();
        livewire(ListTenants::class)->assertCanRenderTableColumn($column);
    })->with(['name', 'isBlacklisted', 'phone_number', 'address', 'created_at']);

    it ('can sort columns', function (string $column): void {
        $tenants = Tenant::factory(5)->create();

        livewire(ListTenants::class)
            ->sortTable($column)
            ->assertCanSeeTableRecords($tenants->sortBy($column), inOrder: true)
            ->sortTable($column, 'desc')
            ->assertCanSeeTableRecords($tenants->sortByDesc($column), inOrder: true);
    })->with(['email']);

    it ('can search columns', function (string $column): void {
        $tenants = Tenant::factory(5)->create();
        $value = $tenants->first()->{$column};

        livewire(ListTenants::class)
            ->searchTable($value)
            ->assertCanSeeTableRecords($tenants->where($column, $value))
            ->assertCanNotSeeTableRecords($tenants->where($column, '!=', $value));
    })->with(['name', 'email', 'phone_number', 'address']);

    it ('can create a record', function (): void {
        $record = Tenant::factory()->make();
        $requestData = ['firstName' => $record->firstName, 'lastName' => $record->lastName, 'email' => $record->email, 'phone_number' => $record->phone_number, 'address' => $record->address];

        livewire(CreateTenant::class)
            ->fillForm($requestData)
            ->assertActionExists('create')
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(Tenant::class, $requestData);
    });

    it ('can update a record', function (): void {
        $record = Tenant::factory()->create();
        $newRecord = Tenant::factory()->make();
        $requestData = ['firstName' => $newRecord->firstName, 'lastName' => $newRecord->lastName, 'email' => $newRecord->email, 'phone_number' => $newRecord->phone_number, 'address' => $newRecord->address];

        livewire(EditTenant::class, ['record' => $record->getRouteKey()])
            ->fillForm($requestData)
            ->assertActionExists('save')
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(Tenant::class, $requestData);
    });

    it ('can delete a record', function (): void {
        $tenant = Tenant::factory()->create();

        livewire(EditTenant::class, ['record' => $tenant->getRouteKey()])
            ->assertActionExists('delete')
            ->callAction(DeleteAction::class);

        $this->assertModelMissing($tenant);
    });

    it ('can bulk delete records', function (): void {
        $records = Tenant::factory(5)->create();

        livewire(ListTenants::class)
            ->assertTableBulkActionExists('delete')
            ->callTableBulkAction(DeleteBulkAction::class, $records);

        foreach ($records as $record) {
            $this->assertModelMissing($record);
        }
    });

    it ('can validate required', function (string $column): void {
        livewire(CreateTenant::class)
            ->fillForm([$column => null])
            ->assertActionExists('create')
            ->call('create')
            ->assertHasFormErrors([$column => ['required']]);
    })->with(['phone_number', 'lastName', 'firstName']);

    it ('can validate email', function (string $column): void {
        livewire(CreateTenant::class)
            ->fillForm(['email' => Str::random()])
            ->assertActionExists('create')
            ->call('create')
            ->assertHasFormErrors([$column => ['email']]);
    })->with(['email']);

    it ('can only have a unique email and telephone_number', function (string $column): void {
        $tenant = Tenant::factory()->create();

        livewire(CreateTenant::class)
            ->fillForm([$column => $tenant->{$column}])
            ->assertActionExists('create')
            ->call('create')
            ->assertHasFormErrors([$column => ['unique']]);
    })->with(['phone_number', 'email']);

    it ('can validate max length', function (string $column): void {
        livewire(CreateTenant::class)
            ->fillForm([$column => Str::random(256)])
            ->assertActionExists('create')
            ->call('create')
            ->assertHasFormErrors([$column => ['max:255']]);
    })->with(['firstName', 'lastName', 'email', 'phone_number', 'address']);
});
