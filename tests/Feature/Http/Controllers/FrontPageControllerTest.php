<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

it ('can successfully display the welcome front page oàf the application', function (): void {
    $this->get(route('welcome'))->assertSuccessful();
});
