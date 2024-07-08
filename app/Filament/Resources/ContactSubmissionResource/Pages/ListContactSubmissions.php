<?php

declare(strict_types=1);

namespace App\Filament\Resources\ContactSubmissionResource\Pages;

use App\Enums\ContactMessageStatus;
use App\Filament\Resources\ContactSubmissionResource;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

final class ListContactSubmissions extends ListRecords
{
    protected static string $resource = ContactSubmissionResource::class;

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
     * @return array<string, mixed>
     */
    protected function getHeaderWidgets(): array
    {
        return ContactSubmissionResource::getWidgets();
    }
}
