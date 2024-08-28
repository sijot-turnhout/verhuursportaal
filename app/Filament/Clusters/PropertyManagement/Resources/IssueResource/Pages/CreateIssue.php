<?php

namespace App\Filament\Clusters\PropertyManagement\Resources\IssueResource\Pages;

use App\Filament\Clusters\PropertyManagement\Resources\IssueResource;
use Filament\Resources\Pages\CreateRecord;

/**
 * The page class responsible for creating a new issue record.
 *
 * This class extends the `CreateRecord` page provided by Filament and is used to handle
 * the creation of a new `Issue` record within the Filament admin panel. It links to the
 * `IssueResource` class that manages the CRUD operations for the issue.
 */
final class CreateIssue extends CreateRecord
{
    /**
     * The associated resource class for the issue.
     *
     * This property defines which resource this page is associated with.
     * In this case, it links to the `IssueResource`, which handles the data and presentation logic
     * for the `Issue` model.
     *
     * @var string
     */
    protected static string $resource = IssueResource::class;
}
