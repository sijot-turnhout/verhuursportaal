<?php

declare(strict_types=1);

namespace App\Filament\Resources\TenantResource\Pages;

use App\Filament\Resources\TenantResource;
use Filament\Resources\Pages\CreateRecord;

/**
 * Class CreateTenant
 *
 * Represents the page for creating a new tenant record in the Filament admin panel.
 * This page extends the `CreateRecord` class provided by Filament, allowing users
 * to create new tenant records with the specified resource configuration.
 *
 * @package App\Filament\Resources\TenantResource\Pages
 */
final class CreateTenant extends CreateRecord
{
    /**
     * The resource associated with this page.
     * This is used by Filament to determine which resource the page is associated with.
     *
     * @var string
     */
    protected static string $resource = TenantResource::class;
}
