<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserGroup;
use App\Models\Lease;
use App\Models\User;
use App\Support\Features;

final readonly class LeasePolicy
{
    public function finalizeMetrics(User $user, Lease $lease): bool
    {
        return $this->featureIsEnabled() && ($lease->isFinalized() || $lease->isConfirmed());
    }

    public function generateInvoice(User $user, Lease $lease): bool
    {
        return ($lease->isConfirmed() || $lease->isFinalized())
            && $lease->invoice()->doesntExist()
            && in_array($user->user_group, [UserGroup::Webmaster, UserGroup::Rvb], true);
    }

    public function viewInvoice(User $user, Lease $lease): bool
    {
        return ($lease->isConfirmed() || $lease->isFinalized())
            && $lease->invoice()->exists()
            && in_array($user->user_group, [UserGroup::Webmaster, UserGroup::Rvb], true);
    }

    public function update(User $user, Lease $lease): bool
    {
        return $user->user_group->isRvb() || $user->user_group->isWebmaster();
    }

    public function unlockMetrics(User $user, Lease $lease): bool
    {
        return $this->featureIsEnabled()
            && $lease->hasRegisteredMetrics()
            && ($user->user_group->isRvb() || $user->user_group->isWebmaster());
    }

    private function featureIsEnabled(): bool
    {
        return Features::enabled(Features::utilityMetrics());
    }
}
