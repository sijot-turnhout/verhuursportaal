<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\RelationManagers;

use App\Filament\Resources\UtilityResource\Actions;
use App\Models\Utility;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

/**
 * Class UtilitiesManager
 *
 * This class contains the core functionality for monitoring utility usage in the form of simple metrics.
 * We monitor now only the Gas, Water, Electricity usage. If the end user decide to perform some sort of billing. We already calculate the cost price automatically in the backend.
 * And put the total cost at the bottom of the panel.
 *
 * For modifying the unit prices consult the billing section in the configuration array for the platform,
 * that is located at /config/reiziger.php
 *
 * @template TModel of \App\Models\Lease
 *
 * @method \App\Models\Lease getOwnerRecord()
 *
 * @todo We need to implement a icon on the tab of the relation mananger.
 *
 * @see \App\Policies\UtilityPolicy
 */
final class UtilitiesRelationManager extends RelationManager
{
    /**
     * Variable for registering a custom name to the panel in the relation manager.
     */
    protected static ?string $title = 'Verbruik';

    /**
     * Variable for defining the name of the relation that will be used in this relation manager.
     */
    protected static string $relationship = 'utilityStatistics';

    /**
     * Method for determining whether the utility metric panel is visible of not.
     *
     * @param  TModel  $ownerRecord  The owner record of the relation entity. In this case it is the lease entity.
     *
     * @see \App\Policies\LeasePolicy::finalizeMetrics()
     */
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return Gate::allows('finalize-metrics', $ownerRecord);
    }

    /**
     * Method for building up the modal that allows us to edit/view the form for the energy metrics.
     *
     * @param  Form  $form  The form builder class that will be used to build the edit form for the utility metrics.
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('Verbruiks type')->disabled()->columnSpan(4),
                Forms\Components\TextInput::make('start_value')->label('Meterstand (start)')->numeric()->columnSpan(4),
                Forms\Components\TextInput::make('end_value')->label('Meterstand (eind)')->required()->numeric()->columnSpan(4),
            ])->columns(12);
    }

    /**
     * The method that allows us to define the view table for the relation manager.
     *
     * @todo Build up an action to revoke the finalization of the energy metrics registration for when a user has made an error and needs to correct it. It only can be performed by administrators and webmasters.
     */
    public function table(Table $table): Table
    {
        return $table
            ->modelLabel('Verbruik')
            ->pluralModelLabel('Verbruik')
            ->emptyStateIcon('heroicon-o-document-chart-bar')
            ->emptyStateDescription('Momenteel zijn er nog geen verbruiks statistieken gevonden die gekoppeld zijn aan de verhuring')
            ->emptyStateActions([Actions\InitializeMetricsAction::make()])
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Type verbruik'),
                Tables\Columns\TextColumn::make('start_value')
                    ->label('Meterstand (start)')
                    ->suffix(fn(Utility $utility): string => ' ' . $utility->name->getSuffix())
                    ->sortable(),
                Tables\Columns\TextColumn::make('usage_total')
                    ->label('Verbruik')
                    ->sortable()
                    ->color('warning')
                    ->weight(FontWeight::Bold)
                    ->prefix('+')
                    ->suffix(fn(Utility $utility): string => ' ' . $utility->name->getSuffix()),
                Tables\Columns\TextColumn::make('end_value')
                    ->label('Meterstand (eind)')
                    ->sortable()
                    ->suffix(fn(Utility $utility): string => ' ' . $utility->name->getSuffix()),
                Tables\Columns\TextColumn::make('unit_price')->label('Eenheidsprijs')
                    ->money('EUR')
                    ->weight(FontWeight::ExtraBold),
                Tables\Columns\TextColumn::make('billing_amount')
                    ->label('Verbruiksprijs')
                    ->sortable()
                    ->money('EUR')
                    ->color('success')
                    ->summarize(Sum::make()->label('Totale kost')->money('EUR'))
                    ->weight(FontWeight::Bold),
            ])
            ->headerActions([
                Actions\FinalizeMetricsAction::make(),
                Actions\UnlockMetricsAction::make(),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->paginated(false);
    }
}
