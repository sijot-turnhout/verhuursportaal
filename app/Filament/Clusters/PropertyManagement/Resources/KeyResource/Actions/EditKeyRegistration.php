<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\KeyResource\Actions;

use Filament\Tables\Actions\EditAction;

/**
 * A special button in the Filament admin panel that allows editing details about a registered key.
 * Think of it like an "Edit" button specifically for key information.
 * This helps community newcomers quickly understand the purpose of this action.
 *
 * @package App\Filament\Clusters\PropertyManagement\Resources\KeyResource\Actions
 */
final class EditKeyRegistration extends EditAction
{
    /**
     * Sets up the "Edit" button with the right text, icon, and behaviour.
     * This method configures how the button look and what it does.
     * It's like a factory that builds the button for you.
     *
     * @param  string|null $name  The interval name of this action. You usually don't need to worry about this.
     * @return static             The configured "Edit" button, ready to be used in your admin panel.
     */
    public static function make(?string $name = null): static
    {
        return parent::make($name)
            ->modalHeading('Sleutelregistratie aanpassen')
            ->modalDescription('Weergave voor het wijzigen van een sleutelregistratie in de applicatie.')
            ->modalIcon('heroicon-o-pencil-square')
            ->slideOver();
    }
}
