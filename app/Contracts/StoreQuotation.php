<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DataObjects\ReservationDataObject;

interface StoreQuotation
{
    public function process(ReservationDataObject $reservationDataObject): void;
}
