<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\Traits;

use App\Filament\Resources\LeaseResource\Pages\ListLeases;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;

trait UsesArchivingSystemActions
{
    protected static function forceDeleteBulkAction(): ForceDeleteBulkAction
    {
        return ForceDeleteBulkAction::make()
            ->label('selectie verwijderen')
            ->translateLabel()
            ->visible(fn (ListLeases $livewire): bool => $livewire->activeTab === 'archive');
    }

    protected static function archiveBulkAction(): DeleteBulkAction
    {
        return DeleteBulkAction::make()
            ->visible(fn (ListLeases $livewire): bool => $livewire->activeTab !== 'archive')
            ->label('Archiveren')
            ->color('gray')
            ->icon('heroicon-o-archive-box');
    }

    protected static function archiveRestoreBulkAction(): RestoreBulkAction
    {
        return RestoreBulkAction::make()
            ->visible(fn (ListLeases $livewire): bool => $livewire->activeTab === 'archive');
    }

    protected static function bulkArchivingActionGroup(): BulkActionGroup
    {
        return BulkActionGroup::make([
            self::forceDeleteBulkAction(),
            self::archiveRestoreBulkAction(),
        ])
        ->label(trans('Archief acties'))
        ->icon('heroicon-o-cog-8-tooth');
    }

    protected static function restoreArchiveEntityAction(): RestoreAction
    {
        return RestoreAction::make()
            ->label('Herstellen')
            ->translateLabel();
    }

    protected static function archiveEntityAction(): DeleteAction
    {
        return DeleteAction::make();
    }

    protected static function forceDeleteEntityAction(): ForceDeleteAction
    {
        return ForceDeleteAction::make();
    }
}
