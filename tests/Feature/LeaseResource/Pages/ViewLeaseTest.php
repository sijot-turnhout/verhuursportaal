<?php

declare(strict_types=1);

use App\Filament\Resources\LeaseResource;
use App\Models\Lease;

test('it can render the page', closure: function (): void {
    $this->get(LeaseResource::getUrl('view', [
        'record' => Lease::factory()->create(),
    ]))->assertSuccessful();
});

test('it can render the notes relation manager', closure: function (): void {});
