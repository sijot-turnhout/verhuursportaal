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
 * @todo Write documentation for this resource.
 * @todo Write implementation for attach 'lopkalen' to the lease in the backend.
 */
final class LeasesRelationManager extends RelationManager
{
    protected static string $relationship = 'leases';

    protected static ?string $modelLabel = 'Verhuring';

    protected static ?string $pluralModelLabel = 'Verhuringen';

    protected static ?string $title = 'Verhuringen';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('supervisor_id')->label('Aanspreekpunt / Verantwoordelijke')->relationship('supervisor', 'name')->columnSpan(4),
                Forms\Components\TextInput::make('group')->label('Groep')->required()->columnSpan(4),
                Forms\Components\TextInput::make('persons')->numeric()->label('Aantal personen')->required()->columnSpan(4),
                Forms\Components\DatePicker::make('arrival_date')->required()->label('aankomst datum')->columnSpan(6)->format('d-m-Y')->native(false)->prefixIcon('heroicon-m-calendar-days'),
                Forms\Components\DatePicker::make('departure_date')->label('vertrek')->required()->date()->columnSpan(6)->format('d-m-Y')->afterOrEqual('arrival_date')->native(false)->prefixIcon('heroicon-m-calendar-days'),
                Forms\Components\Select::make('spaces')->label('Lokalen')->multiple()->options(config('sijot-verhuur.lokalen', []))->columnSpan(12),
                Forms\Components\ToggleButtons::make('status')->inline()->options(LeaseStatus::class)->required()->columnSpan(12),
            ])->columns(12);
    }

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
