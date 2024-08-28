<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\IssueResource\Infolists;

use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;

/**
 * Defines the information list for displaying issue details.
 *
 * The `IssueInformationInfolist` class is responsible for constructing the layout and components
 * that will display detailed information about an issue in the Filament admin panel.
 * It organizes the data into fieldsets, showing key information such as the creator, the user
 * responsible for follow-up, and details about the issue itself.
 */
final class IssueInformationInfolist
{
    /**
     * Create and configure the infolist for an issue.
     *
     * This static method builds an `Infolist` schema, organizing the information into fieldsets
     * for a clear and structured presentation. It includes general information about the issue,
     * as well as specific details like the title, status, and description.
     *
     * @param  Infolist $infolist   The `Infolist` instance to be configured.
     * @return Infolist             The configured `Infolist` instance.
     */
    public static function make(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(12)
            ->schema([
                Fieldset::make(trans('Algemene informatie'))
                    ->columns(12)
                    ->schema([
                        TextEntry::make('creator.name')->label('Aangemaakt door')->columnSpan(4)->icon('heroicon-o-user-circle')->iconColor('primary'),
                        TextEntry::make('user.name')->label('Opgevolgd door')->columnSpan(4)->icon('heroicon-o-user-circle')->iconColor('primary'),
                        TextEntry::make('created_at')->label('Aangemaakt op')->columnSpan(4)->icon('heroicon-o-clock')->iconColor('primary'),
                    ]),
                Fieldset::make(trans('Werkpunt informatie'))
                    ->columns(12)
                    ->schema([
                        TextEntry::make('title')->label('Titel')->columnSpan(8),
                        TextEntry::make('status')->label('Status')->columnSpan(4)->badge(),
                        TextEntry::make('description')->label('Beschrijving')->columnSpan(12),
                    ])
            ]);
    }
}
