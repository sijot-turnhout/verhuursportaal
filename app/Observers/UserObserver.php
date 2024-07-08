<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\User;

final class UserObserver
{
    public function created(User $user): void
    {
        dd('Needs to implement the method for sending the welcome mail');
    }
}
