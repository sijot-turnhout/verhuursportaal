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
     *
     * Filament uses this property to know which resource configuration should be applied.
     * By setting this property, we clearly link this page to the TenantResource, so that all the forms, fields, table columns, and actions defined in TenantResource are used for this page.
     *
     * @var string This must be the fully-qualified class name of a Filament Resource.
     */
    protected static string $resource = TenantResource::class;
}
