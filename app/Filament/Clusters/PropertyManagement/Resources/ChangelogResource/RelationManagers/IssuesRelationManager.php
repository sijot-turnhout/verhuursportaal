<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource\RelationManagers;

use App\Filament\Clusters\PropertyManagement\Resources\IssueResource\Infolists\IssueInformationInfolist;
use App\Models\Issue;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
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
     * @todo GH #14 - Refactoring van de open/close acties voor de werkpunten in de applicatie.
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
                Tables\Columns\TextColumn::make('id')->label('#')->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('user.name')->searchable()->sortable()->icon('heroicon-o-user-circle')->iconColor('primary')->label('Opgevolgd door'),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('title')->label('titel')->searchable(),
                Tables\Columns\TextColumn::make('description')->label('beschrijving')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('aangemaakt op')->date(),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->modalHeading(trans('Werkpunt koppelen aan deze werklijst'))
                    ->modalIcon('heroicon-o-link')
                    ->modalIconColor('primary')
                    ->modalDescription(trans('Koppel deze werklijst aan gerelateerde werkpunten. Dit helpt bij het verbinden en organiseren van gerelateerde information binnen het systeem'))
                    ->preloadRecordSelect()
                    ->icon('heroicon-o-link')
                    ->slideOver()
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalIcon('heroicon-o-information-circle')
                    ->modalDescription(fn(Issue $issue): string => trans('Referentienummer #:number', ['number' => $issue->id]))
                    ->modalIconColor('primary')
                    ->slideOver()
                    ->modalCancelAction(false)
                    ->extraModalFooterActions([
                        Tables\Actions\Action::make('Werkpunt afsluiten')
                            ->visible(fn (Issue $issue): bool => auth()->user()->can('close', $issue))
                            ->action(fn (Issue $issue) => $issue->state()->transitionToClosed())
                            ->color('danger')
                            ->icon('heroicon-o-document-check'),

                        Tables\Actions\Action::make('Werkpunt heropenen')
                            ->visible(fn (Issue $issue): bool => auth()->user()->can('reopen', $issue))
                            ->action(fn (Issue $issue) => $issue->state()->transitionToOpen())
                            ->color('gray')
                            ->icon('heroicon-o-arrow-path'),
                    ]),

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
