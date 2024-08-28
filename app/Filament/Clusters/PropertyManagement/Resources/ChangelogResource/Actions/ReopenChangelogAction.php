<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource\Actions;

use App\Models\Changelog;
use Filament\Actions\Action;

/**
 * Custom action class for reopening a changelog.
 *
 * The `ReopenChangelogAction` class defines a custom action within the Filament admin panel
 * that allows authorized users to reopen a closed changelog. The action is configured with
 * specific properties such as color, icon, and visibility based on the user's permissions.
 */
final class ReopenChangelogAction extends Action
{
    /**
     * Create a new instance of the reopen changelog action.
     *
     * This method initializes the action with a label, color, and icon. It also defines
     * the visibility of the action based on whether the authenticated user has the
     * 'reopen-changelog' permission for the given changelog. If visible and executed,
     * the action transitions the changelog's state to 'open'.
     *
     * @param  string|null $name  The name of the action. Defaults to a translated label if not provided.
     * @return static             The configured instance of the action.
     */
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? trans('Werklijst heropenen'))
            ->color('warning')
            ->icon('heroicon-o-arrow-path')
            ->visible(fn(Changelog $changelog): bool => auth()->user()->can('reopen-changelog', $changelog))
            ->action(fn(Changelog $changelog) => $changelog->state()->transitionToOpen());
    }
}
