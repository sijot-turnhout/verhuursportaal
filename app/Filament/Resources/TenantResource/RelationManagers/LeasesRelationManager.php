<?php

declare(strict_types=1);

namespace App\Filament\Resources\TenantResource\RelationManagers;

use App\Enums\LeaseStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Class LeasesRelationManager
 *
 * Manages the relationship between tenants and their leases within the Filament admin panel.
 * This relation manager provides functionalities to display, create, edit, and delete lease records
 * associated with a tenant. It also allows customization of forms and tables used for managing leases.
 *
 * @todo Write documentation for this resource.
 *
 * @package App\Filament\Resources\TenantResource\RelationManagers
 */
final class LeasesRelationManager extends RelationManager
{
    /**
     * The name of the relationship being managed by this relation manager.
     * This relationship should be defined in the Tenant model.
     *
     * @var string
     */
    protected static string $relationship = 'leases';

    /**
     * The singular label for the model managed by this relation manager.
     * This label is used in various places, such as forms and tables.
     *
     * @var string|null
     */
    protected static ?string $modelLabel = 'Verhuring';

    /**
     * The plural label for the model managed by this relation manager.
     * This label is used when referring to multiple records of this model.
     *
     * @var string|null
     */
    protected static ?string $pluralModelLabel = 'Verhuringen';

    /**
     * The title for the relation manager page.
     * This title is displayed in the page header.
     *
     * @var string|null
     */
    protected static ?string $title = 'Verhuringen';

    /**
     * Configures the form used to create or edit lease records.
     *
     * @param  Form  $form  The form builder instance to configure.
     * @return Form         The configured form instance.
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('supervisor_id')
                    ->label('Aanspreekpunt / Verantwoordelijke')
                    ->relationship('supervisor', 'name')
                    ->columnSpan(4),

                Forms\Components\TextInput::make('group')
                    ->label('Groep')
                    ->required()
                    ->columnSpan(4),

                Forms\Components\TextInput::make('persons')
                    ->numeric()
                    ->label('Aantal personen')
                    ->required()
                    ->columnSpan(4),

                Forms\Components\DatePicker::make('arrival_date')
                    ->required()
                    ->label('aankomst datum')
                    ->columnSpan(6)
                    ->format('d-m-Y')
                    ->native(false)
                    ->prefixIcon('heroicon-m-calendar-days'),

                Forms\Components\DatePicker::make('departure_date')
                    ->label('vertrek')
                    ->required()
                    ->date()
                    ->columnSpan(6)
                    ->format('d-m-Y')
                    ->afterOrEqual('arrival_date')
                    ->native(false)
                    ->prefixIcon('heroicon-m-calendar-days'),

                Forms\Components\Select::make('spaces')
                    ->label('Lokalen')
                    ->multiple()
                    ->options(config('sijot-verhuur.lokalen', []))
                    ->columnSpan(12),

                Forms\Components\ToggleButtons::make('status')
                    ->inline()
                    ->options(LeaseStatus::class)
                    ->required()
                    ->columnSpan(12),
            ])->columns(12);
    }

    /**
     * Configures the table used to display lease records.
     *
     * @param  Table  $table  The table builder instance to configure.
     * @return Table          The configured table instance.
     */
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('period')->label('Periode')->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('status')->badge()->sortable()->searchable(),
                Tables\Columns\TextColumn::make('supervisor.name')->label('Verantwoordelijke')->placeholder('- geen toewijzing')->sortable(),
                Tables\Columns\TextColumn::make('persons')->label('Aantal personen')->sortable()->badge()->icon('heroicon-o-user'),
                Tables\Columns\TextColumn::make('group')->label('Organisatie')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Aanvragingsdatum')->date()->sortable(),
            ])
            ->filters([

            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
