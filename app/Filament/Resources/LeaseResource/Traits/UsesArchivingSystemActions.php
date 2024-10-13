<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\Traits;

use App\Enums\LeaseStatus;
use App\Filament\Resources\LeaseResource\Pages\ListLeases;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;

trait UsesArchivingSystemActions
{
    protected static function forceDeleteBulkAction(): ForceDeleteBulkAction
    {
        return ForceDeleteBulkAction::make()
            ->label('Verwijderen')
            ->translateLabel()
            ->visible(fn (ListLeases $livewire): bool => $livewire->activeTab === LeaseStatus::Archived->value);
    }

    protected static function archiveBulkAction(): DeleteBulkAction
    {
        return DeleteBulkAction::make()
            ->visible(fn (ListLeases $livewire): bool => $livewire->activeTab !== LeaseStatus::Archived->value)
            ->label('Archiveren')
            ->color('gray')
            ->icon('heroicon-o-archive-box');
    }
}
