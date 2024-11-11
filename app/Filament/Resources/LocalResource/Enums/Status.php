<?php

declare(strict_types=1);

namespace App\Filament\Resources\LocalResource\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

/**
 * Enum representing the status of an issue within the application.
 *
 * The `Status` enum defines two possible states for an issue: `Open` and `Closed`.
 * Each status is associated with specific UI properties such as color, label, and icon,
 * which are used throughout the application to provide a consistent user experience.
 *
 * This enum implements the `HasColor`, `HasIcon`, and `HasLabel` contracts, ensuring
 * that the status values can be easily integrated into the Filament UI components.
 *
 * @todo We need to rename the enum to reduce the mental overhead. The name is pretty unclear.
 *
 * @package App\Filament\Resources\LocalResource\Enums
 */
enum Status: string implements HasColor, HasIcon, HasLabel
{
    /**
     * The issue is open and requires attention or resolution.
     */
    case Open = 'open';

    /**
     * The issue is closed and no longer requires action.
     */
    case Closed = 'gesloten';

    /**
     * Gets the color associated with the current status.
     *
     * Returns the color used to represent the status in the UI. For example, an open issue
     * might be represented with a "danger" (red) color, while a closed issue might be represented
     * with a "success" (green) color.
     *
     * Returns the color value associated with the status.
     *
     * {@inheritDoc}
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Open => 'danger',
            self::Closed => 'success',
        };
    }

    /**
     * Gets the label associated with the current status.
     *
     * Returns the human-readable label for the status, which is displayed in the UI.
     * This label is localized and should be understandable to end-users.
     *
     * @return string|null The label associated with the status.
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::Open => 'Open',
            self::Closed => 'Gesloten',
        };
    }

    /**
     * Gets the icon associated with the current status.
     *
     * Returns the icon used to visually represent the status in the UI. The icons are typically
     * Heroicons, which are used throughout Filament for consistent and recognizable visuals.
     *
     * @return string|null The icon associated with the status.
     */
    public function getIcon(): ?string
    {
        return match ($this) {
            self::Open => 'heroicon-o-exclamation-circle',
            self::Closed => 'heroicon-o-check-circle',
        };
    }

    /**
     * Checks if the current status represents an open issue.
     *
     * This method can be used to determine if the status is currently `Open`.
     * It provides a convenient way to check the state without comparing enum values directly.
     *
     * @deprecated v1.1.0 - This function will be refactored to a more universal approach with enum helpers support.
     *
     * @return bool True if the status is `Open`, otherwise false.
     */
    public function isOpenIssueTicket(): bool
    {
        return self::Open === $this;
    }

    /**
     * Checks if the current status represents a closed issue.
     *
     * This method can be used to determine if the status is currently `Closed`.
     * It provides a convenient way to check the state without comparing enum values directly.
     *
     * @deprecated v1.1.0 - This function will be refactored to a more universal approach with enum helpers support.
     *
     * @return bool True if the status is `Closed`, otherwise false.
     */
    public function isClosedIssueTicket(): bool
    {
        return self::Closed === $this;
    }
}
