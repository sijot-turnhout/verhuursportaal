<?php

declare(strict_types=1);

namespace App\Queries;

use App\DataObjects\CalendarItemDataObject;
use App\Enums\LeaseStatus;
use App\Models\Lease;
use Illuminate\Database\Eloquent\Collection;

final readonly class GetConfirmedLeasesForCalendar
{
    /**
     * Method for putting all the confirmed leases into an array that is manageable for the fullCalendar system.
     *
     * @return array
     *
     *  @phpstan-ignore-next-line
     */
    public function handle(): array
    {
        $leases = [];

        foreach ($this->getConfirmedLeases() as $lease) {
            $leases[] = (new CalendarItemDataObject(start: $lease->arrival_date, end: $lease->departure_date))->toArray();
        }

        return $leases;
    }

    /**
     * Method to get all the confirmed leases out of the database storage.
     *
     * @return Collection<int, Lease>
     */
    private function getConfirmedLeases(): Collection
    {
        return Lease::query()->where('status', LeaseStatus::Confirmed)->get();
    }
}
