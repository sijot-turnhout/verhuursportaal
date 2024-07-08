<?php

declare(strict_types=1);

namespace App\Filament\Resources\LocalResource\Pages;

use App\Filament\Resources\LocalResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLocal extends CreateRecord
{
    protected static string $resource = LocalResource::class;
}
