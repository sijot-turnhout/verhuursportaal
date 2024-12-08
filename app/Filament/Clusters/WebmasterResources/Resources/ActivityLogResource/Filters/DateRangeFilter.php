<?php

declare(strict_types=1);

namespace App\Filament\Clusters\WebmasterResources\Resources\ActivityLogResource\Filters;

use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

final class DateRangeFilter extends Filter
{
    public static function register(): DateRangeFilter
    {
        return parent::make('date-range-filter')
            ->label('Uitvoeringsdatum')
            ->translateLabel()
            ->form(self::dateRangeFormFields())
            ->query(fn(Builder $query, array $data): Builder => self::dateRangeQuery($query, $data))
            ->indicateUsing(fn(array $data): array => self::registerIndicators($data));
    }

    /**
     * Function to build up the form fields for the data range filter.
     *
     * @return array<int, DatePicker>
     */
    private static function dateRangeFormFields(): array
    {
        return [
            DatePicker::make('created_from')
                ->label('van')
                ->translateLabel()
                ->native(false)
                ->placeholder('start datum'),

            DatePicker::make('created_until')
                ->label('tot en met')
                ->translateLabel()
                ->native(false)
                ->placeholder('eind datum'),
        ];
    }

    /**
     * Method to declare the date range queries for the filter.
     *
     * @param  Builder<\Illuminate\Database\Eloquent\Model> $query
     * @param  array<mixed>                                 $data
     * @return Builder<\Illuminate\Database\Eloquent\Model>
     */
    private static function dateRangeQuery(Builder $query, array $data): Builder
    {
        return $query->when(
            value: $data['created_from'],
            callback: fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
        )->when(
            value: $data['created_until'],
            callback: fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
        );
    }

    /**
     * Method to register the indicators from the filters.
     *
     * @param  array{created_from?: string, created_until?: string} $data  The from the filter that will be used in the indicators.
     * @return array{created_from?: string, created_until?: string}
     */
    private static function registerIndicators(array $data): array
    {
        $indicators = [];

        if ($data['created_from'] ?? null) {
            $indicators['created_from'] = trans('vanaf: :date', [
                'date' => Carbon::parse($data['created_from'])->toFormattedDateString(),
            ]);
        }

        if ($data['created_until'] ?? null) {
            $indicators['created_until'] = trans('tot en met: :date', [
                'date' => Carbon::parse($data['created_until'])->toFormattedDateString(),
            ]);
        }

        return $indicators;
    }
}
