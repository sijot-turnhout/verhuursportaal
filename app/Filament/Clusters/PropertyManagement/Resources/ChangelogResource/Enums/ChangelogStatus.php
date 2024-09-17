<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

/**
 * Enum representing the status of a changelog.
 *
 * The `ChangelogStatus` enum defines the possible states of a changelog, such as Open or Closed.
 * It implements `HasLabel` and `HasColor` interfaces to provide UI-friendly labels and colors.
 */
enum ChangelogStatus: int implements HasLabel, HasColor
{
    /**
     * The changelog is currently open and may require further action or follow-up.
     *
     * @var int
     */
    case Open = 1;

    /**
     * The changelog has been closed, indicating that the issue or task has been resolved or completed.
     *
     * @var int
     */
    case Closed = 2;

    /**
     * Get the label associated with the changelog status.
     *
     * This method returns a translated string that represents the current status.
     * The label us used in the UI to display the status in a user-friendly way.
     *
     * @return string|null  The translated label for the status.
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::Open => trans('open'),
            self::Closed => trans('gesloten'),
        };
    }

    /**
     * Get the color associated with the changelog status.
     *
     * This method returns a color string that is used to visually represent the status in the UI.
     * For example, "success" might correspond to green, while "danger" might correspond to red.
     *
     * @return string|array|null  The color or array of colors representing the status.
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Open => 'success',
            self::Closed => 'danger',
        };
    }
}
