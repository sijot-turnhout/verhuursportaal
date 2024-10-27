<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Resources;

use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use function Pest\Livewire\livewire;

describe('UserResource tests', function (): void {
    /**
     * Test that the ListUsers page can be accessed successfully.
     *
     * This test verifies that the index page for listing users in the
     * Filament admin panel is accessible and renders without any errors.
     * Ensures that the Livewire component for ListUsers loads as expected.
     *
     * Expected outcome:
     * - The ListUsers page loads successfully with a 200 status.
     *
     * @return void
     */
    it('can render the index page', function (): void {
        livewire(ListUsers::class)->assertSuccessful();
    });

    /**
     * Test that the CreateUser page can be accessed successfully.
     *
     * This test verifies that the create page for adding new users in the
     * Filament admin panel renders without issues, indicating that the page
     * can be accessed and that the CreateUser Livewire component loads correctly.
     *
     * Expected outcome:
     * - The CreateUser page loads successfully with a 200 status.
     *
     * @return void
     */
    it('can render the create page', function (): void {
        livewire(CreateUser::class)->assertSuccessful();
    });

    /**
     * Test that the EditUser page can be accessed and rendered for a specific user.
     *
     * This test checks that the edit page for modifying a specific user's data
     * in the Filament admin panel is accessible and renders without errors.
     * It creates a new user using a factory and ensures the EditUser component
     * loads when given the correct record.
     *
     * Expected outcome:
     * - The EditUser page loads successfully with a 200 status when a valid user record is provided.
     *
     * @return void
     */
    it('can render the edit page', function (): void {
        $user = User::factory()->create();
        livewire(EditUser::class, ['record' => $user->getRouteKey()])->assertSuccessful();
    });

    /**
     * Test that specific columns are available for rendering in the ListUsers table.
     *
     * This test verifies that certain key columns, such as 'name', 'email', and 'created_at',
     * are present and can be rendered on the ListUsers table. This is crucial to ensure
     * that essential user data is displayed accurately and fully in the user list.
     *
     * Expected outcome:
     * - Each specified column renders successfully in the ListUsers table.
     *
     * @param  string $column The column name to be checked for rendering.
     * @return void
     */
    it('can render columns', function (string $column): void {
        livewire(ListUsers::class)->assertCanRenderTableColumn($column);
    })->with(['name', 'email', 'user_group', 'phone_number', 'last_seen_at', 'created_at']);

    /**
     * Test that specified columns in the ListUsers table can be sorted.
     *
     * This test checks that the 'name', 'user_group', and 'email' columns can be sorted
     * in ascending and descending order. It creates multiple user records, applies sorting
     * on each column, and verifies that records appear in the correct order.
     * This helps ensure that sorting is functional and displays the intended order.
     *
     * Expected outcome:
     * - Records appear in ascending order by default and in descending order when specified, for each column.
     *
     * @param  string $column The column name to be sorted.
     * @return void
     */
    it('can sort columns', function (string $column): void {
        $records = User::factory(5)->create();

        livewire(ListUsers::class)
            ->sortTable($column)
            ->assertCanSeeTableRecords($records->sortBy($column), inOrder: true)
            ->sortTable($column, 'desc')
            ->assertCanSeeTableRecords($records->sortByDesc($column), inOrder: true);
    })->with(['name', 'user_group', 'email']);

    /**
     * Test that specific columns in the ListUsers table can be searched.
     *
     * This test ensures that the search functionality on the ListUsers table works
     * correctly for specified columns, such as 'email', 'phone_number', and 'name'.
     * It creates multiple user records, retrieves the value from the first record for
     * each specified column, and performs a search using that value.
     *
     * Expected outcome:
     * - Records matching the search query in the specified column should be visible.
     * - Records that do not match the search query in the specified column should not be visible.
     *
     * @param  string $column The name of the column to be tested for search functionality.
     * @return void
     */
    it('can search columns', function (string $column): void {
        $records = User::factory(5)->create();
        $value = $records->first()->{$column};

        livewire(ListUsers::class)
            ->searchTable($value)
            ->assertCanSeeTableRecords($records->where($column, $value))
            ->assertCanNotSeeTableRecords($records->where($column, '!=', $value));
    })->with(['email', 'phone_number', 'name']);

    /**
     * Test that a new user record can be created successfully.
     *
     * This test ensures that a user can be created through the CreateUser page.
     * It populates the form fields with data, triggers the 'create' action, and
     * verifies that no form validation errors are present. Additionally, it
     * checks the database to ensure the new user record was saved with the
     * expected data (excluding password confirmation).
     *
     * Expected outcome:
     * - The 'create' action completes without validation errors.
     * - The database contains the new user record with the provided form data.
     *
     * @return void
     */
    it('can create a record', function (): void {
        $record = User::factory()->make();

        $formData = [
            'name' => $record->name,
            'email' => $record->email,
            'user_group' => $record->user_group,
            'password' => $record->password,
            'password_confirmation' => $record->password,
        ];

        livewire(CreateUser::class)
            ->fillForm($formData)
            ->assertActionExists('create')
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(User::class, Arr::except($formData, 'password_confirmation'));
    });

    /**
     * Test that an existing user record can be updated successfully.
     *
     * This test verifies that a user record can be modified through the EditUser page.
     * It creates a user record, loads it in the EditUser page, updates key fields
     * such as 'name', 'user_group', and 'email', and then saves the changes.
     * The test checks for any form validation errors, and confirms that the updated
     * data is present in the database.
     *
     * Expected outcome:
     * - The 'save' action completes without validation errors.
     * - The database reflects the updated user data as per the modified form fields.
     *
     * @return void
     */
    it ('can update a record', function (): void {
        $record = User::factory()->create();
        $newRecord = User::factory()->make();
        $requestData = ['name' => $newRecord->name, 'user_group' => $newRecord->user_group, 'email' => $newRecord->email];

        livewire(EditUser::class, ['record' => $record->getRouteKey()])
            ->fillForm($requestData)
            ->assertActionExists('save')
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(User::class, $requestData);
    });

    /**
     * Test that an existing user record can be deleted.
     *
     * This test verifies that a user record can be deleted through the EditUser page.
     * It creates a user, opens the EditUser page for that user, and performs the
     * delete action using the DeleteAction. Finally, it checks the database to confirm
     * that the user record is no longer present.
     *
     * Expected outcome:
     * - The user record is successfully deleted from the database.
     *
     * @return void
     */
    it('can delete a record', function (): void {
        $william = User::factory()->create();

        livewire(EditUser::class, ['record' => $william->getRouteKey()])
            ->assertActionExists('delete')
            ->callAction(DeleteAction::class);

        $this->assertModelMissing($william);
    });

    /**
     * Test that multiple user records can be deleted in bulk.
     *
     * This test ensures that the bulk delete action in the ListUsers table functions
     * correctly. It creates multiple user records, selects them for bulk deletion,
     * and triggers the DeleteBulkAction. Afterward, it verifies that each selected
     * record has been removed from the database.
     *
     * Expected outcome:
     * - Each user record selected for deletion is no longer present in the database.
     *
     * @return void
     */
    it('can bulk delete records', function (): void {
        $records = User::factory(5)->create();

        livewire(ListUsers::class)
            ->assertTableBulkActionExists('delete')
            ->callTableBulkAction(DeleteBulkAction::class, $records);

        foreach ($records as $record) {
            $this->assertModelMissing($record);
        }
    });

    /**
     * Test that required fields are validated correctly on the CreateUser form.
     *
     * This test verifies that the required fields, such as 'name' and 'email',
     * trigger validation errors if left empty. It attempts to submit the form
     * with null values for each required field and checks that a 'required'
     * validation error is returned for each.
     *
     * Expected outcome:
     * - A 'required' validation error is present for each specified field when its value is null.
     *
     * @param  string $column The name of the field to be tested for required validation.
     * @return void
     */
    it('can validate required', function (string $column): void {
        livewire(CreateUser::class)
            ->fillForm([$column => null])
            ->assertActionExists('create')
            ->call('create')
            ->assertHasFormErrors([$column => ['required']]);
    })->with(['name', 'user_group', 'email']);

    /**
     * Test that the 'email' field is validated correctly for email format.
     *
     * This test ensures that an invalid email format triggers a validation error
     * on the 'email' field. It populates the form with a random string that does
     * not match an email format and verifies that an 'email' validation error is returned.
     *
     * Expected outcome:
     * - An 'email' validation error is present if the 'email' field value does not match an email format.
     *
     * @param  string $column The name of the field to be tested for email format validation.
     * @return void
     */
    it('can validate email', function (string $column): void {
        livewire(CreateUser::class)
            ->fillForm(['email' => Str::random()])
            ->assertActionExists('create')
            ->call('create')
            ->assertHasFormErrors([$column => ['email']]);
    })->with(['email']);

    /**
     * Test that maximum length constraints are validated correctly on specific fields.
     *
     * This test verifies that fields like 'name' and 'email' enforce a maximum character
     * limit of 255. It attempts to submit the form with 256 characters for each specified
     * field and checks for a 'max:255' validation error.
     *
     * Expected outcome:
     * - A 'max:255' validation error is present for each specified field if it exceeds 255 characters.
     *
     * @param  string $column The name of the field to be tested for maximum length validation.
     * @return void
     */
    it('can validate max length', function (string $column): void {
        livewire(CreateUser::class)
            ->fillForm([$column => Str::random(256)])
            ->assertActionExists('create')
            ->call('create')
            ->assertHasFormErrors([$column => ['max:255']]);
    })->with(['name', 'email']);

    /**
     * Test that password confirmation validation is enforced correctly.
     *
     * This test checks that the password confirmation validation is applied by
     * submitting a mismatched password and password_confirmation field. It verifies
     * that a 'same' validation error is returned when the passwords do not match.
     *
     * Expected outcome:
     * - A 'same' validation error is present if 'password' and 'password_confirmation' do not match.
     *
     * @return void
     */
    it('can validate password confirmation', function (): void {
        $record = User::factory()->make();

        livewire(CreateUser::class)
            ->fillForm(['password' => $record->password, 'password_confirmation' => Str::random()])
            ->assertActionExists('create')
            ->call('create')
            ->assertHasFormErrors(['password' => ['same']]);
    });
});
