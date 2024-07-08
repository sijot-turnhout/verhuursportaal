<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum UserGroup: string implements HasColor, HasLabel
{
    case Leiding = 'leiding';
    case Vzw = 'vzw';
    case Rvb = 'raad van bestuur';
    case Webmaster = 'webmaster';

    public function getLabel(): ?string
    {
        return $this->name;
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Leiding => 'info',
            self::Vzw => 'warning',
            self::Rvb => 'success',
            self::Webmaster => 'danger',
        };
    }

    public function isWebmaster(): bool
    {
        return self::Webmaster === $this;
    }

    /**
     * @deprecated 1.0.0
     *
     * In an attempt to make the code readability more fluent we started to phase out this check.
     * Use the ->isBoardMember() check instead. 'RVB' is a Dutch abbreviation for 'raad van bestuur'.
     * Because we want our code more internationalized we should use the term board member.
     * Below are examples of the code approaches that we have in mind:
     *
     * old: $user->user_group->idRvb();
     * new: $user->isBoardMember();
     */
    public function isRvb(): bool
    {
        return self::Rvb === $this;
    }
}
