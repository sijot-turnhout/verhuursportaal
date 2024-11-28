<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\KeyResource\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

/**
 * This enum defines whether a key in the application is a master key (Loper) or a normal key (Normale sleutel).
 *
 * - Label:        Human-readable name that appears in the user interface.
 * - Description:  Additional context and details about the key type.
 * - Icon:         Visual symbol to quickly identify the key type.
 * - Color:        Color code to visually distinguish the different key types.
 *
 * @package App\Filament\Clusters\PropertyManagement\Resources\KeyResource\Enums
 */
enum MasterKey: int implements HasLabel, HasDescription, HasColor, HasIcon
{
    /**
     * Represents a master key (Loper).
     *
     * The label 'Loper' indicates that this is a master key.
     * Master keys do not require access registration since they are assumed to provide access to all areas.
     * It uses the heroicon-o-exclamation-circle icon and is assigned the 'danger' color.
     */
    case True = 1;

    /**
     * Represents a normal key (Normale sleutel).
     *
     * The label 'Normale sleutel' indicates that this is a normal key.
     * Normal keys are assumed to provide access to specific areas; thus, access registration is required.
     * It uses the heroicon-o-information-circle icon and is assigned the 'info' color.
     */
    case False = 0;

    /**
     * Get the user-friendly name for this key type.
     *
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::True => __('Loper'),
            self::False => __('Normale sleutel'),
        };
    }

    /**
     * Provides a more detailed explanation of this key type.
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return match ($this) {
            self::True => __('Bij lopers is het niet nodig om de toegangen te registreren van de sleutel omdat word geacht dat deze sleutel toegang heeft tot alle lokalen.'),
            self::False => __('Bij een normale sleutel word geacht dat de sleutel toegang heeft tot een specifiek lokaal. Daarom is toegang registratie vereist'),
        };
    }

    /**
     * Gets the color used to represent this key type visually.
     *
     * @return string|array|null
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::True => 'danger',
            self::False => 'info',
        };
    }

    /**
     * Gets the icon used to represent this key type visually.
     *
     * @return string|null
     */
    public function getIcon(): ?string
    {
        return match ($this) {
            self::True => 'heroicon-o-exclamation-circle',
            self::False => 'heroicon-o-information-circle',
        };
    }
}
