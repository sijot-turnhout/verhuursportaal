<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Clusters\PropertyManagement;
use App\Filament\Resources\LocalResource\Forms\LocalResourceForm;
use App\Filament\Resources\LocalResource\Pages;
use App\Filament\Resources\LocalResource\RelationManagers\IssuesRelationManager;
use App\Filament\Resources\LocalResource\Tables\LocalResourceTable;
use App\Models\Local;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

/**
 * @todo Relocate this resource class to the Property Management Cluster.
 */
final class LocalResource extends Resource
{
    /**
     * The database model entity that will be used by the resource.
     */
    protected static ?string $model = Local::class;

    /**
     * The singular name for the resource class in the views.
     */
    protected static ?string $modelLabel = 'Lokaal';

    /**
     * The name from the icon that will be displayed in the navigation in the application backend.
     */
    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    /**
     * The plural resource name.
     */
    protected static ?string $pluralModelLabel = 'Lokalen';

    /**
     * The cluster of resources where this resource is placed in.
     */
    protected static ?string $cluster = PropertyManagement::class;

    /**
     * Method to create the edit/create form of the resource in the application backend.
     */
    public static function form(Form $form): Form
    {
        return LocalResourceForm::render($form);
    }

    /**
     * Method to create the overview table for the resource.
     *
     * @param  Table  $table  The table builder instance to create the table in the resource.
     */
    public static function table(Table $table): Table
    {
        return LocalResourceTable::make($table);
    }

    public static function getRelations(): array
    {
        return [
            IssuesRelationManager::class,
        ];
    }

    /**
     * The implementation of the resource endpoints in the resource.
     *
     * @return array<string, \Filament\Resources\Pages\PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLocals::route('/'),
            'create' => Pages\CreateLocal::route('/create'),
            'edit' => Pages\EditLocal::route('/{record}/edit'),
        ];
    }
}
