<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\UserGroup;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * @todo Fill in the empty table rows in the overview of the users.
 */
final class UserResource extends Resource
{
    /**
     * The resource entity model of the users in the application storage.
     *
     * @var ?string
     */
    protected static ?string $model = User::class;

    /**
     * The singular resource name in the application backend.
     *
     * @return ?string
     */
    protected static ?string $modelLabel = 'gebruiker';

    /**
     * The plural model name of the resource in the application.
     *
     * @var ?string
     */
    protected static ?string $pluralModelLabel = 'Gebruikers';

    /**
     * The name of the navigation icon that will be displayed in the navigation bar.
     *
     * @var ?string
     */
    protected static ?string $navigationIcon = 'heroicon-o-users';

    /**
     * Method to render the creation/edit form in the UserReource.
     *
     * @param  Form $form The form builder instance that will be used to render the forms in the UserResource.
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // General information section for the user.
                // here we render the form section for the general information off the user account.
                Forms\Components\Section::make('Algemene informatie')
                    ->description('Alle benodigde informatie die vereist is om een gebruiker aan te maken in het systeem.')
                    ->icon('heroicon-m-user')
                    ->schema([
                        Forms\Components\Select::make('user_group')->label('Functie')->required()->options(UserGroup::class)->columnSpan(3),
                        Forms\Components\TextInput::make('name')->label('Naam + Voornaam')->columnSpan(9)->required(),
                        Forms\Components\TextInput::make('email')->label('Email adres')->columnSpan(6)->required()->email(),
                        Forms\Components\TextInput::make('phone_number')->tel()->label('Telefoon nummer')->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')->columnSpan(6),
                    ])->columns(['sm' => 12, 'md' => 12, 'lg' => 12, 'xl' => 12, '2xl' => 12]),

                // Section that is related to the security information from the user account.
                // Only things such as the password will be handled/registered here.
                Forms\Components\Section::make('Beveiligings informatie')
                    ->icon('heroicon-m-shield-check')
                    ->description('Zorg ervoor dat het account een lang willekeurig wachtwoord gebruikt om veilig te blijven')
                    ->schema([
                        Forms\Components\TextInput::make('password')->label('Wachtwoord')->required()->minLength(8)->confirmed()->columnSpan(6)->password()->revealable(),
                        Forms\Components\TextInput::make('password_confirmation')->label('Herhaal wachtwoord')->password()->revealable()->required()->columnSpan(6),
                    ])
                    ->hidden(fn(string $operation): bool => 'edit' === $operation)
                    ->columns(['sm' => 12, 'md' => 12, 'lg' => 12, 'xl' => 12, '2xl' => 12]),
            ]);
    }

    /**
     * Method to display the information overview table.
     *
     * @param  Table $table The table builder instance that will be used to render the overview table of the resource.
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Naam')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('user_group')->label('Gebruikers groep')->sortable()->badge(),
                Tables\Columns\TextColumn::make('email')->label('Email adres')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('phone_number')->label('Tel. nummer')->searchable(),
                Tables\Columns\TextColumn::make('last_seen_at')->label('Laatst gezien')->since(),
                Tables\Columns\TextColumn::make('created_at')->label('Registratie datum'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
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
     * Method to define all the relation managers that are associated with the user information.
     * Such as key manegement en supervised leases
     *
     * @return array<int>
     */
    public static function getRelations(): array
    {
        return [];
    }

    /**
     * Method to render all the related resource endpoint of the UserResource in the application.
     *
     * @return array<string, \Filament\Resources\Pages\PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
