<?php

namespace App\Filament\Clusters\Billing\Resources\DepositResource\Pages;

use App\Filament\Clusters\Billing\Resources\DepositResource;
use App\Filament\Clusters\Billing\Resources\DepositResource\Enums\DepositStatus;
use App\Models\Deposit;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

final class ListDeposits extends ListRecords
{
    protected static string $resource = DepositResource::class;

    public function updatedActiveTab(): void
    {
        $this->resetPage();
        $this->deselectAllTableRecords();
    }

    public function getTabs(): array
    {
        return collect(DepositStatus::cases())
            ->map(
                fn(DepositStatus $status) => Tab::make()
                    ->label($status->getLabel())
                    ->icon($status->getIcon())
                    ->badgeColor($status->getColor())
                    ->query(fn(Builder $query): Builder => $query->where('status', $status))
                    ->badge(Deposit::query()->where('status', $status)->count()),
            )->toArray();
    }

    protected function getHeaderWidgets(): array
    {
        return DepositResource::getWidgets();
    }
}
