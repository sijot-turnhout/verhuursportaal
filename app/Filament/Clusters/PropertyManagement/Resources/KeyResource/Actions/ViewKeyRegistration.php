<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\KeyResource\Actions;

use Filament\Tables\Actions\ViewAction;

/**
 * A special button in the Filament admin panel that show details about a registered key.
 * Think of it like a 'View details' button specifically for key information.
 * This helps community newcomers quickly understand the purpose of this action.
 *
 * @package App\Filament\Clusters\PropertyManagement\Resources\KeyResource\Actions
 */
final class ViewKeyRegistration extends ViewAction
{
    /**
     * Sets up the "View Details" button with the right text, icon, and behaviour.
     * This method configures how the button looks and what it does.
     * It's like a factory that builds the action for you.
     *
     * @param  string|null $name The internal name of the action. You usually don't need to worry about this.
     * @return static            The configured 'View Details' button, ready to be used in your admin panel.
     */
    public static function make(?string $name = null): static
    {
        return parent::make($name)
            ->modalHeading(trans('Sleutelregistratie bekijken'))
            ->modalIcon('heroicon-o-eye')
            ->modalIconColor('primary')
            ->modalDescription(trans('Alle geregistreerde gegevens omtrent de sleutel die is toegewezen aan de gebruiker in de applicatie'))
            ->slideOver();
    }
}
