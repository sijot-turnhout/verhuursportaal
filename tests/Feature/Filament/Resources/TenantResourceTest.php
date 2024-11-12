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
    /**
     * Test that the ListTenants page can be accessed successfully.
     *
     * This test verifies the index page for listing tenants in the
     * Filament admin panel is accessible and renders without any errors.
     * Ensures that the livewire component for ListTenants loads as expected.
     *
     * Expected outcome:
     * - The ListTenants page loads successfully with a 200 status.
     *
     * @return void
     */
    it ('can render the index page', function (): void {
        livewire(ListTenants::class)->assertSuccessful();
    });

    /**
     * Test that tthe CreateTenant page can be accessed successfully.
     *
     * This test verifies that the create page for adding new tenants in the
     * Filament admin panel renders wuthout issues, indicating that the page
     * can be accessed and that the CreateUser Livewire component loads correctly.
     *
     * Expected outcome:
     * - The CreateTenant page loads successfully with a 200 status.
     *
     * @return void
     */
    it ('can render the create page', function (): void {
        livewire(CreateTenant::class)->assertSuccessful();
    });

    /**
     * Test that the EditTenant page can be accessed and rendered for a specific tenant.
     *
     * qThis test checks that the edit page for modyfying a specific tenant's data
     * in the Filament admin panel is accessible and renders without errors.
     * It creates a new tenant ysing a factory and ensures the EditTenant component
     * loads when given the correct record.
     *
     * Expected outcome:
     * - The Edittenant page loads successfully with a 200 status when a valid user record is provided.
     *
     * @return void
     */
    it ('can render the edit page', function (): void {
        $tenant = Tenant::factory()->create();
        livewire(EditTenant::class, ['record' => $tenant->getRouteKey()])->assertSuccessful();
    });

    /**
     * Test that specific columns are availablle for rendering in the ListTenants table.
     *
     * This test verifies that certain key columns, such as 'name', 'email', and 'created_at',
     * are present and can be rendered on the ListTenants table. This is crucial to ensure
     * that essential tenant data is displayed accurately and fully in the tenant list.
     *
     * Expected outcome:
     * - Each specified column renders successfully in the ListTenants table.
     *
     * @param  string $column The column name to be checked for rendering.
     * @return void
     */
    it ('can render columns', function (string $column): void {
        Tenant::factory()->create();
        livewire(ListTenants::class)->assertCanRenderTableColumn($column);
    })->with(['name', 'isBlacklisted', 'phone_number', 'address', 'created_at']);

    /**
     * Test that specified columns in the ListTenants table can be sorted.
     *
     * This test checks that the 'email' column can be sorted in ascending and descending order.
     * It creates mulitple tenant records, applies sorting on each column, and verifies that records appear in the correct order.
     * This helps ensure that sorting is functional and displays the intended order.
     *
     * Expected outcome:
     * - Records appear in ascending order by default and in descending order when specified, for each column.
     *
     * @param  string $column The column name to be sorted.
     * @return void
     */
    it ('can sort columns', function (string $column): void {
        $tenants = Tenant::factory(5)->create();

        livewire(ListTenants::class)
            ->sortTable($column)
            ->assertCanSeeTableRecords($tenants->sortBy($column), inOrder: true)
            ->sortTable($column, 'desc')
            ->assertCanSeeTableRecords($tenants->sortByDesc($column), inOrder: true);
    })->with(['email']);

    /**
     * Test that specific columns in the ListTenants tale can be searched.
     *
     * This test ensures that the search functionality on the ListTenants table works correctly for the specified columns, such as 'name', 'email', 'phone_number' and 'address'
     * It creates multiple Tenant records, retrieves the value from the first record for each specified column, and performs a search using that value.
     *
     * Expected outcome:
     * - Records matching the search query in the specified column should be visible.
     * - Rezcords that do not match the search query in the specified column should not be visible.
     *
     * @param  string $column The name of the column to be tested for search functionality.
     * @return void
     */
    it ('can search columns', function (string $column): void {
        $tenants = Tenant::factory(5)->create();
        $value = $tenants->first()->{$column};

        livewire(ListTenants::class)
            ->searchTable($value)
            ->assertCanSeeTableRecords($tenants->where($column, $value))
            ->assertCanNotSeeTableRecords($tenants->where($column, '!=', $value));
    })->with(['name', 'email', 'phone_number', 'address']);

    /**
     * t§est that a new Tenant record can be created successfully.
     *
     * This test ensures that a tenant can be created through the CreateTenant page.
     * It populates the form fields with data, triggers the 'create' action, and verifies thet no validationb errors are present.
     * Additionally, it checks the database to ensure the new tenant record was ssaved with the expected data.
     *
     * Expected outcome:
     * - The 'create' action completes without validation errors.
     * - The database contains the new tenant record with the provided form data.
     *
     * @return void
     */
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

    /**
     * Test that an existing tenant record can be updated successfully.
     *
     * This test verifies that a tenant record can be modified through the EditTenant page.
     * It creates a trenant record, loads it in the EditTenant page, updates key fields such as 'firstName', 'lstName', email', etc... and then saves the changes.
     * The test checks for any form validation errors, and confirms that the updated data is present in the database.
     *
     * Expected outcome:
     * - The 'save action' completes without validation errors.
     * - The database reflects the updated tenant data as per the modified form fields.
     *
     * @return void
     */
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

    /**
     * Test that an existing tenant record can be deleted.
     *
     * This test verifies that a tenant record can be deleted through the EditTenant page.
     * It creates a Tenant, openvs the Edittenant page for that Tenant, and performs the delete action using the DeleteAction.
     * Finally, it checks the database to confirm that the tenant record is no longer present.
     *
     * Expected outcome:
     * - The teznant record is successfully deleted from the database.
     *
     * @return void
     */
    it ('can delete a record', function (): void {
        $tenant = Tenant::factory()->create();

        livewire(EditTenant::class, ['record' => $tenant->getRouteKey()])
            ->assertActionExists('delete')
            ->callAction(DeleteAction::class);

        $this->assertModelMissing($tenant);
    });

    /**
     * Test that multiple tenant records can be deleted in bulk.
     *
     * This test ensures that the bulk delete action in ListTenants table functions functions works correctly.
     * It creates multiplez user records, selects them for bulk deletion, and triggers the DeleteBulkAction.
     * Afterward, it verifies that each selected record has been removed from the database.
     *
     * Expected outcome:
     * - Each tenant record selected for deletion is no longer present in the database.
     *
     * @return void
     */
    it ('can bulk delete records', function (): void {
        $records = Tenant::factory(5)->create();

        livewire(ListTenants::class)
            ->assertTableBulkActionExists('delete')
            ->callTableBulkAction(DeleteBulkAction::class, $records);

        foreach ($records as $record) {
            $this->assertModelMissing($record);
        }
    });

    /**
     * Test that required fields are validated correctly on the CreateTenant form.
     *
     * This test verifies that the required fields, such as 'firstname', 'lastName' and 'phone_numbers' trigger validation errors if left empty.
     * It attempts to submit the form with null values for each required field and checks that a 'required' validation error is returned for each.
     *
     * Expected outcome:
     * - A 'required' validation error is present for each specified field when its value is null.
     *
     * @param  string $column The name of the field to be tested for required validation.
     * @return void
     */
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

    /**
     * Test that the email and phone number fields are validated as unique on the CreateTenant form.
     *
     * This test verifies that both 'email' and 'phone_number' fields trigger a validation error if they are not unique.
     * It creates an existing tenant and attempts to submit the form with the same values for 'email' and 'phone_number',
     * checking that a 'unique' validation error is returned for each field.
     *
     * Expected outcome:
     * - A 'unique' validation error is present for each specified field when a duplicate value is used.
     *
     * @param  string $column The name of the field to be tested for uniqueness validation.
     * @return void
     */
    it ('can only have a unique email and telephone_number', function (string $column): void {
        $tenant = Tenant::factory()->create();

        livewire(CreateTenant::class)
            ->fillForm([$column => $tenant->{$column}])
            ->assertActionExists('create')
            ->call('create')
            ->assertHasFormErrors([$column => ['unique']]);
    })->with(['phone_number', 'email']);

    /**
     * Test that maximum length constraints are validated correctly on specific fields.
     *
     * This test verifies that fields like 'firstName' and 'lastName', 'email', 'phone_number' and address enforce a maximum character
     * limit of 255. It attempts to submit the form with 256 characters for each specified
     * field and checks for a 'max:255' validation error.
     *
     * Expected outcome:
     * - A 'max:255' validation error is present for each specified field if it exceeds 255 characters.
     *
     * @param  string $column The name of the field to be tested for maximum length validation.
     * @return void
     */
    it ('can validate max length', function (string $column): void {
        livewire(CreateTenant::class)
            ->fillForm([$column => Str::random(256)])
            ->assertActionExists('create')
            ->call('create')
            ->assertHasFormErrors([$column => ['max:255']]);
    })->with(['firstName', 'lastName', 'email', 'phone_number', 'address']);
});
