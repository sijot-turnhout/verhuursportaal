<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

/**
 * Class CreateUser
 *
 * Represents the page for creating a new user record in the Filament admin panel.
 * This class extends the `CreateRecord` page from Filament and specifies the resource
 * it is associated with, allowing the creation of new user records.
 *
 * @package App\Filament\Resources\UserResource\Pages
 */
final class CreateUser extends CreateRecord
{
    /**
     * The resource class associated with this page.
     * This defines the resource that this page will manage.
     *
     * @var string
     */
    protected static string $resource = UserResource::class;
}
