<?php

namespace App\Filament\Clusters\Billing\Resources\DepositResource\Pages;

use App\Filament\Clusters\Billing\Resources\DepositResource;
use App\Filament\Clusters\Billing\Resources\DepositResource\Enums\DepositStatus;
use App\Models\Deposit;
use Filament\Actions;
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
        $statuses = collect(DepositStatus::cases())
            ->map(
                fn(DepositStatus $status) => Tab::make()
                    ->label($status->getLabel())
                    ->icon($status->getIcon())
                    ->badgeColor($status->getColor())
                    ->query(fn(Builder $query): Builder => $query->where('status', $status))
                    ->badge(Deposit::query()->where('status', $status)->count()),
            )->toArray();

        return array_merge($this->configureDefaultTab(), $statuses);
    }

    public function configureDefaultTab(): array
    {
        return [
            Tab::make()
                ->label(trans('alle'))
                ->icon('heroicon-o-queue-list')
                ->badge(Deposit::query()->count())
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return DepositResource::getWidgets();
    }
}
