<?php

declare(strict_types=1);

namespace App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource\Pages;

use App\Filament\Clusters\PropertyManagement\Resources\ChangelogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/**
 * Class ListChangelogs
 *
 * This class represents the page that lists changelogs in the Property Management module.
 * It extends the Filament's ListRecords class to provide the listing functionality.
 */
final class ListChangelogs extends ListRecords
{
    /**
     * The resoource that this page is associated with.
     * This links the page to the specific resource (ChangelogResource) that defines the data and behavior.
     *
     * @var string
     */
    protected static string $resource = ChangelogResource::class;

    /**
     * Defines the actions that will appear in the header of the list page.
     * In this case, it returns a CreateAction to add a new change log into the application.
     *
     * @return array<Actions\CreateAction>  THe array of header actions available on the list page.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Werkijst toevoegen')
                ->icon('heroicon-o-plus'),
        ];
    }
}
