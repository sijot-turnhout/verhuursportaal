<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\StoreQuotation;
use App\Contracts\StoreReservation;
use App\Http\Requests\StoreReservationRequest;
use Illuminate\Http\RedirectResponse;
use Spatie\LaravelData\Exceptions\InvalidDataClass;

/**
 * BookingController handles the booking process for reservations and quotations.
 *
 * This controller is invoked when a booking request is made. It checks if the
 * request is for a quotation or a reservation and processes it accordingly.
 *
 * @package App\Http\Controllers
 */
final readonly class BookingController
{
    /**
     * Handle the incoming booking request.
     *
     * This method is called when a booking request is received.
     *
     * @param  StoreReservationRequest  $storeReservationRequest  The incoming request containing booking data.
     * @return RedirectResponse                                   A redirect response to the front page after processing the booking.
     *
     * @throws InvalidDataClass
     *
     * @see StoreQuotation
     * @see StoreReservation
     * @see RedirectResponse
     * @see StoreReservationRequest
     */
    public function __invoke(StoreReservationRequest $storeReservationRequest): RedirectResponse
    {
        app(StoreReservation::class)->process($storeReservationRequest->getData());

        return redirect()->action(FrontPageController::class);
    }
}
