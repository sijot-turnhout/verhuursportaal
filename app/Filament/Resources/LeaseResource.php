<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\LeaseStatus;
use App\Filament\Resources\InvoiceResource\LeaseInfolist;
use App\Filament\Resources\LeaseResource\Pages;
use App\Filament\Resources\LeaseResource\RelationManagers;
use App\Models\Lease;
use App\Models\Local;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

/**
 * @todo Write documentation for creating a tenant through the creation view of the lease.
 * @todo Implement cron job command that registers automatically all open leases to closed when the departure date is due
 */
final class LeaseResource extends Resource
{
    /**
     * The database entity for this resource.
     *
     * @var ?string
     */
    protected static ?string $model = Lease::class;

    protected static ?string $recordTitleAttribute = 'periode';

    /**
     * The singular name for the resource entity.
     *
     * @var ?string
     */
    protected static ?string $modelLabel = 'verhuring';

    /**
     * The plural model name for resource enitity.
     *
     * @var ?string
     */
    protected static ?string $pluralModelLabel = 'Verhuringen';

    /**
     * The navigation icon name for the navigation bar.
     */
    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    /**
     * Method to render the creation of the create and edit form for a lease in the resource.
     *
     * @todo Fix the bug where user can still update the status of the lease when they are cancelled of completed.
     *
     * @param  Form  $form  THe form builder instance that will be used to create the edit and creation form in the backend.
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
                            ->columnSpan(12),
                        Forms\Components\Select::make('supervisor_id')->label('Aanspreekpunt / Verantwoordelijke')->relationship('supervisor', 'name')->columnSpan(5),
                        Forms\Components\ToggleButtons::make('status')
                            ->inline()
                            ->options(LeaseStatus::class)
                            ->required()
                            ->columnSpan(7),
                    ])->columns(12),
            ]);
    }

    /**
     * This method takes an Infolist object as a parameter and returns a new
     * Infolist object created by the LeaseInfolist::make() method. This function
     * serves as a wrapper around the LeaseInfolist::make method, providing a
     * simplified interface for creating LeaseInfolist instances from existing
     * Infolist objects.
     *
     * @param  Infolist  $infolist  The Infolist object that serves as the input to the LeaseInfolist::make method.
     * @return Infolist             A new Infolist object as returned by the LeaseInfolist::make method.
     */
    public static function infolist(Infolist $infolist): Infolist
    {
        return LeaseInfolist::make($infolist);
    }

    /**
     * Method to render data overview table in the Filament backend of the application.
     *
     * @param Table $table The instance that is used to build the table.
     *
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('arrival_date', 'ASC')
            ->columns([
                Tables\Columns\TextColumn::make('period')->label('Periode'),
                Tables\Columns\TextColumn::make('status')->badge()->sortable()->searchable(),
                Tables\Columns\TextColumn::make('supervisor.name')->label('Verantwoordelijke')->placeholder('- geen toewijzing')->sortable(),
                Tables\Columns\TextColumn::make('persons')->label('Aantal personen')->sortable()->badge()->icon('heroicon-o-user'),
                Tables\Columns\TextColumn::make('tenant.fullName')->label('Huurder')
                    ->sortable()
                    ->iconColor('warning')
                    ->icon(static fn(Lease $lease) => $lease->tenant->isBanned() ? 'heroicon-o-exclamation-triangle' : null)
                    ->tooltip(static fn(Lease $lease) => $lease->tenant->isBanned() ? trans('Deze huurder staat op de zwarte lijst') : null)
                    ->iconPosition(IconPosition::Before),
                Tables\Columns\TextColumn::make('group')->label('Organisatie')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Aanvragingsdatum')->date()->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\ActionGroup::make([
                    Action::make('factuur')
                        ->icon('heroicon-o-document-text')
                        ->visible(fn(Lease $record): bool => $record->invoice()->exists())
                        ->url(fn(Lease $record) => route('filament.admin.resources.invoices.view', $record->invoice)),

                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->filters([
                SelectFilter::make('status')->options(LeaseStatus::class),
            ])
            ->filtersTriggerAction(fn(Action $action) => $action->button()->label('Filter'))
            ->defaultSort('arrival_date')
            ->bulkActions([
                ExportBulkAction::make(),
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['tenant.name', 'group', 'departure_date', 'arrival_date'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|\Illuminate\Contracts\Support\Htmlable
    {
        return $record->period;
    }

    /**
     * Method to define the global query that will be used by the search functionality related to the leases in the application.
     *
     * @return Builder
     */
    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['tenant'])->limit(5);
    }

    /**
     * Method to define the view in the search results that are related to the leases in the application.
     *
     * @param  Model $record  The record entoty that will be rteturned by the search functionality
     * @return array
     */
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Huurder' => $record->tenant->name,
            'Organisatie' => $record->group,
        ];
    }

    /**
     * Method to define the associated relation managers (views) to the resource.
     *
     * @return array<class-string>
     */
    public static function getRelations(): array
    {
        return [
            RelationManagers\UtilitiesRelationManager::class,
            RelationManagers\NotesRelationManager::class,
        ];
    }

    /**
     * Method to get the pages of the lease resource in the application back-end.
     *
     * @return array<string, \Filament\Resources\Pages\PageRegistration>
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
