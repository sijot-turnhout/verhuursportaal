<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\IssueResource\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

/**
 * Enum Priority
 *
 * Represents the priority levels assigned to issues within the Property Management system.
 * Each priority level is associated with a color, label, and icon, allowing for a consistent
 * and intuitive representation across the user interface. This enum is crucial for categorizing
 * and handling issues based on their urgency.
 *
 * @package App\Filament\Clusters\PropertyManagement\Resources\IssueResource\Enums
 */
enum Priority: int implements HasLabel, HasIcon, HasColor
{
    /**
     * Low Priority
     *
     * Indicates that the issue has a low priority, requiring minimal attention. Typically used
     * for issues that do not need immediate resolution.
     *
     * - Color: Info (blue)
     * - Icon: Ellipsis Horizontal Circle
     */
    case Low = 1;

    /**
     * Medium Priority
     *
     * Indicates that the issue has a medium priority, which means it should be addressed but is not urgent.
     *
     * - Color: Success (green)
     * - Icon: Arrow Down Circle
     */
    case Medium = 0;

    /**
     * High Priority
     *
     * Indicates that the issue has a high priority and should be addressed promptly. These issues are important
     * but not critical.
     *
     * - Color: Warning (yellow)
     * - Icon: Arrow Up Circle
     */
    case High = 2;

    /**
     * Critical Priority
     *
     * Indicates that the issue is critical and requires immediate attention. This is the highest level of priority.
     *
     * - Color: Danger (red)
     * - Icon: Exclamation Circle
     */
    case Critical = 3;

    /**
     * Get the label associated with the priority level.
     *
     * This method returns a translated label that describes the priority level, providing a user-friendly
     * way to convey the urgency of an issue.
     *
     * Returns the label corresponding to the priority level.
     *
     * {@inheritDoc}
     */
    public function getColor(): string
    {
        return match ($this) {
            self::Low => 'info',
            self::Medium => 'success',
            self::High => 'warning',
            self::Critical => 'danger',
        };
    }

    /**
     * Get the label associated with the priority level.
     *
     * This method returns a translated label that describes the priority level, providing a user-friendly
     * way to convey the urgency of an issue.
     *
     * @return string  The label corresponding to the priority level.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::Low => trans('Geen prioriteit'),
            self::Medium => trans('Lage prioriteit'),
            self::High => trans('Hoge prioriteit'),
            self::Critical => trans('Kritieke prioriteit'),
        };
    }

    /**
     * Get the icon associated with the priority level.
     *
     * This method returns an icon name that visually represents the priority level, helping users
     * quickly identify the severity of an issue in the UI.
     *
     * @return string  The icon representing the priority level.
     */
    public function getIcon(): string
    {
        return match ($this) {
            self::Low => 'heroicon-o-ellipsis-horizontal-circle',
            self::Medium => 'heroicon-o-arrow-down-circle',
            self::High => 'heroicon-o-arrow-up-circle',
            self::Critical => 'heroicon-o-exclamation-circle',
        };
    }
}
