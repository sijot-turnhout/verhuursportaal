<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\TenantResource\RelationManagers\IncidentsRelationManager;
use App\Filament\Resources\TenantResource\Pages;
use App\Filament\Resources\TenantResource\RelationManagers\LeasesRelationManager;
use App\Filament\Resources\TenantResource\RelationManagers\NotesRelationManager;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

/**
 * @todo Implement the filter to display only the blacklisted users of the active tenants in the application.
 * @todo Extract the actions for deactivating/reactivating tenants in the applications. To custom action classes.
 */
final class TenantResource extends Resource
{
    /**
     * The database model entity for the resource that is related to the tenants.
     *
     * @return string|null
     */
    protected static ?string $model = Tenant::class;

    /**
     * The label name that willbe displayed from the database model.
     *
     * @return string|null
     */
    protected static ?string $modelLabel = 'Huurder';

    /**
     * The plural representation of the label that will be displayed.
     *
     * @return string|null
     */
    protected static ?string $pluralModelLabel = 'Huurders';

    /**
     * The name of the navigation icon that will be used in the navigation
     *
     * @var string|null
     */
    protected static ?string $navigationIcon = 'heroicon-o-users';

    /**
     * Method for displaying the edit/create view for the tenant resource.
     *
     * @param  Form  $form  The form builder class that we use to build up the create/edit form.$
     * @return Form         THe confiured form instance for the Filament resource
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Huurdersprofiel')
                    ->icon('heroicon-o-user-circle')
                    ->iconColor('primary')
                    ->description(trans('De basis informatie in het systeem voor de huurder.'))
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Forms\Components\TextInput::make('firstName')
                            ->label('voornaam')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(5),
                        Forms\Components\TextInput::make('lastName')
                            ->label('achternaam')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(7),
                        Forms\Components\TextInput::make('email')
                            ->unique(ignoreRecord: true)
                            ->email()
                            ->maxLength(255)
                            ->required()
                            ->columnSpan(6),
                        Forms\Components\TextInput::make('phone_number')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->columnSpan(6),
                        Forms\Components\TextInput::make('address')
                            ->label('Adres')
                            ->maxLength(255)
                            ->columnSpan(12),
                    ])->columns(12),
            ]);
    }

    /**
     * Method for displaying the tenant overview table.
     *
     * @param  Table  $table  The table builder instance for the overview page in the tenant resource.
     * @return Table          The configured table display for the filament resource
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Naam')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('isBlacklisted')
                    ->label('Zwarte lijst')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle'),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email adres')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Tel. nummer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('Adres')
                    ->searchable()
                    ->placeholder('(niet opgegeven)'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registratiedatum')
                    ->since(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),

                    // TODO Place this in  a separated action class
                    Tables\Actions\Action::make('Huurder blokkeren')
                        ->authorize('deactivate')
                        ->icon('heroicon-o-lock-closed')
                        ->color('warning')
                        ->form([
                            Forms\Components\Textarea::make('deactivation_reason')->label('Reden tot plaatsing op de zwarte lijst')
                                ->required()
                                ->columnSpan(12)
                                ->rows(5)
                                ->helperText(new HtmlString('De blokkering van een gebruiker is van kracht tot <strong>6 maanden</strong> na de invoering')),
                        ])

                        // In this call we handle the deactivation of the tenant in the application.
                        // The deactivation is implemented because not every tenants respect the agreements of the domain maintainer
                        ->action(function (Tenant $tenant, array $data): void {
                            $tenant->ban(['expired_at' => now()->addMonths(6), 'comment' => $data['deactivation_reason']]);

                            Notification::make()
                                ->title(trans('De huurder is met success op de zwarte lijst geplaatst'))
                                ->success()
                                ->send();
                        }),

                    // Custom action for reactivating the tenant in the lease managament system.
                    // TODO Place this in  a separated action class
                    Tables\Actions\Action::make('Huurder heractiveren')
                        ->color('success')
                        ->icon('heroicon-o-lock-open')
                        ->authorize('activate')
                        ->action(function (Tenant $tenant, array $data): void {
                            $tenant->unban();

                            Notification::make()
                                ->title(trans('De verhuurder is terug verwijderd van de zwarte lijk'))
                                ->success()
                                ->send();
                        }),

                    // Proceed with normal template actions
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
     * The data relations where for the tenant data. For now only the Notes and Leases are implemented
     *
     * @return array<class-string>
     */
    public static function getRelations(): array
    {
        return [
            LeasesRelationManager::class,
            NotesRelationManager::class,
            IncidentsRelationManager::class,
        ];
    }

    /**
     * The representation array of pages that are implemented by the resource.
     *
     * @return array<string, \Filament\Resources\Pages\PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
        ];
    }
}
