<?php

declare(strict_types=1);

namespace App\Filament\Resources\IssueResource\Actions;

use App\Models\Issue;
use App\Models\User;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;

/**
 * Class ConnectUserAction
 *
 * Represents an action for connecting a user to an issue within the Filament admin panel. This action allows an admin to assign
 * a user to an issue, making them responsible for the issue. It is only visible if the issue does not already have a user assigned.
 *
 * @package App\Filament\Resources\IssueResource\Actions
 */
final class ConnectUserAction extends Action
{
    /**
     * Creates a new instance of ConnectUserAction with default or provided name.
     *
     * @param  string|null $name  The name of the action. Defaults to 'Koppelen' if not provided.
     * @return static             The newly created instance of ConnectUserAction.
     */
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'Koppelen')
            ->modalHeading(trans('Koppelen aan gebruiker'))
            ->modalIcon('heroicon-o-link')
            ->modalIconColor('primary')
            ->modalDescription(trans('Door een gebruiker te koppelen aan het werkpunt, zal deze toegewezen worden als opvolger en verantwoordelijke van het werkpuntje.'))
            ->modalSubmitActionLabel(trans('Koppelen'))
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
                    ->required(),
            ]);
    }
}
