<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource\Pages;

use App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource;
use App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource\Actions\CloseChangelogAction;
use App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource\Actions\ReopenChangelogAction;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/**
 * The page class responsible for editing a changelog record.
 *
 * This class extends the `EditRecord` page provided by Filament and is used to edit
 * the details of an existing `Changelog` record within the Filament admin panel.
 * It is associated with the `ChangelogResource` which manages the CRUD operations
 * and data presentation for the changelog model.
 */
final class EditChangelog extends EditRecord
{
    /**
     * The associated resource class for the changelog.
     *
     * This property defines the resource that this page is associated with.
     * It links to the `ChangelogResource`, which handles the data and presentation
     * logic for the `Changelog` model.
     *
     * @var string
     */
    protected static string $resource = ChangelogResource::class;

    /**
     * Get the actions to display in the header of the edit page.
     *
     * This method returns an array of actions to be shown in the header of the edit page.
     * In this case, it includes a delete action, allowing the user to remove the changelog directly from the edit page.
     *
     * @return array{Actions\ActionGroup}    An array of actions to be displayed in the header.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\ActionGroup::make([
                CloseChangelogAction::make(),
                ReopenChangelogAction::make(),

                Actions\ActionGroup::make([
                    Actions\DeleteAction::make()->icon('heroicon-o-trash'),
                ])->dropdown(false),
            ])
                ->button()
                ->icon('heroicon-o-cog-8-tooth')
                ->color('gray'),
        ];
    }
}
