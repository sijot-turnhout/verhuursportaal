<?php

declare(strict_types=1);

namespace App\DataObjects;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

final class ReservationDataObject extends Data
{
    public function __construct(
        #[MapInputName('aankomst')]
        public readonly string $arrival_date,
        #[MapInputName('vertrek')]
        public readonly string $departure_date,
        #[MapInputName('groep')]
        public readonly string $group,
        #[MapInputName('aantal_personen')]
        public readonly string $persons,
        #[MapInputName('achternaam')]
        public readonly string $firstName,
        #[MapInputName('achternaam')]
        public readonly string $lastName,
        #[MapInputName('email')]
        public readonly string $email,
        #[MapInputName('telefoon_nummer')]
        public readonly ?string $phone_number = null,
        #[MapInputName('offerte_aanvraag')]
        public readonly bool $quotation = false,
    ) {}

    /**
     * Method for mapping only the data that is related to the lease in the data object
     *
     * @return self
     */
    public function getLeaseInformation(): self
    {
        return $this->only('arrival_date', 'departure_date', 'group', 'persons');
    }

    /**
     * Method for mapping only the data that is related to the lease in the data object
     */
    public function getTenantInformation(): self
    {
        return $this->only('firstName', 'lastName', 'email', 'phone_number');
    }

    /**
     *Getter for the email address of the tenant.
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
