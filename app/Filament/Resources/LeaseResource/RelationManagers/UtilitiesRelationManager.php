<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\RelationManagers;

use App\Features\UtilityMetrics;
use App\Filament\Resources\LeaseResource\Pages\ViewLease;
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
use Laravel\Pennant\Feature;

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
     *
     * @car string|null
     */
    protected static ?string $title = 'Verbruik';

    /**
     * Variable for defining the name of the relation that will be used in this relation manager.
     *
     * @var string
     */
    protected static string $relationship = 'utilityStatistics';

    /**
     * The icon name used for representing this relationship in the UI.
     * This string corresponds to an icon identifier, typically used to
     * visually represent the relationship within the application.
     *
     * Note: The icon name usually follows a naming convention or comes from an icon
     * library (e.g., "heroicon-o-queue-list")
     *
     * @var string|null
     */
    protected static ?string $icon = 'heroicon-o-queue-list';

    /**
     * Method for determining whether the utility metric panel is visible of not.
     *
     * @param  Model   $ownerRecord  The owner record of the relation entity. In this case it is the lease entity.
     * @param  string  $pageClass    The name and class FQN for the resource page where this relation manager is rendered.
     * @return bool
     */
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return Feature::active(UtilityMetrics::class)
            && Gate::allows('finalize-metrics', $ownerRecord)
            && new $pageClass() instanceof ViewLease;
    }

    /**
     * Determine whether the relation manager is readonly on the information view of the main resource.
     *
     * @return bool
     */
    public function isReadOnly(): bool
    {
        return false;
    }

    /**
     * Method for building up the modal that allows us to edit/view the form for the energy metrics.w
     *
     * @param  Form  $form  The form builder class that will be used to build the edit form for the utility metrics.
     * @return Form
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
     * @param  Table $table The table builder instance that will be used to render the information table.
     * @return Table
     */
    public function table(Table $table): Table
    {
        return $table
            ->heading(trans('Nutsverbruik registratie'))
            ->description('In dit tabblad dat gekoppeld is aan de verhuring kan u het nutsverbuik van de verbruik registreren. Dit kan handig zijn voor de facturatie of om een analytisch overzicht te verkrijgen')
            ->modelLabel('Verbruik')
            ->pluralModelLabel('Verbruik')
            ->emptyStateIcon('heroicon-o-document-chart-bar')
            ->emptyStateDescription('Momenteel zijn er nog geen verbruiks statistieken gevonden die gekoppeld zijn aan de verhuring')
            ->emptyStateActions([Actions\InitializeMetricsAction::make()])
            ->columns($this->getTableColumnsLayout())
            ->headerActions($this->getTableHeaderActionsLayout())
            ->actions([Tables\Actions\EditAction::make()])
            ->paginated(false);
    }

    /**
     * Method for defining the table layout for the Utilities relation manager.
     * The declaration of the layout is separated from the main method for clarification in the code.
     *
     * @return array<int, Tables\Columns\TextColumn>
     */
    private function getTableColumnsLayout(): array
    {
        return [
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
        ];
    }

    /**
     * Custom method to define the header actions that are implemented in the header of the utility overview table.
     *
     * @return array<int, Tables\Actions\Action>
     */
    private function getTableHeaderActionsLayout(): array
    {
        return [
            Actions\FinalizeMetricsAction::make(),
            Actions\UnlockMetricsAction::make(),
        ];
    }
}
