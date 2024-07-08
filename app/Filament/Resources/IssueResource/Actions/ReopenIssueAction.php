<?php

declare(strict_types=1);

namespace App\Filament\Resources\IssueResource\Actions;

use App\Filament\Resources\LocalResource\Enums\Status;
use App\Models\Issue;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Gate;

/**
 * @see \App\Policies\IssuePolicy::reopen()
 */
final class ReopenIssueAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? trans('Werkpunt heropenen'))
            ->icon('heroicon-o-arrow-path')
            ->color('warning')
            ->visible(fn(Issue $issue): bool => Gate::allows('reopen', $issue))
            ->action(function (Issue $issue): void {
                $issue->update(['status' => Status::Open]);
                Notification::make()->title('Het werkpunt is met success heropend.')->success()->send();
            });
    }
}
