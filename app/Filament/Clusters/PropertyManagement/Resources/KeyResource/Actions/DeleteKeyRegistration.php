<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\KeyResource\Actions;

use Filament\Tables\Actions\DeleteAction;

/**
 * A special button in the Filament admin panel that allows deleting registered keys in the admin panel.
 * Think of it like a "Delete" button specially for the key management registrations.
 * This helps community newcomers quickly understand the purpose of this action.
 *
 * @package App\Filament\Clusters\PropertyManagement\Resources\KeyResource\Actions;
 */
final class DeleteKeyRegistration extends DeleteAction
{
    /**
     * Sets up the "Delete" button with the right behaviour.
     * This method configures how the button functions.
     * It's like a factory that builds the button for you.
     *
     * @param  string|null $name The internal name of this action. You usually don't need to worry about this.
     * @return static            The configured "Delete" button, ready to be used in your admin panel.
     */
    public static function make(?string $name = null): static
    {
        return parent::make($name)
            ->slideOver();
    }
}
