<?php

declare(strict_types=1);

namespace App\Enums;

use ArchTech\Enums\Comparable;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

/**
 * Enum UserGroup
 *
 * Represents different user groups within the application. Each group is associated with
 * a specific label and color, providing a consistent way to categorize and display user roles.
 *
 * @package App\Enums
 */
enum UserGroup: string implements HasColor, HasLabel, HasIcon
{
    use Comparable;

    /**
     * Leiding (Leadership)
     *
     * Represents users who are part of the leadership group.
     */
    case Leiding = 'leiding';

    /**
     * Vzw (Non-profit Organization)
     *
     * Represents users who are part of the non-profit organization group.
     */
    case Vzw = 'vzw';

    /**
     * Rvb (Board of Directors)
     *
     * Represents users who are members of the board of directors.
     * 'RVB' is a Dutch abbreviation for 'raad van bestuur'.
     */
    case Rvb = 'raad van bestuur';

    /**
     * Webmaster
     *
     * Represents users who have webmaster privileges.
     */
    case Webmaster = 'webmaster';

    /**
     * Get the label for the current user group.
     *
     * The label is derived from the enum's name, making it a simple and consistent
     * representation of the group.
     *
     * @return string|null  The label corresponding to the user group.
     */
    public function getLabel(): ?string
    {
        return $this->name;
    }

    public function getIcon(): ?string
    {
        return 'heroicon-o-users';
    }

    /**
     * Get the associated color for the current user group.
     *
     * The returned color is used to visually represent the group in the application's UI,
     * allowing users to quickly identify the group through consistent color coding.
     *
     * Returns the color corresponding to the user group.
     *
     * {@inheritDoc}
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Leiding => 'info',
            self::Vzw => 'warning',
            self::Rvb => 'success',
            self::Webmaster => 'danger',
        };
    }

    /**
     * Check if the user group is 'Webmaster'.
     *
     * This method is deprecated as of version 1.0.0. Use this method to check if the current
     * group is 'Webmaster'. For new implementations, consider using alternative methods
     * as this method is being phased out.
     *
     * @deprecated 1.0.0
     * @return bool  True if the user group is 'Webmaster', false otherwise.
     */
    public function isWebmaster(): bool
    {
        return self::Webmaster === $this;
    }

    /**
     * Check if the user group is 'Rvb' (Board Member).
     *
     * This method is deprecated as of version 1.0.0. The term 'RVB' is a Dutch abbreviation
     * for 'raad van bestuur' (Board of Directors). To improve code readability and internationalization,
     * it's recommended to use the `isBoardMember()` method instead of this check.
     *
     * Example migration:
     * - Old: $user->user_group->isRvb();
     * - New: $user->isBoardMember();
     *
     * @deprecated 1.0.0
     * @return bool  True if the user group is 'Rvb', false otherwise.
     */
    public function isRvb(): bool
    {
        return self::Rvb === $this;
    }
}
