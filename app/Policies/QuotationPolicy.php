<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\QuotationStatus;
use App\Models\Quotation;
use App\Models\User;

final readonly class QuotationPolicy
{
    public function finalize(User $user, Quotation $quotation): bool
    {
        return ($user->user_group->isRvb() || $user->user_group->isWebmaster())
            && QuotationStatus::Draft === $quotation->status;
    }

    public function decline(User $user, Quotation $quotation): bool
    {
        return ($user->user_group->isRvb() || $user->user_group->isWebmaster())
            && QuotationStatus::Open === $quotation->status;
    }

    public function approve(User $user, Quotation $quotation): bool
    {
        return ($user->user_group->isRvb() || $user->user_group->isWebmaster())
            && QuotationStatus::Open === $quotation->status;
    }
}
