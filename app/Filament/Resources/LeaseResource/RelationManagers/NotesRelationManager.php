<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\RelationManagers;

use App\Filament\Resources\LeaseResource\Pages\ViewLease;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * Class NotesRelationManager
 *
 * Manages the "notes" relationship within the Lease resource. This relation manager allows for the creation, viewing, and management
 * of notes associated with a lease. It provides a form for adding and editing notes, as well as a table for displaying and interacting
 * with the notes in the admin panel.
 *
 * @package App\Filament\Resources\LeaseResource\RelationManagers
 */
class NotesRelationManager extends RelationManager
{
    /**
     * The name of the relationship that this manager handles.
     *
     * @var string
     */
    protected static string $relationship = 'notes';

    /**
     * The singular label for the model managed by this relation manager.
     *
     * @var string|null
     */
    protected static ?string $modelLabel = 'Notitie';

    /**
     * The plural label for the model managed by this relation manager.
     *
     * @var string|null
     */
    protected static ?string $pluralModelLabel = 'Notities';

    /**
     * The title of the relation manager section.
     *
     * @var string|null
     */
    protected static ?string $title = 'Notities';
    /**
     * The icon name used for representing this relationship in the UI.
     * This string corresponds to an icon identifier, typically used to
     * visually represent the relationship within the application.
     *
     * Note: The icon name usually follows a naming convention or comes from an icon
     * library (e.g., "heroicon-o-book-open")
     *
     * @var string|null
     */
    protected static ?string $icon = 'heroicon-o-book-open';

    /**
     * Determines if notes can be viewed for a specific lease record and page class.
     *
     * This method ensures notes are only visible when lease details through
     * the ViewLease page, restricting access in other contexts for better security
     * and user expierence.
     *
     * @param  Model  $ownerRecord  The lease record being viewed.
     * @param  string $pageClass    The class name of the current page.
     * @return bool                 Returns true is the current page is a ViewLease page.
     */
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return new $pageClass() instanceof ViewLease;
    }

    /**
     * Determines whether the notes relpation manager should be read-only.
     *
     * This method controls whether users can modify notes associated wuth a lease.
     * When true, notes can be viewed but nog created, edited of deleted.
     * When false, full CRUD operations are allowed on notes.
     *
     * Currently set to false to allow full note management capabilities.
     *
     * @return bool Returns false to enable full note management functionality.
     */
    public function isReadOnly(): bool
    {
        return false;
    }

    /**
     * Configures the form for creating and editing notes.
     *
     * @param  Form $form  The form builder instance to configure.
     * @return Form        The configured form instance.
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->label('Titel')->required()->maxLength(255)->columnSpan(8),
                Forms\Components\Textarea::make('body')->label('Notitie')->columnSpan(12)->rows(6),
            ])->columns(12);
    }

    /**
     * Configures the table for displaying and managing notes.
     *
     * @param  Table $table The table builder instance to configure.
     * @return Table        The configured table instance.
     */
    public function table(Table $table): Table
    {
        return $table
            ->description(trans('Extra notulen omtrent de reservatie aanvraag zoals bv speciale wensen van de huurder.'))
            ->columns([
                Tables\Columns\TextColumn::make('author.name')->label('Ingevoegd door')->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('title')->label('Titel')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Ingevoegd op')->sortable()->since(),
                Tables\Columns\TextColumn::make('updated_at')->label('Laast gewijzigd op')->sortable()->since(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
