<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource\Pages;

use App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource;
use Filament\Resources\Pages\CreateRecord;

/**
 * Class CreateChangelog
 *
 * This class represents the page for creating a new changelog record within the property management module.
 * It extends Filament's CreateRecord class, providing the functionality needed to handle the creation of changelogs.
 */
final class CreateChangelog extends CreateRecord
{
    /**
     * The resource associated with this page.
     * This property links the page to the ChangelogResource,
     * which defines the data and behaviour for creating changelog records.
     *
     * @var string
     */
    protected static string $resource = ChangelogResource::class;
}
