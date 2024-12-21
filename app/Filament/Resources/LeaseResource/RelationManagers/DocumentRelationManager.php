<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\RelationManagers;

use App\Filament\Resources\LeaseResource\Pages\ViewLease;
use App\Models\Document;
use Filament\Actions\StaticAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Manages the 'documents' relationship within a lease.
 *
 * This Relation Manager provides the interface and logic for managing documents
 * attached to a lease, such as uploading, downloading, and deleting documents.
 * Documents are stored as PDF files and can be managed through a dedicated
 * table interface with actions for viewing and modifying them.
 *
 * @todo Implement a method that deletes attached files when a lease is deleted to ensure file system cleanup.
 *
 * @package App\Filament\Resources\LeaseResource\RelationManagers
 */
final class DocumentRelationManager extends RelationManager
{
    /**
     * Defines the relationship name used in the lease model.
     *
     * @var string
     */
    protected static string $relationship = 'documents';

    /**
     * Specifies a singular label for a document item.
     *
     * @var string|null
     */
    protected static ?string $modelLabel = "Document";

    /**
     * Specifies a plural label for document items.
     *
     * @var string|null
     */
    protected static ?string $pluralModelLabel = 'Documenten';

    /**
     * Specifies the title displayed in the interface for this relation.
     *
     * @var string|null
     */
    protected static ?string $title = 'Documenten';

    /**
     * The icon name used for representing this relationship in the UI.
     * This string corresponds to an icon identifier, typically used to
     * visually represent the relationship within the application.
     *
     * Note: The icon name usually follows a naming convention or comes from an icon
     * library (e.g., "heroicon-o-cloud")
     *
     * @var string|null
     */
    protected static ?string $icon = 'heroicon-o-cloud';

    /**
     * Retrieve a badge indicating the number of documents associated with the owner record.
     *
     * This method checks the number of documents linked to the specified owner record (e.g., a Lease or User).
     * If there are any associated documents, it returns the count as a string to be displayed as a badge.
     * If no documents are found, it returns null, indicating the absence of a badge.
     *
     * @param  Model $ownerRecord  The model instance (e.g., Lease or User) for which to retrieve the document count.
     * @param  string $pageClass   The page class where the badge might be displayed. This can help differentiate logic based on the page context (currently unused).
     * @return string|null         The count of associated documents as a string, or null if there are no documents.
     */
    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        /** @phpstan-ignore-next-line */
        $documentCount = $ownerRecord->documents()->count();

        // Return the document count as a string if greater than zero; otherwise, return null.
        return $documentCount > 0 ? (string) $documentCount : null;
    }

    /**
     * Determines whether the relation manager is in read-only mode.
     *
     * This method indicates whether users can modify the data managed by this
     * relation. Returning false means that modifications (such as adding, editing,
     * or deleting documents) are permitted. This method can be overridden in
     * subclasses to enforce read-only behavior based on specific conditions.
     *
     * @return bool Returns false, allowing modifications to the related data.
     */
    public function isReadOnly(): bool
    {
        return false;
    }

    /**
     * Determines if documents can be viewed for a specific lease record an page class.
     *
     * THis method checks if the current page is a ViewLease page, ensuring documents are only visible
     * when viewing lease details and not in other contexts.
     *
     * @param  Model   $ownerRecord  The lease record being viewed.
     * @param  string  $pageClass    The class name of the current page.
     * @return bool                  Returns true if the page is a ViewLease page, false otherwise
     */
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return new $pageClass() instanceof ViewLease;
    }

    /**
     * Creates and returns the form schema for managing document uploads.
     *
     * This form allows users to upload new documents to a lease, specifying
     * both the document name and file attachment. It includes custom validation,
     * file uniqueness, and user-assigned defaults.
     *
     * @param  Form $form The Filament form instance used for building the schema.
     * @return Form
     */
    public function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Opgesteld door')
                    ->translateLabel()
                    ->relationship(name: 'creator', titleAttribute: 'name')
                    ->default(auth()->user()->id)
                    ->columnSpan(5),
                Forms\Components\TextInput::make('name')
                    ->label('Bestandsnaam')
                    ->translateLabel()
                    ->columnSpan(7)
                    ->required()
                    ->unique(ignoreRecord: true),

                Forms\Components\FileUpload::make('attachment')
                    ->disk('local')
                    ->label('Bijlage')
                    ->translateLabel()
                    ->preserveFilenames()
                    ->unique(ignoreRecord: true)
                    ->previewable(false)
                    ->uploadingMessage('uploaden document...')
                    ->columnSpan(12)
                    ->required()
                    ->helperText('Momenteel ondersteunen we enkel pdf bestanden')
                    ->downloadable(),
            ]);
    }

    /**
     * Builds and returns the table schema for displaying a list of documents.
     *
     * The table provides a list of documents associated with a lease, including
     * columns for file name, uploader, and upload date. Table actions for editing,
     * downloading, and deleting are also included.
     *
     * @param  Table $table The Filament table instance used for building the schema.
     * @return Table
     */
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->translateLabel()
                    ->weight(FontWeight::Bold)
                    ->color('primary'),

                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Geupload door'),

                Tables\Columns\TextColumn::make('name')
                    ->translateLabel()
                    ->icon('heroicon-o-document-text')
                    ->iconColor('primary')
                    ->label('Bestandsnaam'),

                Tables\Columns\TextColumn::make('created_at')
                    ->translateLabel()
                    ->label('Geupload op'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                $this->downloadDocumentAction(),
                $this->deleteDocumentAction(),
            ])
            ->bulkActions([DeleteBulkAction::make()])
            ->headerActions([
                $this->createDocumentAction(),
            ]);
    }

    /**
     * Customizes and returns the Create Action for uploading documents.
     *
     * This action allows users to upload a new document via a modal with
     * custom labels, icons, and descriptions. The modal is specifically configured
     * to accept only PDF files, ensuring proper document formatting for leases.
     *
     * @return CreateAction The customized action for document uploads.
     */
    private function createDocumentAction(): CreateAction
    {
        return CreateAction::make()
            ->modalHeading('Document uploaden')
            ->modalIcon('heroicon-o-document-plus')
            ->modalIconColor('primary')
            ->modalAutofocus()
            ->modalSubmitActionLabel('Uploaden')
            ->createAnother(false)
            ->successNotificationTitle('Het bestand is successvol toegevoegd aan de verhuring')
            ->modalSubmitAction(fn(StaticAction $action) => $action->icon('heroicon-o-paper-airplane'))
            ->modalDescription('Upload hier de benodigde documenten in PDF-formaat voor administratie van de verhuring. Zorg ervoor dat alle bestanden duidelijk leesbaar zijn en voldoen aan de interne eisen voor documentatiebeheer.')
            ->label('Document uploaden')
            ->icon('heroicon-o-plus');
    }

    /**
     * Creates a custom action for deleting documents.
     *
     * This action prompts the user for confirmation before deleting a selected document.
     * It displays a modal with a heading and a description, informing the user that
     * the action cannot be undone. The action enhances user experience by ensuring
     * that document deletions are intentional and not accidental.
     *
     * @return DeleteAction The configured delete action for table rows.
     */
    private function deleteDocumentAction(): DeleteAction
    {
        return DeleteAction::make()
            ->modalHeading('Document verwijderen')
            ->modalDescription('Weet je zeker dat je dit geupload document wilt verwijderen? Deze actie kan niet ongedaan worden gemaakt');
    }

    /**
     * Defines and returns a custom action for downloading documents.
     *
     * This action enables users to download a selected document from
     * storage, providing a streamlined way to retrieve attachments directly.
     *
     * @return Action  The configured download action for table rows.
     */
    private function downloadDocumentAction(): Action
    {
        return Action::make('download-file')
            ->label('download')
            ->icon('heroicon-o-cloud-arrow-down')
            ->action(fn(Document $document): StreamedResponse => Storage::disk('local')->download($document->attachment));
    }
}
