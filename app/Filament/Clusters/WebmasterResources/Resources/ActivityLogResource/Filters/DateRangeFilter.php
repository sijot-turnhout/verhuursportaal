<?php

declare(strict_types=1);

namespace App\Filament\Clusters\WebmasterResources\Resources\ActivityLogResource\Filters;

use Filament\Tables\Filters\Filter;

final class DateRangeFilter extends Filter
{
    public static function intitiate() : static
    {
        return parent::make('date-range-filter')
            ->label('Uitvoeringsdatum')
            ->translateLabel()
            ->form(self::dateRangeFormFields());
    }
}
