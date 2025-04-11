<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Clusters\Billing;
use App\Filament\Clusters\Billing\Resources\QuotationResource\RelationManagers\QuotationLinesRelationManager;
use App\Filament\Resources\QuotationResource\Pages;
use App\Models\Quotation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Gate;

/**
 * Class QuotationResource
 *
 * This class is responsible for managing quotation records within the application
 * using Filament. It binds the resource to the Quotation model and organizes it within
 * the Billing cluster for better navigation grouping. The resource is identified in the
 * interface by its singular and plural labels as well as a dedicated navigation icon.
 *
 * @todo Docblock this class.
 * @todo Implement badge on the navigation item that counts the quotation requests
 *
 * @package App\Filament\Resources
 */
final class QuotationResource extends Resource
{
    /**
     * The cluster (or grouping) in which this resource is organized.
     * Set to the Billing cluster for grouping related billing resources.
     *
     * @var string|null
     */
    protected static ?string $cluster = Billing::class;

    /**
     * The Eloquent model associated with this resource.
     * All operations and views of this resource will use the Quotation model.
     *
     * @var string|null
     */
    protected static ?string $model = Quotation::class;

    /**
     * The singular label for this resource.
     * Used for display purposes in the interface when referring to a single record.
     *
     * @var string|null
     */
    protected static ?string $modelLabel = 'Offerte';

    /**
     * The plural label for this resource.
     * Used for display purposes when referring to multiple records.
     *
     * @var string|null
     */
    protected static ?string $pluralModelLabel = 'Offertes';

    /**
     * The navigation icon for this resource.
     * This icon is used in the sidebar navigation to represent the Quotation resource.
     *
     * @var string|null
     */
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    /**
     * Returns the form instance used to manage the Quotation resource.
     *
     * This method configures the form schema and its layout. The schema contains a section labeled "Offerte informatie", which is displayed as a collapsible panel with an icon, description, and a single textarea field for "Notitie".
     * The textarea is marked as read-only when viewing a record and includes a hint indicating that the notes will appear on the invoice.
     *
     * @param  Form  $form  The form instance to be configured.
     * @return Form         The configured form with the defined schema.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Offerte informatie')
                    ->icon('heroicon-o-document-text')
                    ->iconColor('primary')
                    ->collapsible()
                    ->description('De algemene infromatie van de offerte. let wel op alleen de notulen kunnen voor nu gewijzigd worden. Omdat de begunstigde en verhuring dieper gekoppeld zijn in het systeem.')
                    ->compact()
                    ->columns(12)
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Notitie')
                            ->readOnlyOn('view')
                            ->hint('Let op! Deze notulen worden meegedeeld op de factuur')
                            ->hintColor('danger')
                            ->rows(4)
                            ->columnSpan(12),
                    ]),
            ]);
    }

    /**
     * Configure the information display for a quotation record.
     *
     * This method builds and returns the infolist schema used to present detailed information about a quotation.
     * It sets up a section with an icon and description and divides the information into three fieldsets:
     * one for general quotation details (such as reference number, creator, lease period, status, and due date),
     * one for recipient details (including name, address, email, and phone number), and one for any extra information.
     *
     * @param  Infolist  $infolist  The infolist instance to be configured.
     * @return Infolist             The configured infolist instance with the defined schema.
     */
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make(trans('Offerte informatie'))
                ->icon('heroicon-o-document-text')
                ->iconColor('primary')
                ->iconSize(IconSize::Medium)
                ->description('De algemene informatie omtrent de offerte')
                ->compact()
                ->collapsible()
                ->collapsed()
                ->schema([
                    Fieldset::make(trans('Algemene informatie'))
                        ->columns(12)
                        ->schema([
                            TextEntry::make('reference')->label('Referentie')->weight(FontWeight::Bold)->color('primary')->columnSpan(2),
                            TextEntry::make('creator.name')->label('Opgesteld door')->columnSpan(2)->placeholder('-'),
                            TextEntry::make('lease.period')->label('Verhuringsperiode')->columnSpan(3),
                            TextEntry::make('status')->label('Offerte status')->badge()->columnSpan(2),
                            TextEntry::make('quotation_due_at')->label('Verval datum')->columnSpan(3)->date('d/m/Y')->placeholder('-'),
                        ]),

                    Fieldset::make(trans('Begunstigde'))
                        ->columns(12)
                        ->schema([
                            TextEntry::make('reciever.name')->label('Naam')->columnSpan(2)->icon('heroicon-o-user-circle')->iconColor('primary'),
                            TextEntry::make('reciever.address')->label('Adres')->columnSpan(4)->icon('heroicon-o-map-pin')->iconColor('primary')->placeholder('Onbekend of niet opgegeven'),
                            TextEntry::make('reciever.email')->label('Email adres')->columnSpan(3)->icon('heroicon-o-envelope')->iconColor('primary'),
                            TextEntry::make('reciever.phone_number')->label('Telefoon nummer')->columnSpan(3)->icon('heroicon-o-phone')->iconColor('primary')->placeholder('Onbekend on niet opgegeven'),

                        ]),

                    Fieldset::make(trans('Extra informatie'))
                        ->columns(12)
                        ->schema([
                            TextEntry::make('description')->label('Extra informatie')->columnSpan(12)->hiddenLabel(),
                        ]),
                ]),
        ]);
    }

    /**
     * Defines the table configuration for listing quotation records.
     *
     * This method returns a Table instance configured with custom empty state text,
     * a set of columns to display key fields (reference number, creator, status, recipient name,
     * lease period, due date, and creation date), and actions for each row including view,
     * export, edit, and delete functionalities. It also defines bulk actions for operations
     * on multiple records. The export action is only visible if the current user is authorized to download the quotation.
     *
     * @param  Table  $table  The table instance to be configured.
     * @return Table         The configured table instance with defined columns, actions, and bulk actions.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading(trans('Geen offertes gevonden'))
            ->emptyStateDescription(trans('Momenteel zijn er nog geen offertes gevonden in het systeem aangemaakt of gevonden die voldoen aan de opgegeven criteria.'))
            ->columns([
                Tables\Columns\TextColumn::make('reference')->label(trans('Referentie nr.'))->weight(FontWeight::Bold)->color('primary')->searchable(),
                Tables\Columns\TextColumn::make('creator.name')->label(trans('Opgesteld door'))->placeholder('-')->searchable(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('reciever.name')->label(trans('Begunstigde'))->searchable(),
                Tables\Columns\TextColumn::make('lease.period')->label(trans('Verhuringsperiode'))->color('primary')->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('quotation_due_at')->label(trans('vervaldatum'))->date()->sortable()->placeholder('-'),
                Tables\Columns\TextColumn::make('created_at')->label(trans('Aangevraagd op'))->date(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\Action::make('Exporteer offerte')
                        ->visible(fn(Quotation $quotation): bool => Gate::allows('download', $quotation))
                        ->icon('heroicon-o-document-text')
                        ->url(fn(Quotation $quotation): string => route('quotations.download', $quotation))
                        ->openUrlInNewTab(),

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

    /**
     * Retrieves the relation managers for the Quotation resource.
     *
     * This method returns an array of classes responsible for managing the relationships associated with the Quotation resource.
     * In this case, it registers the relation manager for handling quotation line items.
     *
     * @return array An array containing the relation manager classes for the resource.
     */
    public static function getRelations(): array
    {
        return [QuotationLinesRelationManager::class];
    }

    /**
     * Returns an array of pages for the Quotation resource.
     *
     * This method maps page names to their corresponding route definitions by calling thestatic method `route` on the appropriate page classes.
     * It defines the index, create, view, and edit pages for managing quotation records.
     *
     * @return array An associative array in which each key is a page name and each value is the route for that page.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuotations::route('/'),
            'create' => Pages\CreateQuotation::route('/create'),
            'view' => Pages\ViewQuotations::route('/{record}'),
            'edit' => Pages\EditQuotation::route('/{record}/edit'),
        ];
    }
}
