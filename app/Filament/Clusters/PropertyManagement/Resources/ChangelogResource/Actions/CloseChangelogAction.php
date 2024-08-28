<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource\Actions;

use App\Models\Changelog;
use Filament\Actions\Action;

/**
 * Represents the action to close a changelog.
 *
 * The `CloseChangelogAction` class defines an action within the Filament admin panel that allows
 * users to close a changelog. This action is only visible to users who have the appropriate
 * permissions. When triggered, it transitions the changelog to the 'closed' state.
 */
final class CloseChangelogAction extends Action
{
    /**
     * Create a new instance of the close changelog action.
     *
     * This static method initializes the action, setting its display name, color, icon,
     * visibility, and behavior. The action is only visible if the authenticated user has the
     * necessary permissions to close the given changelog.
     *
     * @param  string|null $name Optional. The name of the action. If not provided, a default localized name 'Werklijst afsluiten' is used.
     * @return static
     */
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? trans('Werklijst afsluiten'))
            ->color('success')
            ->icon('heroicon-o-check')
            ->visible(fn (Changelog $changelog): bool => auth()->user()->can('close-changelog', $changelog))
            ->action(fn (Changelog $changelog) => $changelog->state()->transitionToClosed());
    }
}
