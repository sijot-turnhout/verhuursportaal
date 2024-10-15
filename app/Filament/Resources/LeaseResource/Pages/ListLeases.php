<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\Pages;

use App\Filament\Resources\LeaseResource;
use App\Models\Lease;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/**
 * Class ListLeases
 *
 * The `ListLeases` class is responsible for displaying a list of lease records within
 * the system. It extends the `ListRecords` class to provide the functionality for listing
 * lease records and integrates additional actions such as creating new leases.
 *
 * @package App\Filament\Resources\LeaseResource\Pages
 */
final class ListLeases extends ListRecords
{
    /**
     * The associated resource for the list page.
     *
     * This property links the `ListLeases` page to the `LeaseResource` class, which defines
     * the schema and behavior for managing lease records.
     *
     * @var string
     */
    protected static string $resource = LeaseResource::class;

    /**
     * Get the header actions available on the list page.
     *
     * This method returns an array of actions that are displayed in the header of the list
     * page. It currently includes a create action that allows users to add new lease records.
     *
     * @return array An array of actions for the list page header.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus')
                ->visible(Lease::query()->count() > 0),
        ];
    }
}
