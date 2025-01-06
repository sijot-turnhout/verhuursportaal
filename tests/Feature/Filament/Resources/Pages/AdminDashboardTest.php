<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Pages;

it('can successfully display the admin dashboard page', function (): void {
    $this->get(url('admin'))->assertSuccessful();
});
