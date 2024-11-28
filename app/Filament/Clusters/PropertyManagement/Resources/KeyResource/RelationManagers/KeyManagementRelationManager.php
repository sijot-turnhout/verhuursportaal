<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\KeyResource\RelationManagers;

use App\Filament\Clusters\PropertyManagement\Resources\KeyResource;
use App\Filament\Resources\LocalResource\Pages\ViewLocal;
use App\Models\Key;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

/**
 * Manages the Key Management Relationship.
 *
 * This class handles the definition of the relationship, title, and icon used in the "Key Management" section of the application.
 * It provides methods to handle the form schema, table representation, and read-only state determination for the relationship.
 *
 * @package App\Filament\Clusters\PropertyManagement\Resources\KeyResource\RelationManagers
 */
final class KeyManagementRelationManager extends RelationManager
{
    /**
     * The name of the relationship managed by this class.
     * This string corresponds to the relationship method name in the Eloquent model.
     *
     * @var string
     */
    protected static string $relationship = 'keyManagement';

    /**
     * The title displayed in the UI for this relationship.
     * This title will appear in the user interfaces, aiding users in understanding
     * the context or section they are interacting with.
     *
     * Note: The title is in Dutch, meaning "Key Management".
     *
     * @var string|null
     */
    protected static ?string $title = "Sleutelbeheer";

    /**
     * The icon name used for representing this relationship in the UI.
     * This string corresponds to an icon identifier, typically used to
     * visually represent the relationship within the application.
     *
     * Note: The icon name usually follows a naming convention or comes from an icon
     * library (e.g., "heroicon-o-key")
     *
     * @var string|null
     */
    protected static ?string $icon = 'heroicon-o-key';

    /**
     * Defines the form schema for the Key Management relationship.
     *
     * @param  Form $form The form instance used to build the schema.
     * @return Form       The form instance with the schema defined.
     */
    public function form(Form $form) : Form
    {
        return $form
            ->columns(12)
            ->schema(KeyResource::registerKeyManagementForm());
    }

    /**
     * Retrieves and returns the informational list for Key Resource.
     *
     * @param Infolist $infolist The Infolist instance used to retrieve the information.
     * @return Infolist          The Infolist instance populated with Key Resource information.
     */
    public function infolist(Infolist $infolist): Infolist
    {
        return KeyResource::infolist($infolist);
    }

    /**
     * Determine if the user can view the record for the given page class.
     *
     * This method checks if the authenticated user has permission to view the specified
     * record based on the provided Eloquent modal and page class.
     *
     * This method uses Laravel's Gate facade to check the 'viewAny' ability for the 'key' class
     * and ensures that the provided page class matches the expected 'ViewLocal::class'.
     *x
     * @param  Model  $ownerRecord  The record of the owner model to check permissions for.
     * @param  string $pageClass    The page class name of the page to check against.
     * @return bool                 Returns 'true' is the user can view the record; 'false' otherwise
     */
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        // Check if the user is authorized to view any 'key' records and ensure the page class corresponds to 'ViewLocal'
        return Gate::allows('viewAny', Key::class) && $pageClass === ViewLocal::class;
    }

    /**
     * Determine if the relation manager is in a read-only state.
     *
     * This method is used to check whether the relationship is managed by this 'KeyManagementRelationManager'
     * instance is readonly or can be modified.
     *
     * @return bool Returns 'false' indicating that the relationship is not read-only and can be modified.
     */
    public function isReadOnly() : bool
    {
        return false;
    }

    /**
     * Defines the table representation for the Key Management relationship.
     *
     * @param  Table $table The table instance used to build the table representation.
     * @return Table        The table instance with defined columns, actions, and bulk actions.
     */
    public function table(Table $table) : Table
    {
        return $table
            ->heading(trans('Sleutels van het lokaal'))
            ->description(trans('Voor de loper sleutels binnen de organisatie bekijk het algemeen sleutelbeheer. Lopers worden hier niet opgelijst.'))
            ->modelLabel(trans('Sleutel'))
            ->pluralModelLabel(trans('Sleutels'))
            ->emptyStateIcon(self::$icon)
            ->emptyStateHeading(trans('Geen sleutels geregistreerd'))
            ->emptyStateDescription(trans('Het lijkt erop dat er momenteel geen sleutels zijn geregistreerd voor het lokaal in de applicatie'))
            ->columns($this->tableColumnsSchema())
            ->actions(KeyResource::registerTableActions())
            ->bulkActions(KeyResource::registerTableBulkActions());
    }

    /**
     * Defines the schema for the columns in the table representation.
     *
     * @return array The array containing the column definitions.
     */
    private function tableColumnsSchema(): array
    {
        return [
            Tables\Columns\TextColumn::make('id'),
        ];
    }
}
