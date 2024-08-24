<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource\Pages;

use App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource;
use App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource\Actions\CloseChangelogAction;
use App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource\Actions\ReopenChangelogAction;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

/**
 * The page class responsible for viewing a specific changelog record.
 *
 * This class extends the `ViewRecord` page provided by Filament and is used to display the details
 * of a single `Changelog` record within the Filament admin panel. It links to the `ChangelogResource` class
 * that manages the CRUD operations for the changelog.
 */
final class ViewChangelog extends ViewRecord
{
    /**
     * The associated resource class for the changelog.
     *
     * This property defines which resource this page is associated with.
     * In this case, it links to the 'ChangelogResource', which handles the data and presentation logic for the `Changelog` model.
     *
     * @var string
     */
    protected static string $resource = ChangelogResource::class;

    /**
     * Define the actions available in the header of the view page.
     *
     * This method returns an array of actions that will be displayed in the header
     * when viewing a specific changelog record. If no actions are needed, it returns an empty array.
     *
     * @return array An array of header actions.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\ActionGroup::make([
                CloseChangelogAction::make(),
                ReopenChangelogAction::make(),
                Actions\EditAction::make(),

                Actions\ActionGroup::make([
                    Actions\DeleteAction::make(),
                ])->dropdown(false)
            ])
            ->button()
            ->color('gray')
            ->icon('heroicon-o-cog-8-tooth'),
        ];
    }
}
