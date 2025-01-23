<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\KeyResource\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

/**
 * Enum KeyTypes
 *
 * Hi there, and welcome to our project! 🌟
 *
 * This enum represents the different types of keys in our application.
 * Enums help us define a set of constants, making our code more readable and maintainable.
 *
 * Each key type in this enum has:
 * - A **label** that is displayed in the user interface.
 * - An **icon** that visually represents the key type.
 * - A **color** making the key type easily identifiable.
 *
 * Your contributions and suggestions are always welcome. We're glad to have you here!
 *
 * @package App\Filament\Clusters\PropertyManagement\Resources\KeyResource\Enums
 */
enum KeyTypes: string implements HasLabel, HasIcon, HasColor
{
    /**
     * Master key type - This is the primary and most important key.
     *
     * - **Label**: 'Moedersleutel'
     * - **Icon**: A key icon (heroicon-o-key)
     * - **Color**: 'danger' (to signify its importance)
     *
     * Used for accessing all secured areas. Please handle this type with extra care.
     */
    case Master = 'Moedersleutel';

    /**
     * Reproduction key type - This key is a duplicate or copy of the original.
     *
     * - **Label**: 'Bijmaak sleutel'
     * - **Icon**: A key icon (heroicon-o-key)
     * - **Color**: 'info' (to indicate it’s a backup)
     *
     * Typically used as a backup or for limited access areas.
     */
    case Reproduction = 'Bijmaak sleutel';

    /**
     * Get the label for the key type.
     *
     * This friendly label is displayed in the user interface to help users understand the type of key.
     *
     * @return string A user-friendly label for the key type.
     */
    public function getLabel(): string
    {
        return $this->value;
    }

    /**
     * Get the icon for the key type.
     *
     * Icons provide a visual cue to help users quickly recognize the key type.
     * This method returns the name of the icon used.
     *
     * @return string The icon name for the key type.
     */
    public function getIcon(): string
    {
        return 'heroicon-o-key';
    }

    /**
     * Get the color for the key type.
     *
     * Colors add vibrancy to our UI and help distinguish between different key types.
     * This method returns the color code associated with each key type:
     *
     * - Master keys are marked with 'danger' to reflect their importance.
     * - Reproduction keys are marked with 'info' as they are duplicates.
     *
     * @return string The color code for the key type.
     */
    public function getColor(): string
    {
        return match ($this) {
            self::Master => 'danger',
            self::Reproduction => 'info',
        };
    }
}
