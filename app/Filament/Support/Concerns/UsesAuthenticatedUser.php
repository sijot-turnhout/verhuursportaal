<?php

declare(strict_types=1);

namespace App\Filament\Support\Concerns;

use App\Models\User;

trait UsesAuthenticatedUser
{
    public function filamentUser(): User
    {
        return auth()->user();
    }
}
