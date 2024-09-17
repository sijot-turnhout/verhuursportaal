<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Clusters\PropertyManagement;

use App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource;

beforeEach(function (): void {});

test('it can render the index page', function (): void {
    $this->get(ChangelogResource::getUrl('index'))->assertSuccessful();
});

test('it can lists changelogs', function (): void {});


test('it can render the create page', function (): void {});

test('title is required for creating changelogs', function (): void {});

test('it can render the information page', function (): void {});

test('it can render the edit page', function (): void {});

test('it can retrieve data on the edit page', function (): void {});

test('it can update changelog data', function (): void {});
