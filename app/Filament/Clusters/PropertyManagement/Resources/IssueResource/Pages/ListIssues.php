<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\IssueResource\Pages;

use App\Filament\Clusters\PropertyManagement\Resources\IssueResource;
use Filament\Resources\Pages\ListRecords;

/**
 * The page class responsible for listing issue records.
 *
 * The `ListIssues` class extends the `ListRecords` page provided by Filament and is used to
 * display a list of all `Issue` records within the Filament admin panel. It is associated with
 * the `IssueResource` class, which manages the CRUD operations and defines the data structure for the `Issue` model.
 */
final class ListIssues extends ListRecords
{
    /**
     * The associated resource class for the issues.
     *
     * This property defines which resource this page is associated with.
     * In this case, it links to the `IssueResource`, which handles the data and presentation logic for the `Issue` model.
     *
     * @var string
     */
    protected static string $resource = IssueResource::class;
}
