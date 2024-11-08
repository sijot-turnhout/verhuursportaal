<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Billing\Resources\QuotationResource\Actions;

use App\Filament\Resources\QuotationResource\Pages\ViewQuotations;
use App\Models\Lease;
use Filament\Actions\Action;

/**
 * Class ViewQuotation
 *
 * The `ViewQuotation` action class provides a user interface element to view specific quotations
 * related to leases. It extends the base Filament `Action` class and customizes the display and behavior
 * for accessing and viewing quotation details.
 *
 * This action is typically used in lease management contexts where users need quick access to quotation
 * information. It includes visibility checks to ensure the action is only displayed when a related quotation
 * is available, making it a dynamic and responsive part of the UI.
 *
 * @package App\Filament\Clusters\Billing\Resources\QuotaytionResource\Actions
 */
final class ViewQuotation extends Action
{
    /**
     * Creates an instance of the `ViewQuotation` action, setting default properties such as the icon,
     * visibility condition, and URL for viewing the quotation.
     *
     * @param  string|null $name  Optional name for the action, defaulting to a localized label 'Bekijk offerte'.
     * @return static             Returns a configured instance of the ViewQuotation action.
     */
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? __('Bekijk offerte'))
            ->icon('heroicon-o-eye')
            ->visible(fn(Lease $lease): bool => $lease->quotation()->exists())
            ->url(fn(Lease $lease): string => ViewQuotations::getUrl(parameters: ['record' => $lease->quotation]))
            ->openUrlInNewTab();
    }
}
