<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\IssueResource\Pages;

use App\Filament\Clusters\PropertyManagement\Resources\IssueResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/**
 * The page class responsible for editing a specific issue record.
 *
 * This class extends the `EditRecord` page provided by Filament and is used to handle
 * the editing of a single `Issue` record within the Filament admin panel. It links to the
 * `IssueResource` class that manages the CRUD operations for the issue.
 */
final class EditIssue extends EditRecord
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

    /**
     * Get the header actions available on the edit page.
     *
     * This method returns an array of actions that are available in the header of the edit page.
     * It includes the delete action, allowing users to remove the issue record.
     *
     * @return array<int, Actions\DeleteAction>
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->icon('heroicon-o-trash'),
        ];
    }
}
