<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Login;

final class UpdateLastLoginInformation
{
    public function handle(Login $login): void
    {
        $user = User::query()->findOrFail($login->user->getAuthIdentifier());

        /** @phpstan-ignore-next-line */
        $user->update(['last_seen_at' => now(), 'last_login_ip' => request()->ip()]);
    }
}
