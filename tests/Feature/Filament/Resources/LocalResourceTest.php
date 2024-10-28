<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Resources;

use App\Filament\Resources\LocalResource\Pages\CreateLocal;
use App\Filament\Resources\LocalResource\Pages\EditLocal;
use App\Filament\Resources\LocalResource\Pages\ListLocals;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Models\Local;
use Filament\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Support\Str;

use function Pest\Livewire\livewire;

describe('LocalResource tests', function (): void {
    /**
     * Test that the ListLocals page can be accessed successfully.
     *
     * This test verifies that the index page for listing locals in the
     * Filament admin panel is accessible and renders without any errors.
     * Ensures that the livewire component for ListLocals loads as expected.
     *
     * Expected outcome:
     * - The ListLocals page loads successfully with a 200 status.
     *
     * @return void
     */
    it('can render the index page', function (): void {
        livewire(ListLocals::class)->assertSuccessful();
    });

    /**
     * Test that the CreateLocal page can be accessed successfully.
     *
     * This test verifies that the create page for adding new locals in the
     * Filament admin panel renders without issues, indicating that the page
     * can be accessed and that the CreateLocal Livewire component loads correctly.
     *
     * Expected outcome:
     * - The CreateLocal page loads successfully with a 200 status.
     *
     * @return void
     */
    it('can render the create page', function (): void {
        livewire(CreateLocal::class)->assertSuccessful();
    });

    /**
     * Test that the EditLocal page can be accessed and rendered for a specific local.
     *
     * This test checks that the edit page for modifying specific local's data
     * in the Filament admin panel is accessible and renders without errors.
     * It creates a new local using a factory and ensures the EditLocal component
     * loads when given the correct record.
     *
     * Expected outcome:
     * - The EditLocal page loads successfully with a 200 status when a valid local record is provided.
     */
    it('can render the edit page', function (): void {
        $local = Local::factory()->create();
        livewire(EditLocal::class, ['record' => $local->getRouteKey()])->assertSuccessful();
    });

    /**
     * Test that specific columns are available for rendering in the ListLocals table.
     *
     * This test verifies that certain key columns, suck as `name`, `description`, `storage_location`,
     * `updated_at`, `issues_count`, are present and can be rendered on the ListLocals table.
     * This is crucial to ensure that essential local data is displayed accurately and fully in the user list.
     *
     * Expected outcome:
     * - Each specified column renders successfully in the ListLocals table.
     *
     * @param  string $column The column name to be checked for rendering.
     * @return void
     */
    it('can render columns', function (string $column): void {
        livewire(ListLocals::class)->assertCanRenderTableColumn($column);
    })->with(['name', 'description', 'storage_location', 'updated_at', 'issues_count']);


    /**
     * Test that specified columns in the ListLocals table can be sorted.
     *
     * This test checks that the 'name' column can be sorted in ascending and descending order.
     * It creates multiple user records, applies sorting on each column, and verifies that records appear in
     * the correct order. This helps ensure that sorting is functional and displays the intended order.
     *
     * Expected outcome:
     * - Records appear in ascending order by default and in descending order when specified, for each column.
     *
     * @param  string $column The column name to be sorted.
     * @return void
     */
    it('can sort columns', function (string $column): void {
        $records = Local::factory(5)->create();

        livewire(ListLocals::class)
            ->sortTable($column)
            ->assertCanSeeTableRecords($records->sortBy($column), inOrder: true)
            ->sortTable($column, 'desc')
            ->assertCanSeeTableRecords($records->sortByDesc($column), inOrder: true);
    })->with(['name']);

    /**
     * Test that specific columns in the ListLocals table can be searched.
     *
     * This test ensures that the search functionality on the ListLocals table works correctly
     * for specified columns such as 'name' and 'description'.
     * It creates multiple user local records, retrieves the value from the first record for
     * each specified column, and performs a search using that value;
     *
     * Expected outcome:
     * - Records matching the search query in the specified column should be visible.
     * - Records that do not match the search query in the specified column should not be visible.
     *
     * @param  string $column  The name of the column to be tested for search functionality
     * @return void
     */
    it('can search columns', function (string $column): void {
        $records = Local::factory(5)->create();
        $value = $records->first()->{$column};

        livewire(ListLocals::class)
            ->searchTable($value)
            ->assertCanSeeTableRecords($records->where($column, $value))
            ->assertCanNotSeeTableRecords($records->where($column, '!=', $value));
    })->with(['name', 'description']);

    /**
     * Test that an existing local record can be created successfully.
     *
     * This test ensures that a local can be created through the CreateLocal paoge.
     * It populates the form field with data, triggers the 'create' action, and
     * verifies that no form validation errors are present. Additionally, it
     * checks the database to ensure the new local was saved with the
     * expected data.
     *
     * Expected outcome:
     * - The 'create' action completes without calidation errors.
     * - The database contains the new user record with the provided form data.
     *
     * @return void
     */
    it('can create a record', function (): void {
        $record = Local::factory()->make();

        $requestData = [
            'name' => $record->name,
            'description' => $record->description,
            'storage_location' => $record->storage_location
        ];

        livewire(CreateLocal::class)
            ->fillForm($requestData)
            ->assertActionExists('create')
            ->call('create')
            ->assertHasNoFormErrors();

            $this->assertDatabaseHas(Local::class, $requestData);
    });

    /**
     * Test that an existing local record can be updated successfully.
     *
     * This test verifies that a local record can be modified through the EditLocal page.
     * It creates a local record, loads it in the EditLocal page, updates key fields
     * such as 'name', 'description', 'storage_location', and then saves the changes.
     * The test checks for any form validation errors, and confirms that the updated
     * data is present in the database.
     *
     * Expected outcome:
     * - The 'save' action completes without validation errors.
     * - The database reflects the updated local data as per the modified form fields.
     */
    it ('can update a record', function (): void {
        $record = Local::factory()->create();
        $newRecord = Local::factory()->make();
        $requestData = ['name' => $newRecord->name, 'description' => $newRecord->description, 'storage_location' => $newRecord->storage_location];

        livewire(EditLocal::class, ['record' => $record->getRouteKey()])
            ->fillForm($requestData)
            ->assertActionExists('save')
            ->call('save')
            ->assertHasNoFormErrors();

            $this->assertDatabaseHas(Local::class, $requestData);
    });

    /**
     * Test that an existing local record can be deleted.
     *
     * This test verifies that a local record can be deleted through the EditLocal page.
     * It creates a local, opens the EditLocal page for that local, and performs the
     * delete action using the DeleteAction. Finally, it checks the database to confirm
     * that the local record is no longer present.
     *
     * Expected outcome:
     * - The local is successfully deleted from the database.
     *
     * @return void
     */
    it('can delete a record', function (): void {
        $local = Local::factory()->create();

        livewire(EditLocal::class, ['record' => $local->getRouteKey()])
            ->assertActionExists('delete')
            ->callAction(DeleteAction::class);

        $this->assertModelMissing($local);
    });

    /**
     * Test that multiple local records can be deleted in bulk.
     *
     * This test ensures that the bulk delete action in the ListLocals table functions correctly.
     * It creates multiple local records, selects them for bulk deletion,
     * and triggers the DeleteBulkAction. Afterward, it verifies that each selected
     * record had been removed from the database.
     *
     * Expected outcome:
     * - Each user record selected for deletion is no longer present in the database.
     *
     * @return void
     */
    it('can bulk delete records', function (): void {
        $records = Local::factory(5)->create();

        livewire(ListLocals::class)
            ->assertTableBulkActionExists('delete')
            ->callTableBulkAction(DeleteBulkAction::class, $records);

        foreach ($records as $record) {
            $this->assertModelMissing($record);
        }
    });

    /**
     * Test that required fields are validated correctly on the CreateLocal form.
     *
     * This test verifies that the required fields, such as 'name' trigger validation errors if left empty.
     * It attempts to submit the form with null values for each required field and checks that a 'required'
     * validation error is returned for each.
     *
     * Expected outcome:
     * - A 'required' validation error is present for each specified field when its value is null.
     *
     * @param  string $column The name of the field to be tested for required validation.
     * @return void
     */
    it('can validate required', function (string $column): void {
        livewire(CreateLocal::class)
            ->fillForm([$column => null])
            ->assertActionExists('create')
            ->call('create')
            ->assertHasFormErrors([$column => ['required']]);
    })->with(['name']);

    /**
     * Test that maximum length constraints are validated correctly on specific fields.
     *
     * This test verifies that fields like 'name' enforce a maximum character limit of 255.
     * It Attempts to submit the form with 256 characters for each specified field and
     * checks for a 'max:255' validation error.
     *
     * Expected outcome:
     * - A 'max:255' validation error is present for each specified field if it exceeds 255 characters.
     *
     * @param  string $column The name of the field to be tested for maximum length validation.
     * @return void
     */
    it('can validate max length', function (string $column): void {
        livewire(CreateLocal::class)
            ->fillForm([$column => Str::random(400)])
            ->assertActionExists('create')
            ->call('create')
            ->assertHasFormErrors([$column => ['max:255']]);
    })->with(['name']);
});
