<?php

declare(strict_types=1);

namespace App\Filament\Resources\LocalResource\RelationManagers;

use App\Filament\Clusters\PropertyManagement\Resources\IssueResource\Enums\Priority;
use App\Filament\Clusters\PropertyManagement\Resources\IssueResource\Infolists\IssueInformationInfolist;
use App\Filament\Resources\IssueResource;
use App\Filament\Resources\LocalResource\Enums\Status;
use App\Filament\Resources\LocalResource\Pages\EditLocal;
use App\Models\Issue;
use App\Models\User;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * Class IssuesRelationManager
 *
 * Manages the relationship between the local resource and issues. This relation manager handles
 * the creation, editing, and overview of issues associated with a specific local resource.
 * It provides methods to configure forms, tables, and infolists for managing issues.
 *
 * @todo Implement unit tests for the relation manager.
 * @todo See if we can implement a cron job console command to register a issue ticket as inactive after X months.
 *
 * @see \App\Policies\IssuePolicy::class
 *
 * @package App\Filament\Resources\LocalResource\RelationManagers
 */
final class IssuesRelationManager extends RelationManager
{
    /**
     * The data relation that will be used by the relation manager.
     *
     * @return ?string
     */
    protected static string $relationship = 'issues';

    /**
     * The name declaration of the relation manager.
     *
     * @return ?string
     */
    protected static ?string $title = 'Werkpunten';

    /**
     * Method to build the create/edit form that is attached to the relation manager.
     *
     * @param  Form  $form  The form builder that will be used to create the form in the relation manager.
     * @return Form
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->label('Titel')->required()->maxLength(255)->columnSpan(12),
                Forms\Components\Select::make('user_id')->label('Opgevolgd door')->options(User::query()->pluck('name', 'id'))->searchable()->columnSpan(6),
                Forms\Components\Select::make('priority')->label('Prioriteit')->options(Priority::class)->columnSpan(6)->default(Priority::Low)->selectablePlaceholder(false),
                Forms\Components\Textarea::make('description')->label('Beschrijving')->autosize()->rows(4)->columnSpan(12),
            ])->columns(12);
    }

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

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $pageClass === EditLocal::class;
    }

    /**
     * Method to display the issues overview table in the relation manager.
     *
     * @param  Table  $table  The table builder instance that will be used to display the issue overview table.
     * @return Table
     *
     * @todo GH #14 - Refactoring van de open/close acties voor de werkpunten in de applicatie.
     *
     * @throws Exception
     */
    public function table(Table $table): Table
    {
        return $table
            ->modelLabel('Werkpunt')
            ->pluralModelLabel('Werkpunten')
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#')->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Opgevolgd door')
                    ->icon('heroicon-o-user-circle')
                    ->placeholder('(geen opvolger)')
                    ->iconColor('primary')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')->label('Status')->sortable()->badge(),
                Tables\Columns\TextColumn::make('title')->label('Titel')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Aangemaakt op')->date()->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(Status::class)
                    ->multiple()
                    ->searchable(false)
                    ->default([Status::Open->value, Status::Closed->value]),
                Tables\Filters\SelectFilter::make('user')
                    ->label('Toegewezen aan')
                    ->relationship('user', 'name')
                    ->searchable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->icon('heroicon-o-plus')
                    ->slideOver()
                    ->after(function (Issue $issue): void {
                        $issue->creator()->associate(auth()->user())->save();
                    }),
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
                            ->visible(fn(Issue $issue): bool => auth()->user()->can('close', $issue))
                            ->action(fn(Issue $issue) => $issue->state()->transitionToClosed())
                            ->color('danger')
                            ->icon('heroicon-o-document-check'),

                        Tables\Actions\Action::make('Werkpunt heropenen')
                            ->visible(fn(Issue $issue): bool => auth()->user()->can('reopen', $issue))
                            ->action(fn(Issue $issue) => $issue->state()->transitionToOpen())
                            ->color('gray')
                            ->icon('heroicon-o-arrow-path'),
                    ]),

                Tables\Actions\ActionGroup::make([
                    IssueResource\Actions\ConnectUserAction::make(),
                    Tables\Actions\EditAction::make()->slideOver(),
                    IssueResource\Actions\CloseIssueAction::make(),
                    IssueResource\Actions\ReopenIssueAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ]);
    }
}
