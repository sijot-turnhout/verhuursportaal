<?php

declare(strict_types=1);

namespace App\Filament\Support\Concerns;

trait HasStatusses
{
    public function setStatus(mixed $status): self
    {
        $this->update(['status' => $status]);

        return $this;
    }
}
