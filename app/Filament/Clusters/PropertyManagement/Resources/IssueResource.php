<?php

namespace App\Filament\Clusters\PropertyManagement\Resources;

use App\Filament\Clusters\PropertyManagement;
use App\Filament\Clusters\PropertyManagement\Resources\IssueResource\Pages;
use App\Filament\Clusters\PropertyManagement\Resources\IssueResource\RelationManagers;
use App\Models\Issue;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IssueResource extends Resource
{
    protected static ?string $model = Issue::class;

    /**
     * The name from the icon that will be displayed in the navigation in the application backend.
     *
     * @var string|null
     */
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    /**
     * The singular name for the resource class in the views.
     */
    protected static ?string $modelLabel = 'Werkpunt';

    /**
     * The plural name for the class in the views.
     */
    protected static ?string $pluralModelLabel = 'Werkpunten';

    /**
     * The navigation group from this resource.
     *
     * @var string|null
     */
    protected static ?string $navigationGroup = 'Problemen & verbeteringen';

    /**
     * The cluster of resources where this resource is placed in.
     *
     * @var string|null
     */
    protected static ?string $cluster = PropertyManagement::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIssues::route('/'),
            'create' => Pages\CreateIssue::route('/create'),
            'edit' => Pages\EditIssue::route('/{record}/edit'),
        ];
    }
}
