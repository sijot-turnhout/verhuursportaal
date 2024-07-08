<?php

declare(strict_types=1);

namespace App\Filament\Resources\IssueResource\Actions;

use App\Models\Issue;
use App\Models\User;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;

final class ConnectUserAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'Koppelen')
            ->icon('heroicon-o-link')
            ->visible(fn(Issue $issue): bool => $issue->user()->doesntExist())
            ->action(function (Issue $issue, array $data): void {
                $issue->update(['user_id' => $data['user_id']]);
                Notification::make()->title('De gebruikers is met succes gekoppeld aan het werkpunt')->success()->send();
            })
            ->form([
                Forms\Components\Select::make('user_id')
                    ->label('Opvolger van het werkpunt')
                    ->options(User::query()->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->helperText(trans('Deze gebruiker zal toegewezen worden als opvolger en verantwoordelijke van het werkpuntje')),
            ]);
    }
}
