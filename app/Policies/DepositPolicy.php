<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserGroup;
use App\Filament\Clusters\Billing\Resources\DepositResource\Enums\DepositStatus;
use App\Models\Deposit;
use App\Models\User;

final readonly class DepositPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if (! $user->user_group->in(enums: [UserGroup::Rvb, UserGroup::Vzw, UserGroup::Webmaster])) {
            return false;
        }

        return null;
    }

    public function markAsPartiallyRefunded(User $user, Deposit $deposit): bool
    {
        return $deposit->status->is(DepositStatus::Paid);
    }
}
