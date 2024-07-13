<?php

declare(strict_types=1);

namespace App\Filament\Resources\LocalResource\RelationManagers;

use App\Filament\Resources\IssueResource;
use App\Filament\Resources\LocalResource\Enums\Status;
use App\Models\Issue;
use App\Models\User;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * @todo Implement unit tests for the relation manager.
 * @todo See if we can implement a cron job console command to register a issue ticket as inactive after X months.
 *
 * @see \App\Policies\IssuePolicy::class
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
                Forms\Components\TextInput::make('title')->label('Titel')->required()->maxLength(255)->columnSpan(8),
                Forms\Components\Select::make('user_id')->label('Opgevolgd door')->options(User::query()->pluck('name', 'id'))->searchable()->columnSpan(4),
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
        return $infolist
            ->columns(12)
            ->schema([
                TextEntry::make('creator.name')->label('Aangemaakt door')->columnSpan(4)->icon('heroicon-o-user-circle')->iconColor('primary'),
                TextEntry::make('user.name')->label('Opgevolgd door')->columnSpan(4)->icon('heroicon-o-user-circle')->iconColor('primary'),
                TextEntry::make('created_at')->label('Aangemaakt op')->columnSpan(4)->icon('heroicon-o-clock')->iconColor('primary'),
                TextEntry::make('title')->label('Titel')->columnSpan(8),
                TextEntry::make('status')->label('Status')->columnSpan(4)->badge(),
                TextEntry::make('description')->label('Beschrijving')->columnSpan(12),
            ]);
    }

    /**
     * Method to display the issues overview table in the relation manager.
     *
     * @param  Table  $table  The table builder instance that will be used to display the issue overview table.
     * @return Table
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
                Tables\Columns\TextColumn::make('description')->label('Beschrijving')->placeholder('(geen beschrijving opgegeven)')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Aangemaakt op')->date()->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(Status::class),
                Tables\Filters\SelectFilter::make('user')
                    ->label('Toegewezen aan')
                    ->relationship('user', 'name')
                    ->searchable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->icon('heroicon-o-plus')
                    ->after(function (Issue $issue): void {
                        $issue->creator()->associate(auth()->user())->save();
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\ActionGroup::make([
                    IssueResource\Actions\ConnectUserAction::make(),
                    Tables\Actions\EditAction::make(),
                    IssueResource\Actions\CloseIssueAction::make(),
                    IssueResource\Actions\ReopenIssueAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ]);
    }
}
