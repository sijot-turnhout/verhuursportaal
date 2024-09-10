<?php

declare(strict_types=1);

namespace App\Filament\Resources\IssueResource\Actions;

use App\Filament\Resources\LocalResource\Enums\Status;
use App\Models\Issue;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Gate;

/**
 * Class ReopenIssueAction
 *
 * Represents an action for reopening an issue within the Filament admin panel. This action allows an admin to change
 * the status of an issue from closed to open, making it active again. It is only visible if the current user has permission
 * to reopen the issue.
 *
 * @see \App\Policies\IssuePolicy::reopen() - The policy method that defines the authorization logic for reopening an issue.
 */
final class ReopenIssueAction extends Action
{
    /**
     * Creates a new instance of ReopenIssueAction with a default or provided name.
     *
     * @param  string|null $name  The name of the action. Defaults to the translation of 'Werkpunt heropenen' if not provided.
     * @return static             The newly created instance of ReopenIssueAction.
     */
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? trans('Werkpunt heropenen'))
            ->icon('heroicon-o-arrow-path')
            ->color('warning')
            ->visible(fn(Issue $issue): bool => Gate::allows('reopen', $issue))
            ->action(function (Issue $issue): void {
                $issue->update(['status' => Status::Open, 'closed_at' => null]);
                Notification::make()->title('Het werkpunt is met success heropend.')->success()->send();
            });
    }
}
