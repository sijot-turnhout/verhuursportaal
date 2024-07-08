<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\Pages;

use App\Filament\Resources\InvoiceResource\Actions\DownloadInvoiceAction;
use App\Filament\Resources\InvoiceResource\Actions\GenerateInvoice;
use App\Filament\Resources\InvoiceResource\Actions\ViewInvoice;
use App\Filament\Resources\LeaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

final class ViewLease extends ViewRecord
{
    protected static string $resource = LeaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ActionGroup::make([
                Actions\EditAction::make()->color('gray'),
                GenerateInvoice::make(),
                ViewInvoice::make(),
                DownloadInvoiceAction::make(),

                Actions\ActionGroup::make([
                    Actions\DeleteAction::make(),
                ])->dropdown(false),
            ])
                ->button()
                ->label('opties')
                ->icon('heroicon-o-cog-8-tooth')
                ->color('gray'),
        ];
    }
}
