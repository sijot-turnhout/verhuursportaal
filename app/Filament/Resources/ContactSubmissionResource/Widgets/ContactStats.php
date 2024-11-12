<?php

declare(strict_types=1);

namespace App\Filament\Resources\ContactSubmissionResource\Widgets;

use App\Models\ContactSubmission;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Class ContactStats
 *
 * A widget that displays various statistics related to contact submissions.
 *
 * This widget extends the `StatsOverviewWidget` class and provides a summary of
 * contact submissions by showing counts of submissions in different states.
 *
 * Cannot be testing because its a stat widget. And when there are erros here we will pick it up in onther tests.
 * @codeCoverageIgnore
 *
 * @package App\Filament\Resources\ContactSubmissionResource\Widgets
 */
final class ContactStats extends BaseWidget
{
    /**
     * Retrieves the statistics to be displayed in the widget.
     *
     * This method aggregates various statistics related to contact submissions
     * and returns them in an array of `Stat` objects. Each stat represents a
     * different metric regarding contact submissions.
     *
     * @return array<Stat>  An array of `Stat` objects representing different metrics.
     */
    protected function getStats(): array
    {
        return [
            Stat::make('Alle contactnames', ContactSubmission::count()),
            Stat::make('Nieuwe contactnames', $this->statusCountBaseQuery('nieuwe contactname')),
            Stat::make('Contactnames in behandeling', $this->statusCountBaseQuery('in behandeling')),
            Stat::make('Behandelde contactnames', $this->statusCountBaseQuery('behandeld')),
        ];
    }

    /**
     * Counts the number of contact submissions with a specific status.
     *
     * This method performs a query on the `ContactSubmission` model to count the
     * number of submissions that match the given status.
     *
     * @param  string $status  The status of contact submissions to count.
     * @return int             The number of contact submissions with the given status.
     */
    protected function statusCountBaseQuery(string $status): int
    {
        return ContactSubmission::query()->where('status', $status)->count();
    }
}
