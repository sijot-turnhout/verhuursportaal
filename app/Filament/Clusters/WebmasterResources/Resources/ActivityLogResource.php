<?php

declare(strict_types=1);

namespace App\Filament\Clusters\WebmasterResources\Resources;

use App\Filament\Clusters\WebmasterResources;
use App\Filament\Clusters\WebmasterResources\Resources\ActivityLogResource\Pages;
use App\Models\Activity;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Illuminate\Database\Eloquent\Builder;

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
            ->filters([

                        DatePicker::make('created_from')->native(false)->placeholder('start datum'),
                        DatePicker::make('created_until')->native(false)->placeholder('eind datum'),
                    ])->query(function (Builder $query, array $data) : Builder {
                        return $query
                            ->when(value: $data['created_from'], callback: fn (Builder $query, $date) : Builder => $query->whereDate('created_at', '>=', $date))
                            ->when(value: $data['created_until'], callback: fn (Builder $query, $date) : Builder => $query->whereDate('created_at', '<=', $date));
                    })->indicateUsing(function (array $data) : array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Order from ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Order until ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
            ]);
    }

    public static function getPages() : array
    {
        return ["index" => Pages\ListActivityLogs::route("/")];
    }

    private static function tableRecordActions() : array
    {
        return [
            Tables\Actions\ViewAction::make()->label("Bekijk")->slideOver(),
        ];
    }

    private static function headerActions() : array
    {
        return [
            ExportBulkAction::make(),
        ];
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
