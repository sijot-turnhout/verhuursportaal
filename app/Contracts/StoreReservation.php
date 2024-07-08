<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DataObjects\ReservationDataObject;

interface StoreReservation
{
    public function process(ReservationDataObject $reservationDataObject): void;
}
