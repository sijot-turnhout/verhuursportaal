<?php

declare(strict_types=1);

namespace App\Filament\Resources\IssueResource\Actions;

use App\Models\Issue;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Gate;

/**
 * Class CloseIssueAction
 *
 * Represents an action for closing an issue within the Filament admin panel. This action allows a user with
 * the necessary permissions to mark an issue as closed. The action is visible only if the user has the
 * authorization to perform the close operation.
 *
 * @see \App\Policies\IssuePolicy::close() - The policy method that defines the authorization logic for closing an issue.
 */
final class CloseIssueAction extends Action
{
    /**
     * Creates a new instance of CloseIssueAction with a default or provided name.
     *
     * @param  string|null $name  The name of the action. Defaults to 'Werkpunt sluiten' if not provided.
     * @return static             The newly created instance of CloseIssueAction.
     */
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'Werkpunt sluiten')
            ->icon('heroicon-o-check')
            ->color('success')
            ->visible(fn(Issue $issue): bool => Gate::allows('close', $issue))
            ->action(function (Issue $issue): void {
                $issue->markAsClosed();
                Notification::make()->title('Het werkpunt is gesloten.')->success()->send();
            });
    }
}
