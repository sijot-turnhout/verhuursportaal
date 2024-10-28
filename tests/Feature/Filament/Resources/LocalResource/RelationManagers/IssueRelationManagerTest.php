<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Resources\LocalResource\RelationManagers;

use App\Filament\Clusters\PropertyManagement\Resources\IssueResource\Pages\EditIssue;
use App\Filament\Resources\LocalResource\RelationManagers\IssuesRelationManager;
use App\Models\Issue;
use App\Models\Local;

use function Pest\Livewire\livewire;

/**
 * Create a local instance with 10 assoicated issue records before each testcase
 */
beforeEach(function (): void {
    $this->local = Local::factory()
        ->has(Issue::factory()->count(10))
        ->create();
});

/**
 * Test suite for validating the behavior of the IssuesRelationManager.
 *
 * This suite ensures:
 * - The IssuesRelationManager renders correctly.
 * - Columns can be rendered, sorted, and searched within the table.
 * - Table actions (edit, delete, close, reopen) function as expected.
 *
 * @todo Implement tests for the relation manager filters
 */
describe('Issue relation manager tests', function (): void {
    /**
     * Test that the IssuesRelationManager component renders without errors.
     *
     * Verifies that the IssuesRelationManager displays successfully when loaded.
     *
     * Expected outcome:
     * - The IssuesRelationManager renders without any errors.
     *
     * @return void
     */
    it('can display the issue relation manager', function (): void {
        livewire(IssuesRelationManager::class, ['ownerRecord' => $this->local, 'pageClass' => EditIssue::class])
            ->assertSuccessful();
    });

    /**
     * Tests related to rendering, sorting, and searching within the table columns of the IssuesRelationManager.
     *
     * @return void
     */
    describe('table rendering', function (): void {
        /**
         * Test that columns in the IssuesRelationManager table can be sorted in ascending and descending order.
         *
         * This test applies ascending and descending sorting on a specified column, checking that the records are displayed in the correct order.
         *
         * Expected outcome:
         * - Records in the IssuesRelationManager table are ordered correctly by ascending and descending sort orders.
         *
         * @param  string $column The column to be tested for sorting.
         * @return void
         */
        it('can sort columns', function (string $column): void {
            livewire(IssuesRelationManager::class, ['ownerRecord' => $this->local, 'pageClass' => EditIssue::class])
                ->call('sortTable', $column) // Sort in ascending order
                ->assertSeeInOrder($this->local->issues->sortBy($column)->pluck($column)->toArray())
                ->call('sortTable', $column, 'desc') // Sort in descending order
                ->assertSeeInOrder($this->local->issues->sortByDesc($column)->pluck($column)->toArray());
        })->with(['user.name']);

        /**
         * Test that specified columns are rendered within the IssuesRelationManager table.
         *
         * Verifies that specified columns are visible, ensuring table structure and fields are correct.
         *
         * Expected outcome:
         * - Each specified column is rendered in the IssuesRelationManager table.
         *
         * @param string $column The column expected to be rendered.
         * @return void
         */
        it('can render columns', function (string $column): void {
            livewire(IssuesRelationManager::class, ['ownerRecord' => $this->local, 'pageClass' => EditIssue::class])
                ->assertCanRenderTableColumn($column);
        })->with(['id', 'user.name', 'status', 'title', 'description', 'created_at']);

        /**
         * Test that specified columns in the IssuesRelationManager table support search functionality.
         *
         * This test searches each column for a specific value and verifies that matching records are displayed, while non-matching records are hidden.
         *
         * Expected outcome:
         * - Records matching the search term in the specified column are visible, while others are not.
         *
         * @param  string $column The column to be searched.
         * @return void
         */
        it('can search columns', function (string $column): void {
            if ($column === 'status') {
                $value = $this->local->issues->first()->{$column}->value;
            } else {
                $value = $this->local->issues->first()->{$column};
            }

            livewire(IssuesRelationManager::class, ['ownerRecord' => $this->local, 'pageClass' => EditIssue::class])
                ->searchTable($value)
                ->assertCanSeeTableRecords($this->local->issues->where($column, $value))
                ->assertCanNotSeeTableRecords($this->local->issues->where($column, '!=', $value));

        })->with(['name', 'status']);
    });

    /**
     * Tests that verify the presence of table actions within the IssuesRelationManager.
     *
     * @return void
     */
    describe('actions', function (): void {
        /**
         * Test that the edit action is available on the IssuesRelationManager table.
         *
         * This verifies that users can access the edit functionality on related records in the table.
         *
         * Expected outcome:
         * - The 'edit' action is present on the IssuesRelationManager table.
         *
         * @return void
         */
        it('The edit action is present on the relation manager table', function (): void {
            livewire(IssuesRelationManager::class, ['ownerRecord' => $this->local, 'pageClass' => EditIssue::class])
                ->assertTableActionExists('edit');
        });

        /**
         * Test that the delete action is available on the IssuesRelationManager table.
         *
         * This verifies that users can delete related records directly from the table.
         *
         * Expected outcome:
         * - The 'delete' action is present on the IssuesRelationManager table.
         *
         * @return void
         */
        it('The delete action is present on the relation manager table', function (): void {
            livewire(IssuesRelationManager::class, ['ownerRecord' => $this->local, 'pageClass' => EditIssue::class])
                ->assertTableActionExists('delete');
        });

        /**
         * Test that the close issue action is available on the IssuesRelationManager table.
         *
         * Verifies that users can mark issues as closed directly from the table interface.
         *
         * Expected outcome:
         * - The 'close issue' action is present on the IssuesRelationManager table.
         *
         * @return void
         */
        it('The close issue action is present on the relation manager table', function (): void {
            livewire(IssuesRelationManager::class, ['ownerRecord' => $this->local, 'pageClass' => EditIssue::class])
                ->assertTableActionExists('Werkpunt sluiten');
        });

        /**
         * Test that the reopen issue action is available on the IssuesRelationManager table.
         *
         * This verifies that closed issues can be reopened from the table interface.
         *
         * Expected outcome:
         * - The 'reopen issue' action is present on the IssuesRelationManager table.
         *
         * @return void
         */
        it('The reopen issue action is present on the relation manager table', function (): void {
            livewire(IssuesRelationManager::class, ['ownerRecord' => $this->local, 'pageClass' => EditIssue::class])
                ->assertTableActionExists('Werkpunt heropenen');
        });

        /**
         * Test that the IssuesRelationManager header actions appear in the specified order.
         *
         * Verifies that each header action, such as 'create', is rendered in the correct order.
         *
         * Expected outcome:
         * - Header actions in the IssuesRelationManager table match the specified order.
         *
         * @return void
         */
        it ('Assures that the correct header actions exists in order', function (): void {
            livewire(IssuesRelationManager::class, ['ownerRecord' => $this->local, 'pageClass' => EditIssue::class])
                ->assertTableHeaderActionsExistInOrder(['create']);
        });

        /**
         * Test that the 'connect to user' action is present on the IssuesRelationManager table.
         *
         * This test ensures that users can link an issue to a user via the 'Koppelen' action.
         *
         * Expected outcome:
         * - The 'Koppelen' (connect to user) action is visible on the IssuesRelationManager table.
         *
         * @return void
         */
        it ('The connect to user action is present on the relation manager table', function (): void {
            livewire(IssuesRelationManager::class, ['ownerRecord' => $this->local, 'pageClass' => EditIssue::class])
                ->assertTableActionExists('Koppelen');
        });
    });
});
