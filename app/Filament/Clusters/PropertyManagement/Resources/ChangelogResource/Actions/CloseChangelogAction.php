<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource\Actions;

use App\Models\Changelog;
use Filament\Actions\Action;

final class CloseChangelogAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? trans('Werklijst afsluiten'))
            ->color('success')
            ->icon('heroicon-o-check')
            ->visible(fn (Changelog $changelog): bool => auth()->user()->can('close-changelog', $changelog))
            ->action(fn (Changelog $changelog) => $changelog->state()->transitionToClosed());
    }
}
