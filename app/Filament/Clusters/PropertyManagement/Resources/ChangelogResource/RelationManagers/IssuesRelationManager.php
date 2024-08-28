<?php

namespace App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource\RelationManagers;

use App\Filament\Clusters\PropertyManagement\Resources\IssueResource\Infolists\IssueInformationInfolist;
use App\Models\Issue;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Manages the relationship between a changeog and its associated issues.
 *
 * The 'IssueRelationManager' class is responsible for handling the presentation and interaction
 * of the 'issues' relationship within a changelog in the filament admin panel.
 * It allows users to view, attach, edit, detach, and delete issues related to a specific changelog.
 */
final class IssuesRelationManager extends RelationManager
{
    /**
     * The title displayed for this relation manager in the UI.
     *
     * @var string|null
     */
    protected static ?string $title = 'Gekoppelde werkpunten';

    /**
     * The name of the relationship managed by this relation manager.
     * This specifies the Eloquent relationship method name in the `Changelog` model.
     *
     * @var string
     */
    protected static string $relationship = 'issues';

    /**
     * Method to define the infolist view for the information view of the issue ticket;
     *
     * @param  Infolist  $infolist  The infolist builder instance to build up the infolist
     * @return Infolist
     */
    public function infolist(Infolist $infolist): Infolist
    {
        return IssueInformationInfolist::make($infolist);
    }

    /**
     * Configure the table that displays the issues related to the changelog.
     *
     * This method sets up the table to display related issues, including columns, actions,
     * and bulk actions. It also defines an empty state message and icon when no issues are linked.
     *
     * @param  Table $table     The table instance to be configured.
     * @return Table            The configured table instance.
     */
    public function table(Table $table): Table
    {
        return $table
            ->emptyStateIcon('heroicon-o-wrench-screwdriver')
            ->emptyStateDescription('Momenteel zijn er nog geen werkpunt gekoppeld aan deze werklijst.')
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->modalHeading(trans('Werkpunt koppelen aan deze werklijst'))
                    ->modalIcon('heroicon-o-link')
                    ->modalIconColor('primary')
                    ->modalDescription(trans('Koppel deze werklijst aan gerelateerde werkpunten. Dit helpt bij het verbinden en organiseren van gerelateerde information binnen het systeem'))
                    ->slideOver()
                    ->preloadRecordSelect()
                    ->icon('heroicon-o-link'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalIcon('heroicon-o-information-circle')
                    ->modalDescription(fn (Issue $issue): string => trans('Referentienummer #:number', ['number' => $issue->id]))
                    ->modalIconColor('primary')
                    ->slideOver(),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DetachAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
