<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\LeaseStatus;
use App\Filament\Clusters\LeaseManagement;
use App\Filament\Resources\InvoiceResource\LeaseInfolist;
use App\Filament\Resources\LeaseResource\Pages;
use App\Filament\Resources\LeaseResource\RelationManagers;
use App\Filament\Resources\LeaseResource\Traits\UsesArchivingSystemActions;
use App\Models\Lease;
use App\Models\Local;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

/**
 * Class LeaseResource
 *
 * The `LeaseResource` class defines the resource for managing lease records in the Filament admin panel.
 * It provides configurations for forms, tables, global search, and related pages, as well as integrating
 * functionalities like creating, viewing, editing, and deleting lease records.
 *
 * @todo sijot-turnhout/verhuur-portaal-documentatie#7  - Write documentation for creating a tenant through the creation view of the lease.
 * @todo sijot-turnhout/verhuur-portaal-documentatie#12 - Documenteren van de authorisatie checks met betrekking op de verhuringen
 * @todo verhuursportaal/issues#21                      - Implement cron job command that registers automatically all open leases to closed when the departure date is due
 *
 * @package App\Filament\Resources
 */
final class LeaseResource extends Resource
{
    use UsesArchivingSystemActions;

    /**
     * The Eloquent model associated with this resource.
     *
     * This property specifies the model that the resource is managing. In this case, it is the `Lease` model.
     *
     * @var ?string
     */
    protected static ?string $model = Lease::class;

    /**
     * Defines the attribute that will be used as the display title for records in the resource.
     *
     * Filament uses this property to determine which model attribute should be used to represent
     * the record in various contexts, such as in table headers, resource titles, or breadcrumbs.
     *
     * In this case, the `periode` attribute of the model is set as the record title.
     * This means that whenever Filament needs to display the title of a record, it will
     * use the value of the `periode` column from the database.
     *
     * @var ?string The name of the attribute to be used as the record title.
     */
    protected static ?string $recordTitleAttribute = 'periode';

    /**
     * The attribute used as the title for records in this resource.
     *
     * This property defines the attribute of the model that will be used as the title in various views of this resource.
     *
     * @var ?string
     */
    protected static ?string $modelLabel = 'Aanvraag';

    /**
     * The singular label for this resource.
     *
     * This property specifies the singular name of the resource entity used in the UI.
     *
     * @var ?string
     */
    protected static ?string $pluralModelLabel = 'Aanvragen';

    /**
     * The icon used for navigation in the admin panel.
     *
     * This property sets the icon that represents this resource in the navigation bar.
     *
     * @var ?string
     */
    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    /**
     * The cluster (group) of resources that this resource belongs to.
     *
     * This specifies that the 'LeaseResource' belongs to the 'LeaseManagement' clister.
     * Clusters are used to group related resources in the admin panel for better organisation.
     *
     * @var class-string<\Filament\Clusters\Cluster>|null
     */
    protected static ?string $cluster = LeaseManagement::class;

    /**
     * Defines the form schema for creating and editing lease records.
     *
     * This method returns the form schema used for creating and editing lease records in the admin panel.
     * It defines the fields and their properties, including relationships and validation rules.
     *
     * @param  Form $form   The form builder instance used to define the form schema.
     * @return Form         The configured form instance.
     *
     * @todo sijot-turnhout/verhuursportaal#22 - Gebruikers kunnen nog steeds de status aanpassen.
     * @todo Het lijkt om het het formulier in een soort wizard te laten lopen.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Reservatie informatie')
                    ->description('Algemene informatie omtrent de aanvraag tot verhuring')
                    ->icon('heroicon-m-user')
                    ->iconColor('primary')
                    ->collapsible()
                    ->collapsed(fn(string $operation): bool => 'edit' === $operation)
                    ->schema([
                        Forms\Components\Select::make('tenant_id')->label('Huurder')
                            ->required()
                            ->relationship('tenant', 'firstName')
                            ->createOptionModalHeading('Registreren van een nieuwe huurder')
                            ->createOptionForm([
                                Forms\Components\Grid::make(12)->schema([
                                    Forms\Components\TextInput::make('firstName')->label('Voornaam')->required()->columnSpan(5),
                                    Forms\Components\TextInput::make('lastName')->label('Achternaam')->required()->columnSpan(7),
                                    Forms\Components\TextInput::make('email')->label('Email adres')->unique(ignoreRecord: true)->required()->columnSpan(6),
                                    Forms\Components\TextInput::make('phone_number')->label('Telefoon nummer')->columnSpan(6),
                                    Forms\Components\TextInput::make('address')->label('adres')->columnSpan(12),

                                ]),
                            ])
                            ->disabled(fn(string $operation): bool => 'edit' === $operation)
                            ->columnSpan(4),

                        Forms\Components\TextInput::make('group')->label('Groep')->required()->columnSpan(6),
                        Forms\Components\TextInput::make('persons')->numeric()->label('Aantal personen')->required()->columnSpan(2),
                        Forms\Components\DateTimePicker::make('arrival_date')->required()->label('Aankomst')->seconds(false)->columnSpan(6)->format('d-m-Y')->native(false)->prefixIcon('heroicon-m-calendar-days'),
                        Forms\Components\DateTimePicker::make('departure_date')->required()->date()->label('Vertrek')->seconds(false)->columnSpan(6)->format('d-m-Y')->afterOrEqual('arrival_date')->native(false)->prefixIcon('heroicon-m-calendar-days'),
                        Forms\Components\Select::make('spaces')->label('Lokalen')
                            ->multiple()
                            ->relationship('locals', 'name')
                            ->options(fn() => Local::query()->where('storage_location', false)->pluck('name', 'id'))
                            ->columnSpan(9),
                        Forms\Components\Select::make('supervisor_id')->label('Aanspreekpunt / Verantwoordelijke')->relationship('supervisor', 'name')->columnSpan(3),
                        Forms\Components\ToggleButtons::make('status')
                            ->inline()
                            ->visible(fn(string $operation): bool => 'create' === $operation)
                            ->options(LeaseStatus::class)
                            ->required()
                            ->columnSpan(12),
                    ])->columns(12),
            ]);
    }

    /**
     * Creates an infolist for the lease resource.
     *
     * This method wraps the `LeaseInfolist::make()` method, allowing customization and configuration of the infolist
     * used to display additional information about lease records.
     *
     * @param  Infolist $infolist   The existing infolist object to be customized.
     * @return Infolist             The customized infolist object.
     */
    public static function infolist(Infolist $infolist): Infolist
    {
        return LeaseInfolist::make($infolist);
    }

    /**
     * Defines the table schema for displaying lease records.
     *
     * This method returns the table configuration used to display a list of lease records in the admin panel,
     * including columns, actions, filters, and bulk actions.
     *
     * @param  Table $table  The table builder instance used to define the table schema.
     * @return Table         The configured table instance.
     *
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateIcon(self::$navigationIcon)
            ->emptyStateHeading('Geen verhuringen gevonden')
            ->emptyStateDescription('Momenteel zijn er geen verhuringen gevonden onder de matchende criteria. U kunt er makkelijk een registreren met de knop hieronder')
            ->emptyStateActions([CreateAction::make()->icon('heroicon-o-plus')])
            ->defaultSort('arrival_date', 'ASC')
            ->columns([
                Tables\Columns\TextColumn::make('period')->label('Periode')
                    ->weight(FontWeight::SemiBold)
                    ->icon('heroicon-o-exclamation-triangle')
                    /** @phpstan-ignore-next-line */
                    ->iconColor(fn(Lease $lease) => $lease->risk_accessment_label->getColor() ?? 'gray'),

                Tables\Columns\TextColumn::make('tenant.fullName')->label('Huurder')
                    ->sortable()
                    ->iconColor('warning')
                    ->icon(static fn(Lease $lease) => $lease->tenant->isBanned() ? 'heroicon-o-exclamation-triangle' : null)
                    ->tooltip(static fn(Lease $lease) => $lease->tenant->isBanned() ? trans('Deze huurder staat op de zwarte lijst') : null)
                    ->iconPosition(IconPosition::Before),

                Tables\Columns\TextColumn::make('group')->label('Organisatie')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable()->searchable(),
                Tables\Columns\TextColumn::make('persons')->label('Aantal personen')->sortable()->badge()->icon('heroicon-o-user'),
                Tables\Columns\TextColumn::make('created_at')->label('Aangevraagd op')->date()->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->defaultSort('arrival_date')
            ->bulkActions([
                ExportBulkAction::make(),
                self::archiveBulkAction(),
                Tables\Actions\DeleteBulkAction::make()
                    ->visible(fn(Lease $record): bool => Gate::allows('delete', $record))
                    ->label('verwijderen'),
            ]);
    }

    /**
     * Retrieves an array of attributes that are globally searchable for the lease resource.
     *
     * This method defines which attributes of the `Lease` resource can be searched using global search functionality
     * in the application, such as the tenant's name, group, and key dates like departure and arrival.
     *
     * @return array<string> The list of searchable attributes.
     */
    public static function getGloballySearchableAttributes(): array
    {
        return ['tenant.name', 'group', 'departure_date', 'arrival_date'];
    }

    /**
     * Retrieves the title for a lease record when it appears in global search results.
     *
     * This method returns the lease's period to be displayed as the title in global search results.
     *
     * @param  Model $record  The lease record being displayed in search results.
     * @return string
     *
     * @phpstan-param Lease $record
     */
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->period;
    }

    /**
     * Defines the global search query used for lease records.
     *
     * This method customizes the query used in global search to retrieve lease records,
     * ensuring that the tenant relationship is eager loaded and the number of results is limited.
     *
     * @return Builder<Model> The Eloquent query used for global search.
     */
    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['tenant'])->limit(5);
    }

    /**
     * Defines the details of a lease record for the global search result view.
     *
     * This method returns an array of attributes to be displayed when a lease record is shown in global search results,
     * including the tenant's name and the organization (group) associated with the lease.
     *
     * @param  Model $record          The lease record being displayed.
     * @return array<string, string>  The details of the lease record for search results.
     *
     * @phpstan-param Lease $record
     */
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Huurder' => $record->tenant->name,
            'Organisatie' => $record->group,
        ];
    }

    /**
     * Defines the associated relation managers for the lease resource.
     *
     * This method specifies the relation managers associated with the lease resource,
     * such as utilities and notes, which provide additional management views in the application.
     *
     * @return array<class-string> An array of relation manager class names.
     */
    public static function getRelations(): array
    {
        return [
            RelationManagers\UtilitiesRelationManager::class,
            RelationManagers\NotesRelationManager::class,
            RelationManagers\DocumentRelationManager::class,
        ];
    }

    /**
     * Retrieves the pages associated with the lease resource.
     *
     * This method returns an array of page registrations for the lease resource, including routes for listing,
     * creating, viewing, and editing lease records in the application back-end.
     *
     * @return array<string, \Filament\Resources\Pages\PageRegistration> The list of pages with their routes.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeases::route('/'),
            'create' => Pages\CreateLease::route('/create'),
            'view' => Pages\ViewLease::route('/{record}'),
            'edit' => Pages\EditLease::route('/{record}/edit'),
        ];
    }
}
