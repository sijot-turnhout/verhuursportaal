<?php

declare(strict_types=1);

namespace App\Filament\Resources\TenantResource\RelationManagers;

use App\Enums\IncidentCodes;
use App\Enums\IncidentImpact;
use App\Models\Incident;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions;
use Filament\Tables\Table;

/**
 * Class IncidentsRelationManager
 *
 * This Relation Manager brings together leases and any incidents reported during them.
 * Whether you're reviewing, adding, or managing incidents, this section has been designed
 * to make the process clear and efficient. We aim for this class to serve as a transparent
 * and community-driven tool for handling incident records related to leases.
 *
 * Contributions are always welcome! If you see ways to enhance this functionality,
 * feel free to contribute and help us improve!
 *
 * @see     https://sijot-turnhout.github.io/verhuur-portaal-documentatie/leases/incidents.html
 * @package App\Filament\Resources\LeaseResource\RelationManagers
 */
final class IncidentsRelationManager extends RelationManager
{
    /**
     * The 'incidents' relationship lets us access all incident records for a specific lease.
     * By keeping the relationship name consistent, we help ensure clarity in the codebase.
     *
     * @var string This defines the relationship between a Lease and its Incidents.
     */
    protected static string $relationship = 'incidents';

    /**
     * We use 'Incident' here as a clear identifier, and it's easy to translate if needed.
     *
     * @var string|null Label displayed for a single incident.
     */
    protected static ?string $modelLabel = 'Incident';

    /**
     * 'Incidenten' is used for the plural form, keeping the Dutch terminology for our users.
     * This also ensures the interface is familiar for those managing incidents in Dutch.
     *
     * @var string|null Label for multiple incidents in the interface.
     */
    protected static ?string $pluralModelLabvel = 'Incidenten';

    /**
     * The title of the relation manager.
     *
     * @var string|null Title displayed at the top of the incidents section.
     */
    protected static ?string $title = 'Incidenten';

    /**
     * Sets up the form for creating or editing an incident.
     *
     * This form allows users to categorize incidents and add extra details.
     * We’ve kept it minimal but open for further customization if needed.
     *
     * @param  Form $form  Form instance for incident records.
     * @return Form        Returns a flexible form that can easily be extended.
     */
    public function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema([
                Forms\Components\Select::make('incident_code')
                    ->label('Categorie')
                    ->options(IncidentCodes::class)
                    ->columnSpan(6)
                    ->required()
                    ->native(false),

                Forms\Components\Select::make('impact_score')
                    ->label('Impact')
                    ->options(IncidentImpact::class)
                    ->columnSpan(6)
                    ->required()
                    ->native(false)
                    ->hintAction(fn(Action $action): Action => $action->make('check-documentation')
                        ->label('help')
                        ->url('https://sijot-turnhout.github.io/verhuur-portaal-documentatie/leases/incidents.html#impact-score-van-incidenten')
                        ->openUrlInNewTab()
                        ->color('primary')
                        ->icon('heroicon-m-question-mark-circle')),

                Forms\Components\Textarea::make('description')
                    ->label(__('Extra informatie'))
                    ->columnSpan(12)
                    ->rows(5)
                    ->required(),
            ]);
    }

    /**
     * Sets whether the incidents form is read-only.
     *
     * Returning `false` here allows users to create or modify incidents.
     * If there's a need to limit this further, let us know and we can adjust!
     *
     * @return bool Returns false so users can edit incidents.
     */
    public function isReadOnly(): bool
    {
        return false;
    }

    /**
     * Defines the table layout for displaying incidents linked to a lease.
     *
     * This table structure includes all essential information, such as who reported the incident
     * and when. It's designed to be compact but easy to read. Suggestions to improve the layout
     * are always welcome!
     *
     * @param  Table $table  The table instance.
     * @return Table         Configured table for incident records.
     */
    public function table(Table $table): Table
    {
        return $table
            ->emptyStateIcon('heroicon-o-shield-exclamation')
            ->emptyStateHeading(trans('Geen incidenten gerapporteerd'))
            ->emptyStateDescription(trans('Het lijkt erop dat er momenteel voor deze verhuring geen incidenten zijn geregistreerd.'))
            ->columns(self::getTableColumnsLayout())
            ->actions(self::getIncidentTableActions())
            ->headerActions(self::getHeaderActions());
    }

    /**
     * Configures the Infolist layout for displaying detailed incident information.
     *
     * This method defines a structured view for incident details, including the reporter's name,
     * incident category, report date, and a description of the incident. Each entry is styled
     * to ensure clarity, making the incident details easy to read and access.
     *
     * @param  Infolist $infolist  The Infolist instance for configuring the schema and layout.
     * @return Infolist            Returns the configured Infolist with incident data fields.
     */
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(12)
            ->schema([
                TextEntry::make('user.name')
                    ->label(trans('Gerapporteerd door'))
                    ->columnSpan(6)
                    ->weight(FontWeight::Bold)
                    ->color('primary')
                    ->icon('heroicon-o-user-circle')
                    ->iconColor('primary'),

                TextEntry::make('created_at')
                    ->label(trans('Gerapporteerd op'))
                    ->icon('heroicon-o-clock')
                    ->iconColor('primary')
                    ->columnSpan(6)
                    ->date(),

                TextEntry::make('incident_code')
                    ->label(trans('Categorie'))
                    ->columnSpan(6)
                    ->badge(),

                TextEntry::make('impact_score')
                    ->label(trans('Impact'))
                    ->columnSpan(6)
                    ->badge(),

                TextEntry::make('description')
                    ->columnSpan(12)
                    ->label(trans('Incident beschrijving')),
            ]);
    }

    /**
     * Defines the actions available in the header of the incidents table.
     *
     * These actions allow users to create new incident records directly.
     * This section is flexible if you’d like to add other batch actions.
     *
     * @return array<int, Actions\Action> List of header actions for incidents.
     */
    private static function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalIcon('heroicon-o-shield-exclamation')
                ->modalHeading(trans('Incident registreren'))
                ->modalSubmitActionLabel(trans('registreren'))
                ->modalDescription(trans('De huurder heeft een incident voorval gehad tijdens de verhuring. Indien nodig kan je deze hier registreren. De registratie van de impact score zal worden gebruikt om risico analyses te vormen bij de aanvraag van nieuwe verhuringen'))
                ->icon('heroicon-o-plus')
                ->label('Incident registreren')
                ->translateLabel()
                ->color('warning')
                ->createAnother(false)
                ->after(function (Actions\CreateAction $action, Incident $record): void {
                    /** @phpstan-ignore-next-line */
                    $record->user()->associate(auth()->user())->save();
                }),
        ];
    }

    /**
     * Defines the columns layout for the incidents table.
     *
     * We’ve included fields like 'Reported By' and 'Date' to ensure key info is accessible.
     * More columns can be added here if there are additional details you’d like displayed.
     *
     * @return array<int, Tables\Columns\TextColumn> Array of table columns for incidents.
     */
    private static function getTableColumnsLayout(): array
    {
        return [
            Tables\Columns\TextColumn::make('user.name')
                ->searchable()
                ->label(__('Ingevoegd door'))
                ->icon('heroicon-o-user-circle')
                ->weight(FontWeight::Bold)
                ->placeholder('Onbekende gebruiker')
                ->color('primary'),

            Tables\Columns\TextColumn::make('impact_score')
                ->label('Impact')
                ->sortable()
                ->badge(),

            Tables\Columns\TextColumn::make('incident_code')
                ->label(__('Incident categorie'))
                ->sortable(),

            Tables\Columns\TextColumn::make('description')
                ->label(__('Extra informatie'))
                ->words(12)
                ->searchable(),

            Tables\Columns\TextColumn::make('created_at')
                ->label(__('Ingevoegd op'))
                ->date()
                ->sortable(),
        ];
    }

    /**
     * Defines the available actions for each individual incident in the table.
     *
     * With edit and delete actions, users can easily manage each incident entry.
     * We’re open to suggestions on additional actions that may be useful here!
     *
     * @return array<int, Actions\ActionGroup> List of actions for each incident record.
     */
    private function getIncidentTableActions(): array
    {
        return [
            Actions\ActionGroup::make([
                Actions\ViewAction::make()
                    ->slideOver()
                    ->modalHeading(trans('Incident gegevens'))
                    ->modalIcon('heroicon-o-exclamation-triangle')
                    ->modalDescription(trans('Gegevens over het gerapporteerde incident tijdens de verhuring.'))
                    ->modalIconColor('danger'),

                Actions\EditAction::make()
                    ->modalHeading(trans('Incident bewerken'))
                    ->modalIcon('heroicon-o-pencil-square')
                    ->modalDescription('Hebt u meer informatie dat je wilt toevoegen bij het incident of is er een meer passende categorie. Dan kun je hier de registratie wijzigen.'),

                Actions\DeleteAction::make()
                    ->modalHeading(__('Incident registratie verwijderen'))
                    ->modalDescription('Weet je zeker dat je de incident registratie wilt verwijderen. Deze actie kan niet ongedaan worden gemaakt. Dus weet zeker dat je deze actie wilt uitvoeren.'),
            ])
                ->button()
                ->link()
                ->icon('heroicon-o-ellipsis-horizontal')
                ->label(''),
        ];
    }
}
