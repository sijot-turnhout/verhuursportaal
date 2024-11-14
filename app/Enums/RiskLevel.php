<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

/**
 * Enum class representing different levels of risk, each with a label and an associated color for visual indication.
 *
 * This class implements the HasColor and HasLabel interfaces, allowing each risk level to provide a label (descriptive name)
 * and color coding for UI elements, enhancing readability and user experience.
 *
 * @package App\Enums
 */
enum RiskLevel: string implements HasColor, HasLabel
{
    /**
     * Represents a 'unknown' risk level, denoting that the risk level is unknown.
     * This can occur when the risk level zccessment is not performed. Becasue of the queue or database seeding.
     */
    case Unknown = 'onbekend';

    /**
     * Represents a 'Very Low' risk level, denoting minimal risk.
     * Commonly used to signal safe or minimal concerns.
     */
    case VeryLow = 'zeer laag';

    /**
     * Represents a 'Low' risk level, denoting below-average risk.
     * Typically used for areas with some concerns but low impact.
     */
    case Low = 'laag';

    /**
     * Represents a 'Medium' risk level, indicating moderate risk.
     * This level implies a balance, where risk is present but manageable.
     */
    case Medium = 'gemiddeld';

    /**
     * Represents a 'High' risk level, indicating above-average risk.
     * Usually signals areas requiring caution or preventative measures.
     */
    case High = 'hoog';

    /**
     * Represents a 'Very High' risk level, indicating significant or severe risk.
     * Used to denote situations requiring immediate attention or action.
     */
    case VeryHigh = 'zeer hoog';

    /**
     * Retrieves the label for the specific risk level.
     *
     * Returns the label that represents the risk level, used as a user-friendly name in UI components.
     * For instance, the 'zeer laag' value for VeryLow can be directly displayed to users for easier interpretation.
     *
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->value;
    }

    /**
     * Provides the color aoàssciated with each risk level for visual representation
     *
     * Color: coding
     * - Green is associated with 'Very low' and 'Low' levels, indicating minimal to low risk.
     * - Yellow represents a 'Medium' risk level, marking an intermediate concern level.
     * - Red corresponds to 'High' and 'Very High' levels, alerting users to elevated risk areas.
     *
     * Returns the color value that matches the risk level, used to enhance UI elements with intuitive
     * color-coded risk indicators. Color is a key tool for helping users quickly identify and assess risk at a glance.
     *
     * {@inheritDoc}
     */
    public function getColor(): string|array|null
    {
<<<<<<< HEAD
        return match($this) {
            self::Unknown => Color::Slate,
=======
        return match ($this) {
>>>>>>> 93a45db1641bf811ff8c32bc0c2fe25f62ebcca6
            self::VeryLow, self::Low => Color::Green,
            self::Medium => Color::Yellow,
            self::High, self::VeryHigh => Color::Red,
        };
    }
}
