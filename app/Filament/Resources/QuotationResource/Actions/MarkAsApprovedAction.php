<?php

declare(strict_types=1);

namespace App\Filament\Resources\QuotationResource\Actions;

use App\Models\Invoice;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Gate;

final class MarkAsApprovedAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? trans('offerte goedkeuren'))
            ->icon('heroicon-o-check')
            ->visible(fn(Invoice $invoice): bool => Gate::allows('approve-quotation', $invoice))
            ->color('gray');
    }
}
