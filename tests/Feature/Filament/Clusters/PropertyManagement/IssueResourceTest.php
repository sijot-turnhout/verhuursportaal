<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Clusters\PropertyManagement;

use App\Filament\Clusters\PropertyManagement\Resources\IssueResource;
use App\Filament\Clusters\PropertyManagement\Resources\IssueResource\Pages\EditIssue;
use App\Filament\Clusters\PropertyManagement\Resources\IssueResource\Pages\ListIssues;
use App\Models\Issue;
use App\Models\User;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->actingAs(User::factory()->createQuietly());
});

test ('it can render the index page', function (): void {
    $this->get(IssueResource::getUrl('index'))->assertSuccessful();
});

test ('it can list issues', function (): void {
    $issues = Issue::factory(10)->create();
    livewire(ListIssues::class)->assertSee($issues->pluck('title')->toArray());
});

test ('it can render the edit page', function (): void {
    $issue = Issue::factory()->create();
    livewire(EditIssue::class, ['record' => $issue->getRouteKey()])->assertSuccessful();
})->skip('issue with the permissions that needs further investigation');

test ('it can retrieve data', function (): void {
});

test ('it can update issue data', function (): void {
})->skip();
