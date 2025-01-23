<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\ContactMessageStatus;
use App\Filament\Resources\ContactSubmissionResource\Pages;
use App\Filament\Resources\ContactSubmissionResource\Widgets\ContactStats;
use App\Models\ContactSubmission;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ContactSubmissionResource
 *
 * Manages the ContactSubmission resource within the Filament admin panel.
 * This resource handles the display, management, and interaction with contact submissions
 * through various pages, tables, and widgets.
 *
 * @package App\Filament\Resources
 */
final class ContactSubmissionResource extends Resource
{
    /**
     * The entity model resource for the ContactSubmission Resource in the backend.
     *
     * @var string|null
     */
    protected static ?string $model = ContactSubmission::class;

    /**
     * Thet singular resource entity name
     *
     * @var string|null
     */
    protected static ?string $modelLabel = 'contact';

    /**
     * The plural model name of the resource
     *
     * @var string|null
     */
    protected static ?string $pluralModelLabel = 'Contact';

    /**
     * The navigation icon name that will be displayed in the navigation bar
     *
     * @var string|null
     */
    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    /**
     * Method to display the information overview table in the application backend.
     *
     * @todo Investigate if its possible to use a alert when a contact message is created in the backend.
     *
     * @param  Table $table The table instance that will be used to build the overview table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateIcon(self::$navigationIcon)
            ->emptyStateHeading(trans('Geen contactnames gevonden'))
            ->emptyStateDescription(trans('Het lijkt erop dat er geen contactnames zijn gevonden onder de opgegeven citeria'))
            ->columns([
                TextColumn::make('full_name')->label('Ingestuurd door')->sortable()->searchable(),
                TextColumn::make('status')->label('Status')->sortable()->badge(),
                TextColumn::make('email')->label('Email adres')->sortable()->searchable(),
                TextColumn::make('phone_number')->label('Tel. nummer')->placeholder('(niet opgegeven)')->searchable(),
                TextColumn::make('created_at')->label('Ingezonden op')->date()->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    // Custom contact message status update actions
                    BulkAction::make('In behandeling')
                        ->icon('heroicon-m-pencil-square')
                        ->action(fn(Collection $records) => $records->each->update(['status' => ContactMessageStatus::InProgress])),

                    BulkAction::make('Behandeld')
                        ->icon('heroicon-m-check')
                        ->action(fn(Collection $records) => $records->each->update(['status' => ContactMessageStatus::Completed])),
                ]),
            ]);
    }

    /**
     * Method to render the infolist in the application backend.
     *
     * @param  Infolist $infolist The infolist builder instance that will be used to build the infolist information.
     * @return Infolist
     */
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Fieldset::make('Ingezonden door')->schema([
                TextEntry::make('full_name')->label('Volledige naam')->hiddenLabel()->icon('heroicon-m-user-circle')->iconColor('primary')->columnSpan(5),
                TextEntry::make('email')->label('Email adres')->hiddenLabel()->copyable()->icon('heroicon-m-envelope')->iconColor('primary')->columnSpan(4),
                TextEntry::make('phone_number')->label('Tel. nr')->hiddenLabel()->placeholder('(niet opgegeven)')->icon('heroicon-m-device-phone-mobile')->iconColor('primary')->columnSpan(3),
            ])->columns(12),

            Fieldset::make('Vraag en of opmerking')->schema([
                TextEntry::make('message')->markdown()->hiddenLabel()->columnSpan(12),
            ])->columns(12),
        ]);
    }

    /**
     * MMethod to display the item count in the navigation bar item of the resource.
     *
     * @return string
     */
    public static function getNavigationBadge(): string
    {
        /** @var class-string<ContactSubmission> $modelClass */
        $modelClass = static::$model;

        return (string) $modelClass::where('status', ContactMessageStatus::New)
            ->orWhere('status', ContactMessageStatus::InProgress)
            ->count();
    }

    /**
     * Method to define the widgets that are associated with the resource.
     *
     * @return array<mixed>
     */
    public static function getWidgets(): array
    {
        return [ContactStats::class];
    }

    /**
     * Method to register the resource endpoint urls.
     *
     * @return array<string, \Filament\Resources\Pages\PageRegistration>
     */
    public static function getPages(): array
    {
        return ['index' => Pages\ListContactSubmissions::route('/')];
    }
}
