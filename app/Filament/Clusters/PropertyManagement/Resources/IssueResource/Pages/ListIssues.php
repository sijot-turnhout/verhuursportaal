<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\IssueResource\Pages;

use App\Filament\Clusters\PropertyManagement\Resources\IssueResource;
use Filament\Actions;
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

    /**
     * Get the actions to be displayed in the header of the issues list page.
     *
     * This method returns an array of actions that will appear in the header section of the issues list page.
     * By default, it includes the action to create a new issue, represented by an icon.
     *
     * @return array<\Filament\Actions\Action>  An array of actions for the header.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->icon('heroicon-o-plus'),
        ];
    }
}
