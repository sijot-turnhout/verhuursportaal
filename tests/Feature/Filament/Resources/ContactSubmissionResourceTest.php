<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Resources;

use App\Filament\Resources\ContactSubmissionResource;
use App\Filament\Resources\ContactSubmissionResource\Pages\ListContactSubmissions;
use App\Models\ContactSubmission;
use Filament\Tables\Actions\DeleteBulkAction;

use function Pest\Livewire\livewire;

describe('ContactSumissioNRsource tests', function (): void {
    it('can render the index page', function (): void {
        livewire(ListContactSubmissions::class)->assertSuccessful();
    });

    it('can render columns', function (string $column): void {
        livewire(ListContactSubmissions::class)->assertCanRenderTableColumn($column);
    })->with(['full_name', 'status', 'email', 'phone_number', 'created_at']);

    it('can sort columns', function (string $column): void {
        $records = ContactSubmission::factory(5)->create();

        livewire(ListContactSubmissions::class)
            ->sortTable($column)
            ->assertCanSeeTableRecords($records->sortBy($column), inOrder: true)
            ->sortTable($column, 'desc')
            ->assertCanSeeTableRecords($records->sortByDesc($column), inOrder: true);
    })->with(['status', 'email', 'created_at']);

    it('can search columns', function (string $column): void {
        $records = ContactSubmission::factory(5)->create();
        $value = $records->first()->{$column};

        livewire(ListContactSubmissions::class)
            ->searchTable($value)
            ->assertCanSeeTableRecords($records->where($column, $value))
            ->assertCanNotSeeTableRecords($records->where($column, '!=', $value));
    })->with(['full_name', 'email']);

    it('can view a constact submission', function (): void {
        $record = ContactSubmission::factory()->create();

        livewire(ListContactSubmissions::class)
            ->callTableAction('view', $record)
            ->assertHasNoTableActionErrors()
            ->assertSee($record->full_name)
            ->assertSee($record->email)
            ->assertSee($record->phone_number);
    });

    it('can bulk delete contact submissions', function (): void {
        $records = ContactSubmission::factory(5)->create();

        livewire(ListContactSubmissions::class)
            ->assertTableBulkActionExists('delete')
            ->callTableBulkAction(DeleteBulkAction::class, $records);

        foreach ($records as $record) {
            $this->assertModelMissing($record);
        }
    });

    it('can bulk finalize contact submissions', function (): void {
        $records = ContactSubmission::factory(5)->create();

        livewire(ListContactSubmissions::class)
            ->assertTableBulkActionExists('In behandeling')
            ->callTableBulkAction('In behandeling', $records)
            ->assertHasNoTableBulkActionErrors();
    });

    it('checks that the getNavigationBadge function works correctly', function (): void {
        ContactSubmission::factory()->create();
        expect(ContactSubmissionResource::getNavigationBadge())->toEqual(1);

        ContactSubmission::factory(5)->create();
        expect(ContactSubmissionResource::getNavigationBadge())->toEqual(6);

    });

    it('can bulk mark contact submissions in progress', function (): void {
        $records = ContactSubmission::factory(5)->create();

        livewire(ListContactSubmissions::class)
            ->assertTableBulkActionExists('Behandeld')
            ->callTableBulkAction('In behandeling', $records)
            ->assertHasNoTableBulkActionErrors();
    });
});
