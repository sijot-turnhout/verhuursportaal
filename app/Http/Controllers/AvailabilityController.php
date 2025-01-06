<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Queries\GetConfirmedLeasesForCalendar;
use Illuminate\Contracts\Support\Renderable;

/**
 * AvailablilityController Class
 *
 * This controller class is solely responsble for displaying the lease calendAr in the application frontend.
 * We do 2 things here. Displaying the view and rendering the confirmed leases into the view.
 *
 * The Calenda
 *
 * @todo Only there a a bug located in the view from the calendar. If there is a confiormed lease. that ends on the 3th of July.
 *       The calendar will render it as end on the second day of July. But if we updated the timestamp to 00:001:00 on the 3th of july.
 *       It will dispaly correctly on the calendar. We should further investigate this and fix the issue.
 */
final readonly class AvailabilityController
{
    /**
     * @param  GetConfirmedLeasesForCalendar $getConfirmedLeasesForCalendar The query class for getting all the confirmed leases out of the storage.
     * @return Renderable
     */
    public function __invoke(GetConfirmedLeasesForCalendar $getConfirmedLeasesForCalendar): Renderable
    {
        return view('availability', ['leases' => $getConfirmedLeasesForCalendar->handle()]);
    }
}
