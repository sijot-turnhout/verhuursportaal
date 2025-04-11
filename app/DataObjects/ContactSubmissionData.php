<?php

declare(strict_types=1);

namespace App\DataObjects;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

final class ContactSubmissionData extends Data
{
    public function __construct(
        #[MapInputName('voornaam')]
        public readonly string $first_name,
        #[MapInputName('achternaam')]
        public readonly string $last_name,
        public readonly string $email,
        #[MapInputName('tekst')]
        public readonly string $message,
        #[MapInputName('telefoon_nummer')]
        public readonly ?string $phone_number,
    ) {}
}
