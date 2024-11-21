<?php

declare(strict_types=1);

namespace App\Filament\Clusters\WebmasterResources\Resources;

use App\Filament\Clusters\WebmasterResources;
use App\Filament\Clusters\WebmasterResources\Resources\ActivityLogResource\Filters\DateRangeFilter;
use App\Filament\Clusters\WebmasterResources\Resources\ActivityLogResource\Pages;
use App\Filament\Widgets\ActivityRegistrationChart;
use App\Models\Activity;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Svg\Tag\Text;

final class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;
    protected static ?string $modelLabel = "Logboek";
    protected static ?string $pluralModelLabel = "Logboek";
    protected static ?string $navigationIcon = "heroicon-o-book-open";
    protected static ?string $cluster = WebmasterResources::class;
    protected static ?string $navigationGroup = "Monitoring";

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::tableColumnsLayout())
            ->actions(self::tableRecordActions())
            ->bulkActions(self::headerActions())
            ->filters([DateRangeFilter::register()]);
    }

    public static function getPages() : array
    {
        return ["index" => Pages\ListActivityLogs::route("/")];
    }

    private static function tableRecordActions() : array
    {
        return [
            Tables\Actions\ViewAction::make()->label("Bekijk")
                ->slideOver()
                ->modalHeading(trans('Geregistreerde activiteit'))
                ->modalSubheading(trans('Alle benodigde informatie voor het bekijken van de geregistreerde activiteit in de applicatie'))
                ->modalIconColor('primary')
                ->modalIcon('heroicon-o-information-circle'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(12)
            ->schema([
                TextEntry::make('causer.name')->label('Uitgevoerd door')->translateLabel()->icon('heroicon-o-user-circle')->iconColor('primary')->columnSpan(6),
                TextEntry::make('created_at')->label('Uitgevoerd op')->translateLabel()->icon('heroicon-o-clock')->iconColor('primary')->columnSpan(6),
                TextEntry::make('log_name')->label('Categorie')->translateLabel()->icon('heroicon-o-tag')->badge()->columnSpan(6),
                TextEntry::make('event')->label('Handeling')->translateLabel()->columnSpan(6),
                TextEntry::make('description')->label('Gebeurtenis')->translateLabel()->columnSpan(12),
                KeyValueEntry::make('properties')->label('Extra waarden')->keyLabel('Sleutel')->valueLabel('Waarde')->translateLabel()->columnSpan(12),
            ]);
    }

    private static function headerActions() : array
    {
        return [
            ExportBulkAction::make(),
        ];
    }

    public static function getWidgets(): array
    {
        return [ActivityRegistrationChart::class];
    }

    private static function tableColumnsLayout() : array
    {
        return [
            Tables\Columns\TextColumn::make("causer.name")
                ->label("Uitgevoerd door")
                ->icon('heroicon-o-user-circle')
                ->iconColor('primary')
                ->translateLabel()
                ->searchable(),

            Tables\Columns\TextColumn::make("log_name")
                ->label("Categorie")
                ->icon("heroicon-o-tag")
                ->badge()
                ->translateLabel()
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make("event")
                ->label("Handeling")
                ->translateLabel()
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make("description")
                ->label("Gebeurtenis")
                ->translateLabel()
                ->searchable(),

            Tables\Columns\TextColumn::make("created_at")
                ->label("Uitgevoerd op")
                ->icon('heroicon-o-clock')
                ->iconColor('primary')
                ->translateLabel()
                ->date(),
        ];
    }
}
