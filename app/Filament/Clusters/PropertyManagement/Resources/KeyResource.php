<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources;

use App\Filament\Clusters\PropertyManagement;
use App\Filament\Clusters\PropertyManagement\Resources\KeyResource\Enums\KeyTypes;
use App\Filament\Clusters\PropertyManagement\Resources\KeyResource\Enums\MasterKey;
use App\Filament\Clusters\PropertyManagement\Resources\KeyResource\Pages;
use App\Models\Key;
use App\Models\Local;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * This class controls how key information is managed within the Filament admin panel.
 * It defines how keys are displayed, created, updated, and deleted.
 * Think of it as the contral control panel for everything related to keys in the admin area.
 *
 * @package App\Filament\Clusters\PropertyManagement\Resources
 */
final class KeyResource extends Resource
{
    /**
     * This links the resource to the 'Key' model, which is where key data is stored in the database.
     *
     * @var string|null
     */
    protected static ?string $model = Key::class;

    /**
     * Sets the icon used to represent keys in the admin panel's navigation menu.
     *
     * @var string|null
     */
    protected static ?string $navigationIcon = 'heroicon-o-key';

    /**
     * The singular label for "Key", used in headings and labels throughout the admin panel.
     *
     * @var string|null
     */
    protected static ?string $modelLabel = 'Sleutel';

    /**
     * The plural label for "Keys", used in headings and labels throughout the admin panel.
     *
     * @var string|null
     */
    protected static ?string $pluralModelLabel = 'Sleutels';

    /**
     * Associates this resource with the "Property management" cluster.
     * This bundles multiple resources in to a main resource.
     *
     * {@inheritDoc}
     */
    protected static ?string $cluster = PropertyManagement::class;

    /**
     * Configures how key details are displayed on a key's individual information page.
     * This method arranges the layout and content of that page.
     *
     * @param  Infolist $infolist   The object responsible for displaying the information.
     * @return Infolist             The configured 'Infolist' object.
     */
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(12)
            ->schema([
                TextEntry::make('user.name')->label('Bewaarder')->columnSpan(4)->icon('heroicon-o-user-circle')->iconColor('primary'),
                TextEntry::make('name')->label('Naam v/d sleutel')->columnSpan(8)->icon('heroicon-o-key')->iconColor('primary'),
                TextEntry::make('type')->label('Productie type')->columnSpan(4)->badge(),
                TextEntry::make('is_master_key')->label('Sleutel type')->columnSpan(4)->badge(),
                TextEntry::make('key_number')->label('Serienummer')->columnSpan(4)->placeholder('onbekend / n.v.t'),
                TextEntry::make('local.name')->columnSpan(6)->label('Geeft toegang tot')->icon('heroicon-o-home')->default('alle lokalen')->iconColor('primary'),
                TextEntry::make('description')->label('Beschrijving/Extra informatie')->placeholder('Geen beschrijving of extra informatie opgegeven')->columnSpan(12),
            ]);
    }

    /**
     * Defines the structure of the form used to create or edit the key information.
     * This method determines whuch fields are included in the form and how they are presented.
     *
     * @param  Form $form   The form object that needs to be configured
     * @return Form         The configured from, ready to display
     */
    public static function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema(self::registerKeyManagementForm());
    }

    /**
     * Sets up the table that displays a list of keys in the admin panel.
     * This method controls which columns are shown, what actions are available (like editing or deleting), and other table settings.
     *
     * @param  Table $table The table object that needs to be configured.
     * @return Table        The configured table, ready to display the list of key registrations.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateIcon(self::$navigationIcon)
            ->emptyStateHeading(trans('Geen sleutels geregistreerd'))
            ->emptyStateDescription(trans('Momenteel zijn er nog geen sleutels geregistreerd in de applicatie'))
            ->columns([
                TextColumn::make('user.name')->label('Bewaarder')->translateLabel()->icon('heroicon-o-user-circle')->weight(FontWeight::SemiBold)->iconColor('primary')->searchable(),
                TextColumn::make('local.name')->default('alle lokalen')->icon('heroicon-o-home')->iconColor('primary')->searchable(),
                TextColumn::make('key_number')->label('Serienummer')->searchable()->sortable()->placeholder('onbekend / n.v.t'),
                TextColumn::make('name')->label('Naam v/d sleutel')->translateLabel()->searchable(),
                TextColumn::make('is_master_key')->label('Sleutel type')->translateLabel()->searchable()->badge(),
                TextColumn::make('type')->label('Productie')->badge()->sortable(),
            ])
            ->actions(self::registerTableActions())
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Defines which pages in the admin panel are related to managing keys.
     * This method returns a list of page definitions, telling Filament where to find these pages and how to access them.
     *
     * @return array|\Filament\Resources\Pages\PageRegistration[] An array defining the pages related to this resource.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKeys::route('/'),
        ];
    }

    /**
     * Creates the specific form fields used for managing key data.
     * This method defines details like input fields for the key's name, type, and other attributes.
     *
     * Returns an array defining the form fields for key management.
     *
     * @return array<Forms\Components\Component>
     */
    public static function registerKeyManagementForm(): array
    {
        return [
            Forms\Components\Fieldset::make()
                ->label('Sleutel karakteristieken')
                ->columns(12)
                ->schema([
                    Forms\Components\Select::make('type')
                        ->label(trans('Productie type'))
                        ->translateLabel()
                        ->options(KeyTypes::class)
                        ->required()
                        ->columnSpan(12),

                    Forms\Components\Radio::make('is_master_key')
                        ->hint('Kan na de registratie niet meer gewijzigd worden')
                        ->hintIcon('heroicon-o-exclamation-triangle')
                        ->hintColor('danger')
                        ->label('Sleutel type')
                        ->hiddenOn('edit')
                        ->columnSpan(12)
                        ->live()
                        ->options(MasterKey::class),
                ]),

            Forms\Components\Fieldset::make('Sleutel informatie')
                ->columns(12)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label(trans('Naam v/d sleutel'))
                        ->translateLabel()
                        ->required()
                        ->columnSpan(12),

                    Forms\Components\Select::make('user_id')
                        ->label(trans('Toegewezen aan'))
                        ->translateLabel()
                        ->required()
                        ->columnSpan(6)
                        ->relationship(name: 'user', titleAttribute: 'name'),

                    Forms\Components\TextInput::make('key_number')
                        ->label(trans('Serienummer'))
                        ->translateLabel()
                        ->requiredIf('type', KeyTypes::Master->value)
                        ->columnSpan(6),

                    Forms\Components\Select::make('local_id')
                        ->label('Geeft toegang tot')
                        ->translateLabel()
                        ->options(fn() => Local::query()->pluck('name', 'id'))
                        ->columnSpan(12)
                        ->requiredIf('is_master_key', MasterKey::False->value)
                        ->hidden(fn(Forms\Get $get) => $get('is_master_key') === MasterKey::True->value),

                    Forms\Components\Textarea::make('description')
                        ->label(trans('Beschrijving/Extra informatie'))
                        ->rows(3)
                        ->columnSpan(12),
                ]),
        ];
    }

    /**
     * Configures the actions available for the table.
     *
     * Returns the array of configured table actions including view, edit, and delete actions encapsulated in an action group.
     *
     * @return array<int, Tables\Actions\ActionGroup>
     */
    public static function registerTableActions(): array
    {
        return [
            Tables\Actions\ActionGroup::make([
                KeyResource\Actions\ViewKeyRegistration::make(),
                KeyResource\Actions\EditKeyRegistration::make(),
                KeyResource\Actions\DeleteKeyRegistration::make(),
            ]),
        ];
    }

    /**
     * Registers the bulk actions available for the table.
     * Returns the array of bulk action groups defined for the table.
     *
     * @return array<int, Tables\Actions\BulkActionGroup>
     */
    public static function registerTableBulkActions(): array
    {
        return [
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ];
    }
}
