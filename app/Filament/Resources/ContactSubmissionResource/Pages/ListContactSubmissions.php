<?php

declare(strict_types=1);

namespace App\Filament\Resources\ContactSubmissionResource\Pages;

use App\Enums\ContactMessageStatus;
use App\Filament\Resources\ContactSubmissionResource;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

/**
 * Class ListContactSubmissions
 *
 * Represents the page listing all contact submissions in the Filament admin panel.
 *
 * This page extends the base `ListRecords` page and provides functionality to
 * display contact submissions with the ability to filter them by their status.
 * It utilizes tabs to allow users to switch between different statuses of contact
 * submissions, such as new, in-progress, and completed.
 *
 * @package App\Filament\Resources\ContactSubmissionResource\Pages
 */
final class ListContactSubmissions extends ListRecords
{
    /**
     * The resource class that this page is associated with.
     *
     * This specifies which resource the page is managing. In this case, it
     * is the `ContactSubmissionResource`, which manages contact submissions.
     *
     * @var string
     */
    protected static string $resource = ContactSubmissionResource::class;

    /**
     * Returns the tabs to be displayed on the page.
     *
     * The tabs are used to filter contact submissions based on their status.
     * Each tab is associated with a specific status of contact submissions.
     * The null key is used to display all contact submissions without any filter.
     *
     * @return array<string, Tab>
     */
    public function getTabs(): array
    {
        return [
            ContactMessageStatus::New->value => Tab::make()->query(fn($query) => $query->where('status', ContactMessageStatus::New)),
            ContactMessageStatus::InProgress->value => Tab::make()->query(fn($query) => $query->where('status', ContactMessageStatus::InProgress)),
            ContactMessageStatus::Completed->value => Tab::make()->query(fn($query) => $query->where('status', ContactMessageStatus::Completed)),
            null => Tab::make('Alle'),
        ];
    }

    /**
     * Retrieves the header widgets for the page.
     *
     * This method returns an array of widgets to be displayed in the header
     * of the page. The widgets are defined in the `ContactSubmissionResource`.
     *
     * @return array<string, mixed>
     */
    protected function getHeaderWidgets(): array
    {
        return ContactSubmissionResource::getWidgets();
    }
}
