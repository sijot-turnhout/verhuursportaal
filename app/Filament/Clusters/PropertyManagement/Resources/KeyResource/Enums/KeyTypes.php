<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\KeyResource\Enums;

use Filament\Support\Contracts\HasLabel;

enum KeyTypes: string implements HasLabel
{
    case Master = 'Moedersleutel';
    case Reproduction = 'Bijmaak sleutel';

    public function getLabel(): ?string
    {
        return $this->value;
    }
}
