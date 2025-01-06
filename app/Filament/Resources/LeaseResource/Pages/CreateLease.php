<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\Pages;

use App\Filament\Resources\LeaseResource;
use Filament\Resources\Pages\CreateRecord;

/**
 * Class CreateLease
 *
 * The `CreateLease` class is responsible for providing the functionality to create new lease
 * records within the system. It extends the `CreateRecord` class to handle the creation
 * process for lease records.
 *
 * @package App\Filament\Resources\LeaseResource\Pages
 */
final class CreateLease extends CreateRecord
{
    /**
     * The associated resource for the create page.
     *
     * This property links the `CreateLease` page to the `LeaseResource` class, which defines
     * the schema and behavior for managing lease records.
     *
     * @var string
     */
    protected static string $resource = LeaseResource::class;
}
