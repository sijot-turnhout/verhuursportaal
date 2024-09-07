<?php

declare(strict_types=1);

namespace App\Filament\Resources\LocalResource\Pages;

use App\Filament\Resources\LocalResource;
use Filament\Resources\Pages\CreateRecord;

/**
 * Class CreateLocal
 *
 * The `CreateLocal` class extends the `CreateRecord` functionality to handle the creation
 * of new local resource records within the system. This class is tied to the `LocalResource`
 * class, which defines the resource schema and behavior.
 *
 * @package App\Filament\Resources\LocalResource\Pages
 */
final class CreateLocal extends CreateRecord
{
    /**
     * The associated resource for this create page.
     *
     * This property links the `CreateLocal` page to the `LocalResource` class, which
     * defines the form schema and other configurations for the local resource.
     *
     * @var string
     */
    protected static string $resource = LocalResource::class;
}
