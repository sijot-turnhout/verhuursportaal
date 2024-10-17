<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\UserGroup;
use App\Models\PanAnalytics;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

/**
 * AnalyticsTableWidget
 *
 * This widget displays a table of platform analytics for tracking page-level statistics such as impressions, hovers, and clicks.
 * It is designed to be viewed by users in the "Webmaster" user group.
 *
 * The widget pulls data from the `pan_analytics` table and presents it with various formatting and sorting options.
 * Additionally, it includes action buttons to link to external documentation.
 *
 * @package App\Filament\Widgets
 */
final class AnalyticsTableWidget extends TableWidget
{
    /**
     * Determines the column span of the table widget.
     * Setting it to 'full' means the table will take up the full width.
     *
     * @var int|string|array
     */
    protected int|string|array $columnSpan = 'full';

    /**
     * The heading that appears at the top of the widget.
     *
     * @var string|null
     */
    protected static ?string $heading = 'Platform analytics';

    /**
     * Sort order of the widget on the page.
     * A lower number means the widget will appear earlier.
     *
     * @var int|null
     */
    protected static ?int $sort = 2;

    /**
     * Determines if the authenticated user can view this widget.
     * Only users in the "Webmaster" user group can view this widget.
     *
     * @return bool True if the user can view the widget, false otherwise.
     */
    public static function canView(): bool
    {
        return auth()->user()->user_group->is(UserGroup::Webmaster);
    }
    /**
     * Defines the table's structure, columns, and data source.
     * This method configures the table to pull data from the `PanAnalytics` model and display it.
     *
     * @param  Table $table  The instance of the Filament Table being configured.
     * @return Table         The configured Table object with columns, query, and actions.
     */
    public function table(Table $table): Table
    {
        return $table
            ->query(PanAnalytics::query())
            ->paginated(false)
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->weight(FontWeight::Bold),

                TextColumn::make('name')
                    ->label('pagina')
                    ->sortable()
                    ->weight(FontWeight::SemiBold)
                    ->icon('heroicon-o-globe-alt')
                    ->color('primary'),

                TextColumn::make('impressions')
                    ->label('impressies')
                    ->sortable()
                    ->translateLabel(),

                TextColumn::make('hovers')
                    ->label('hovers')
                    ->sortable()
                    ->translateLabel()
                ,
                TextColumn::make('hoversPercentage')
                    ->label('hover ratio')
                    ->sortable()
                    ->translateLabel()
                    ->color('gray')
                    ->placeholder('0%'),

                TextColumn::make('clicks')
                    ->label('clicks')
                    ->sortable()
                    ->translateLabel(),

                TextColumn::make('clicksPercentage')
                    ->label('click ratio')
                    ->sortable()
                    ->translateLabel()
                    ->color('gray')
                    ->placeholder('0%'),
            ])
            ->headerActions([
                Tables\Actions\Action::make('documentatie')
                    ->color('gray')
                    ->icon('heroicon-o-book-open')
                    ->url('https://www.google.com')
                    ->openUrlInNewTab(),
            ]);
    }
}
