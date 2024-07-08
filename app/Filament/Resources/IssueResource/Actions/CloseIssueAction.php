<?php

declare(strict_types=1);

namespace App\Filament\Resources\IssueResource\Actions;

use App\Models\Issue;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Gate;

/**
 * @see \App\Policies\IssuePolicy::close()
 */
final class CloseIssueAction extends Action
{
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
