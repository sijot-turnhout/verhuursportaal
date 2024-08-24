<?php

namespace App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource\RelationManagers;

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
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->icon('heroicon-o-link'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
